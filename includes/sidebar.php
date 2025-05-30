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

    <!-- Personas y Usuarios -->
    <li>
      <a href="persona.php" class="nav-link <?= $currentPage == 'persona.php' ? 'active' : '' ?>">
        <i class="bi bi-people-fill me-2"></i>Personas
      </a>
    </li>
    <li>
      <a href="felice.php" class="nav-link <?= $currentPage == 'felice.php' ? 'active' : '' ?>">
        <i class="bi bi-person me-2"></i>Feligreses
      </a>
    </li>
    <li>
      <a href="usuario.php" class="nav-link <?= $currentPage == 'usuario.php' ? 'active' : '' ?>">
        <i class="bi bi-person-lock me-2"></i>Usuarios
      </a>
    </li>

    <!-- Catequesis -->
<!--     <li><hr class="dropdown-divider"></li>
    <li><span class="text-white small fw-bold ms-2">Catequesis</span></li> -->
    <li>
      <a href="catequista.php" class="nav-link <?= $currentPage == 'catequista.php' ? 'active' : '' ?>">
        <i class="bi bi-person-badge-fill me-2"></i>Catequistas
      </a>
    </li>
    <li>
      <a href="catequesis.php" class="nav-link <?= $currentPage == 'catequesis.php' ? 'active' : '' ?>">
        <i class="bi bi-journal-bookmark-fill  me-2"></i>Catequesis
      </a>
    </li>
    <li>
      <a href="parroco.php" class="nav-link <?= $currentPage == 'parroco.php' ? 'active' : '' ?>">
        <i class="bi bi-person me-2"></i>Parroco
      </a>
    </li>
    <li>
      <a href="participante.php" class="nav-link <?= $currentPage == 'participante.php' ? 'active' : '' ?>">
        <i class="bi bi-person-check-fill me-2"></i>Participantes
      </a>
    </li>
    <li>
      <a href="acto.php" class="nav-link <?= $currentPage == 'acto.php' ? 'active' : '' ?>">
        <i class="bi bi-journal-text me-2"></i>Actos Sacramentales
      </a>
    </li>
  </ul>

  <hr>
  <div class="d-flex align-items-center justify-content-between">
    <span><i class="bi bi-person-circle"></i> Admin</span>
    <a href="../logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i></a>
  </div>
</div>
