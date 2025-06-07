<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?> 
<?php
 

 
try {
  // Totales para el resumen
  $total_feligreses = $pdo->query("SELECT COUNT(*) FROM feligreses")->fetchColumn();
  $total_catequesis = $pdo->query("SELECT COUNT(*) FROM catequesis")->fetchColumn();
  $total_catequistas = $pdo->query("SELECT COUNT(*) FROM catequistas")->fetchColumn();
  $total_cursos = $pdo->query("SELECT COUNT(*) FROM cursos")->fetchColumn();
  $total_parroquias = $pdo->query("SELECT COUNT(*) FROM parroquias")->fetchColumn();
  $total_sacramentos = $pdo->query("SELECT COUNT(*) FROM sacramentos")->fetchColumn();
  $total_usuarios = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE estado = 1")->fetchColumn();

} catch (PDOException $e) {
  // En caso de error, registrar y asignar 0 como fallback
  error_log("Error al consultar resumen dashboard: " . $e->getMessage());
  $total_feligreses = $total_catequesis = $total_catequistas = $total_cursos = 0;
  $total_parroquias = $total_sacramentos = $total_usuarios = 0;
}
?>

 

<!-- Main Content -->
 
<main class="content">
  <div class="container py-4">
    <h2 class="mb-4"><i class="bi bi-speedometer2 me-2"></i>Resumen del Sistema</h2>

    <div class="row g-4">
      <!-- Total de Feligreses -->
      <div class="col-sm-6 col-xl-3">
        <div class="card text-bg-light border-0 shadow-sm h-100">
          <div class="card-body d-flex flex-column justify-content-between">
            <h5 class="card-title"><i class="bi bi-people-fill me-2 text-primary"></i>Feligreses</h5>
            <h3 class="fw-bold"><?= $total_feligreses ?? 0 ?></h3>
            <p class="mb-0 text-muted">Registrados en el sistema</p>
          </div>
        </div>
      </div>

      <!-- Catequesis -->
      <div class="col-sm-6 col-xl-3">
        <div class="card text-bg-light border-0 shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title"><i class="bi bi-journal-bookmark-fill me-2 text-success"></i>Catequesis</h5>
            <h3 class="fw-bold"><?= $total_catequesis ?? 0 ?></h3>
            <p class="mb-0 text-muted">Programas activos</p>
          </div>
        </div>
      </div>

      <!-- Catequistas -->
      <div class="col-sm-6 col-xl-3">
        <div class="card text-bg-light border-0 shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title"><i class="bi bi-person-lines-fill me-2 text-warning"></i>Catequistas</h5>
            <h3 class="fw-bold"><?= $total_catequistas ?? 0 ?></h3>
            <p class="mb-0 text-muted">Encargados registrados</p>
          </div>
        </div>
      </div>

      <!-- Cursos -->
      <div class="col-sm-6 col-xl-3">
        <div class="card text-bg-light border-0 shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title"><i class="bi bi-easel-fill me-2 text-danger"></i>Cursos</h5>
            <h3 class="fw-bold"><?= $total_cursos ?? 0 ?></h3>
            <p class="mb-0 text-muted">Catequesis en marcha</p>
          </div>
        </div>
      </div>

      <!-- Parroquias -->
      <div class="col-sm-6 col-xl-3">
        <div class="card text-bg-light border-0 shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title"><i class="bi bi-building me-2 text-info"></i>Parroquias</h5>
            <h3 class="fw-bold"><?= $total_parroquias ?? 0 ?></h3>
            <p class="mb-0 text-muted">Registradas</p>
          </div>
        </div>
      </div>

      <!-- Sacramentos -->
      <div class="col-sm-6 col-xl-3">
        <div class="card text-bg-light border-0 shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title"><i class="bi bi-patch-check-fill me-2 text-secondary"></i>Sacramentos</h5>
            <h3 class="fw-bold"><?= $total_sacramentos ?? 0 ?></h3>
            <p class="mb-0 text-muted">Disponibles</p>
          </div>
        </div>
      </div>

      <!-- Usuarios -->
      <div class="col-sm-6 col-xl-3">
        <div class="card text-bg-light border-0 shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title"><i class="bi bi-person-badge-fill me-2 text-dark"></i>Usuarios</h5>
            <h3 class="fw-bold"><?= $total_usuarios ?? 0 ?></h3>
            <p class="mb-0 text-muted">Activos en el sistema</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

 
<?php include '../includes/footer.php'; ?>