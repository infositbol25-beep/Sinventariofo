<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario</title>
    <style>
        :root{
            --bg: #0b1220;
            --panel: #111827;
            --line: rgba(255,255,255,0.08);
            --text: #f8fafc;
            --muted: #94a3b8;
            --primary: #2563eb;
            --primary-soft: rgba(37,99,235,0.15);
            --success: #10b981;
            --danger: #dc2626;
            --warning: #f59e0b;
            --secondary: #475569;
            --shadow: 0 20px 40px rgba(0,0,0,0.25);
            --sidebar-width: 270px;
            --radius: 18px;
        }

        * { box-sizing: border-box; }

        body{
            margin:0;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #0b1220, #0f172a, #111827);
            color: var(--text);
        }

        .app-shell{ min-height:100vh; }

        .sidebar{
            position: fixed;
            top:0;
            left:0;
            width: var(--sidebar-width);
            height:100vh;
            background: rgba(17,24,39,0.96);
            backdrop-filter: blur(14px);
            border-right: 1px solid var(--line);
            padding: 22px 18px;
            z-index: 1000;
            overflow-y:auto;
            transition: transform .25s ease;
        }

        .brand{
            display:flex;
            align-items:center;
            gap:14px;
            margin-bottom: 24px;
        }

        .brand-icon{
            width:48px;
            height:48px;
            border-radius:14px;
            display:flex;
            align-items:center;
            justify-content:center;
            background: linear-gradient(135deg, var(--primary), #60a5fa);
            font-size:22px;
            box-shadow: 0 12px 25px rgba(37,99,235,.30);
        }

        .brand-text h1{ margin:0; font-size:1.05rem; }
        .brand-text p{ margin:4px 0 0; color:var(--muted); font-size:.85rem; }

        .nav-section{ margin-top: 20px; }

        .nav-section-title{
            color: var(--muted);
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin: 0 0 10px 10px;
        }

        .nav-link{
            display:flex;
            align-items:center;
            gap:12px;
            width:100%;
            text-decoration:none;
            color: var(--text);
            padding: 12px 14px;
            border-radius: 14px;
            margin-bottom: 8px;
            transition: all .2s ease;
            border: 1px solid transparent;
        }

        .nav-link:hover{
            background: rgba(255,255,255,0.05);
            border-color: var(--line);
        }

        .nav-link.active{
            background: var(--primary-soft);
            border-color: rgba(37,99,235,.30);
            color: #dbeafe;
        }

        .nav-icon{
            width:36px;
            height:36px;
            border-radius:10px;
            display:flex;
            align-items:center;
            justify-content:center;
            background: rgba(255,255,255,0.06);
            font-size:16px;
            flex-shrink:0;
        }

        .sidebar-footer{
            margin-top: 26px;
            padding: 14px;
            border-radius: 16px;
            background: rgba(255,255,255,0.04);
            border:1px solid var(--line);
        }

        .sidebar-footer strong{ display:block; margin-bottom:4px; }
        .sidebar-footer small{ color: var(--muted); }

        .main{
            margin-left: var(--sidebar-width);
            min-height:100vh;
            padding: 24px;
        }

        .topbar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:16px;
            margin-bottom:24px;
            flex-wrap:wrap;
        }

        .topbar-left{
            display:flex;
            align-items:center;
            gap:14px;
        }

        .page-title{ margin:0; font-size:1.35rem; }
        .page-subtitle{ margin:4px 0 0; color: var(--muted); font-size:.92rem; }

        .menu-toggle{
            display:none;
            border:none;
            background: rgba(255,255,255,0.08);
            color:white;
            width:44px;
            height:44px;
            border-radius:12px;
            cursor:pointer;
            font-size:18px;
        }

        .btn{
            display:inline-block;
            padding: 10px 16px;
            border:none;
            border-radius: 12px;
            text-decoration:none;
            cursor:pointer;
            font-weight:600;
            color:white;
        }

        .btn-primary{ background: var(--primary); }
        .btn-success{ background: var(--success); }
        .btn-warning{ background: var(--warning); }
        .btn-danger{ background: var(--danger); }
        .btn-secondary{ background: var(--secondary); }

        .alert{
            padding:14px;
            border-radius: 14px;
            margin-bottom:18px;
        }

        .alert-success{
            background: rgba(16,185,129,0.14);
            border:1px solid rgba(16,185,129,0.30);
            color:#d1fae5;
        }

        .alert-error{
            background: rgba(239,68,68,0.14);
            border:1px solid rgba(239,68,68,0.30);
            color:#fecaca;
        }

        .grid{
            display:grid;
            gap:18px;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }

        .stat{
            background: rgba(255,255,255,0.04);
            border:1px solid var(--line);
            border-radius:16px;
            padding:20px;
        }

        .stat h3{
            margin:0 0 8px;
            color:#cbd5e1;
            font-size:1rem;
        }

        .stat p{
            margin:0;
            font-size:2rem;
            font-weight:700;
        }

        .module-grid{
            display:grid;
            gap:16px;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            margin-top: 20px;
        }

        .module-box{
            background: rgba(255,255,255,0.04);
            border:1px solid var(--line);
            border-radius:16px;
            padding:20px;
        }

        .module-box h3{
            margin-top:0;
            margin-bottom:10px;
        }

        .module-box p{
            color:#cbd5e1;
            font-size:.92rem;
            min-height: 42px;
        }

        table{
            width:100%;
            border-collapse: collapse;
            margin-top:20px;
        }

        th, td{
            padding:12px;
            border-bottom:1px solid var(--line);
            text-align:left;
            vertical-align:top;
        }

        th{
            color:#93c5fd;
            font-weight:700;
        }

        .badge{
            display:inline-block;
            padding:6px 10px;
            border-radius:999px;
            font-size:.82rem;
            font-weight:700;
        }

        .badge-active{ background: rgba(16,185,129,0.15); color:#6ee7b7; }
        .badge-inactive{ background: rgba(239,68,68,0.15); color:#fca5a5; }
        .badge-locked{ background: rgba(245,158,11,0.15); color:#fcd34d; }
        .badge-low{ background: rgba(245,158,11,0.15); color:#fcd34d; }
        .badge-ok{ background: rgba(16,185,129,0.15); color:#6ee7b7; }
        .badge-registered{ background: rgba(16,185,129,0.15); color:#6ee7b7; }
        .badge-cancelled{ background: rgba(239,68,68,0.15); color:#fca5a5; }

        .form-group{ margin-bottom:16px; }

        label{
            display:block;
            margin-bottom:7px;
            font-weight:600;
        }

        input, select, textarea{
            width:100%;
            padding:12px;
            border-radius:12px;
            border:1px solid var(--line);
            background: rgba(255,255,255,0.06);
            color:white;
        }

        textarea{
            min-height: 110px;
            resize: vertical;
        }

        option{ color:black; }

        .error-text{
            color:#fca5a5;
            font-size:.85rem;
            margin-top:6px;
        }

        .row-actions{
            display:flex;
            gap:8px;
            flex-wrap:wrap;
        }

        .sidebar-overlay{ display:none; }

        @media (max-width: 980px){
            .sidebar{ transform: translateX(-100%); }
            .sidebar.open{ transform: translateX(0); }
            .main{ margin-left: 0; }

            .menu-toggle{
                display:inline-flex;
                align-items:center;
                justify-content:center;
            }

            .sidebar-overlay{
                display:none;
                position:fixed;
                inset:0;
                background: rgba(0,0,0,0.45);
                z-index: 900;
            }

            .sidebar-overlay.show{ display:block; }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        @auth
            <aside class="sidebar" id="sidebar">
                <div class="brand">
                    <div class="brand-icon">📦</div>
                    <div class="brand-text">
                        <h1>Sistema de Inventario</h1>
                        <p>Panel empresarial</p>
                    </div>
                </div>

                <div class="nav-section">
                    <p class="nav-section-title">Principal</p>

                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="nav-icon">🏠</span>
                        <span>Panel Principal</span>
                    </a>

                    @if(auth()->user()->rol === 'Administrador')
                        <a href="{{ route('usuarios.index') }}" class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                            <span class="nav-icon">👥</span>
                            <span>Usuarios</span>
                        </a>
                    @endif

                    @if(in_array(auth()->user()->rol, ['Administrador', 'Almacenero']))
                        <a href="{{ route('tecnicos.index') }}" class="nav-link {{ request()->routeIs('tecnicos.*') ? 'active' : '' }}">
                            <span class="nav-icon">🛠️</span>
                            <span>Técnicos</span>
                        </a>

                        <a href="{{ route('categorias.index') }}" class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}">
                            <span class="nav-icon">🗂️</span>
                            <span>Categorías</span>
                        </a>

                        <a href="{{ route('productos.index') }}" class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}">
                            <span class="nav-icon">📦</span>
                            <span>Productos</span>
                        </a>

                        <a href="{{ route('entradas.index') }}" class="nav-link {{ request()->routeIs('entradas.*') ? 'active' : '' }}">
                            <span class="nav-icon">⬇️</span>
                            <span>Entradas</span>
                        </a>

                        <a href="{{ route('salidas.index') }}" class="nav-link {{ request()->routeIs('salidas.*') ? 'active' : '' }}">
                            <span class="nav-icon">⬆️</span>
                            <span>Salidas</span>
                        </a>

                        <a href="{{ route('devoluciones.index') }}" class="nav-link {{ request()->routeIs('devoluciones.*') ? 'active' : '' }}">
                            <span class="nav-icon">↩️</span>
                            <span>Devoluciones</span>
                        </a>

                        <a href="{{ route('kardex.index') }}" class="nav-link {{ request()->routeIs('kardex.*') ? 'active' : '' }}">
                            <span class="nav-icon">📘</span>
                            <span>Kardex</span>
                        </a>

                        <a href="{{ route('reportes.index') }}" class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                            <span class="nav-icon">📊</span>
                            <span>Reportes</span>
                        </a>
                    @endif
                </div>

                <div class="nav-section">
                    <p class="nav-section-title">Cuenta</p>

                    <a href="{{ route('perfil.show') }}" class="nav-link {{ request()->routeIs('perfil.show') ? 'active' : '' }}">
                        <span class="nav-icon">🙍</span>
                        <span>Mi Perfil</span>
                    </a>

                    <a href="{{ route('perfil.password.form') }}" class="nav-link {{ request()->routeIs('perfil.password.form') ? 'active' : '' }}">
                        <span class="nav-icon">🔐</span>
                        <span>Cambiar Clave</span>
                    </a>
                </div>

                <div class="sidebar-footer">
                    <strong>{{ auth()->user()->name }}</strong>
                    <small>{{ auth()->user()->rol }}</small>
                </div>
            </aside>

            <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
        @endauth

        <main class="main">
            @auth
                <div class="topbar">
                    <div class="topbar-left">
                        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
                        <div>
                            <h2 class="page-title">@yield('page_title', 'Panel Principal')</h2>
                            <p class="page-subtitle">@yield('page_subtitle', 'Gestione los módulos del sistema')</p>
                        </div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-danger" type="submit">Cerrar sesión</button>
                    </form>
                </div>
            @endauth

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebar) {
                sidebar.classList.toggle('open');
            }

            if (overlay) {
                overlay.classList.toggle('show');
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebar) {
                sidebar.classList.remove('open');
            }

            if (overlay) {
                overlay.classList.remove('show');
            }
        }
    </script>
</body>
</html>