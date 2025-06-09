
<?php
$buscar = $_GET['buscar'] ?? '';

// Consulta de parroquias
$sql = "SELECT * FROM parroquias";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$parroquias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main id="content">
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= ucfirst($_GET['mensaje']) ?> correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="container mt-4">
        <h2><i class="bi bi-buildings me-2"></i>Gestión de Parroquias</h2>

        <div class="d-flex justify-content-between mb-3">
            <form class="d-flex" method="GET">
                <input class="form-control me-2" type="search" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
                    placeholder="Buscar parroquia...">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
            </form>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistro">
                <i class="bi bi-plus-lg me-1"></i>Nueva Parroquia
            </button>
        </div>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($parroquias as $p): ?>
                    <tr>
                        <td><?= $p['id_parroquia'] ?></td>
                        <td><?= htmlspecialchars($p['nombre']) ?></td>
                        <td><?= htmlspecialchars($p['direccion']) ?></td>
                        <td><?= htmlspecialchars($p['telefono']) ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                data-bs-target="#modalEditar<?= $p['id_parroquia'] ?>">
                                <i class="bi bi-pencil-square"></i> Editar
                            </button>
                            <a href="../php/eliminar_parroquia.php?id=<?= $p['id_parroquia'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('¿Eliminar esta parroquia?')">
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
            <form action="../php/guardar_parroquia.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-1"></i>Registrar Parroquia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" name="direccion" id="direccion" class="form-control">
                    </div>
                    <div class="col-12">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Registrar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modales de edición -->
    <?php foreach ($parroquias as $p): ?>
        <div class="modal fade" id="modalEditar<?= $p['id_parroquia'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="../php/editar_parroquia.php" method="POST" class="modal-content">
                    <input type="hidden" name="id_parroquia" value="<?= $p['id_parroquia'] ?>">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-pencil-square me-1"></i>Editar Parroquia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-12">
                            <label for="nombre<?= $p['id_parroquia'] ?>" class="form-label">Nombre</label>
                            <input type="text" name="nombre" id="nombre<?= $p['id_parroquia'] ?>" class="form-control"
                                   value="<?= htmlspecialchars($p['nombre']) ?>" required>
                        </div>
                        <div class="col-12">
                            <label for="direccion<?= $p['id_parroquia'] ?>" class="form-label">Dirección</label>
                            <input type="text" name="direccion" id="direccion<?= $p['id_parroquia'] ?>" class="form-control"
                                   value="<?= htmlspecialchars($p['direccion']) ?>">
                        </div>
                        <div class="col-12">
                            <label for="telefono<?= $p['id_parroquia'] ?>" class="form-label">Teléfono</label>
                            <input type="text" name="telefono" id="telefono<?= $p['id_parroquia'] ?>" class="form-control"
                                   value="<?= htmlspecialchars($p['telefono']) ?>">
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
