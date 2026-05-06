@extends('layouts.app')

@section('page_title', 'Detalle de Devolución')
@section('page_subtitle', 'Consulta de materiales devueltos')

@section('content')
    <div class="card">
        <h2 style="margin-top:0;">Devolución #{{ $devolucion->id }}</h2>

        <div class="grid" style="margin-top:20px;">
            <div class="stat">
                <h3>Fecha</h3>
                <p style="font-size:1.2rem;">{{ $devolucion->fecha->format('d/m/Y') }}</p>
            </div>

            <div class="stat">
                <h3>Salida origen</h3>
                <p style="font-size:1.2rem;">#{{ $devolucion->salida?->id }}</p>
            </div>

            <div class="stat">
                <h3>Técnico</h3>
                <p style="font-size:1.2rem;">{{ $devolucion->salida?->tecnico?->nombre_completo }}</p>
            </div>

            <div class="stat">
                <h3>CI</h3>
                <p style="font-size:1.2rem;">{{ $devolucion->salida?->tecnico?->ci }}</p>
            </div>

            <div class="stat">
                <h3>Registrado por</h3>
                <p style="font-size:1.2rem;">{{ $devolucion->usuario?->name }}</p>
            </div>

            <div class="stat">
                <h3>Estado</h3>
                <p style="font-size:1.2rem;">{{ $devolucion->estado }}</p>
            </div>
        </div>

        <div style="margin-top:24px;">
            <h3>Observaciones</h3>
            <div class="card" style="margin-top:10px; background:rgba(255,255,255,0.03);">
                {{ $devolucion->observaciones ?: 'Sin observaciones.' }}
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
                        <th>Cantidad devuelta</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($devolucion->detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->producto?->nombre }}</td>
                            <td>{{ $detalle->producto?->codigo }}</td>
                            <td>{{ $detalle->producto?->unidad_medida }}</td>
                            <td>{{ number_format((float)$detalle->cantidad, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top:24px; display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('devoluciones.index') }}" class="btn btn-secondary">Volver</a>

            @if($devolucion->estado === 'REGISTRADA')
                <form action="{{ route('devoluciones.destroy', $devolucion) }}" method="POST" onsubmit="return confirm('¿Desea anular esta devolución?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Anular devolución</button>
                </form>
            @endif
        </div>
    </div>
@endsection