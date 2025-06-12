<?php
// Suponiendo que $pdo ya está definido y es una instancia de PDO

$sql = "
SELECT 
    f.id_feligres,
    CONCAT(f.nombre, ' ', f.apellido) AS feligres,
    p.nombre AS parroquia,
    c.id_curso,
    c.nombre AS curso,
    cf.estado AS estado_curso,
    s.id_sacramento,
    s.nombre AS sacramento,
    fs.estado AS estado_sacramento
FROM feligreses f
LEFT JOIN parroquias p ON f.id_parroquia = p.id_parroquia
LEFT JOIN curso_feligres cf ON f.id_feligres = cf.id_feligres
LEFT JOIN cursos c ON cf.id_curso = c.id_curso
LEFT JOIN feligres_sacramento fs ON f.id_feligres = fs.id_feligres
LEFT JOIN sacramentos s ON fs.id_sacramento = s.id_sacramento
WHERE cf.id_feligres IS NOT NULL OR fs.id_feligres IS NOT NULL
ORDER BY f.apellido, f.nombre";

$stmt = $pdo->query($sql);
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Función de colores según estado
function estadoColor($estado)
{
    return match (strtolower($estado)) {
        'pendiente' => 'secondary',
        'en_proceso' => 'warning',
        'completado' => 'success',
        default => 'dark',
    };
}
?>

<main id="content" class="container mt-4">
    <h2 class="mb-4"><i class="bi bi-people-fill me-2"></i>Gestión de Evaluaciones Catequéticas</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Feligres</th>
                    <th>Parroquia</th>
                    <th>Curso</th>
                    <th>Estado Curso</th>
                    <th>Sacramento</th>
                    <th>Estado Sacramento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $i => $r): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($r['feligres']) ?></td>
                        <td><?= htmlspecialchars($r['parroquia']) ?></td>
                        <td><?= htmlspecialchars($r['curso']) ?></td>
                        <td>
                            <span class="badge bg-<?= estadoColor($r['estado_curso']) ?>"><?= htmlspecialchars($r['estado_curso']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($r['sacramento']) ?></td>
                        <td>
                            <span 
                              class="badge bg-<?= estadoColor($r['estado_sacramento']) ?>" 
                              id="sacramento-estado-<?= $r['id_feligres'] ?>"
                            >
                              <?= htmlspecialchars($r['estado_sacramento']) ?>
                            </span>
                        </td>
                        <td>
                            <button 
                                class="btn btn-sm btn-success" 
                                onclick="abrirModalEstadoSacramento(<?= $r['id_feligres'] ?>, <?= $r['id_sacramento'] ?>, '<?= addslashes(htmlspecialchars($r['feligres'])) ?>')"
                                title="Concluir Sacramento"
                            >
                                <i class="bi bi-check-circle me-1"></i>Completar
                            </button>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para concluir el Sacramento -->
    <div class="modal fade" id="modalEstadoSacramento" tabindex="-1" aria-labelledby="modalEstadoSacramentoLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="modalEstadoSacramentoLabel">Concluir Sacramento</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>

          <div class="modal-body">
            <p>¿Deseas marcar el sacramento de <strong id="nombreSacramentoFeligres"></strong> como <span class="badge bg-success">completado</span>?</p>
            
            <form id="formSacramento">
              <div class="mb-3">
                <label for="fechaSacramento" class="form-label">Fecha del Sacramento <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="fechaSacramento" name="fechaSacramento" required>
              </div>

              <div class="mb-3">
                <label for="lugarSacramento" class="form-label">Lugar <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="lugarSacramento" name="lugarSacramento" maxlength="255" placeholder="Ejemplo: Parroquia San Juan" required>
              </div>

              <div class="mb-3">
                <label for="observacionesSacramento" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observacionesSacramento" name="observacionesSacramento" rows="3" placeholder="Comentarios adicionales (opcional)"></textarea>
              </div>
            </form>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-success" id="confirmarCambioSacramento">Confirmar</button>
          </div>
        </div>
      </div>
    </div>
</main>

<script>
  let sacramentoActual = {};

  function abrirModalEstadoSacramento(idFeligres, idSacramento, nombreFeligres) {
    sacramentoActual = { idFeligres, idSacramento };
    document.getElementById('nombreSacramentoFeligres').textContent = nombreFeligres;

    // Resetear formulario
    document.getElementById('formSacramento').reset();

    // Mostrar modal
    const modalEl = document.getElementById('modalEstadoSacramento');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
  }

  document.getElementById('confirmarCambioSacramento').addEventListener('click', () => {
    const fecha = document.getElementById('fechaSacramento').value;
    const lugar = document.getElementById('lugarSacramento').value.trim();
    const observaciones = document.getElementById('observacionesSacramento').value.trim();

    if (!fecha) {
      alert('Por favor, ingresa la fecha del sacramento.');
      return;
    }
    if (!lugar) {
      alert('Por favor, ingresa el lugar del sacramento.');
      return;
    }

    const datos = {
      id_feligres: sacramentoActual.idFeligres,
      id_sacramento: sacramentoActual.idSacramento,
      fecha,
      lugar,
      observaciones
    };

    fetch('php/estado_sacramento.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams(datos)
    })
    .then(res => {
      if (!res.ok) throw new Error('Error en la respuesta');
      return res.json();
    })
    .then(data => {
      if (data.error) {
        alert('Error: ' + data.error);
        return;
      }

      // Actualizar estado en la tabla
      const spanEstado = document.getElementById(`sacramento-estado-${sacramentoActual.idFeligres}`);
      if (spanEstado) {
        spanEstado.textContent = data.estado;
        spanEstado.className = 'badge bg-' + data.color;
      }

      // Cerrar modal
      const modalEl = document.getElementById('modalEstadoSacramento');
      const modal = bootstrap.Modal.getInstance(modalEl);
      modal.hide();
    })
    .catch(err => {
      console.error(err);
      alert('Ocurrió un error al registrar el sacramento.');
    });
  });
</script>
