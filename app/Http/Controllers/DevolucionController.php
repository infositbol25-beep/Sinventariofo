<?php

namespace App\Http\Controllers;

use App\Models\DetalleDevolucion;
use App\Models\Devolucion;
use App\Models\Producto;
use App\Models\Salida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DevolucionController extends Controller
{
    private function puedeGestionar(): void
    {
        if (!auth()->check() || !in_array(auth()->user()->rol, ['Administrador', 'Almacenero'])) {
            abort(403, 'No autorizado.');
        }
    }

    public function index(Request $request)
    {
        $this->puedeGestionar();

        $q = trim((string) $request->q);

        $devoluciones = Devolucion::with(['usuario', 'salida.tecnico', 'detalles']);

        if ($q !== '') {
            $devoluciones->where(function ($query) use ($q) {
                $query->where('id', 'like', "%{$q}%")
                    ->orWhereHas('salida', function ($subQuery) use ($q) {
                        $subQuery->where('trabajo_referencia', 'like', "%{$q}%")
                            ->orWhereHas('tecnico', function ($qTecnico) use ($q) {
                                $qTecnico->where('nombre_completo', 'like', "%{$q}%")
                                    ->orWhere('ci', 'like', "%{$q}%");
                            });
                    });
            });
        }

        $devoluciones = $devoluciones
            ->orderByDesc('id')
            ->get();

        return view('devoluciones.index', compact('devoluciones', 'q'));
    }

    public function create()
    {
        $this->puedeGestionar();

        $salidas = Salida::with(['tecnico', 'detalles.producto', 'devoluciones.detalles'])
            ->where('estado', 'REGISTRADA')
            ->orderByDesc('id')
            ->get()
            ->filter(function ($salida) {
                foreach ($salida->detalles as $detalle) {
                    $devuelto = 0;

                    foreach ($salida->devoluciones->where('estado', 'REGISTRADA') as $devolucion) {
                        foreach ($devolucion->detalles as $detalleDev) {
                            if ($detalleDev->producto_id === $detalle->producto_id) {
                                $devuelto += (float) $detalleDev->cantidad;
                            }
                        }
                    }

                    $pendiente = (float) $detalle->cantidad - $devuelto;

                    if ($pendiente > 0) {
                        return true;
                    }
                }

                return false;
            })
            ->values();

        $productos = Producto::where('estado', true)
            ->orderBy('nombre')
            ->get();

        return view('devoluciones.create', compact('salidas', 'productos'));
    }

    public function store(Request $request)
    {
        $this->puedeGestionar();

        $request->validate([
            'fecha' => 'required|date',
            'salida_id' => 'required|exists:salidas,id',
            'observaciones' => 'nullable|string|max:1000',
            'detalles' => 'required|array|min:1',
            'detalles.*.producto_id' => 'required|distinct|exists:productos,id',
            'detalles.*.cantidad' => 'required|numeric|gt:0',
        ], [
            'fecha.required' => 'La fecha es obligatoria.',
            'salida_id.required' => 'Debe seleccionar una salida.',
            'salida_id.exists' => 'La salida seleccionada no es válida.',
            'detalles.required' => 'Debe agregar al menos un producto.',
            'detalles.*.producto_id.required' => 'Debe seleccionar un producto.',
            'detalles.*.producto_id.distinct' => 'No repita el mismo producto en la misma devolución.',
            'detalles.*.cantidad.required' => 'La cantidad es obligatoria.',
            'detalles.*.cantidad.gt' => 'La cantidad debe ser mayor a cero.',
        ]);

        $salida = Salida::with(['detalles', 'devoluciones.detalles'])
            ->where('estado', 'REGISTRADA')
            ->findOrFail($request->salida_id);

        DB::transaction(function () use ($request, $salida) {
            $devolucion = Devolucion::create([
                'fecha' => $request->fecha,
                'salida_id' => $salida->id,
                'observaciones' => $request->observaciones ?: null,
                'user_id' => auth()->id(),
                'estado' => 'REGISTRADA',
            ]);

            foreach ($request->detalles as $detalle) {
                $producto = Producto::findOrFail($detalle['producto_id']);

                $detalleSalida = $salida->detalles->firstWhere('producto_id', $producto->id);

                if (!$detalleSalida) {
                    throw new \Exception("El producto {$producto->nombre} no pertenece a la salida seleccionada.");
                }

                $cantidadEntregada = (float) $detalleSalida->cantidad;
                $cantidadDevueltaAcumulada = 0;

                foreach ($salida->devoluciones->where('estado', 'REGISTRADA') as $devExistente) {
                    foreach ($devExistente->detalles as $detDev) {
                        if ($detDev->producto_id === $producto->id) {
                            $cantidadDevueltaAcumulada += (float) $detDev->cantidad;
                        }
                    }
                }

                $pendiente = $cantidadEntregada - $cantidadDevueltaAcumulada;

                if ((float) $detalle['cantidad'] > $pendiente) {
                    throw new \Exception("La devolución del producto {$producto->nombre} excede lo pendiente por devolver.");
                }

                DetalleDevolucion::create([
                    'devolucion_id' => $devolucion->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $detalle['cantidad'],
                ]);

                $producto->increment('stock_actual', (float) $detalle['cantidad']);
            }
        });

        return redirect()->route('devoluciones.index')->with('success', 'Devolución registrada correctamente.');
    }

    public function show(Devolucion $devolucion)
    {
        $this->puedeGestionar();

        $devolucion->load(['usuario', 'salida.tecnico', 'detalles.producto']);

        return view('devoluciones.show', compact('devolucion'));
    }

    public function destroy(Devolucion $devolucion)
    {
        $this->puedeGestionar();

        if ($devolucion->estado === 'ANULADA') {
            return back()->with('error', 'La devolución ya fue anulada.');
        }

        $devolucion->load('detalles.producto');

        DB::transaction(function () use ($devolucion) {
            foreach ($devolucion->detalles as $detalle) {
                $producto = $detalle->producto;

                if ((float) $producto->stock_actual < (float) $detalle->cantidad) {
                    throw new \Exception("No se puede anular la devolución porque el producto {$producto->nombre} no tiene stock suficiente para revertir.");
                }

                $producto->decrement('stock_actual', (float) $detalle->cantidad);
            }

            $devolucion->update([
                'estado' => 'ANULADA',
            ]);
        });

        return redirect()->route('devoluciones.index')->with('success', 'Devolución anulada correctamente.');
    }
}