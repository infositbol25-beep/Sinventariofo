@extends('layouts.app')

@section('page_title', 'Editar Producto')
@section('page_subtitle', 'Actualización de materiales y productos')

@section('content')
    <div class="card" style="max-width:900px; margin:0 auto;">
        <h2 style="margin-top:0;">Editar Producto</h2>

        <form action="{{ route('productos.update', $producto) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Código</label>
                <input type="text" name="codigo" value="{{ old('codigo', $producto->codigo) }}">
                @error('codigo')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Nombre del producto</label>
                <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}">
                @error('nombre')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Categoría</label>
                <select name="categoria_id">
                    <option value="">Seleccione una categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
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
                    <option value="UND" {{ old('unidad_medida', $producto->unidad_medida) == 'UND' ? 'selected' : '' }}>UND</option>
                    <option value="M" {{ old('unidad_medida', $producto->unidad_medida) == 'M' ? 'selected' : '' }}>M</option>
                    <option value="ROLLO" {{ old('unidad_medida', $producto->unidad_medida) == 'ROLLO' ? 'selected' : '' }}>ROLLO</option>
                    <option value="CAJA" {{ old('unidad_medida', $producto->unidad_medida) == 'CAJA' ? 'selected' : '' }}>CAJA</option>
                    <option value="PAQUETE" {{ old('unidad_medida', $producto->unidad_medida) == 'PAQUETE' ? 'selected' : '' }}>PAQUETE</option>
                </select>
                @error('unidad_medida')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Stock actual</label>
                <input type="number" step="0.01" min="0" name="stock_actual" value="{{ old('stock_actual', $producto->stock_actual) }}">
                @error('stock_actual')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Stock mínimo</label>
                <input type="number" step="0.01" min="0" name="stock_minimo" value="{{ old('stock_minimo', $producto->stock_minimo) }}">
                @error('stock_minimo')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="estado">
                    <option value="1" {{ old('estado', $producto->estado ? '1' : '0') == '1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('estado', $producto->estado ? '1' : '0') == '0' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion">{{ old('descripcion', $producto->descripcion) }}</textarea>
                @error('descripcion')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">Actualizar producto</button>
                <a href="{{ route('productos.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>
@endsection