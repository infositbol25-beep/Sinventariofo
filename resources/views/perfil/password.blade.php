@extends('layouts.app')

@section('content')
    <div class="card" style="max-width:700px; margin:0 auto;">
        <h2 style="margin-top:0;">Cambiar Contraseña</h2>

        <form action="{{ route('perfil.password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Contraseña actual</label>
                <input type="password" name="current_password">
                @error('current_password')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Nueva contraseña</label>
                <input type="password" name="password">
                @error('password')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Confirmar nueva contraseña</label>
                <input type="password" name="password_confirmation">
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">Actualizar contraseña</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>
@endsection