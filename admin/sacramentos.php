<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<?php
$buscar = $_GET['buscar'] ?? '';

// Consulta con filtro de búsqueda
$sql = "SELECT * FROM sacramentos WHERE nombre LIKE :buscar ORDER BY id_sacramento";
$stmt = $pdo->prepare($sql);
$stmt->execute(['buscar' => "%$buscar%"]);
$sacramentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content">
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars(ucfirst($_GET['mensaje'])) ?> correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="container mt-4">
        <h2><i class="bi bi-bookmarks me-2"></i>Gestión de Sacramentos</h2>

        <div class="d-flex justify-content-between mb-3">
            <form class="d-flex" method="GET">
                <input class="form-control me-2" type="search" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
                    placeholder="Buscar sacramento...">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
            </form>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistro">
                <i class="bi bi-plus-lg me-1"></i>Nuevo Sacramento
            </button>
        </div>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sacramentos as $s): ?>
                    <tr>
                        <td><?= $s['id_sacramento'] ?></td>
                        <td><?= htmlspecialchars($s['nombre']) ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                data-bs-target="#modalEditar<?= $s['id_sacramento'] ?>">
                                <i class="bi bi-pencil-square"></i> Editar
                            </button>
                            <a href="../php/eliminar_sacramento.php?id=<?= $s['id_sacramento'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('¿Eliminar este sacramento?')">
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
            <form action="../php/guardar_sacramento.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-1"></i>Registrar Sacramento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required maxlength="50">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Registrar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modales de edición -->
    <?php foreach ($sacramentos as $s): ?>
        <div class="modal fade" id="modalEditar<?= $s['id_sacramento'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="../php/editar_sacramento.php" method="POST" class="modal-content">
                    <input type="hidden" name="id_sacramento" value="<?= $s['id_sacramento'] ?>">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-pencil-square me-1"></i>Editar Sacramento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label for="nombre<?= $s['id_sacramento'] ?>" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="nombre<?= $s['id_sacramento'] ?>" class="form-control"
                               value="<?= htmlspecialchars($s['nombre']) ?>" required maxlength="50">
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

<?php include '../includes/footer.php'; ?>
