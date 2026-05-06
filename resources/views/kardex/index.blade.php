@extends('layouts.app')

@section('page_title', 'Kardex')
@section('page_subtitle', 'Historial de movimientos por producto')

@section('content')
    <div class="card">
        <h2 style="margin-top:0;">Consulta de Kardex</h2>

        <form method="GET" action="{{ route('kardex.index') }}" style="margin-top:20px;">
            <div class="grid">
                <div class="form-group">
                    <label>Producto</label>
                    <select name="producto_id">
                        <option value="">Seleccione un producto</option>
                        @foreach($productos as $item)
                            <option value="{{ $item->id }}" {{ request('producto_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->codigo }} - {{ $item->nombre }}
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
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">Consultar</button>
                <a href="{{ route('kardex.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </form>
    </div>

    @if($producto)
        <div class="card" style="margin-top:20px;">
            <h3 style="margin-top:0;">Resumen del producto</h3>

            <div class="grid" style="margin-top:15px;">
                <div class="stat">
                    <h3>Producto</h3>
                    <p style="font-size:1.1rem;">{{ $producto->nombre }}</p>
                </div>

                <div class="stat">
                    <h3>Código</h3>
                    <p style="font-size:1.1rem;">{{ $producto->codigo }}</p>
                </div>

                <div class="stat">
                    <h3>Unidad</h3>
                    <p style="font-size:1.1rem;">{{ $producto->unidad_medida }}</p>
                </div>

                <div class="stat">
                    <h3>Stock actual</h3>
                    <p style="font-size:1.1rem;">{{ number_format((float)$producto->stock_actual, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="card" style="margin-top:20px;">
            <h3 style="margin-top:0;">Resumen del período</h3>

            <div class="grid" style="margin-top:15px;">
                <div class="stat">
                    <h3>Saldo inicial</h3>
                    <p>{{ number_format((float)$saldoInicial, 2) }}</p>
                </div>

                <div class="stat">
                    <h3>Total entradas</h3>
                    <p>{{ number_format((float)$totalEntradas, 2) }}</p>
                </div>

                <div class="stat">
                    <h3>Total salidas</h3>
                    <p>{{ number_format((float)$totalSalidas, 2) }}</p>
                </div>

                <div class="stat">
                    <h3>Total devoluciones</h3>
                    <p>{{ number_format((float)$totalDevoluciones, 2) }}</p>
                </div>

                <div class="stat">
                    <h3>Saldo final</h3>
                    <p>{{ number_format((float)$saldoFinal, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="card" style="margin-top:20px;">
            <h3 style="margin-top:0;">Movimientos</h3>

            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Referencia</th>
                        <th>Detalle</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Saldo</th>
                        <th>Responsable</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movimientos as $movimiento)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($movimiento['fecha'])->format('d/m/Y') }}</td>
                            <td>{{ $movimiento['tipo'] }}</td>
                            <td>{{ $movimiento['referencia'] }}</td>
                            <td>{{ $movimiento['detalle'] }}</td>
                            <td>{{ number_format((float)$movimiento['entrada'], 2) }}</td>
                            <td>{{ number_format((float)$movimiento['salida'], 2) }}</td>
                            <td>{{ number_format((float)$movimiento['saldo'], 2) }}</td>
                            <td>{{ $movimiento['responsable'] ?: '—' }}</td>
                            <td>{{ $movimiento['observacion'] ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">No hay movimientos para el filtro seleccionado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
@endsection