<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        $puedeRegistrarse = User::count() === 0;

        return view('welcome', compact('puedeRegistrarse'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Debe ingresar su usuario.',
            'password.required' => 'Debe ingresar su contraseña.',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'Usuario o contraseña incorrectos.'
            ])->onlyInput('username');
        }

        if (!$user->estado) {
            return back()->withErrors([
                'username' => 'Este usuario está inactivo. Contacte al administrador.'
            ])->onlyInput('username');
        }

        if ($user->locked_until && now()->lt($user->locked_until)) {
            $minutos = max(1, now()->diffInMinutes($user->locked_until));

            return back()->withErrors([
                'username' => "Cuenta bloqueada temporalmente. Intente nuevamente en {$minutos} minuto(s)."
            ])->onlyInput('username');
        }

        if (!Hash::check($request->password, $user->password)) {
            $intentos = $user->failed_attempts + 1;

            if ($intentos >= 5) {
                $user->update([
                    'failed_attempts' => 0,
                    'locked_until' => now()->addMinutes(10),
                ]);

                return back()->withErrors([
                    'username' => 'Demasiados intentos fallidos. La cuenta fue bloqueada por 10 minutos.'
                ])->onlyInput('username');
            }

            $restantes = 5 - $intentos;

            $user->update([
                'failed_attempts' => $intentos,
            ]);

            return back()->withErrors([
                'username' => "Usuario o contraseña incorrectos. Intentos restantes: {$restantes}."
            ])->onlyInput('username');
        }

        $user->update([
            'failed_attempts' => 0,
            'locked_until' => null,
            'last_login_at' => now(),
        ]);

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

public function dashboard()
{
    $totalUsuarios = \App\Models\User::count();
    $usuariosActivos = \App\Models\User::where('estado', true)->count();
    $usuariosInactivos = \App\Models\User::where('estado', false)->count();
    $usuariosBloqueados = \App\Models\User::whereNotNull('locked_until')
        ->where('locked_until', '>', now())
        ->count();

    return view('dashboard', compact(
        'totalUsuarios',
        'usuariosActivos',
        'usuariosInactivos',
        'usuariosBloqueados'
    ));
}

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}