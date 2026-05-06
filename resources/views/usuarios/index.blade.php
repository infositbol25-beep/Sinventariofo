@extends('layouts.app')

@section('content')
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
            <div>
                <h2 style="margin:0;">Gestión de Usuarios</h2>
                <p style="margin:6px 0 0; color:#94a3b8;">Administre los usuarios del sistema.</p>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Dashboard</a>
                <a href="{{ route('usuarios.create') }}" class="btn btn-success">Nuevo usuario</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Último acceso</th>
                    <th>Bloqueo</th>
                    <th style="width:320px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->username }}</td>
                        <td>{{ $usuario->email ?: '—' }}</td>
                        <td>{{ $usuario->rol }}</td>
                        <td>
                            @if($usuario->estado)
                                <span class="badge badge-active">Activo</span>
                            @else
                                <span class="badge badge-inactive">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            {{ $usuario->last_login_at ? $usuario->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                        </td>
                        <td>
                            @if($usuario->locked_until && $usuario->locked_until->isFuture())
                                <span class="badge badge-locked">
                                    Hasta {{ $usuario->locked_until->format('H:i') }}
                                </span>
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning">Editar</a>

                                @if($usuario->estado)
                                    <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" onsubmit="return confirm('¿Desea desactivar este usuario?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Desactivar</button>
                                    </form>
                                @else
                                    <form action="{{ route('usuarios.activate', $usuario) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success">Activar</button>
                                    </form>
                                @endif

                                @if($usuario->locked_until && $usuario->locked_until->isFuture())
                                    <form action="{{ route('usuarios.unlock', $usuario) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-secondary">Desbloquear</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection