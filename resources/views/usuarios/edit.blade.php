@extends('layouts.app')

@section('content')
    <div class="card" style="max-width:700px; margin:0 auto;">
        <h2 style="margin-top:0;">Editar Usuario</h2>

        @if($usuario->locked_until && $usuario->locked_until->isFuture())
            <div class="alert alert-error">
                Este usuario está bloqueado hasta {{ $usuario->locked_until->format('d/m/Y H:i') }}.
            </div>
        @endif

        <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nombre completo</label>
                <input type="text" name="name" value="{{ old('name', $usuario->name) }}">
                @error('name')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="username" value="{{ old('username', $usuario->username) }}">
                @error('username')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Correo</label>
                <input type="email" name="email" value="{{ old('email', $usuario->email) }}">
                @error('email')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Nueva contraseña</label>
                <input type="password" name="password" placeholder="Déjelo vacío si no desea cambiarla">
                @error('password')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Confirmar nueva contraseña</label>
                <input type="password" name="password_confirmation" placeholder="Repita la nueva contraseña">
            </div>

            <div class="form-group">
                <label>Rol</label>
                <select name="rol">
                    <option value="Administrador" {{ old('rol', $usuario->rol) == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                    <option value="Almacenero" {{ old('rol', $usuario->rol) == 'Almacenero' ? 'selected' : '' }}>Almacenero</option>
                </select>
                @error('rol')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="estado">
                    <option value="1" {{ old('estado', $usuario->estado ? '1' : '0') == '1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('estado', $usuario->estado ? '1' : '0') == '0' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="form-group">
                <label>Último acceso</label>
                <input type="text" value="{{ $usuario->last_login_at ? $usuario->last_login_at->format('d/m/Y H:i') : 'Nunca' }}" disabled>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>
@endsection