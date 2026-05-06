@extends('layouts.app')

@section('page_title', 'Entradas')
@section('page_subtitle', 'Registro de ingresos al almacén')

@section('content')
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
            <div>
                <h2 style="margin:0;">Listado de Entradas</h2>
                <p style="margin:6px 0 0; color:#94a3b8;">Controle los ingresos de materiales al almacén.</p>
            </div>

            <a href="{{ route('entradas.create') }}" class="btn btn-success">Nueva entrada</a>
        </div>

        <form method="GET" action="{{ route('entradas.index') }}" style="margin-top:18px;">
            <div style="display:grid; grid-template-columns: 1fr auto; gap:10px;">
                <input type="text" name="q" value="{{ $q }}" placeholder="Buscar por proveedor, documento, tipo o ID">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Proveedor</th>
                    <th>Documento</th>
                    <th>Registrado por</th>
                    <th>Items</th>
                    <th>Estado</th>
                    <th style="width:260px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entradas as $entrada)
                    <tr>
                        <td>{{ $entrada->id }}</td>
                        <td>{{ $entrada->fecha->format('d/m/Y') }}</td>
                        <td>{{ $entrada->tipo_ingreso }}</td>
                        <td>{{ $entrada->proveedor ?: '—' }}</td>
                        <td>{{ $entrada->documento_referencia ?: '—' }}</td>
                        <td>{{ $entrada->usuario?->name }}</td>
                        <td>{{ $entrada->detalles->count() }}</td>
                        <td>
                            @if($entrada->estado === 'REGISTRADA')
                                <span class="badge badge-registered">Registrada</span>
                            @else
                                <span class="badge badge-cancelled">Anulada</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('entradas.show', $entrada) }}" class="btn btn-primary">Ver detalle</a>

                                @if($entrada->estado === 'REGISTRADA')
                                    <form action="{{ route('entradas.destroy', $entrada) }}" method="POST" onsubmit="return confirm('¿Desea anular esta entrada? Esta acción revertirá el stock.')">
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
                        <td colspan="9">No hay entradas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection