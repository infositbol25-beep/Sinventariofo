@extends('layouts.app')

@section('page_title', 'Nueva Entrada')
@section('page_subtitle', 'Registro de ingresos al almacén')

@section('content')
    <div class="card" style="max-width:1100px; margin:0 auto;">
        <h2 style="margin-top:0;">Registrar Entrada</h2>

        @if($errors->any())
            <div class="alert alert-error">
                Revise los datos del formulario. Hay campos con error.
            </div>
        @endif

        <form action="{{ route('entradas.store') }}" method="POST">
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
                    <label>Tipo de ingreso</label>
                    <select name="tipo_ingreso">
                        <option value="COMPRA" {{ old('tipo_ingreso') == 'COMPRA' ? 'selected' : '' }}>COMPRA</option>
                        <option value="AJUSTE_INICIAL" {{ old('tipo_ingreso') == 'AJUSTE_INICIAL' ? 'selected' : '' }}>AJUSTE_INICIAL</option>
                        <option value="DEVOLUCION_INTERNA" {{ old('tipo_ingreso') == 'DEVOLUCION_INTERNA' ? 'selected' : '' }}>DEVOLUCION_INTERNA</option>
                        <option value="OTRO" {{ old('tipo_ingreso') == 'OTRO' ? 'selected' : '' }}>OTRO</option>
                    </select>
                    @error('tipo_ingreso')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Proveedor</label>
                    <input type="text" name="proveedor" value="{{ old('proveedor') }}">
                    @error('proveedor')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Documento de referencia</label>
                    <input type="text" name="documento_referencia" value="{{ old('documento_referencia') }}">
                    @error('documento_referencia')
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
                <div class="detalle-item" style="display:grid; grid-template-columns: 2fr 1fr 1fr auto; gap:10px; margin-bottom:12px;">
                    <div>
                        <select name="detalles[0][producto_id]">
                            <option value="">Seleccione producto</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}">
                                    {{ $producto->codigo }} - {{ $producto->nombre }} ({{ $producto->unidad_medida }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <input type="number" step="0.01" min="0.01" name="detalles[0][cantidad]" placeholder="Cantidad">
                    </div>
                    <div>
                        <input type="number" step="0.01" min="0" name="detalles[0][costo_referencial]" placeholder="Costo ref.">
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
                <button type="submit" class="btn btn-success">Guardar entrada</button>
                <a href="{{ route('entradas.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>

    <script>
        let detalleIndex = 1;

        function agregarFila() {
            const container = document.getElementById('detalle-container');

            const html = `
                <div class="detalle-item" style="display:grid; grid-template-columns: 2fr 1fr 1fr auto; gap:10px; margin-bottom:12px;">
                    <div>
                        <select name="detalles[${detalleIndex}][producto_id]">
                            <option value="">Seleccione producto</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}">
                                    {{ $producto->codigo }} - {{ $producto->nombre }} ({{ $producto->unidad_medida }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <input type="number" step="0.01" min="0.01" name="detalles[${detalleIndex}][cantidad]" placeholder="Cantidad">
                    </div>
                    <div>
                        <input type="number" step="0.01" min="0" name="detalles[${detalleIndex}][costo_referencial]" placeholder="Costo ref.">
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
                alert('Debe quedar al menos un producto en la entrada.');
                return;
            }

            button.closest('.detalle-item').remove();
        }
    </script>
@endsection