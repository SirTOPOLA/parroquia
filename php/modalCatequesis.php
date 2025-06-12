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

function obtenerFeligresesPorCurso($pdo, $id_curso, $id_catequesis) {
    $stmt = $pdo->prepare("
        SELECT f.*
        FROM feligres_catequesis fc
        INNER JOIN feligreses f ON fc.id_feligres = f.id_feligres
        WHERE fc.id_catequesis = ? AND EXISTS (
            SELECT 1 FROM cursos c WHERE c.id_curso = ? AND c.id_catequesis = fc.id_catequesis
        )
    ");
    $stmt->execute([$id_catequesis, $id_curso]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!-- MODAL -->
<div class="modal fade" id="modalCatequesisDetalle" tabindex="-1" aria-labelledby="modalCatequesisLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content shadow rounded-4">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalCatequesisLabel">
          <i class="bi bi-book me-2"></i>Detalles de la Catequesis: <?= htmlspecialchars($catequesis['nombre']) ?>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        <?php if (empty($cursos)): ?>
            <div class="alert alert-warning mb-0">No hay cursos asociados a esta catequesis.</div>
        <?php else: ?>
            <?php foreach ($cursos as $curso): ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light">
                        <strong>Curso:</strong> <?= htmlspecialchars($curso['nombre']) ?> 
                        (Inicio: <?= $curso['fecha_inicio'] ?> – Fin: <?= $curso['fecha_fin'] ?>)
                    </div>
                    <div class="card-body">
                        <?php
                        $feligreses = obtenerFeligresesPorCurso($pdo, $curso['id_curso'], $id_catequesis);
                        if (empty($feligreses)): ?>
                            <p class="text-muted">No hay feligreses inscritos en este curso.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-sm align-middle">
                                    <thead class="table-light">
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
                                                <td><?= $f['id_feligres'] ?></td>
                                                <td><?= htmlspecialchars($f['nombre'] . ' ' . $f['apellido']) ?></td>
                                                <td><?= $f['fecha_nacimiento'] ?></td>
                                                <td><?= $f['genero'] ?></td>
                                                <td><?= htmlspecialchars($f['telefono']) ?></td>
                                                <td><?= ucfirst($f['estado_civil']) ?></td>
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
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-lg"></i> Cerrar
        </button>
      </div>
    </div>
  </div>
</div>
