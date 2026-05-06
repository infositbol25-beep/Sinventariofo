@extends('layouts.app')

@section('page_title', 'Reporte de Entradas')
@section('page_subtitle', 'Consulta de ingresos al almacén')

@section('content')
    <div class="card">
        <form method="GET" action="{{ route('reportes.entradas') }}">
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
                <a href="{{ route('reportes.entradas') }}" class="btn btn-secondary">Limpiar</a>
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
                    <th>Entrada</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Costo ref.</th>
                    <th>Proveedor</th>
                    <th>Documento</th>
                    <th>Registrado por</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->entrada->fecha->format('d/m/Y') }}</td>
                        <td>#{{ $detalle->entrada->id }}</td>
                        <td>{{ $detalle->producto?->nombre }}</td>
                        <td>{{ number_format((float)$detalle->cantidad, 2) }}</td>
                        <td>{{ number_format((float)$detalle->costo_referencial, 2) }}</td>
                        <td>{{ $detalle->entrada->proveedor ?: '—' }}</td>
                        <td>{{ $detalle->entrada->documento_referencia ?: '—' }}</td>
                        <td>{{ $detalle->entrada->usuario?->name }}</td>
                        <td>{{ $detalle->entrada->estado }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">No hay resultados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection