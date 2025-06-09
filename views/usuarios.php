<?php
$buscar = $_GET['buscar'] ?? '';

// Consulta de usuarios
$sql = "SELECT * FROM usuarios WHERE nombre LIKE :buscar OR usuario LIKE :buscar ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['buscar' => "%$buscar%"]);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main id="content">
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= ucfirst($_GET['mensaje']) ?> correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="container mt-4">
        <h2><i class="bi bi-person-gear me-2"></i>Gestión de Usuarios</h2>

        <div class="d-flex justify-content-between mb-3">
            <form class="d-flex" method="GET">
                <input class="form-control me-2" type="search" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
                    placeholder="Buscar usuario o nombre...">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
            </form>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistro">
                <i class="bi bi-person-plus me-1"></i>Nuevo Usuario
            </button>
        </div>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Registro</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['nombre']) ?></td>
                        <td><?= htmlspecialchars($u['dni']) ?></td>
                        <td><?= htmlspecialchars($u['usuario']) ?></td>
                        <td><span class="badge bg-dark"><?= ucfirst($u['rol']) ?></span></td>
                        <td>
                            <span class="badge bg-<?= $u['estado'] ? 'success' : 'danger' ?>">
                                <?= $u['estado'] ? 'Activo' : 'Inactivo' ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($u['fecha_registro'])) ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                data-bs-target="#modalEditar<?= $u['id'] ?>">
                                <i class="bi bi-pencil-square"></i> Editar
                            </button>
                            <a href="../php/eliminar_usuario.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('¿Eliminar este usuario?')">
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
            <form action="php/guardar_usuario.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus me-1"></i>Registrar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label for="nombre" class="form-label">Nombre completo</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label for="dni" class="form-label">DNI</label>
                        <input type="text" name="dni" id="dni" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label for="usuario" class="form-label">Usuario</label>
                        <input type="text" name="usuario" id="usuario" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label for="contrasena" class="form-label">Contraseña</label>
                        <input type="password" name="contrasena" id="contrasena" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label for="rol" class="form-label">Rol</label>
                        <select name="rol" id="rol" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="admin">Admin</option>
                            <option value="secretario">Secretario</option>
                            <option value="archivista">Archivista</option>
                            <option value="parroco">Párroco</option>
                        </select>
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
    <?php foreach ($usuarios as $u): ?>
        <div class="modal fade" id="modalEditar<?= $u['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="php/editar_usuario.php" method="POST" class="modal-content">
                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-pencil-square me-1"></i>Editar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-12">
                            <label class="form-label">Nombre completo</label>
                            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($u['nombre']) ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">DNI</label>
                            <input type="text" name="dni" class="form-control" value="<?= htmlspecialchars($u['dni']) ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Usuario</label>
                            <input type="text" name="usuario" class="form-control" value="<?= htmlspecialchars($u['usuario']) ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Nueva contraseña</label>
                            <input type="password" name="contrasena" class="form-control" placeholder="(Opcional)">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Rol</label>
                            <select name="rol" class="form-select" required>
                                <option value="admin" <?= $u['rol'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="secretario" <?= $u['rol'] === 'secretario' ? 'selected' : '' ?>>Secretario</option>
                                <option value="archivista" <?= $u['rol'] === 'archivista' ? 'selected' : '' ?>>Archivista</option>
                                <option value="parroco" <?= $u['rol'] === 'parroco' ? 'selected' : '' ?>>Párroco</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="1" <?= $u['estado'] ? 'selected' : '' ?>>Activo</option>
                                <option value="0" <?= !$u['estado'] ? 'selected' : '' ?>>Inactivo</option>
                            </select>
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
