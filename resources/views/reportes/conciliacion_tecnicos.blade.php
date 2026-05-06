@extends('layouts.app')

@section('page_title', 'Conciliación por Técnico')
@section('page_subtitle', 'Entregado, devuelto y pendiente por técnico')

@section('content')
    <div class="card">
        <form method="GET" action="{{ route('reportes.conciliacion_tecnicos') }}">
            <div class="grid">
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
                    <label>Solo con pendiente</label>
                    <select name="solo_pendientes">
                        <option value="">Todos</option>
                        <option value="1" {{ $soloPendientes ? 'selected' : '' }}>Sí</option>
                    </select>
                </div>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">Consultar</button>
                <a href="{{ route('reportes.conciliacion_tecnicos') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </form>
    </div>

    <div class="card" style="margin-top:20px;">
        <div class="grid">
            <div class="stat">
                <h3>Total entregado</h3>
                <p>{{ number_format((float)$totalEntregado, 2) }}</p>
            </div>

            <div class="stat">
                <h3>Total devuelto</h3>
                <p>{{ number_format((float)$totalDevuelto, 2) }}</p>
            </div>

            <div class="stat">
                <h3>Total pendiente</h3>
                <p>{{ number_format((float)$totalPendiente, 2) }}</p>
            </div>

            <div class="stat">
                <h3>Técnicos con pendiente</h3>
                <p>{{ $tecnicosConPendiente }}</p>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top:20px;">
        <h3 style="margin-top:0;">Resumen por técnico</h3>

        <table>
            <thead>
                <tr>
                    <th>Técnico</th>
                    <th>Entregado</th>
                    <th>Devuelto</th>
                    <th>Pendiente</th>
                    <th>Control</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resumenTecnicos as $fila)
                    <tr>
                        <td>{{ $fila['tecnico_nombre'] }}</td>
                        <td>{{ number_format((float)$fila['entregado'], 2) }}</td>
                        <td>{{ number_format((float)$fila['devuelto'], 2) }}</td>
                        <td>{{ number_format((float)$fila['pendiente'], 2) }}</td>
                        <td>
                            @if($fila['pendiente'] > 0)
                                <span class="badge badge-low">Pendiente</span>
                            @else
                                <span class="badge badge-ok">Conciliado</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No hay resultados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card" style="margin-top:20px;">
        <h3 style="margin-top:0;">Detalle por producto</h3>

        <table>
            <thead>
                <tr>
                    <th>Técnico</th>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Unidad</th>
                    <th>Entregado</th>
                    <th>Devuelto</th>
                    <th>Pendiente</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detalleConciliacion as $fila)
                    <tr>
                        <td>{{ $fila['tecnico_nombre'] }}</td>
                        <td>{{ $fila['producto_codigo'] }}</td>
                        <td>{{ $fila['producto_nombre'] }}</td>
                        <td>{{ $fila['unidad_medida'] }}</td>
                        <td>{{ number_format((float)$fila['entregado'], 2) }}</td>
                        <td>{{ number_format((float)$fila['devuelto'], 2) }}</td>
                        <td>{{ number_format((float)$fila['pendiente'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No hay resultados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection