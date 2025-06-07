<?php
session_start();

// Si no hay sesión iniciada o no hay usuario, redirigir al login
if (isset($_SESSION['usuario'])) {
    header('Location: admin/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
  <title>Login - Sistema Parroquial</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body, html {
      height: 100%;
      margin: 0;
      padding: 0;
      background: url('img/fondo.png') no-repeat center center fixed; 
      background-size: cover;
    }

    .login-wrapper {
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }

    .login-box {
      backdrop-filter: blur(10px);
      background-color: rgba(255, 255, 255, 0.9);
      border-radius: 1rem;
      padding: 2.5rem;
      width: 100%;
      max-width: 450px;
      box-shadow: 0 0 25px rgba(0, 0, 0, 0.4);
    }

    .form-control::placeholder {
      color: #888;
    }

    .toggle-password {
      cursor: pointer;
    }

    h3 {
      font-weight: bold;
    }

    @media (max-width: 576px) {
      .login-box {
        padding: 2rem 1.5rem;
      }
    }
  </style>
</head>
<body>

  <div class="login-wrapper">
  <?php if (isset($_GET['mensaje'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_GET['mensaje']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>


    <div class="login-box">
      <h3 class="text-center mb-4"><i class="bi bi-building"></i> Sistema de Gestión Parroquial</h3>
      <form action="php/auth.php" method="POST" autocomplete="on">
        <!-- Usuario -->
        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
          <input type="text" name="usuario" class="form-control form-control-lg" placeholder="Usuario" required autocomplete="username">
        </div>

        <!-- Contraseña -->
        <div class="mb-4 input-group">
          <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
          <input type="password" name="contrasena" class="form-control form-control-lg" placeholder="Contraseña" id="password" required autocomplete="current-password">
          <span class="input-group-text toggle-password" onclick="togglePassword()">
            <i class="bi bi-eye-slash" id="icono-password"></i>
          </span>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-box-arrow-in-right"></i> Ingresar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Mostrar/Ocultar contraseña -->
  <script>
    function togglePassword() {
      const passwordInput = document.getElementById("password");
      const icon = document.getElementById("icono-password");

      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
      } else {
        passwordInput.type = "password";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
      }
    }
  </script>
</body>
</html>
