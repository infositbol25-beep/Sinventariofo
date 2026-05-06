@extends('layouts.app')

@section('page_title', 'Reportes')
@section('page_subtitle', 'Panel de reportes del sistema')

@section('content')
    <div class="card">
        <h2 style="margin-top:0;">Módulo de Reportes</h2>
        <p style="color:#cbd5e1;">
            Desde aquí puede consultar los principales reportes operativos del almacén.
        </p>

        <div class="module-grid" style="margin-top:20px;">
            <div class="module-box">
                <h3>Reporte de stock</h3>
                <p>Consulta existencias actuales y productos con stock bajo.</p>
                <a href="{{ route('reportes.stock') }}" class="btn btn-primary">Ver reporte</a>
            </div>

            <div class="module-box">
                <h3>Reporte de entradas</h3>
                <p>Consulta ingresos al almacén por producto y fechas.</p>
                <a href="{{ route('reportes.entradas') }}" class="btn btn-success">Ver reporte</a>
            </div>

            <div class="module-box">
                <h3>Reporte de salidas</h3>
                <p>Consulta materiales entregados a técnicos.</p>
                <a href="{{ route('reportes.salidas') }}" class="btn btn-warning">Ver reporte</a>
            </div>

            <div class="module-box">
                <h3>Reporte de devoluciones</h3>
                <p>Consulta materiales devueltos al almacén.</p>
                <a href="{{ route('reportes.devoluciones') }}" class="btn btn-secondary">Ver reporte</a>
            </div>

            <div class="module-box">
                <h3>Conciliación por técnico</h3>
                <p>Compara lo entregado, lo devuelto y lo pendiente por cada técnico.</p>
                <a href="{{ route('reportes.conciliacion_tecnicos') }}" class="btn btn-danger">Ver reporte</a>
            </div>
        </div>
    </div>
@endsection