@extends('layouts.app')

@section('page_title', 'Nuevo Técnico')
@section('page_subtitle', 'Registro de personal técnico')

@section('content')
    <div class="card" style="max-width:800px; margin:0 auto;">
        <h2 style="margin-top:0;">Registrar Técnico</h2>

        <form action="{{ route('tecnicos.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Nombre completo</label>
                <input type="text" name="nombre_completo" value="{{ old('nombre_completo') }}">
                @error('nombre_completo')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>CI / Documento</label>
                <input type="text" name="ci" value="{{ old('ci') }}">
                @error('ci')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" value="{{ old('telefono') }}">
                @error('telefono')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Cargo</label>
                <input type="text" name="cargo" value="{{ old('cargo', 'Técnico') }}">
                @error('cargo')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Cuadrilla</label>
                <input type="text" name="cuadrilla" value="{{ old('cuadrilla') }}">
                @error('cuadrilla')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Zona</label>
                <input type="text" name="zona" value="{{ old('zona') }}">
                @error('zona')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones">{{ old('observaciones') }}</textarea>
                @error('observaciones')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn btn-success">Guardar técnico</button>
                <a href="{{ route('tecnicos.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>
@endsection