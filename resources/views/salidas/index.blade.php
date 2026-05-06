@extends('layouts.app')

@section('page_title', 'Salidas')
@section('page_subtitle', 'Registro de materiales entregados a técnicos')

@section('content')
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
            <div>
                <h2 style="margin:0;">Listado de Salidas</h2>
                <p style="margin:6px 0 0; color:#94a3b8;">Controle los materiales entregados al personal técnico.</p>
            </div>

            <a href="{{ route('salidas.create') }}" class="btn btn-success">Nueva salida</a>
        </div>

        <form method="GET" action="{{ route('salidas.index') }}" style="margin-top:18px;">
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
                    <th>Técnico</th>
                    <th>Trabajo / Referencia</th>
                    <th>Registrado por</th>
                    <th>Items</th>
                    <th>Estado</th>
                    <th style="width:260px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salidas as $salida)
                    <tr>
                        <td>{{ $salida->id }}</td>
                        <td>{{ $salida->fecha->format('d/m/Y') }}</td>
                        <td>{{ $salida->tecnico?->nombre_completo }}</td>
                        <td>{{ $salida->trabajo_referencia ?: '—' }}</td>
                        <td>{{ $salida->usuario?->name }}</td>
                        <td>{{ $salida->detalles->count() }}</td>
                        <td>
                            @if($salida->estado === 'REGISTRADA')
                                <span class="badge badge-registered">Registrada</span>
                            @else
                                <span class="badge badge-cancelled">Anulada</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('salidas.show', $salida) }}" class="btn btn-primary">Ver detalle</a>

                                @if($salida->estado === 'REGISTRADA')
                                    <form action="{{ route('salidas.destroy', $salida) }}" method="POST" onsubmit="return confirm('¿Desea anular esta salida? Esta acción devolverá el stock.')">
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
                        <td colspan="8">No hay salidas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection