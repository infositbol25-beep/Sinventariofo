@extends('layouts.app')

@section('content')
    <div class="card" style="max-width:700px; margin:0 auto;">
        <h2 style="margin-top:0;">Mi Perfil</h2>

        <form action="{{ route('perfil.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nombre completo</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}">
                @error('name')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="username" value="{{ old('username', auth()->user()->username) }}">
                @error('username')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Correo</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}">
                @error('email')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Rol</label>
                <input type="text" value="{{ auth()->user()->rol }}" disabled>
            </div>

            <div class="form-group">
                <label>Último acceso</label>
                <input type="text" value="{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('d/m/Y H:i') : 'Nunca' }}" disabled>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">Actualizar perfil</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>
@endsection