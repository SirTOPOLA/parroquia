<?php


$sql = "SELECT 
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
LEFT JOIN catequesis cat ON c.id_catequesis = cat.id_catequesis
LEFT JOIN feligres_sacramento fs ON f.id_feligres = fs.id_feligres
LEFT JOIN sacramentos s ON fs.id_sacramento = s.id_sacramento
WHERE cf.id_feligres IS NOT NULL
ORDER BY f.apellido, f.nombre";

$stmt = $pdo->query($sql);
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<main id="content" class="container mt-4">
    <h2 class="mb-4"><i class="bi bi-people-fill me-2"></i>Gestión de Evaluaciones Catequéticas</h2>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <!--  <form class="d-flex flex-grow-1 me-2" method="GET">
            <input class="form-control me-2" type="search" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
                placeholder="Buscar por nombre o apellido...">
            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
        </form> -->

    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
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
                            <span class="badge bg-<?= estadoColor($r['estado_curso']) ?>"
                                id="curso-estado-<?= $r['id_feligres'] ?>"><?= $r['estado_curso'] ?></span>
                        </td>
                        <td><?= htmlspecialchars($r['sacramento']) ?></td>
                        <td>
                            <span class="badge bg-<?= estadoColor($r['estado_sacramento']) ?>"
                                id="sacramento-estado-<?= $r['id_feligres'] ?>"><?= $r['estado_sacramento'] ?></span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary"
                                onclick="cambiarEstadoCurso(<?= $r['id_feligres'] ?>, <?= $r['id_curso'] ?>)">Cambiar
                                Curso</button>
                            <button class="btn btn-sm btn-success"
                                onclick="cambiarEstadoSacramento(<?= $r['id_feligres'] ?>, <?= $r['id_sacramento'] ?>)">Cambiar
                                Sacramento</button>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>


    <!-- Aquí se pueden incluir los modales para registro y edición reutilizando código -->
    <!-- Modal Estado Curso -->
    <div class="modal fade" id="modalEstadoCurso" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Cambiar estado del curso</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas cambiar el estado del curso para <strong
                        id="nombreCursoFeligres"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmarCambioCurso">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Estado Sacramento -->
    <div class="modal fade" id="modalEstadoSacramento" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Cambiar estado del sacramento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas cambiar el estado del sacramento para <strong
                        id="nombreSacramentoFeligres"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="confirmarCambioSacramento">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

</main>

<script>
    let cursoActual = {}, sacramentoActual = {};

    // Mostrar modal de curso
    function cambiarEstadoCurso(idFeligres, idCurso) {
        cursoActual = { idFeligres, idCurso };
        const fila = document.querySelector(`#curso-estado-${idFeligres}`).closest('tr');
        const nombre = fila.children[1].textContent;
        const curso = fila.children[3].textContent;
        document.getElementById('nombreCursoFeligres').textContent = `${nombre} (${curso})`;
        new bootstrap.Modal(document.getElementById('modalEstadoCurso')).show();
    }

    // Confirmar cambio curso
    document.getElementById('confirmarCambioCurso').addEventListener('click', () => {
        fetch('php/estado_curso.php', {
            method: 'POST',
            body: new URLSearchParams(cursoActual)
        })
            .then(res => res.json())
            .then(data => {
                const span = document.getElementById(`curso-estado-${cursoActual.idFeligres}`);
                span.textContent = data.estado;
                span.className = 'badge bg-' + data.color;
                bootstrap.Modal.getInstance(document.getElementById('modalEstadoCurso')).hide();
            });
    });

    // Mostrar modal de sacramento
    function cambiarEstadoSacramento(idFeligres, idSacramento) {
        sacramentoActual = { idFeligres, idSacramento };
        const fila = document.querySelector(`#sacramento-estado-${idFeligres}`).closest('tr');
        const nombre = fila.children[1].textContent;
        const sacramento = fila.children[5].textContent;
        document.getElementById('nombreSacramentoFeligres').textContent = `${nombre} (${sacramento})`;
        new bootstrap.Modal(document.getElementById('modalEstadoSacramento')).show();
    }

    // Confirmar cambio sacramento
    document.getElementById('confirmarCambioSacramento').addEventListener('click', () => {
        fetch('php/estado_sacramento.php', {
            method: 'POST',
            body: new URLSearchParams(sacramentoActual)
        })
            .then(res => res.json())
            .then(data => {
                const span = document.getElementById(`sacramento-estado-${sacramentoActual.idFeligres}`);
                span.textContent = data.estado;
                span.className = 'badge bg-' + data.color;
                bootstrap.Modal.getInstance(document.getElementById('modalEstadoSacramento')).hide();
            });
    });
 
</script>
<?php
function estadoColor($estado)
{
    return match ($estado) {
        'pendiente' => 'secondary',
        'en_proceso' => 'warning',
        'completado' => 'success',
        default => 'dark',
    };
}
?>