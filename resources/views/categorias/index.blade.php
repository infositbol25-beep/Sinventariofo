@extends('layouts.app')

@section('page_title', 'Categorías')
@section('page_subtitle', 'Gestión de categorías del sistema')

@section('content')
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
            <div>
                <h2 style="margin:0;">Listado de Categorías</h2>
                <p style="margin:6px 0 0; color:#94a3b8;">Administre las clasificaciones de materiales y productos.</p>
            </div>

            <a href="{{ route('categorias.create') }}" class="btn btn-success">Nueva categoría</a>
        </div>

        <form method="GET" action="{{ route('categorias.index') }}" style="margin-top:18px;">
            <div style="display:grid; grid-template-columns: 1fr auto; gap:10px;">
                <input type="text" name="q" value="{{ $q }}" placeholder="Buscar por nombre de categoría">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th style="width:260px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categorias as $categoria)
                    <tr>
                        <td>{{ $categoria->id }}</td>
                        <td>{{ $categoria->nombre }}</td>
                        <td>{{ $categoria->descripcion ?: '—' }}</td>
                        <td>
                            @if($categoria->estado)
                                <span class="badge badge-active">Activo</span>
                            @else
                                <span class="badge badge-inactive">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-warning">Editar</a>

                                @if($categoria->estado)
                                    <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" onsubmit="return confirm('¿Desea desactivar esta categoría?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Desactivar</button>
                                    </form>
                                @else
                                    <form action="{{ route('categorias.activate', $categoria) }}" method="POST">
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
                        <td colspan="5">No hay categorías registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection