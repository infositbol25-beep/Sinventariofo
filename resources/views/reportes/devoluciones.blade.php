@extends('layouts.app')

@section('page_title', 'Reporte de Devoluciones')
@section('page_subtitle', 'Consulta de materiales devueltos por técnicos')

@section('content')
    <div class="card">
        <form method="GET" action="{{ route('reportes.devoluciones') }}">
            <div class="grid">
                <div class="form-group">
                    <label>Producto</label>
                    <select name="producto_id">
                        <option value="">Todos</option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}" {{ $productoId == $producto->id ? 'selected' : '' }}>
                                {{ $producto->codigo }} - {{ $producto->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Técnico</label>
                    <select name="tecnico_id">
                        <option value="">Todos</option>
                        @foreach($tecnicos as $tecnico)
                            <option value="{{ $tecnico->id }}" {{ $tecnicoId == $tecnico->id ? 'selected' : '' }}>
                                {{ $tecnico->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Fecha desde</label>
                    <input type="date" name="fecha_desde" value="{{ $fechaDesde }}">
                </div>

                <div class="form-group">
                    <label>Fecha hasta</label>
                    <input type="date" name="fecha_hasta" value="{{ $fechaHasta }}">
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado">
                        <option value="">Todos</option>
                        <option value="REGISTRADA" {{ $estado == 'REGISTRADA' ? 'selected' : '' }}>REGISTRADA</option>
                        <option value="ANULADA" {{ $estado == 'ANULADA' ? 'selected' : '' }}>ANULADA</option>
                    </select>
                </div>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">Consultar</button>
                <a href="{{ route('reportes.devoluciones') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </form>
    </div>

    <div class="card" style="margin-top:20px;">
        <div class="grid">
            <div class="stat">
                <h3>Total registros</h3>
                <p>{{ $totalRegistros }}</p>
            </div>

            <div class="stat">
                <h3>Total cantidad</h3>
                <p>{{ number_format((float)$totalCantidad, 2) }}</p>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top:20px;">
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Devolución</th>
                    <th>Salida origen</th>
                    <th>Técnico</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Registrado por</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->devolucion->fecha->format('d/m/Y') }}</td>
                        <td>#{{ $detalle->devolucion->id }}</td>
                        <td>#{{ $detalle->devolucion->salida?->id }}</td>
                        <td>{{ $detalle->devolucion->salida?->tecnico?->nombre_completo }}</td>
                        <td>{{ $detalle->producto?->nombre }}</td>
                        <td>{{ number_format((float)$detalle->cantidad, 2) }}</td>
                        <td>{{ $detalle->devolucion->usuario?->name }}</td>
                        <td>{{ $detalle->devolucion->estado }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No hay resultados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection