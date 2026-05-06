<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg,#0f172a,#1e293b);
            color:white;
            display:flex;
            justify-content:center;
            align-items:center;
            min-height:100vh;
            margin:0;
            padding:20px;
        }
        .card{
            background: rgba(255,255,255,0.08);
            padding:30px;
            border-radius:20px;
            width:100%;
            max-width:460px;
            box-shadow:0 20px 50px rgba(0,0,0,.35);
            backdrop-filter: blur(12px);
        }
        h1{
            text-align:center;
            margin-bottom:20px;
        }
        .info{
            background: rgba(59,130,246,.18);
            border: 1px solid rgba(59,130,246,.35);
            color:#dbeafe;
            padding:12px;
            border-radius:10px;
            margin-bottom:15px;
            font-size:14px;
        }
        .error-general{
            background: rgba(239,68,68,.18);
            border: 1px solid rgba(239,68,68,.35);
            color:#fecaca;
            padding:12px;
            border-radius:10px;
            margin-bottom:15px;
        }
        label{
            display:block;
            margin-bottom:6px;
            font-weight:bold;
        }
        input, select{
            width:100%;
            padding:12px;
            margin-bottom:15px;
            border:none;
            border-radius:10px;
            outline:none;
            box-sizing:border-box;
        }
        option {
            color: black;
        }
        button, a{
            display:inline-block;
            width:100%;
            text-align:center;
            padding:12px;
            border:none;
            border-radius:10px;
            text-decoration:none;
            font-weight:bold;
            cursor:pointer;
            box-sizing:border-box;
        }
        button{
            background:#10b981;
            color:white;
            margin-bottom:10px;
        }
        a{
            background:#3b82f6;
            color:white;
        }
        .error-texto{
            color:#fecaca;
            font-size:14px;
            margin-top:-10px;
            margin-bottom:12px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>{{ $esPrimerUsuario ? 'Crear Administrador Inicial' : 'Crear Usuario' }}</h1>

        @if($errors->any())
            <div class="error-general">
                Hay errores en el formulario. Revise los datos ingresados.
            </div>
        @endif

        @if($esPrimerUsuario)
            <div class="info">
                Este será el primer usuario del sistema y se registrará automáticamente como <strong>Administrador</strong>.
            </div>
        @endif

        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf

            <label>Nombre completo</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Ingrese su nombre completo">
            @error('name')
                <div class="error-texto">{{ $message }}</div>
            @enderror

            <label>Usuario</label>
            <input type="text" name="username" value="{{ old('username') }}" placeholder="Ingrese su usuario">
            @error('username')
                <div class="error-texto">{{ $message }}</div>
            @enderror

            <label>Correo</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Ingrese su correo">
            @error('email')
                <div class="error-texto">{{ $message }}</div>
            @enderror

            <label>Contraseña</label>
            <input type="password" name="password" placeholder="Ingrese su contraseña">
            @error('password')
                <div class="error-texto">{{ $message }}</div>
            @enderror

            <label>Confirmar contraseña</label>
            <input type="password" name="password_confirmation" placeholder="Repita su contraseña">

            @if(!$esPrimerUsuario)
                <label>Rol</label>
                <select name="rol">
                    <option value="">Seleccione un rol</option>
                    <option value="Administrador" {{ old('rol') == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                    <option value="Almacenero" {{ old('rol') == 'Almacenero' ? 'selected' : '' }}>Almacenero</option>
                </select>
                @error('rol')
                    <div class="error-texto">{{ $message }}</div>
                @enderror
            @endif

            <button type="submit">Guardar Usuario</button>
        </form>

        <a href="{{ auth()->check() ? route('usuarios.index') : route('login') }}">
            {{ auth()->check() ? 'Volver a Usuarios' : 'Volver al Login' }}
        </a>
    </div>
</body>
</html>