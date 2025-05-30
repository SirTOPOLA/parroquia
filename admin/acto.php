<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<?php
$buscar = $_GET['buscar'] ?? '';

// Consulta principal

$sql = "SELECT 
            a.id, a.fecha, a.libro, a.folio, a.partida, a.certificado_emitido,
            p.nombres, p.apellidos,
            s.nombre AS sacramento_nombre,
            -- Concatenamos las personas espirituales y sus roles
            GROUP_CONCAT(CONCAT(rp.rol, ': ', pesp.nombres, ' ', pesp.apellidos) SEPARATOR '; ') AS personas_espirituales
        FROM acto_sacramental a
        INNER JOIN persona p ON a.persona_id = p.id
        INNER JOIN sacramento s ON a.sacramento_id = s.id
        LEFT JOIN relaciones_persona rp ON rp.acto_sacramental_id = a.id
        LEFT JOIN persona pesp ON rp.persona_id = pesp.id
        WHERE CONCAT(p.nombres, ' ', p.apellidos) LIKE :buscar
        GROUP BY a.id, a.fecha, a.libro, a.folio, a.partida, a.certificado_emitido,
                 p.nombres, p.apellidos, s.nombre
        ORDER BY a.fecha DESC";


$stmt = $pdo->prepare($sql);
$stmt->execute(['buscar' => "%$buscar%"]);
$actos = $stmt->fetchAll(PDO::FETCH_ASSOC);


foreach ($actos as &$acto) {
    $stmt2 = $pdo->prepare("SELECT rp.rol, p.nombres, p.apellidos FROM relaciones_persona rp INNER JOIN persona p ON rp.persona_id = p.id WHERE rp.acto_sacramental_id = ?");
    $stmt2->execute([$acto['id']]);
    $acto['personas_espirituales'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);
}
unset($acto);

function rolBadgeColor($rol) {
    return match ($rol) {
        'padre' => 'bg-linght text-dark',
        'madre' => 'bg-linght text-dark',
        'padrino' => 'bg-linght text-dark',
        'madrina' => 'bg-linght text-dark',
        default => 'bg-linght',
    };
}


// Selects
$personas = $pdo->query("SELECT id, nombres, apellidos FROM persona ORDER BY nombres")->fetchAll(PDO::FETCH_ASSOC);
$sacramentos = $pdo->query("SELECT id, nombre FROM sacramento ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content">
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['mensaje']) ?> correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="container mt-4">
        <h2>Gestión de Actos Sacramentales</h2>

        <div class="d-flex justify-content-between mb-3">
            <form class="d-flex" method="GET">
                <input class="form-control me-2" type="search" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
                    placeholder="Buscar persona...">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
            </form>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistro">+ Nuevo Acto</button>
        </div>

  <table class="table table-bordered table-hover">
