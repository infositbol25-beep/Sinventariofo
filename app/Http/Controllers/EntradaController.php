<?php

namespace App\Http\Controllers;

use App\Models\DetalleEntrada;
use App\Models\Entrada;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntradaController extends Controller
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

        $entradas = Entrada::with(['usuario', 'detalles']);

        if ($q !== '') {
            $entradas->where(function ($query) use ($q) {
                $query->where('proveedor', 'like', "%{$q}%")
                    ->orWhere('documento_referencia', 'like', "%{$q}%")
                    ->orWhere('tipo_ingreso', 'like', "%{$q}%")
                    ->orWhere('id', 'like', "%{$q}%");
            });
        }

        $entradas = $entradas
            ->orderByDesc('id')
            ->get();

        return view('entradas.index', compact('entradas', 'q'));
    }

    public function create()
    {
        $this->puedeGestionar();

        $productos = Producto::where('estado', true)
            ->orderBy('nombre')
            ->get();

        return view('entradas.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $this->puedeGestionar();

        $request->validate([
            'fecha' => 'required|date',
            'tipo_ingreso' => 'required|in:COMPRA,AJUSTE_INICIAL,DEVOLUCION_INTERNA,OTRO',
            'proveedor' => 'nullable|string|max:150',
            'documento_referencia' => 'nullable|string|max:100',
            'observaciones' => 'nullable|string|max:1000',
            'detalles' => 'required|array|min:1',
            'detalles.*.producto_id' => 'required|distinct|exists:productos,id',
            'detalles.*.cantidad' => 'required|numeric|gt:0',
            'detalles.*.costo_referencial' => 'nullable|numeric|min:0',
        ], [
            'fecha.required' => 'La fecha es obligatoria.',
            'tipo_ingreso.required' => 'Debe seleccionar el tipo de ingreso.',
            'detalles.required' => 'Debe agregar al menos un producto.',
            'detalles.*.producto_id.required' => 'Debe seleccionar un producto.',
            'detalles.*.producto_id.distinct' => 'No repita el mismo producto en la misma entrada.',
            'detalles.*.cantidad.required' => 'La cantidad es obligatoria.',
            'detalles.*.cantidad.gt' => 'La cantidad debe ser mayor a cero.',
        ]);

        DB::transaction(function () use ($request) {
            $entrada = Entrada::create([
                'fecha' => $request->fecha,
                'tipo_ingreso' => $request->tipo_ingreso,
                'proveedor' => $request->proveedor ?: null,
                'documento_referencia' => $request->documento_referencia ?: null,
                'observaciones' => $request->observaciones ?: null,
                'user_id' => auth()->id(),
                'estado' => 'REGISTRADA',
            ]);

            foreach ($request->detalles as $detalle) {
                $producto = Producto::findOrFail($detalle['producto_id']);

                DetalleEntrada::create([
                    'entrada_id' => $entrada->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $detalle['cantidad'],
                    'costo_referencial' => $detalle['costo_referencial'] ?? 0,
                ]);

                $producto->increment('stock_actual', (float) $detalle['cantidad']);
            }
        });

        return redirect()->route('entradas.index')->with('success', 'Entrada registrada correctamente.');
    }

    public function show(Entrada $entrada)
    {
        $this->puedeGestionar();

        $entrada->load(['usuario', 'detalles.producto']);

        return view('entradas.show', compact('entrada'));
    }

    public function destroy(Entrada $entrada)
    {
        $this->puedeGestionar();

        if ($entrada->estado === 'ANULADA') {
            return back()->with('error', 'La entrada ya fue anulada.');
        }

        $entrada->load('detalles.producto');

        DB::transaction(function () use ($entrada) {
            foreach ($entrada->detalles as $detalle) {
                $producto = $detalle->producto;

                if ((float) $producto->stock_actual < (float) $detalle->cantidad) {
                    throw new \Exception("No se puede anular la entrada porque el producto {$producto->nombre} ya no tiene stock suficiente para revertir.");
                }

                $producto->decrement('stock_actual', (float) $detalle->cantidad);
            }

            $entrada->update([
                'estado' => 'ANULADA',
            ]);
        });

        return redirect()->route('entradas.index')->with('success', 'Entrada anulada correctamente.');
    }
}