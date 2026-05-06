@extends('layouts.app')

@section('page_title', 'Productos')
@section('page_subtitle', 'Gestión del catálogo de materiales y productos')

@section('content')
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
            <div>
                <h2 style="margin:0;">Listado de Productos</h2>
                <p style="margin:6px 0 0; color:#94a3b8;">Administre el catálogo de materiales de la empresa.</p>
            </div>

            <a href="{{ route('productos.create') }}" class="btn btn-success">Nuevo producto</a>
        </div>

        <form method="GET" action="{{ route('productos.index') }}" style="margin-top:18px;">
            <div style="display:grid; grid-template-columns: 1fr auto; gap:10px;">
                <input type="text" name="q" value="{{ $q }}" placeholder="Buscar por código, nombre o categoría">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Unidad</th>
                    <th>Stock actual</th>
                    <th>Stock mínimo</th>
                    <th>Estado</th>
                    <th>Control stock</th>
                    <th style="width:260px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                    <tr>
                        <td>{{ $producto->id }}</td>
                        <td>{{ $producto->codigo }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->categoria?->nombre }}</td>
                        <td>{{ $producto->unidad_medida }}</td>
                        <td>{{ number_format((float)$producto->stock_actual, 2) }}</td>
                        <td>{{ number_format((float)$producto->stock_minimo, 2) }}</td>
                        <td>
                            @if($producto->estado)
                                <span class="badge badge-active">Activo</span>
                            @else
                                <span class="badge badge-inactive">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            @if((float)$producto->stock_actual <= (float)$producto->stock_minimo && (float)$producto->stock_minimo > 0)
                                <span class="badge badge-low">Stock bajo</span>
                            @else
                                <span class="badge badge-ok">Normal</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning">Editar</a>

                                @if($producto->estado)
                                    <form action="{{ route('productos.destroy', $producto) }}" method="POST" onsubmit="return confirm('¿Desea desactivar este producto?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Desactivar</button>
                                    </form>
                                @else
                                    <form action="{{ route('productos.activate', $producto) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success">Activar</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">No hay productos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection