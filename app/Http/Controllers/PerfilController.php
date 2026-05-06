<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PerfilController extends Controller
{
    public function show()
    {
        return view('perfil.show');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'username.required' => 'El usuario es obligatorio.',
            'username.unique' => 'Ese nombre de usuario ya existe.',
            'email.email' => 'El correo no es válido.',
            'email.unique' => 'Ese correo ya existe.',
        ]);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email ?: null,
        ]);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function passwordForm()
    {
        return view('perfil.password');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Debe ingresar su contraseña actual.',
            'password.required' => 'Debe ingresar una nueva contraseña.',
            'password.min' => 'La nueva contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación no coincide.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'La contraseña actual no es correcta.'
            ]);
        }

        $user->update([
            'password' => $request->password,
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }
}