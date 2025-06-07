<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<?php
$buscar = $_GET['buscar'] ?? '';

// Consulta de parroquias para el select en registro y edición
$sql = "SELECT * FROM parroquias";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$parroquias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta feligreses con filtro de búsqueda (por nombre o apellido)
$sql = "SELECT f.*, p.nombre AS nombre_parroquia 
        FROM feligreses f 
        LEFT JOIN parroquias p ON f.id_parroquia = p.id_parroquia
        WHERE f.nombre LIKE :buscar OR f.apellido LIKE :buscar
        ORDER BY f.id_feligres DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['buscar' => "%$buscar%"]);
$feligreses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content">
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= ucfirst($_GET['mensaje']) ?> correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="container mt-4">
        <h2><i class="bi bi-people-fill me-2"></i>Gestión de Feligreses</h2>

        <div class="d-flex justify-content-between mb-3">
            <form class="d-flex" method="GET">
                <input class="form-control me-2" type="search" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
                    placeholder="Buscar por nombre o apellido...">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
            </form>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistro">
                <i class="bi bi-plus-lg me-1"></i>Nuevo Feligres
            </button>
        </div>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Parroquia</th>
                    <th>Fecha Nacimiento</th>
                    <th>Género</th>
                    <th>Teléfono</th>
                    <th>Estado Civil</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($feligreses as $f): ?>
                    <tr>
                        <td><?= $f['id_feligres'] ?></td>
                        <td><?= htmlspecialchars($f['nombre'] . ' ' . $f['apellido']) ?></td>
                        <td><?= htmlspecialchars($f['nombre_parroquia']) ?></td>
                        <td><?= $f['fecha_nacimiento'] ? date('d/m/Y', strtotime($f['fecha_nacimiento'])) : '' ?></td>
                        <td><?= $f['genero'] === 'M' ? 'Masculino' : ($f['genero'] === 'F' ? 'Femenino' : '') ?></td>
                        <td><?= htmlspecialchars($f['telefono']) ?></td>
                        <td><?= ucfirst($f['estado_civil']) ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                data-bs-target="#modalEditar<?= $f['id_feligres'] ?>">
                                <i class="bi bi-pencil-square"></i> Editar
                            </button>
                            <a href="../php/eliminar_feligres.php?id=<?= $f['id_feligres'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('¿Eliminar este feligrés?')">
                                <i class="bi bi-trash"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
                <?php if(count($feligreses) === 0): ?>
                    <tr><td colspan="8" class="text-center">No se encontraron resultados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Registro -->
    <div class="modal fade" id="modalRegistro" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="../php/guardar_feligres.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-1"></i>Registrar Feligres</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label for="id_parroquia" class="form-label">Parroquia</label>
                        <select name="id_parroquia" id="id_parroquia" class="form-select" required>
                            <option value="" disabled selected>Seleccione parroquia</option>
                            <?php foreach ($parroquias as $p): ?>
                                <option value="<?= $p['id_parroquia'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" name="apellido" id="apellido" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="genero" class="form-label">Género</label>
                        <select name="genero" id="genero" class="form-select">
                            <option value="" selected>Seleccione género</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" name="direccion" id="direccion" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="estado_civil" class="form-label">Estado Civil</label>
                        <select name="estado_civil" id="estado_civil" class="form-select">
                            <option value="" selected>Seleccione estado civil</option>
                            <option value="soltero">Soltero</option>
                            <option value="casado">Casado</option>
                            <option value="viudo">Viudo</option>
                            <option value="separado">Separado</option>
                        </select>
                    </div>
                    <!-- Matrimonio podría ser JSON, pero para registrar se puede agregar campos simples -->
                    <!-- Aquí dejamos vacío o podemos agregar una sección avanzada si quieres -->
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Registrar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modales de edición -->
    <?php foreach ($feligreses as $f): ?>
        <div class="modal fade" id="modalEditar<?= $f['id_feligres'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="../php/editar_feligres.php" method="POST" class="modal-content">
                    <input type="hidden" name="id_feligres" value="<?= $f['id_feligres'] ?>">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-pencil-square me-1"></i>Editar Feligres</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-12">
                            <label for="id_parroquia<?= $f['id_feligres'] ?>" class="form-label">Parroquia</label>
                            <select name="id_parroquia" id="id_parroquia<?= $f['id_feligres'] ?>" class="form-select" required>
                                <option value="" disabled>Seleccione parroquia</option>
                                <?php foreach ($parroquias as $p): ?>
                                    <option value="<?= $p['id_parroquia'] ?>" <?= $p['id_parroquia'] == $f['id_parroquia'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($p['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="nombre<?= $f['id_feligres'] ?>" class="form-label">Nombre</label>
                            <input type="text" name="nombre" id="nombre<?= $f['id_feligres'] ?>" class="form-control"
                                   value="<?= htmlspecialchars($f['nombre']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="apellido<?= $f['id_feligres'] ?>" class="form-label">Apellido</label>
                            <input type="text" name="apellido" id="apellido<?= $f['id_feligres'] ?>" class="form-control"
                                   value="<?= htmlspecialchars($f['apellido']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_nacimiento<?= $f['id_feligres'] ?>" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento<?= $f['id_feligres'] ?>" class="form-control"
                                   value="<?= $f['fecha_nacimiento'] ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="genero<?= $f['id_feligres'] ?>" class="form-label">Género</label>
                            <select name="genero" id="genero<?= $f['id_feligres'] ?>" class="form-select">
                                <option value="" <?= $f['genero'] === null ? 'selected' : '' ?>>Seleccione género</option>
                                <option value="M" <?= $f['genero'] === 'M' ? 'selected' : '' ?>>Masculino</option>
                                <option value="F" <?= $f['genero'] === 'F' ? 'selected' : '' ?>>Femenino</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="direccion<?= $f['id_feligres'] ?>" class="form-label">Dirección</label>
                            <input type="text" name="direccion" id="direccion<?= $f['id_feligres'] ?>" class="form-control"
                                   value="<?= htmlspecialchars($f['direccion']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="telefono<?= $f['id_feligres'] ?>" class="form-label">Teléfono</label>
                            <input type="text" name="telefono" id="telefono<?= $f['id_feligres'] ?>" class="form-control"
                                   value="<?= htmlspecialchars($f['telefono']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="estado_civil<?= $f['id_feligres'] ?>" class="form-label">Estado Civil</label>
                            <select name="estado_civil" id="estado_civil<?= $f['id_feligres'] ?>" class="form-select">
                                <option value="" <?= $f['estado_civil'] === null ? 'selected' : '' ?>>Seleccione estado civil</option>
                                <option value="soltero" <?= $f['estado_civil'] === 'soltero' ? 'selected' : '' ?>>Soltero</option>
                                <option value="casado" <?= $f['estado_civil'] === 'casado' ? 'selected' : '' ?>>Casado</option>
                                <option value="viudo" <?= $f['estado_civil'] === 'viudo' ? 'selected' : '' ?>>Viudo</option>
                                <option value="separado" <?= $f['estado_civil'] === 'separado' ? 'selected' : '' ?>>Separado</option>
                            </select>
                        </div>
                        <!-- Por simplicidad no manejamos aquí el JSON de matrimonio -->
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
