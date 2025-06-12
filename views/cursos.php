<?php
 

$buscar = $_GET['buscar'] ?? '';

$sql = "SELECT c.*, cat.nombre AS catequesis_nombre FROM cursos c
        LEFT JOIN catequesis cat ON c.id_catequesis = cat.id_catequesis
        WHERE c.nombre LIKE :buscar OR cat.nombre LIKE :buscar
        ORDER BY c.id_curso DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['buscar' => "%$buscar%"]);
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main id="content">
 

    <div class="container mt-4">
        <h2><i class="bi bi-journal-bookmark me-2"></i>Gestión de Cursos</h2>

        <div class="d-flex justify-content-between mb-3">
            <form class="d-flex" method="GET">
                <input class="form-control me-2" type="search" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
                       placeholder="Buscar curso o catequesis...">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
            </form>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistro">
                <i class="bi bi-journal-plus me-1"></i>Nuevo Curso
            </button>
        </div>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Catequesis</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cursos as $c): ?>
                    <tr>
                        <td><?= $c['id_curso'] ?></td>
                        <td><?= htmlspecialchars($c['nombre']) ?></td>
                        <td><?= htmlspecialchars($c['catequesis_nombre']) ?></td>
                        <td><?= date('d/m/Y', strtotime($c['fecha_inicio'])) ?></td>
                        <td><?= date('d/m/Y', strtotime($c['fecha_fin'])) ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#modalEditar<?= $c['id_curso'] ?>">
                                <i class="bi bi-pencil-square"></i> Editar
                            </button>
                            <a href="php/eliminar_cursos.php?id=<?= $c['id_curso'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('¿Eliminar este curso?')">
                                <i class="bi bi-trash"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Registro -->
    <div class="modal fade" id="modalRegistro" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="php/guardar_cursos.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-journal-plus me-1"></i>Registrar Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label for="nombre" class="form-label">Nombre del Curso</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label for="id_catequesis" class="form-label">Catequesis</label>
                        <select name="id_catequesis" id="id_catequesis" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php
                            $cats = $pdo->query("SELECT * FROM catequesis ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($cats as $cat): ?>
                                <option value="<?= $cat['id_catequesis'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Registrar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modales de Edición -->
    <?php foreach ($cursos as $c): ?>
        <div class="modal fade" id="modalEditar<?= $c['id_curso'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="php/editar_cursos.php" method="POST" class="modal-content">
                    <input type="hidden" name="id_curso" value="<?= $c['id_curso'] ?>">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-pencil-square me-1"></i>Editar Curso</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-12">
                            <label class="form-label">Nombre del Curso</label>
                            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($c['nombre']) ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Catequesis</label>
                            <select name="id_catequesis" class="form-select" required>
                                <?php foreach ($cats as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $c['id_catequesis'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control" value="<?= $c['fecha_inicio'] ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Fecha de Fin</label>
                            <input type="date" name="fecha_fin" class="form-control" value="<?= $c['fecha_fin'] ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3"><?= htmlspecialchars($c['descripcion']) ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</main>
