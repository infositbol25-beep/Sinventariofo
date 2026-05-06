@extends('layouts.app')

@section('page_title', 'Nueva Categoría')
@section('page_subtitle', 'Registro de categorías del sistema')

@section('content')
    <div class="card" style="max-width:800px; margin:0 auto;">
        <h2 style="margin-top:0;">Registrar Categoría</h2>

        <form action="{{ route('categorias.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Nombre de la categoría</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}">
                @error('nombre')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn btn-success">Guardar categoría</button>
                <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>
@endsection