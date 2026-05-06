<?php

namespace App\Http\Controllers;

use App\Models\Tecnico;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TecnicoController extends Controller
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

        $tecnicos = Tecnico::query();

        if ($q !== '') {
            $tecnicos->where(function ($query) use ($q) {
                $query->where('nombre_completo', 'like', "%{$q}%")
                    ->orWhere('ci', 'like', "%{$q}%")
                    ->orWhere('telefono', 'like', "%{$q}%");
            });
        }

        $tecnicos = $tecnicos
            ->orderByDesc('id')
            ->get();

        return view('tecnicos.index', compact('tecnicos', 'q'));
    }

    public function create()
    {
        $this->puedeGestionar();

        return view('tecnicos.create');
    }

    public function store(Request $request)
    {
        $this->puedeGestionar();

        $request->validate([
            'nombre_completo' => 'required|string|max:150',
            'ci' => 'required|string|max:20|unique:tecnicos,ci',
            'telefono' => 'nullable|string|max:30',
            'cargo' => 'required|string|max:100',
            'cuadrilla' => 'nullable|string|max:100',
            'zona' => 'nullable|string|max:100',
            'observaciones' => 'nullable|string|max:1000',
        ], [
            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'ci.required' => 'La cédula o documento es obligatorio.',
            'ci.unique' => 'Ya existe un técnico con ese documento.',
            'cargo.required' => 'El cargo es obligatorio.',
        ]);

        Tecnico::create([
            'nombre_completo' => $request->nombre_completo,
            'ci' => $request->ci,
            'telefono' => $request->telefono ?: null,
            'cargo' => $request->cargo,
            'cuadrilla' => $request->cuadrilla ?: null,
            'zona' => $request->zona ?: null,
            'estado' => true,
            'observaciones' => $request->observaciones ?: null,
        ]);

        return redirect()->route('tecnicos.index')->with('success', 'Técnico registrado correctamente.');
    }

    public function edit(Tecnico $tecnico)
    {
        $this->puedeGestionar();

        return view('tecnicos.edit', compact('tecnico'));
    }

    public function update(Request $request, Tecnico $tecnico)
    {
        $this->puedeGestionar();

        $request->validate([
            'nombre_completo' => 'required|string|max:150',
            'ci' => [
                'required',
                'string',
                'max:20',
                Rule::unique('tecnicos', 'ci')->ignore($tecnico->id),
            ],
            'telefono' => 'nullable|string|max:30',
            'cargo' => 'required|string|max:100',
            'cuadrilla' => 'nullable|string|max:100',
            'zona' => 'nullable|string|max:100',
            'estado' => 'required|in:0,1',
            'observaciones' => 'nullable|string|max:1000',
        ], [
            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'ci.required' => 'La cédula o documento es obligatorio.',
            'ci.unique' => 'Ya existe un técnico con ese documento.',
            'cargo.required' => 'El cargo es obligatorio.',
        ]);

        $tecnico->update([
            'nombre_completo' => $request->nombre_completo,
            'ci' => $request->ci,
            'telefono' => $request->telefono ?: null,
            'cargo' => $request->cargo,
            'cuadrilla' => $request->cuadrilla ?: null,
            'zona' => $request->zona ?: null,
            'estado' => $request->estado == '1',
            'observaciones' => $request->observaciones ?: null,
        ]);

        return redirect()->route('tecnicos.index')->with('success', 'Técnico actualizado correctamente.');
    }

    public function destroy(Tecnico $tecnico)
    {
        $this->puedeGestionar();

        $tecnico->update([
            'estado' => false,
        ]);

        return redirect()->route('tecnicos.index')->with('success', 'Técnico desactivado correctamente.');
    }

    public function activate(Tecnico $tecnico)
    {
        $this->puedeGestionar();

        $tecnico->update([
            'estado' => true,
        ]);

        return redirect()->route('tecnicos.index')->with('success', 'Técnico activado correctamente.');
    }
}