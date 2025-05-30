<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<?php include '../php/guardar_sacramento.php'; ?>
<?php
// Asumiendo que $pdo es tu conexión PDO

// Total personas
$stmt = $pdo->query("SELECT COUNT(*) FROM persona");
$totalPersonas = $stmt->fetchColumn();

// Total usuarios activos
$stmt = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE estado = TRUE");
$totalUsuarios = $stmt->fetchColumn();

// Total catequistas
$stmt = $pdo->query("SELECT COUNT(*) FROM catequistas");
$totalCatequistas = $stmt->fetchColumn();

// Catequesis activas (ejemplo: fecha_inicio <= hoy <= fecha_fin)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM catequesis WHERE fecha_inicio <= CURDATE() AND fecha_fin >= CURDATE()");
$stmt->execute();
$catequesisActivas = $stmt->fetchColumn();

// Total actos sacramentales
$stmt = $pdo->query("SELECT COUNT(*) FROM acto_sacramental");
$totalActos = $stmt->fetchColumn();

// Últimos actos (5 más recientes)
$stmt = $pdo->query("SELECT a.id, p.nombres, p.apellidos, s.nombre AS sacramento, a.fecha 
                    FROM acto_sacramental a
                    INNER JOIN persona p ON a.persona_id = p.id
                    INNER JOIN sacramento s ON a.sacramento_id = s.id
                    ORDER BY a.fecha DESC LIMIT 5");
$ultimosActos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- Main Content -->
<main class="content">
  <div class="container-fluid">
    <h2 class="mb-4">Dashboard Parroquia</h2>
    <div class="row g-4">
      <div class="col-12 col-sm-6 col-md-3">
        <div class="card-dashboard card-personas">
          <div class="card-icon" aria-label="Icono Personas">
            <!-- Ícono de personas -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="28" height="28">
              <path d="M16 14a4 4 0 1 0-8 0v1a2 2 0 0 0-2 2v2h12v-2a2 2 0 0 0-2-2v-1zM12 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"/>
            </svg>
          </div>
          <div class="card-content">
            <h5 class="card-title">Personas</h5>
            <p class="card-value"><?= $totalPersonas ?></p>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="card-dashboard card-usuarios">
          <div class="card-icon" aria-label="Icono Usuarios">
            <!-- Ícono usuario -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="28" height="28">
              <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm-7 8a7 7 0 0 1 14 0z"/>
            </svg>
          </div>
          <div class="card-content">
            <h5 class="card-title">Usuarios Activos</h5>
            <p class="card-value"><?= $totalUsuarios ?></p>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="card-dashboard card-catequistas">
          <div class="card-icon" aria-label="Icono Catequistas">
            <!-- Ícono catequista -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="28" height="28">
              <circle cx="12" cy="8" r="4"/>
              <path d="M6 20v-2a6 6 0 0 1 12 0v2"/>
            </svg>
          </div>
          <div class="card-content">
            <h5 class="card-title">Catequistas</h5>
            <p class="card-value"><?= $totalCatequistas ?></p>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="card-dashboard card-catequesis">
          <div class="card-icon" aria-label="Icono Catequesis">
            <!-- Ícono libro -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="28" height="28">
              <path d="M4 19h16v-2H4zm0-4h16v-2H4zm0-4h16v-2H4z"/>
            </svg>
          </div>
          <div class="card-content">
            <h5 class="card-title">Catequesis Activas</h5>
            <p class="card-value"><?= $catequesisActivas ?></p>
          </div>
        </div>
      </div>
    </div>

    <h4 class="mt-5 mb-3">Últimos actos sacramentales</h4>
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Persona</th>
          <th>Sacramento</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($ultimosActos as $acto): ?>
          <tr>
            <td><?= $acto['id'] ?></td>
            <td><?= htmlspecialchars($acto['nombres'] . ' ' . $acto['apellidos']) ?></td>
            <td><?= htmlspecialchars($acto['sacramento']) ?></td>
            <td><?= $acto['fecha'] ?></td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</main>

<?php include '../includes/footer.php'; ?>