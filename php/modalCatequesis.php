<?php 
require_once '../config/conexion.php';

$id_catequesis = $_GET['id'] ?? null;
if (!$id_catequesis) {
    echo "<div class='alert alert-danger'>ID de catequesis no proporcionado.</div>";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM catequesis WHERE id_catequesis = ?");
$stmt->execute([$id_catequesis]);
$catequesis = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$catequesis) {
    echo "<div class='alert alert-warning'>Catequesis no encontrada.</div>";
    exit;
}

$stmtCursos = $pdo->prepare("SELECT * FROM cursos WHERE id_catequesis = ?");
$stmtCursos->execute([$id_catequesis]);
$cursos = $stmtCursos->fetchAll(PDO::FETCH_ASSOC);

// Obtener catequistas de un curso
function obtenerCatequistas($pdo, $id_curso) {
    $stmt = $pdo->prepare("
        SELECT c.id_catequista, ct.nombre, ct.apellido
        FROM curso_catequistas c
        JOIN catequistas ct ON c.id_catequista = ct.id_catequista
        WHERE c.id_curso = ?
    ");
    $stmt->execute([$id_curso]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener feligreses asociados a un curso de catequesis
function obtenerFeligresesPorCurso($pdo, $id_curso) {
    $stmt = $pdo->prepare("
        SELECT f.*
        FROM feligreses f
        JOIN feligres_catequesis fc ON f.id_feligres = fc.id_feligres
        JOIN cursos c ON c.id_catequesis = fc.id_catequesis
        WHERE c.id_curso = ?
    ");
    $stmt->execute([$id_curso]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!-- MODAL -->
<div class="modal fade" id="modalCatequesisDetalle" tabindex="-1" aria-labelledby="modalCatequesisLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content shadow-lg rounded-4 border-0">
      <div class="modal-header bg-primary text-white" style="background: linear-gradient(90deg, #4e73df, #224abe);">
        <h5 class="modal-title text-white d-flex align-items-center" id="modalCatequesisLabel">
          <i class="bi bi-book-fill me-2 fs-4"></i>
          Detalles de la Catequesis: 
          <span class="ms-2 badge bg-light text-dark"><?= htmlspecialchars($catequesis['nombre']) ?></span>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body bg-light-subtle">

        <?php if (empty($cursos)): ?>
          <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            No hay cursos asociados a esta catequesis.
          </div>
        <?php else: ?>
          <?php foreach ($cursos as $curso): ?>
            <?php
              $feligreses = obtenerFeligresesPorCurso($pdo, $curso['id_curso']);
              $catequistas = obtenerCatequistas($pdo, $curso['id_curso']);
            ?>
            <div class="card mb-4 shadow-sm border-start border-5 border-primary rounded-4 ">
              <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center rounded-top-4">
                <div>
                  <h6 class="mb-1">
                    <i class="bi bi-mortarboard-fill me-1 text-primary"></i> Curso: <?= htmlspecialchars($curso['nombre']) ?>
                  </h6>
                  <small class="text-muted"><i class="bi bi-calendar-event me-1"></i> <?= $catequesis['nombre'] ?></small><br>
                  <span class="badge bg-secondary bg-opacity-10 text-white me-2">
                    <i class="bi bi-calendar-plus me-1"></i> Inicio: <?= $curso['fecha_inicio'] ?>
                  </span>
                  <span class="badge bg-secondary bg-opacity-10 text-white">
                    <i class="bi bi-calendar-check me-1"></i> Fin: <?= $curso['fecha_fin'] ?>
                  </span>
                </div>
                <?php if (!empty($catequistas)): ?>
                  <div class="mt-3 mt-md-0 text-md-end">
                    <span class="fw-semibold text-muted">
                      <i class="bi bi-person-lines-fill me-1 text-success"></i>Catequistas:
                    </span><br>
                    <?= implode(', ', array_map(fn($c) => htmlspecialchars($c['nombre'].' '.$c['apellido']), $catequistas)) ?>
                  </div>
                <?php endif; ?>
              </div>
              <div class="card-body">
                <?php if (empty($feligreses)): ?>
                  <p class="text-muted mb-0"><i class="bi bi-info-circle me-1"></i> No hay feligreses inscritos en este curso.</p>
                <?php else: ?>
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm align-middle bg-white">
                      <thead class="table-light text-center">
                        <tr>
                          <th>ID</th>
                          <th>Nombre Completo</th>
                          <th>Fecha Nacimiento</th>
                          <th>Género</th>
                          <th>Teléfono</th>
                          <th>Estado Civil</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($feligreses as $f): ?>
                          <tr>
                            <td class="text-center"><?= $f['id_feligres'] ?></td>
                            <td><?= htmlspecialchars($f['nombre'] . ' ' . $f['apellido']) ?></td>
                            <td class="text-center"><?= $f['fecha_nacimiento'] ?></td>
                            <td class="text-center"><?= $f['genero'] ?></td>
                            <td class="text-center"><?= htmlspecialchars($f['telefono']) ?></td>
                            <td class="text-center"><?= ucfirst($f['estado_civil']) ?></td>
                          </tr>
                        <?php endforeach ?>
                      </tbody>
                    </table>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

      </div>
      <div class="modal-footer bg-white border-top-0">
        <button class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
          <i class="bi bi-x-lg me-1"></i> Cerrar
        </button>
      </div>
    </div>
  </div>
</div>
