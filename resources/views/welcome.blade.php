<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Sistema de Inventario</title>

  <style>
    :root {
      --bg-primary: #0f172a;
      --bg-secondary: #1e293b;
      --card-bg: rgba(255, 255, 255, 0.08);
      --card-border: rgba(255, 255, 255, 0.15);
      --input-bg: rgba(255, 255, 255, 0.06);
      --input-border: rgba(255, 255, 255, 0.12);
      --text-primary: #ffffff;
      --text-secondary: #cbd5e1;
      --accent: #3b82f6;
      --accent-hover: #2563eb;
      --success: #10b981;
      --success-hover: #059669;
      --shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
      --radius-lg: 20px;
      --radius-md: 12px;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      min-height: 100vh;
      font-family: Arial, Helvetica, sans-serif;
      background:
        radial-gradient(circle at top left, rgba(59, 130, 246, 0.25), transparent 30%),
        radial-gradient(circle at bottom right, rgba(16, 185, 129, 0.18), transparent 25%),
        linear-gradient(135deg, #0f172a, #111827, #1e293b);
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 24px;
      color: var(--text-primary);
    }

    .login-wrapper { width: 100%; max-width: 430px; }

    .login-card {
      background: var(--card-bg);
      backdrop-filter: blur(16px);
      border: 1px solid var(--card-border);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow);
      padding: 32px;
    }

    .brand {
      text-align: center;
      margin-bottom: 28px;
    }

    .brand-badge {
      width: 64px;
      height: 64px;
      margin: 0 auto 14px;
      border-radius: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, var(--accent), #60a5fa);
      box-shadow: 0 12px 25px rgba(59, 130, 246, 0.35);
      font-size: 28px;
    }

    .brand h1 { font-size: 1.7rem; margin-bottom: 6px; }
    .brand p { color: var(--text-secondary); font-size: 0.95rem; }

    .alert-error, .alert-success {
      padding: 12px;
      border-radius: 12px;
      margin-bottom: 18px;
      text-align: center;
      font-size: 0.92rem;
    }

    .alert-error {
      background: rgba(239, 68, 68, 0.18);
      border: 1px solid rgba(239, 68, 68, 0.35);
      color: #fecaca;
    }

    .alert-success {
      background: rgba(16,185,129,0.18);
      border: 1px solid rgba(16,185,129,0.35);
      color: #d1fae5;
    }

    .form-group { margin-bottom: 18px; }
    .form-label {
      display: block;
      margin-bottom: 8px;
      font-size: 0.95rem;
      font-weight: 600;
      color: #e5e7eb;
    }

    .input-wrapper { position: relative; }

    .input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 16px;
      color: #94a3b8;
      pointer-events: none;
    }

    .form-input {
      width: 100%;
      padding: 14px 14px 14px 44px;
      border-radius: var(--radius-md);
      border: 1px solid var(--input-border);
      background: var(--input-bg);
      color: var(--text-primary);
      outline: none;
      font-size: 0.95rem;
    }

    .password-toggle {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      border: none;
      background: transparent;
      color: #cbd5e1;
      cursor: pointer;
      font-size: 0.85rem;
      font-weight: 600;
      padding: 4px 6px;
    }

    .error-text {
      color: #fecaca;
      font-size: 0.85rem;
      margin-top: 8px;
      margin-left: 2px;
    }

    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
      margin-bottom: 22px;
      flex-wrap: wrap;
    }

    .remember-me {
      display: flex;
      align-items: center;
      gap: 8px;
      color: var(--text-secondary);
      font-size: 0.9rem;
    }

    .remember-me input { accent-color: var(--accent); cursor: pointer; }

    .helper-text {
      color: #93c5fd;
      font-size: 0.88rem;
    }

    .btn {
      width: 100%;
      border: none;
      border-radius: var(--radius-md);
      padding: 14px;
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      display: block;
      text-align: center;
      text-decoration: none;
    }

    .btn-login {
      background: linear-gradient(135deg, var(--accent), var(--accent-hover));
      color: white;
      margin-bottom: 12px;
    }

    .divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 22px 0;
      color: #94a3b8;
      font-size: 0.88rem;
    }

    .divider::before,
    .divider::after {
      content: "";
      flex: 1;
      height: 1px;
      background: rgba(255, 255, 255, 0.12);
    }

    .btn-create {
      background: linear-gradient(135deg, var(--success), var(--success-hover));
      color: white;
    }

    .footer-text {
      margin-top: 20px;
      text-align: center;
      color: var(--text-secondary);
      font-size: 0.85rem;
      line-height: 1.5;
    }
  </style>
</head>
<body>
  <div class="login-wrapper">
    <div class="login-card">
      <div class="brand">
        <div class="brand-badge">📦</div>
        <h1>Sistema de Inventario</h1>
        <p>Accede con tus credenciales para continuar</p>
      </div>

      @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
      @endif

      @if($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
      @endif

      <form action="{{ route('login.attempt') }}" method="POST">
        @csrf

        <div class="form-group">
          <label for="usuario" class="form-label">Usuario</label>
          <div class="input-wrapper">
            <span class="input-icon">👤</span>
            <input
              id="usuario"
              name="username"
              type="text"
              class="form-input"
              value="{{ old('username') }}"
              placeholder="Ingrese su usuario"
              required
            />
          </div>
          @error('username')
            <div class="error-text">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="password" class="form-label">Contraseña</label>
          <div class="input-wrapper">
            <span class="input-icon">🔒</span>
            <input
              id="password"
              name="password"
              type="password"
              class="form-input"
              placeholder="Ingrese su contraseña"
              required
            />
            <button type="button" class="password-toggle" onclick="togglePassword()">Ver</button>
          </div>
          @error('password')
            <div class="error-text">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-options">
          <label class="remember-me">
            <input type="checkbox" name="remember" />
            Recordarme
          </label>

          <span class="helper-text">Si olvidó su clave, solicite restablecimiento al administrador.</span>
        </div>

        <button type="submit" class="btn btn-login">Iniciar Sesión</button>
      </form>

      @if($puedeRegistrarse)
        <div class="divider">o</div>
        <a href="{{ route('usuarios.create') }}" class="btn btn-create">Crear primer usuario</a>
      @endif

      <p class="footer-text">
        Plataforma segura para la gestión de inventario, usuarios y movimientos.
      </p>
    </div>
  </div>

  <script>
    function togglePassword() {
      const passwordInput = document.getElementById("password");
      const toggleBtn = document.querySelector(".password-toggle");

      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleBtn.textContent = "Ocultar";
      } else {
        passwordInput.type = "password";
        toggleBtn.textContent = "Ver";
      }
    }
  </script>
</body>
</html>