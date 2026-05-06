<?php

namespace App\Http\Controllers;

use App\Models\DetalleSalida;
use App\Models\Producto;
use App\Models\Salida;
use App\Models\Tecnico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalidaController extends Controller
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

        $salidas = Salida::with(['usuario', 'tecnico', 'detalles']);

        if ($q !== '') {
            $salidas->where(function ($query) use ($q) {
                $query->where('trabajo_referencia', 'like', "%{$q}%")
                    ->orWhere('id', 'like', "%{$q}%")
                    ->orWhereHas('tecnico', function ($subQuery) use ($q) {
                        $subQuery->where('nombre_completo', 'like', "%{$q}%")
                            ->orWhere('ci', 'like', "%{$q}%");
                    });
            });
        }

        $salidas = $salidas
            ->orderByDesc('id')
            ->get();

        return view('salidas.index', compact('salidas', 'q'));
    }

    public function create()
    {
        $this->puedeGestionar();

        $tecnicos = Tecnico::where('estado', true)
            ->orderBy('nombre_completo')
            ->get();

        $productos = Producto::where('estado', true)
            ->orderBy('nombre')
            ->get();

        return view('salidas.create', compact('tecnicos', 'productos'));
    }

    public function store(Request $request)
    {
        $this->puedeGestionar();

        $request->validate([
            'fecha' => 'required|date',
            'tecnico_id' => 'required|exists:tecnicos,id',
            'trabajo_referencia' => 'nullable|string|max:150',
            'observaciones' => 'nullable|string|max:1000',
            'detalles' => 'required|array|min:1',
            'detalles.*.producto_id' => 'required|distinct|exists:productos,id',
            'detalles.*.cantidad' => 'required|numeric|gt:0',
        ], [
            'fecha.required' => 'La fecha es obligatoria.',
            'tecnico_id.required' => 'Debe seleccionar un técnico.',
            'tecnico_id.exists' => 'El técnico seleccionado no es válido.',
            'detalles.required' => 'Debe agregar al menos un producto.',
            'detalles.*.producto_id.required' => 'Debe seleccionar un producto.',
            'detalles.*.producto_id.distinct' => 'No repita el mismo producto en la misma salida.',
            'detalles.*.cantidad.required' => 'La cantidad es obligatoria.',
            'detalles.*.cantidad.gt' => 'La cantidad debe ser mayor a cero.',
        ]);

        DB::transaction(function () use ($request) {
            $salida = Salida::create([
                'fecha' => $request->fecha,
                'tecnico_id' => $request->tecnico_id,
                'trabajo_referencia' => $request->trabajo_referencia ?: null,
                'observaciones' => $request->observaciones ?: null,
                'user_id' => auth()->id(),
                'estado' => 'REGISTRADA',
            ]);

            foreach ($request->detalles as $detalle) {
                $producto = Producto::findOrFail($detalle['producto_id']);

                if ((float) $producto->stock_actual < (float) $detalle['cantidad']) {
                    throw new \Exception("No hay stock suficiente para el producto {$producto->nombre}.");
                }

                DetalleSalida::create([
                    'salida_id' => $salida->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $detalle['cantidad'],
                ]);

                $producto->decrement('stock_actual', (float) $detalle['cantidad']);
            }
        });

        return redirect()->route('salidas.index')->with('success', 'Salida registrada correctamente.');
    }

    public function show(Salida $salida)
    {
        $this->puedeGestionar();

        $salida->load(['usuario', 'tecnico', 'detalles.producto']);

        return view('salidas.show', compact('salida'));
    }

    public function destroy(Salida $salida)
    {
        $this->puedeGestionar();

        if ($salida->estado === 'ANULADA') {
            return back()->with('error', 'La salida ya fue anulada.');
        }

        $salida->load('detalles.producto');

        DB::transaction(function () use ($salida) {
            foreach ($salida->detalles as $detalle) {
                $producto = $detalle->producto;
                $producto->increment('stock_actual', (float) $detalle->cantidad);
            }

            $salida->update([
                'estado' => 'ANULADA',
            ]);
        });

        return redirect()->route('salidas.index')->with('success', 'Salida anulada correctamente.');
    }
}