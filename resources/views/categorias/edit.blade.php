@extends('layouts.app')

@section('page_title', 'Editar Categoría')
@section('page_subtitle', 'Actualización de categorías del sistema')

@section('content')
    <div class="card" style="max-width:800px; margin:0 auto;">
        <h2 style="margin-top:0;">Editar Categoría</h2>

        <form action="{{ route('categorias.update', $categoria) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nombre de la categoría</label>
                <input type="text" name="nombre" value="{{ old('nombre', $categoria->nombre) }}">
                @error('nombre')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                @error('descripcion')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="estado">
                    <option value="1" {{ old('estado', $categoria->estado ? '1' : '0') == '1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('estado', $categoria->estado ? '1' : '0') == '0' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">Actualizar categoría</button>
                <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>
@endsection