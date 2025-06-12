<?php
 

$buscar = $_GET['buscar'] ?? '';

$sql = "SELECT c.*, COUNT(cc.id_curso) AS total_cursos
        FROM catequistas c
        LEFT JOIN curso_catequistas cc ON c.id_catequista = cc.id_catequista
        WHERE c.nombre LIKE :buscar OR c.apellido LIKE :buscar OR c.correo LIKE :buscar
        GROUP BY c.id_catequista
        ORDER BY c.id_catequista DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['buscar' => "%$buscar%"]);
$catequistas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de cursos para uso en los modales
$cursos = $pdo->query("SELECT id_curso, nombre FROM cursos ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
?>

<main id="content">
    <div class="container mt-4">
        <h2><i class="bi bi-person-badge me-2"></i>Gestión de Catequistas</h2>

        <div class="d-flex justify-content-between mb-3">
            <form class="d-flex" method="GET">
                <input class="form-control me-2" type="search" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
                       placeholder="Buscar por nombre o correo...">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
            </form>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistro">
                <i class="bi bi-person-plus me-1"></i>Nuevo Catequista
            </button>
        </div>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Cursos Asignados</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($catequistas as $c): ?>
                    <tr>
                        <td><?= $c['id_catequista'] ?></td>
                        <td><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?></td>
                        <td><?= htmlspecialchars($c['correo']) ?></td>
                        <td><?= htmlspecialchars($c['telefono']) ?></td>
                        <td class="text-center"><span class="badge bg-info"><?= $c['total_cursos'] ?></span></td>
                        <td class="text-center">
    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
            data-bs-target="#modalVer<?= $c['id_catequista'] ?>">
        <i class="bi bi-eye"></i>
    </button>

    <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
            data-bs-target="#modalAsignar<?= $c['id_catequista'] ?>">
        <i class="bi bi-journal-plus"></i>
    </button>

    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
            data-bs-target="#modalEditar<?= $c['id_catequista'] ?>">
        <i class="bi bi-pencil-square"></i>
    </button>

    <button class="btn btn-sm btn-danger" onclick="confirmarEliminacion(<?= $c['id_catequista'] ?>)">
        <i class="bi bi-trash"></i>
    </button>
</td>

                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Registro -->
    <div class="modal fade" id="modalRegistro" tabindex="-1">
        <div class="modal-dialog">
            <form action="php/guardar_catequista.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus me-1"></i>Registrar Catequista</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido</label>
                        <input type="text" name="apellido" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo</label>
                        <input type="email" name="correo" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">¿Asignar curso?</label>
                        <select name="id_curso" class="form-select">
                            <option value="">No asignar</option>
                            <?php foreach ($cursos as $curso): ?>
                                <option value="<?= $curso['id_curso'] ?>"><?= htmlspecialchars($curso['nombre']) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Registrar</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modales Ver y Asignar -->
    <?php foreach ($catequistas as $c): ?>
        <?php
$idCatequista = $c['id_catequista'];

$sqlDetalles = "SELECT cu.nombre AS curso_nombre, f.nombre AS feligrese_nombre, f.apellido AS feligrese_apellido
                FROM curso_catequistas cc
                INNER JOIN cursos cu ON cc.id_curso = cu.id_curso
                LEFT JOIN curso_feligres cf ON cu.id_curso = cf.id_curso
                LEFT JOIN feligreses f ON cf.id_feligres = f.id_feligres
                WHERE cc.id_catequista = :id_catequista
                ORDER BY cu.nombre, f.apellido";

$stmtDetalles = $pdo->prepare($sqlDetalles);
$stmtDetalles->execute(['id_catequista' => $idCatequista]);
$detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

// Agrupar los resultados por curso
$datosAgrupados = [];
foreach ($detalles as $d) {
    $curso = $d['curso_nombre'];
    $feligrese = trim($d['feligrese_nombre'] . ' ' . $d['feligrese_apellido']);

    if (!isset($datosAgrupados[$curso])) {
        $datosAgrupados[$curso] = [];
    }

    if (!empty(trim($feligrese))) {
        $datosAgrupados[$curso][] = $feligrese;
    }
}
?>

        <!-- Modal Ver -->
        <div class="modal fade" id="modalVer<?= $c['id_catequista'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-person-vcard me-1"></i>Detalle del Catequista</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <div class="modal-body">
    <p><strong>Nombre:</strong> <?= htmlspecialchars($c['nombre']) ?></p>
    <p><strong>Apellido:</strong> <?= htmlspecialchars($c['apellido']) ?></p>
    <p><strong>Correo:</strong> <?= htmlspecialchars($c['correo']) ?></p>
    <p><strong>Teléfono:</strong> <?= htmlspecialchars($c['telefono']) ?></p>
    <p><strong>Total cursos asignados:</strong> <?= $c['total_cursos'] ?></p>

    <hr>
    <h6><i class="bi bi-journal-text me-1"></i>Cursos Detallados:</h6>
    <?php if (!empty($datosAgrupados)): ?>
        <?php foreach ($datosAgrupados as $curso => $feligreses): ?>
            <div class="mb-2">
                <strong class="text-primary"><?= htmlspecialchars($curso) ?></strong>
                <?php if (!empty($feligreses)): ?>
                    <ul class="mb-0">
                        <?php foreach ($feligreses as $feligrese): ?>
                            <li><?= htmlspecialchars($feligrese) ?></li>
                        <?php endforeach ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted mb-0">Sin feligreses registrados.</p>
                <?php endif ?>
            </div>
        <?php endforeach ?>
    <?php else: ?>
        <p class="text-muted">No hay cursos asignados.</p>
    <?php endif ?>
</div>

                </div>
            </div>
        </div>

        <!-- Modal Asignar Curso -->
        <div class="modal fade" id="modalAsignar<?= $c['id_catequista'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <form action="php/asignar_curso.php" method="POST" class="modal-content">
                    <input type="hidden" name="id_catequista" value="<?= $c['id_catequista'] ?>">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-journal-plus me-1"></i>Asignar Curso</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Curso</label>
                        <select name="id_curso" class="form-select" required>
                            <option value="">Seleccione un curso...</option>
                            <?php foreach ($cursos as $curso): ?>
                                <option value="<?= $curso['id_curso'] ?>"><?= htmlspecialchars($curso['nombre']) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Asignar</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Modal Editar -->
<div class="modal fade" id="modalEditar<?= $c['id_catequista'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <form action="php/editar_catequista.php" method="POST" class="modal-content">
            <input type="hidden" name="id_catequista" value="<?= $c['id_catequista'] ?>">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-1"></i>Editar Catequista</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($c['nombre']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Apellido</label>
                    <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($c['apellido']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($c['telefono']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Correo</label>
                    <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($c['correo']) ?>" required>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning" type="submit">Guardar Cambios</button>
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </form>
    </div>
</div>

    <?php endforeach; ?>
</main>
<script>
function confirmarEliminacion(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este catequista?')) {
        window.location.href = 'php/eliminar_catequista.php?id=' + id;
    }
}
</script>
