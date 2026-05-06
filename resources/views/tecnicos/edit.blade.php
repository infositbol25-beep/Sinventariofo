@extends('layouts.app')

@section('page_title', 'Editar Técnico')
@section('page_subtitle', 'Actualización de datos del personal técnico')

@section('content')
    <div class="card" style="max-width:800px; margin:0 auto;">
        <h2 style="margin-top:0;">Editar Técnico</h2>

        <form action="{{ route('tecnicos.update', $tecnico) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nombre completo</label>
                <input type="text" name="nombre_completo" value="{{ old('nombre_completo', $tecnico->nombre_completo) }}">
                @error('nombre_completo')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>CI / Documento</label>
                <input type="text" name="ci" value="{{ old('ci', $tecnico->ci) }}">
                @error('ci')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" value="{{ old('telefono', $tecnico->telefono) }}">
                @error('telefono')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Cargo</label>
                <input type="text" name="cargo" value="{{ old('cargo', $tecnico->cargo) }}">
                @error('cargo')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Cuadrilla</label>
                <input type="text" name="cuadrilla" value="{{ old('cuadrilla', $tecnico->cuadrilla) }}">
                @error('cuadrilla')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Zona</label>
                <input type="text" name="zona" value="{{ old('zona', $tecnico->zona) }}">
                @error('zona')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="estado">
                    <option value="1" {{ old('estado', $tecnico->estado ? '1' : '0') == '1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('estado', $tecnico->estado ? '1' : '0') == '0' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones">{{ old('observaciones', $tecnico->observaciones) }}</textarea>
                @error('observaciones')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">Actualizar técnico</button>
                <a href="{{ route('tecnicos.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>
@endsection