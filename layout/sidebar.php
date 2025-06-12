<?php
$rol = strtolower(trim($_SESSION['usuario']['rol'] ?? 'sin_permiso'));
$current = $_GET['vista'] ?? 'dashboard';

// Íconos por vista
$iconos = [
  'dashboard'    => 'bi-speedometer2',           // Indicador general y panel
  'catequesis'   => 'bi-book-half',              // Libro abierto, enseñanza
  'catequistas'  => 'bi-person-badge',           // Persona con placa identificativa
  'cursos'       => 'bi-journal-text',           // Cuaderno con texto, curso
  'feligreses'   => 'bi-people-fill',             // Grupo de personas
  'parientes'    => 'bi-people',                  // Grupo de personas en outline
  'parroquias'   => 'bi-building',                // Edificio, parroquia
  'evaluacion'   => 'bi-file-earmark-check',      // Documento con check, evaluación
  'sacramentos'  => 'bi-award',                   // Medalla o premio (sacramentos como logro)
  'reportes'     => 'bi-bar-chart-line-fill',    // Gráfica de barras, reportes
  'usuarios'     => 'bi-person-gear',             // Persona con engranaje (config usuarios)
  'perfil'       => 'bi-person-circle',           // Perfil de usuario
  'configuracion'=> 'bi-sliders',                  // Ajustes con sliders, configuración
];


// Menú por rol
$menu = [
  'admin' => [
    'Dashboard' => 'dashboard',
    'Usuarios' => 'usuarios',
    'Feligreses' => 'feligreses',
    'Catequesis' => 'catequesis',
    'Catequistas' => 'catequistas',
    'Cursos' => 'cursos',
    'Parientes' => 'parientes',
    'Parroquias' => 'parroquias',
    'Sacramentos' => 'sacramentos',
    'Evaluacion' => 'evaluacion',
    'reportes' => 'reportes',
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