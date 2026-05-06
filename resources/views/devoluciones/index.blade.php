@extends('layouts.app')

@section('page_title', 'Devoluciones')
@section('page_subtitle', 'Registro de materiales devueltos al almacén')

@section('content')
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
            <div>
                <h2 style="margin:0;">Listado de Devoluciones</h2>
                <p style="margin:6px 0 0; color:#94a3b8;">Controle los materiales devueltos por los técnicos.</p>
            </div>

            <a href="{{ route('devoluciones.create') }}" class="btn btn-success">Nueva devolución</a>
        </div>

        <form method="GET" action="{{ route('devoluciones.index') }}" style="margin-top:18px;">
            <div style="display:grid; grid-template-columns: 1fr auto; gap:10px;">
                <input type="text" name="q" value="{{ $q }}" placeholder="Buscar por técnico, CI, trabajo o ID">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Salida origen</th>
                    <th>Técnico</th>
                    <th>Registrado por</th>
                    <th>Items</th>
                    <th>Estado</th>
                    <th style="width:260px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($devoluciones as $devolucion)
                    <tr>
                        <td>{{ $devolucion->id }}</td>
                        <td>{{ $devolucion->fecha->format('d/m/Y') }}</td>
                        <td>#{{ $devolucion->salida?->id }}</td>
                        <td>{{ $devolucion->salida?->tecnico?->nombre_completo }}</td>
                        <td>{{ $devolucion->usuario?->name }}</td>
                        <td>{{ $devolucion->detalles->count() }}</td>
                        <td>
                            @if($devolucion->estado === 'REGISTRADA')
                                <span class="badge badge-registered">Registrada</span>
                            @else
                                <span class="badge badge-cancelled">Anulada</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('devoluciones.show', $devolucion) }}" class="btn btn-primary">Ver detalle</a>

                                @if($devolucion->estado === 'REGISTRADA')
                                    <form action="{{ route('devoluciones.destroy', $devolucion) }}" method="POST" onsubmit="return confirm('¿Desea anular esta devolución? Esta acción descontará nuevamente el stock.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Anular</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No hay devoluciones registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection