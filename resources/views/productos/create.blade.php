@extends('layouts.app')

@section('page_title', 'Nuevo Producto')
@section('page_subtitle', 'Registro de materiales y productos del almacén')

@section('content')
    <div class="card" style="max-width:900px; margin:0 auto;">
        <h2 style="margin-top:0;">Registrar Producto</h2>

        <form action="{{ route('productos.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Código</label>
                <input type="text" name="codigo" value="{{ old('codigo') }}" placeholder="Ej.: CBL-001">
                @error('codigo')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Nombre del producto</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}">
                @error('nombre')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Categoría</label>
                <select name="categoria_id">
                    <option value="">Seleccione una categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('categoria_id')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Unidad de medida</label>
                <select name="unidad_medida">
                    <option value="UND" {{ old('unidad_medida') == 'UND' ? 'selected' : '' }}>UND</option>
                    <option value="M" {{ old('unidad_medida') == 'M' ? 'selected' : '' }}>M</option>
                    <option value="ROLLO" {{ old('unidad_medida') == 'ROLLO' ? 'selected' : '' }}>ROLLO</option>
                    <option value="CAJA" {{ old('unidad_medida') == 'CAJA' ? 'selected' : '' }}>CAJA</option>
                    <option value="PAQUETE" {{ old('unidad_medida') == 'PAQUETE' ? 'selected' : '' }}>PAQUETE</option>
                </select>
                @error('unidad_medida')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Stock actual</label>
                <input type="number" step="0.01" min="0" name="stock_actual" value="{{ old('stock_actual', 0) }}">
                @error('stock_actual')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Stock mínimo</label>
                <input type="number" step="0.01" min="0" name="stock_minimo" value="{{ old('stock_minimo', 0) }}">
                @error('stock_minimo')
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
                <button type="submit" class="btn btn-success">Guardar producto</button>
                <a href="{{ route('productos.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>
@endsection