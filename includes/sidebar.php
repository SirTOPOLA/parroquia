<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<div class="sidebar d-flex flex-column p-3">
  <h4 class="text-white text-center mb-4"><i class="bi bi-building"></i> Parroquia</h4>
  <ul class="nav nav-pills flex-column mb-auto">

    <!-- Inicio -->
    <li class="nav-item">
      <a href="index.php" class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>">
        <i class="bi bi-house-door-fill me-2"></i>Inicio
      </a>
    </li>

    <!-- Parroquias y Párrocos -->
    <li>
      <a href="parroquias.php" class="nav-link <?= $currentPage == 'parroquias.php' ? 'active' : '' ?>">
        <i class="bi bi-geo-alt-fill me-2"></i>Parroquias
      </a>
    </li>
    <!-- <li>
      <a href="parrocos.php" class="nav-link <?= $currentPage == 'parrocos.php' ? 'active' : '' ?>">
        <i class="bi bi-person-vcard me-2"></i>Párrocos
      </a>
    </li>
 -->
    <!-- Feligreses y Parientes -->
    <li>
      <a href="feligreses.php" class="nav-link <?= $currentPage == 'feligreses.php' ? 'active' : '' ?>">
        <i class="bi bi-person-fill me-2"></i>Feligreses
      </a>
    </li>
    <li>
      <a href="parientes.php" class="nav-link <?= $currentPage == 'parientes.php' ? 'active' : '' ?>">
        <i class="bi bi-people me-2"></i>Parientes
      </a>
    </li>

    <!-- Sacramentos -->
    <li>
      <a href="sacramentos.php" class="nav-link <?= $currentPage == 'sacramentos.php' ? 'active' : '' ?>">
        <i class="bi bi-book me-2"></i>Sacramentos
      </a>
    </li>
    <!-- <li>
      <a href="registros_sacramentos.php" class="nav-link <?= $currentPage == 'registros_sacramentos.php' ? 'active' : '' ?>">
        <i class="bi bi-journal-check me-2"></i>Registros
      </a>
    </li> -->

    <!-- Catequesis -->
    <!-- <li>
      <a href="catequesis.php" class="nav-link <?= $currentPage == 'catequesis.php' ? 'active' : '' ?>">
        <i class="bi bi-journal-bookmark-fill me-2"></i>Catequesis
      </a>
    </li>
    <li>
      <a href="cursos.php" class="nav-link <?= $currentPage == 'cursos.php' ? 'active' : '' ?>">
        <i class="bi bi-book-half me-2"></i>Cursos
      </a>
    </li> -->
    <!-- <li>
      <a href="catequistas.php" class="nav-link <?= $currentPage == 'catequistas.php' ? 'active' : '' ?>">
        <i class="bi bi-person-badge-fill me-2"></i>Catequistas
      </a>
    </li> -->
   <!--  <li>
      <a href="participantes_catequesis.php" class="nav-link <?= $currentPage == 'participantes_catequesis.php' ? 'active' : '' ?>">
        <i class="bi bi-person-lines-fill me-2"></i>Participantes
      </a>
    </li> -->

    <!-- Usuarios -->
    <li>
      <a href="usuarios.php" class="nav-link <?= $currentPage == 'usuarios.php' ? 'active' : '' ?>">
        <i class="bi bi-person-lock me-2"></i>Usuarios
      </a>
    </li>
  </ul>

  <hr>
  <div class="d-flex align-items-center justify-content-between">
    <span><i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['usuario']['nombre'] ?? '') ?></span>
    <a href="../logout.php" class="text-danger" title="Cerrar sesión" onclick="return confirmarCerrarSesion();">
        <i class="bi bi-box-arrow-right"></i>
    </a>
</div>

<script>
function confirmarCerrarSesion() {
    return confirm("¿Estás seguro de que deseas cerrar sesión?");
}
</script>

</div>