<thead class="table-light">
    <tr>
        <th>ID</th>
        <th>Persona</th>
        <th>Sacramento</th>
        <th>Fecha</th>
        <th>Libro</th>
        <th>Folio</th>
        <th>Partida</th>
        <th>Certificado</th>
        <th>Personas Espirituales</th>
        <th>Acciones</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($actos as $a): ?>
        <tr>
            <td><?= $a['id'] ?></td>
            <td><?= htmlspecialchars($a['nombres'] . ' ' . $a['apellidos']) ?></td>
            <td><?= htmlspecialchars($a['sacramento_nombre']) ?></td>
            <td><?= $a['fecha'] ?></td>
            <td><?= htmlspecialchars($a['libro']) ?></td>
            <td><?= htmlspecialchars($a['folio']) ?></td>
            <td><?= htmlspecialchars($a['partida']) ?></td>
            <td><?= $a['certificado_emitido'] ? 'Sí' : 'No' ?></td>
            <td>
                <div class="accordion" id="accordionPersonasEspirituales<?= $a['id'] ?>">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?= $a['id'] ?>">
                            <button class="accordion-button collapsed p-1" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#collapse<?= $a['id'] ?>" aria-expanded="false" 
                                aria-controls="collapse<?= $a['id'] ?>">
                                Ver Personas
                            </button>
                        </h2>
                        <div id="collapse<?= $a['id'] ?>" class="accordion-collapse collapse" 
                             aria-labelledby="heading<?= $a['id'] ?>" data-bs-parent="#accordionPersonasEspirituales<?= $a['id'] ?>">
                            <div class="accordion-body p-2">
                                <?php 
                                // Aquí muestras las personas espirituales asociadas a este acto sacramental
                                if (!empty($a['personas_espirituales'])) {
                                    foreach ($a['personas_espirituales'] as $personaEsp) {
                                        echo '<span class="badge rounded-pill ';
                                        // Asumiendo que tienes la función para asignar colores según rol
                                        echo rolBadgeColor($personaEsp['rol']);
                                        echo ' me-1">';
                                        echo htmlspecialchars($personaEsp['nombres'] . ' ' . $personaEsp['apellidos']) . ' (' . $personaEsp['rol'] . ')';
                                        echo '</span>';
                                    }
                                } else {
                                    echo '<small class="text-muted">No hay personas espirituales</small>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                    data-bs-target="#modalEditar<?= $a['id'] ?>">Editar</button>
                <a href="../php/eliminar_acto.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-danger"
                    onclick="return confirm('¿Eliminar este acto sacramental?')">Eliminar</a>
                <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                    data-bs-target="#modalEditarPersonaEspiritual<?= $a['id'] ?>">Persona Esp.</button>
            </td>
        </tr>
    <?php endforeach ?>
</tbody>
</table>


    </div>

    <!-- Modal Registro -->
    <div class="modal fade" id="modalRegistro" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="../php/guardar_acto.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Acto Sacramental</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <?php
                    $acto = [
                        'id' => '',
                        'persona_id' => '',
                        'sacramento_id' => '',
                        'parroco_id' => '',
                        'fecha' => '',
                        'libro' => '',
                        'folio' => '',
                        'partida' => '',
                        'observaciones' => '',
                        'certificado_emitido' => 0
                    ];
                    include '../components/form_acto.php';
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Registrar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modales Edición -->
    <?php foreach ($actos as $a): ?>
        <?php
        $stmtActo = $pdo->prepare("SELECT * FROM acto_sacramental WHERE id=?");
        $stmtActo->execute([$a['id']]);
        $actoEditar = $stmtActo->fetch(PDO::FETCH_ASSOC);

        $stmtRel = $pdo->prepare("SELECT rp.persona_id, rp.rol, p.nombres, p.apellidos
                                  FROM relaciones_persona rp
                                  JOIN persona p ON rp.persona_id = p.id
                                  WHERE rp.acto_sacramental_id = ?");
        $stmtRel->execute([$a['id']]);
        $relaciones = $stmtRel->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="modal fade" id="modalEditar<?= $a['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <form action="../php/editar_acto.php" method="POST" class="modal-content">
                    <input type="hidden" name="id" value="<?= $actoEditar['id'] ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Acto Sacramental</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <?php $acto = $actoEditar;
                        include '../components/form_acto.php'; ?>
                        <hr>
                        <h6>Relaciones familiares/espirituales</h6>
                        <div id="relacionesContainer<?= $a['id'] ?>">
                            <?php foreach ($relaciones as $rel): ?>
                                <div class="row mb-2 align-items-center">
                                    <input type="hidden" name="relaciones[][persona_id]" value="<?= $rel['persona_id'] ?>">
                                    <div class="col-6"><?= htmlspecialchars($rel['nombres'] . ' ' . $rel['apellidos']) ?></div>
                                    <div class="col-6">
                                        <select name="relaciones[][rol]" class="form-select" required>
                                            <option value="padre" <?= $rel['rol'] === 'padre' ? 'selected' : '' ?>>Padre</option>
                                            <option value="madre" <?= $rel['rol'] === 'madre' ? 'selected' : '' ?>>Madre</option>
                                            <option value="padrino" <?= $rel['rol'] === 'padrino' ? 'selected' : '' ?>>Padrino
                                            </option>
                                            <option value="madrina" <?= $rel['rol'] === 'madrina' ? 'selected' : '' ?>>Madrina
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; ?>



<!-- Modal -->
<div class="modal fade" id="modalEditarPersonaEspiritual<?= $a['id'] ?>" tabindex="-1"
    aria-labelledby="personaEspiritualLabel<?= $a['id'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="../php/guardar_personas_espirituales.php" method="POST">
            <input type="hidden" name="acto_sacramental_id" value="<?= $a['id'] ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="personaEspiritualLabel<?= $a['id'] ?>">
                        Registrar Personas Espirituales - Acto #<?= $a['id'] ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $personas = $pdo->query("
                        SELECT p.id, p.nombres, p.apellidos
                        FROM persona p
                        LEFT JOIN participante_catequesis pc ON p.id = pc.persona_id
                        WHERE pc.persona_id IS NULL
                        ORDER BY p.apellidos, p.nombres
                    ")->fetchAll();

                    $roles = ['padre' => 'Padre', 'madre' => 'Madre', 'padrino' => 'Padrino', 'madrina' => 'Madrina'];
                    ?>

                    <?php foreach ($roles as $clave => $etiqueta): ?>
                        <div class="mb-3">
                            <label class="form-label"><?= $etiqueta ?></label>
                            <select name="persona_<?= $clave ?>" class="form-select" required>
                                <option value="">Seleccione persona...</option>
                                <?php foreach ($personas as $p): ?>
                                    <option value="<?= $p['id'] ?>">
                                        <?= htmlspecialchars($p['apellidos'] . ', ' . $p['nombres']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar relaciones</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

</main>
<script>
   /*  function agregarCampoPersona(btn, rol) {
        const grupo = btn.closest('.mb-3');
        const select = grupo.querySelector('select').cloneNode(true);
        select.name = `personas[${rol}][]`; // asegúrate que sea array
        const wrapper = document.createElement('div');
        wrapper.className = 'input-group mb-1';
        wrapper.appendChild(select);

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-outline-danger';
        removeBtn.textContent = '-';
        removeBtn.onclick = function () { wrapper.remove(); };

        wrapper.appendChild(removeBtn);

        const contenedor = grupo.querySelector('.campos-extra-' + rol);
        contenedor.appendChild(wrapper);
    } */
</script>

<?php include '../includes/footer.php'; ?>