@extends('layouts.app')

@section('page_title', $titulo)
@section('page_subtitle', 'Módulo en construcción')

@section('content')
    <div class="card" style="max-width:850px; margin:0 auto;">
        <h2 style="margin-top:0;">{{ $titulo }}</h2>

        <p style="color:#cbd5e1; font-size:16px;">
            {{ $descripcion }}
        </p>

        <div style="margin-top:25px; padding:20px; border-radius:14px; background:rgba(255,255,255,0.05); border:1px dashed rgba(255,255,255,0.15);">
            <p style="margin:0 0 12px;">Este espacio queda listo para que luego agregues:</p>

            <ul style="margin:0; padding-left:20px; color:#cbd5e1; line-height:1.8;">
                <li>formularios</li>
                <li>tablas</li>
                <li>consultas</li>
                <li>reportes</li>
                <li>operaciones específicas del sistema</li>
            </ul>
        </div>

        <div style="margin-top:20px;">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">Volver al Panel Principal</a>
        </div>
    </div>
@endsection