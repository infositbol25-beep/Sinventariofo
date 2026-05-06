<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
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

        $productos = Producto::with('categoria');

        if ($q !== '') {
            $productos->where(function ($query) use ($q) {
                $query->where('codigo', 'like', "%{$q}%")
                    ->orWhere('nombre', 'like', "%{$q}%")
                    ->orWhereHas('categoria', function ($subQuery) use ($q) {
                        $subQuery->where('nombre', 'like', "%{$q}%");
                    });
            });
        }

        $productos = $productos
            ->orderByDesc('id')
            ->get();

        return view('productos.index', compact('productos', 'q'));
    }

    public function create()
    {
        $this->puedeGestionar();

        $categorias = Categoria::where('estado', true)
            ->orderBy('nombre')
            ->get();

        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $this->puedeGestionar();

        $request->validate([
            'codigo' => 'required|string|max:50|unique:productos,codigo',
            'nombre' => 'required|string|max:150',
            'categoria_id' => 'required|exists:categorias,id',
            'unidad_medida' => 'required|in:UND,M,ROLLO,CAJA,PAQUETE',
            'stock_actual' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string|max:1000',
        ], [
            'codigo.required' => 'El código del producto es obligatorio.',
            'codigo.unique' => 'Ya existe un producto con ese código.',
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'categoria_id.required' => 'Debe seleccionar una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
            'unidad_medida.required' => 'Debe seleccionar la unidad de medida.',
            'stock_actual.required' => 'El stock actual es obligatorio.',
            'stock_minimo.required' => 'El stock mínimo es obligatorio.',
        ]);

        Producto::create([
            'codigo' => strtoupper(trim($request->codigo)),
            'nombre' => $request->nombre,
            'categoria_id' => $request->categoria_id,
            'unidad_medida' => $request->unidad_medida,
            'stock_actual' => $request->stock_actual,
            'stock_minimo' => $request->stock_minimo,
            'descripcion' => $request->descripcion ?: null,
            'estado' => true,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto registrado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $this->puedeGestionar();

        $categorias = Categoria::where('estado', true)
            ->orderBy('nombre')
            ->get();

        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $this->puedeGestionar();

        $request->validate([
            'codigo' => [
                'required',
                'string',
                'max:50',
                Rule::unique('productos', 'codigo')->ignore($producto->id),
            ],
            'nombre' => 'required|string|max:150',
            'categoria_id' => 'required|exists:categorias,id',
            'unidad_medida' => 'required|in:UND,M,ROLLO,CAJA,PAQUETE',
            'stock_actual' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'estado' => 'required|in:0,1',
            'descripcion' => 'nullable|string|max:1000',
        ], [
            'codigo.required' => 'El código del producto es obligatorio.',
            'codigo.unique' => 'Ya existe un producto con ese código.',
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'categoria_id.required' => 'Debe seleccionar una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
            'unidad_medida.required' => 'Debe seleccionar la unidad de medida.',
            'stock_actual.required' => 'El stock actual es obligatorio.',
            'stock_minimo.required' => 'El stock mínimo es obligatorio.',
        ]);

        $producto->update([
            'codigo' => strtoupper(trim($request->codigo)),
            'nombre' => $request->nombre,
            'categoria_id' => $request->categoria_id,
            'unidad_medida' => $request->unidad_medida,
            'stock_actual' => $request->stock_actual,
            'stock_minimo' => $request->stock_minimo,
            'descripcion' => $request->descripcion ?: null,
            'estado' => $request->estado == '1',
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $this->puedeGestionar();

        $producto->update([
            'estado' => false,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto desactivado correctamente.');
    }

    public function activate(Producto $producto)
    {
        $this->puedeGestionar();

        $producto->update([
            'estado' => true,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto activado correctamente.');
    }
}