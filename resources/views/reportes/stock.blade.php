@extends('layouts.app')

@section('page_title', 'Reporte de Stock')
@section('page_subtitle', 'Consulta de existencias y stock bajo')

@section('content')
    <div class="card">
        <form method="GET" action="{{ route('reportes.stock') }}">
            <div class="grid">
                <div class="form-group">
                    <label>Buscar</label>
                    <input type="text" name="q" value="{{ $q }}" placeholder="Código, producto o categoría">
                </div>

                <div class="form-group">
                    <label>Solo stock bajo</label>
                    <select name="solo_bajo">
                        <option value="">Todos</option>
                        <option value="1" {{ $soloBajo ? 'selected' : '' }}>Sí</option>
                    </select>
                </div>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">Consultar</button>
                <a href="{{ route('reportes.stock') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </form>
    </div>

    <div class="card" style="margin-top:20px;">
        <div class="grid">
            <div class="stat">
                <h3>Total productos</h3>
                <p>{{ $totalProductos }}</p>
            </div>

            <div class="stat">
                <h3>Productos activos</h3>
                <p>{{ $productosActivos }}</p>
            </div>

            <div class="stat">
                <h3>Productos con stock bajo</h3>
                <p>{{ $productosBajoStock }}</p>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top:20px;">
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Unidad</th>
                    <th>Stock actual</th>
                    <th>Stock mínimo</th>
                    <th>Control</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                    <tr>
                        <td>{{ $producto->codigo }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->categoria?->nombre }}</td>
                        <td>{{ $producto->unidad_medida }}</td>
                        <td>{{ number_format((float)$producto->stock_actual, 2) }}</td>
                        <td>{{ number_format((float)$producto->stock_minimo, 2) }}</td>
                        <td>
                            @if((float)$producto->stock_actual <= (float)$producto->stock_minimo && (float)$producto->stock_minimo > 0)
                                <span class="badge badge-low">Stock bajo</span>
                            @else
                                <span class="badge badge-ok">Normal</span>
                            @endif
                        </td>
                        <td>
                            @if($producto->estado)
                                <span class="badge badge-active">Activo</span>
                            @else
                                <span class="badge badge-inactive">Inactivo</span>
                            @endif
                        </td>
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