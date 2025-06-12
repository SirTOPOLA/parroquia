<?php
$rol = strtolower(trim($_SESSION['usuario']['rol'] ?? 'sin_permiso'));
$current = $_GET['vista'] ?? 'dashboard';

// Íconos por vista
$iconos = [
  'dashboard' => 'bi-speedometer2',
  'catequesis' => 'bi-journal-bookmark-fill',
  'catequistas' => 'bi-person-badge-fill',
  'cursos' => 'bi-book',
  'feligreses' => 'bi-people-fill',
  'parientes' => 'bi-people',
  'parroquias' => 'bi-building',
  'evaluacion' => 'bi-cross',
  'sacramentos' => 'bi-bookmarks',
  'reportes' => 'bi-bookmarks',
  'usuarios' => 'bi-person-gear',
  'perfil' => 'bi-person-circle',
  'configuracion' => 'bi-gear-wide-connected',
];

// Menú por rol
$menu = [
  'admin' => [
    'Dashboard' => 'dashboard',
    'Catequesis' => 'catequesis',
    'Catequistas' => 'catequistas',
    'Evaluacion' => 'evaluacion',
    'Cursos' => 'cursos',
    'Feligreses' => 'feligreses',
    'Parientes' => 'parientes',
    'Parroquias' => 'parroquias',
    'reportes' => 'reportes',
    'Sacramentos' => 'sacramentos',
    'Usuarios' => 'usuarios',
    'Configuración' => 'configuracion',
  ],
  'secretario' => [
    'Dashboard' => 'dashboard',
    'Catequesis' => 'catequesis',
    'Catequistas' => 'catequistas',
    'Cursos' => 'cursos',
    'Feligreses' => 'feligreses',
    'Parientes' => 'parientes',
  ],
  'archivista' => [
    'Dashboard' => 'dashboard',
    'Feligreses' => 'feligreses',
    'Parientes' => 'parientes',
    'Sacramentos' => 'sacramentos',
  ],
  'parroco' => [
    'Dashboard' => 'dashboard',
    'Catequesis' => 'catequesis',
    'Cursos' => 'cursos',
    'Feligreses' => 'feligreses',
    'Parroquias' => 'parroquias',
    'Sacramentos' => 'sacramentos',
  ],
];
?>
<div class="wrapper">
  <div id="sidebar" class="sidebar position-fixed scroll-box overflow-auto h-100 p-3">
    <h5 class="mb-4"><i class="bi bi-journal-bookmark-fill me-2"></i>Menú</h5>
    <ul class="nav nav-pills flex-column">
      <?php foreach ($menu[$rol] as $label => $vistaName):
        $active = ($current === $vistaName) ? 'active' : '';
        $icon = $iconos[$vistaName] ?? 'bi-chevron-right';
        ?>
        <li class="nav-item mb-1">
          <a href="index.php?vista=<?= $vistaName ?>" class="nav-link <?= $active ?>">
            <i class="bi <?= $icon ?> me-2"></i>
            <span class="link-text"><?= $label ?></span>
          </a>
        </li>
      <?php endforeach; ?>
      <li class="nav-item mb-1">
        <a href="" id="cerrarSession" class="nav-link">
          <i class="bi bi-box-arrow-right me-1"></i> Salir
        </a>
      </li>
    </ul>
  </div>