<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
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

        $categorias = Categoria::query();

        if ($q !== '') {
            $categorias->where('nombre', 'like', "%{$q}%");
        }

        $categorias = $categorias
            ->orderByDesc('id')
            ->get();

        return view('categorias.index', compact('categorias', 'q'));
    }

    public function create()
    {
        $this->puedeGestionar();

        return view('categorias.create');
    }

    public function store(Request $request)
    {
        $this->puedeGestionar();

        $request->validate([
            'nombre' => 'required|string|max:120|unique:categorias,nombre',
            'descripcion' => 'nullable|string|max:1000',
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique' => 'Ya existe una categoría con ese nombre.',
        ]);

        Categoria::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion ?: null,
            'estado' => true,
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoría registrada correctamente.');
    }

    public function edit(Categoria $categoria)
    {
        $this->puedeGestionar();

        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $this->puedeGestionar();

        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:120',
                Rule::unique('categorias', 'nombre')->ignore($categoria->id),
            ],
            'descripcion' => 'nullable|string|max:1000',
            'estado' => 'required|in:0,1',
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique' => 'Ya existe una categoría con ese nombre.',
        ]);

        $categoria->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion ?: null,
            'estado' => $request->estado == '1',
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria)
    {
        $this->puedeGestionar();

        $categoria->update([
            'estado' => false,
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoría desactivada correctamente.');
    }

    public function activate(Categoria $categoria)
    {
        $this->puedeGestionar();

        $categoria->update([
            'estado' => true,
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoría activada correctamente.');
    }
}