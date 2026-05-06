@extends('layouts.app')

@section('page_title', 'Panel Principal')
@section('page_subtitle', 'Acceda a los módulos principales del sistema')

@section('content')
    <div class="card">
        <h2 style="margin-top:0;">Bienvenido al sistema</h2>
        <p style="color:#cbd5e1;">
            Hola, {{ auth()->user()->name }}. Desde este panel podrá ingresar a las distintas áreas del sistema.
        </p>

        <div class="grid" style="margin-top:20px;">
            <div class="stat">
                <h3>Total de usuarios</h3>
                <p>{{ $totalUsuarios }}</p>
            </div>

            <div class="stat">
                <h3>Usuarios activos</h3>
                <p>{{ $usuariosActivos }}</p>
            </div>

            <div class="stat">
                <h3>Usuarios inactivos</h3>
                <p>{{ $usuariosInactivos }}</p>
            </div>

            <div class="stat">
                <h3>Usuarios bloqueados</h3>
                <p>{{ $usuariosBloqueados }}</p>
            </div>
        </div>

        <div style="margin-top:30px;">
            <h3 style="margin-bottom:15px;">Accesos rápidos</h3>

            <div class="module-grid">
                @if(auth()->user()->rol === 'Administrador')
                    <div class="module-box">
                        <h3>Usuarios</h3>
                        <p>Módulo listo para gestionar usuarios del sistema.</p>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-primary">Entrar</a>
                    </div>
                @endif

                @if(in_array(auth()->user()->rol, ['Administrador', 'Almacenero']))
                    <div class="module-box">
                        <h3>Técnicos</h3>
                        <p>Módulo para registrar y controlar el personal técnico.</p>
                        <a href="{{ route('tecnicos.index') }}" class="btn btn-success">Entrar</a>
                    </div>

                    <div class="module-box">
                        <h3>Categorías</h3>
                        <p>Módulo para clasificar materiales y organizar productos.</p>
                        <a href="{{ route('categorias.index') }}" class="btn btn-warning">Entrar</a>
                    </div>

                    <div class="module-box">
                        <h3>Productos</h3>
                        <p>Módulo para registrar materiales y controlar stock base del almacén.</p>
                        <a href="{{ route('productos.index') }}" class="btn btn-primary">Entrar</a>
                    </div>

                    <div class="module-box">
                        <h3>Entradas</h3>
                        <p>Módulo para registrar ingresos al almacén y aumentar stock.</p>
                        <a href="{{ route('entradas.index') }}" class="btn btn-success">Entrar</a>
                    </div>

                    <div class="module-box">
                        <h3>Salidas</h3>
                        <p>Módulo para entregar materiales a técnicos y descontar stock.</p>
                        <a href="{{ route('salidas.index') }}" class="btn btn-warning">Entrar</a>
                    </div>

                    <div class="module-box">
                        <h3>Devoluciones</h3>
                        <p>Módulo para registrar devoluciones desde técnicos y reingresar stock.</p>
                        <a href="{{ route('devoluciones.index') }}" class="btn btn-secondary">Entrar</a>
                    </div>

                    <div class="module-box">
                        <h3>Kardex</h3>
                        <p>Módulo para ver el historial completo de movimientos por producto.</p>
                        <a href="{{ route('kardex.index') }}" class="btn btn-primary">Entrar</a>
                    </div>

                    <div class="module-box">
                        <h3>Reportes</h3>
                        <p>Módulo para consultar stock, entradas, salidas y devoluciones.</p>
                        <a href="{{ route('reportes.index') }}" class="btn btn-success">Entrar</a>
                    </div>
                                        <div class="module-box">
                        <h3>chat</h3>
                        <p>Módulo para consultar stock, entradas, salidas y devoluciones.</p>
                        <a href="{{ route('reportes.index') }}" class="btn btn-success">Entrar</a>
                    </div>
                    
                @endif
            </div>
        </div>
    </div>
@endsection