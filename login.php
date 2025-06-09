<div class="login-wrapper">
  <div class="login-box">
    <h3 class="text-center mb-4"><i class="bi bi-building"></i> Sistema de Gestión Parroquial</h3>
    <form action="php/login.php" method="POST" autocomplete="on">
      <!-- Usuario -->
      <div class="mb-3 input-group">
        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
        <input type="text" name="usuario" class="form-control form-control-lg" placeholder="Usuario" required
          autocomplete="username">
      </div>

      <!-- Contraseña -->
      <div class="mb-4 input-group">
        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
        <input type="password" name="contrasena" class="form-control form-control-lg" placeholder="Contraseña"
          id="password" required autocomplete="current-password">
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