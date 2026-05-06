<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    private function soloAdmin(): void
    {
        if (!auth()->check() || auth()->user()->rol !== 'Administrador') {
            abort(403, 'No autorizado.');
        }
    }

    private function adminsActivos(): int
    {
        return User::where('rol', 'Administrador')
            ->where('estado', true)
            ->count();
    }

    public function index()
    {
        $this->soloAdmin();

        $usuarios = User::orderByDesc('id')->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $esPrimerUsuario = User::count() === 0;

        if (!$esPrimerUsuario && auth()->check() && auth()->user()->rol !== 'Administrador') {
            abort(403, 'No autorizado.');
        }

        if (!$esPrimerUsuario && !auth()->check()) {
            return redirect()->route('login')->withErrors([
                'username' => 'El registro público ya fue cerrado. Solicite su usuario al administrador.'
            ]);
        }

        return view('usuarios.create', compact('esPrimerUsuario'));
    }

    public function store(Request $request)
    {
        $esPrimerUsuario = User::count() === 0;

        if (!$esPrimerUsuario && auth()->check() && auth()->user()->rol !== 'Administrador') {
            abort(403, 'No autorizado.');
        }

        if (!$esPrimerUsuario && !auth()->check()) {
            return redirect()->route('login')->withErrors([
                'username' => 'El registro público ya fue cerrado. Solicite su usuario al administrador.'
            ]);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ];

        if (!$esPrimerUsuario) {
            $rules['rol'] = 'required|in:Administrador,Almacenero';
        }

        $request->validate($rules, [
            'name.required' => 'El nombre es obligatorio.',
            'username.required' => 'El usuario es obligatorio.',
            'username.unique' => 'Ese nombre de usuario ya existe.',
            'email.email' => 'El correo no es válido.',
            'email.unique' => 'Ese correo ya existe.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'rol.required' => 'Debe seleccionar un rol.',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email ?: null,
            'password' => $request->password,
            'rol' => $esPrimerUsuario ? 'Administrador' : $request->rol,
            'estado' => true,
            'failed_attempts' => 0,
            'locked_until' => null,
        ]);

        if ($esPrimerUsuario) {
            return redirect()->route('login')->with('success', 'Primer administrador creado correctamente. Ya puede iniciar sesión.');
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        $this->soloAdmin();

        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $this->soloAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->ignore($usuario->id),
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($usuario->id),
            ],
            'password' => 'nullable|string|min:6|confirmed',
            'rol' => 'required|in:Administrador,Almacenero',
            'estado' => 'required|in:0,1',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'username.required' => 'El usuario es obligatorio.',
            'username.unique' => 'Ese nombre de usuario ya existe.',
            'email.email' => 'El correo no es válido.',
            'email.unique' => 'Ese correo ya existe.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'rol.required' => 'Debe seleccionar un rol.',
        ]);

        $estadoNuevo = $request->estado == '1';
        $rolNuevo = $request->rol;

        if ($usuario->rol === 'Administrador' && $usuario->estado) {
            $esUltimoAdminActivo = $this->adminsActivos() <= 1;

            if ($esUltimoAdminActivo && !$estadoNuevo) {
                return back()->with('error', 'No puede desactivar al único administrador activo.')->withInput();
            }

            if ($esUltimoAdminActivo && $rolNuevo !== 'Administrador') {
                return back()->with('error', 'No puede cambiar el rol del único administrador activo.')->withInput();
            }
        }

        $datos = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email ?: null,
            'rol' => $rolNuevo,
            'estado' => $estadoNuevo,
        ];

        if ($request->filled('password')) {
            $datos['password'] = $request->password;
        }

        $usuario->update($datos);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        $this->soloAdmin();

        if (auth()->id() === $usuario->id) {
            return back()->with('error', 'No puede desactivarse a sí mismo.');
        }

        if ($usuario->rol === 'Administrador' && $usuario->estado && $this->adminsActivos() <= 1) {
            return back()->with('error', 'No puede desactivar al único administrador activo.');
        }

        $usuario->update([
            'estado' => false
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario desactivado correctamente.');
    }

    public function activate(User $usuario)
    {
        $this->soloAdmin();

        $usuario->update([
            'estado' => true
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario activado correctamente.');
    }

    public function unlock(User $usuario)
    {
        $this->soloAdmin();

        $usuario->update([
            'failed_attempts' => 0,
            'locked_until' => null,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario desbloqueado correctamente.');
    }
}