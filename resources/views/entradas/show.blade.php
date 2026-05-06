@extends('layouts.app')

@section('page_title', 'Detalle de Entrada')
@section('page_subtitle', 'Consulta de ingreso al almacén')

@section('content')
    <div class="card">
        <h2 style="margin-top:0;">Entrada #{{ $entrada->id }}</h2>

        <div class="grid" style="margin-top:20px;">
            <div class="stat">
                <h3>Fecha</h3>
                <p style="font-size:1.2rem;">{{ $entrada->fecha->format('d/m/Y') }}</p>
            </div>

            <div class="stat">
                <h3>Tipo</h3>
                <p style="font-size:1.2rem;">{{ $entrada->tipo_ingreso }}</p>
            </div>

            <div class="stat">
                <h3>Proveedor</h3>
                <p style="font-size:1.2rem;">{{ $entrada->proveedor ?: '—' }}</p>
            </div>

            <div class="stat">
                <h3>Documento</h3>
                <p style="font-size:1.2rem;">{{ $entrada->documento_referencia ?: '—' }}</p>
            </div>

            <div class="stat">
                <h3>Registrado por</h3>
                <p style="font-size:1.2rem;">{{ $entrada->usuario?->name }}</p>
            </div>

            <div class="stat">
                <h3>Estado</h3>
                <p style="font-size:1.2rem;">{{ $entrada->estado }}</p>
            </div>
        </div>

        <div style="margin-top:24px;">
            <h3>Observaciones</h3>
            <div class="card" style="margin-top:10px; background:rgba(255,255,255,0.03);">
                {{ $entrada->observaciones ?: 'Sin observaciones.' }}
            </div>
        </div>

        <div style="margin-top:24px;">
            <h3>Detalle de productos</h3>

            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Código</th>
                        <th>Unidad</th>
                        <th>Cantidad</th>
                        <th>Costo referencial</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entrada->detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->producto?->nombre }}</td>
                            <td>{{ $detalle->producto?->codigo }}</td>
                            <td>{{ $detalle->producto?->unidad_medida }}</td>
                            <td>{{ number_format((float)$detalle->cantidad, 2) }}</td>
                            <td>{{ number_format((float)$detalle->costo_referencial, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top:24px; display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('entradas.index') }}" class="btn btn-secondary">Volver</a>

            @if($entrada->estado === 'REGISTRADA')
                <form action="{{ route('entradas.destroy', $entrada) }}" method="POST" onsubmit="return confirm('¿Desea anular esta entrada?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Anular entrada</button>
                </form>
            @endif
        </div>
    </div>
@endsection