@extends('layouts.app')

@section('page_title', 'Nueva Devolución')
@section('page_subtitle', 'Registro de material devuelto por técnico')

@section('content')
    <div class="card" style="max-width:1100px; margin:0 auto;">
        <h2 style="margin-top:0;">Registrar Devolución</h2>

        @if($errors->any())
            <div class="alert alert-error">
                Revise los datos del formulario. Hay campos con error.
            </div>
        @endif

        <form action="{{ route('devoluciones.store') }}" method="POST">
            @csrf

            <div class="grid">
                <div class="form-group">
                    <label>Fecha</label>
                    <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}">
                    @error('fecha')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Salida origen</label>
                    <select name="salida_id">
                        <option value="">Seleccione una salida</option>
                        @foreach($salidas as $salida)
                            <option value="{{ $salida->id }}" {{ old('salida_id') == $salida->id ? 'selected' : '' }}>
                                #{{ $salida->id }} - {{ $salida->tecnico?->nombre_completo }} - {{ $salida->fecha->format('d/m/Y') }} - {{ $salida->trabajo_referencia ?: 'Sin referencia' }}
                            </option>
                        @endforeach
                    </select>
                    @error('salida_id')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones">{{ old('observaciones') }}</textarea>
                @error('observaciones')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <hr style="border-color: rgba(255,255,255,0.08); margin: 24px 0;">

            <h3>Detalle de productos</h3>

            <div id="detalle-container">
                <div class="detalle-item" style="display:grid; grid-template-columns: 2fr 1fr auto; gap:10px; margin-bottom:12px;">
                    <div>
                        <select name="detalles[0][producto_id]">
                            <option value="">Seleccione producto</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}">
                                    {{ $producto->codigo }} - {{ $producto->nombre }} | Stock actual: {{ number_format((float)$producto->stock_actual, 2) }} {{ $producto->unidad_medida }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <input type="number" step="0.01" min="0.01" name="detalles[0][cantidad]" placeholder="Cantidad">
                    </div>
                    <div>
                        <button type="button" class="btn btn-danger" onclick="eliminarFila(this)">X</button>
                    </div>
                </div>
            </div>

            @error('detalles')
                <div class="error-text">{{ $message }}</div>
            @enderror

            <div style="margin-top:12px;">
                <button type="button" class="btn btn-secondary" onclick="agregarFila()">Agregar producto</button>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:24px;">
                <button type="submit" class="btn btn-success">Guardar devolución</button>
                <a href="{{ route('devoluciones.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>

    <script>
        let detalleIndex = 1;

        function agregarFila() {
            const container = document.getElementById('detalle-container');

            const html = `
                <div class="detalle-item" style="display:grid; grid-template-columns: 2fr 1fr auto; gap:10px; margin-bottom:12px;">
                    <div>
                        <select name="detalles[${detalleIndex}][producto_id]">
                            <option value="">Seleccione producto</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}">
                                    {{ $producto->codigo }} - {{ $producto->nombre }} | Stock actual: {{ number_format((float)$producto->stock_actual, 2) }} {{ $producto->unidad_medida }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <input type="number" step="0.01" min="0.01" name="detalles[${detalleIndex}][cantidad]" placeholder="Cantidad">
                    </div>
                    <div>
                        <button type="button" class="btn btn-danger" onclick="eliminarFila(this)">X</button>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', html);
            detalleIndex++;
        }

        function eliminarFila(button) {
            const filas = document.querySelectorAll('.detalle-item');

            if (filas.length === 1) {
                alert('Debe quedar al menos un producto en la devolución.');
                return;
            }

            button.closest('.detalle-item').remove();
        }
    </script>
@endsection