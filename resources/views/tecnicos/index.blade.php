@extends('layouts.app')

@section('page_title', 'Técnicos')
@section('page_subtitle', 'Gestión del personal técnico')

@section('content')
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
            <div>
                <h2 style="margin:0;">Listado de Técnicos</h2>
                <p style="margin:6px 0 0; color:#94a3b8;">Administre el personal técnico de la empresa.</p>
            </div>

            <a href="{{ route('tecnicos.create') }}" class="btn btn-success">Nuevo técnico</a>
        </div>

        <form method="GET" action="{{ route('tecnicos.index') }}" style="margin-top:18px;">
            <div style="display:grid; grid-template-columns: 1fr auto; gap:10px;">
                <input type="text" name="q" value="{{ $q }}" placeholder="Buscar por nombre, CI o teléfono">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre completo</th>
                    <th>CI</th>
                    <th>Teléfono</th>
                    <th>Cargo</th>
                    <th>Cuadrilla</th>
                    <th>Zona</th>
                    <th>Estado</th>
                    <th style="width:260px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tecnicos as $tecnico)
                    <tr>
                        <td>{{ $tecnico->id }}</td>
                        <td>{{ $tecnico->nombre_completo }}</td>
                        <td>{{ $tecnico->ci }}</td>
                        <td>{{ $tecnico->telefono ?: '—' }}</td>
                        <td>{{ $tecnico->cargo }}</td>
                        <td>{{ $tecnico->cuadrilla ?: '—' }}</td>
                        <td>{{ $tecnico->zona ?: '—' }}</td>
                        <td>
                            @if($tecnico->estado)
                                <span class="badge badge-active">Activo</span>
                            @else
                                <span class="badge badge-inactive">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('tecnicos.edit', $tecnico) }}" class="btn btn-warning">Editar</a>

                                @if($tecnico->estado)
                                    <form action="{{ route('tecnicos.destroy', $tecnico) }}" method="POST" onsubmit="return confirm('¿Desea desactivar este técnico?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Desactivar</button>
                                    </form>
                                @else
                                    <form action="{{ route('tecnicos.activate', $tecnico) }}" method="POST">
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
                        <td colspan="9">No hay técnicos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection