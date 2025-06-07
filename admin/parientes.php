<?php 
include '../includes/header.php'; 
include '../includes/sidebar.php'; 

if (!isset($pdo)) {
    die("Error: No hay conexión a la base de datos.");
}

$buscar = trim($_GET['buscar'] ?? '');

// Consulta con filtro por nombre o apellido
$sql = "SELECT * FROM parientes 
        WHERE nombre LIKE :buscar OR apellido LIKE :buscar 
        ORDER BY id_pariente";
$stmt = $pdo->prepare($sql);
$stmt->execute(['buscar' => "%$buscar%"]);
$parientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obtener feligreses (falta ejecutar consulta en original)
$sql = "SELECT * FROM feligreses";
$stmtFeligreses = $pdo->prepare($sql);
$stmtFeligreses->execute();
$feligreses = $stmtFeligreses->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content">
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars(ucfirst($_GET['mensaje'])) ?> correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <div class="container mt-4">
        <h2><i class="bi bi-people-fill me-2"></i>Gestión de Parientes</h2>

        <div class="d-flex justify-content-between mb-3">
            <form class="d-flex" method="GET" action="">
                <input class="form-control me-2" type="search" name="buscar" placeholder="Buscar por nombre o apellido"
                    value="<?= htmlspecialchars($buscar) ?>">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
            </form>

            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistro">
                <i class="bi bi-plus-lg me-1"></i>Nuevo Pariente
            </button>
        </div>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Teléfono</th>
                    <th>Tipo</th>
                    <th>Datos Adicionales</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($parientes) === 0): ?>
                    <tr>
                        <td colspan="7" class="text-center">No se encontraron parientes.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($parientes as $p): ?>
                        <tr>
                            <td><?= (int)$p['id_pariente'] ?></td>
                            <td><?= htmlspecialchars($p['nombre']) ?></td>
                            <td><?= htmlspecialchars($p['apellido']) ?></td>
                            <td><?= htmlspecialchars($p['telefono']) ?></td>
                            <td><?= htmlspecialchars($p['tipo_pariente']) ?></td>
                            <td>
                                <?php 
                                $datos = json_decode($p['datos_adicionales'], true);
                                if (!empty($datos) && is_array($datos)) {
                                    echo '<ul class="mb-0">';
                                    foreach ($datos as $clave => $valor) {
                                        echo '<li><strong>' . htmlspecialchars($clave) . ':</strong> ' . htmlspecialchars($valor) . '</li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditar<?= (int)$p['id_pariente'] ?>">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </button>
                                <a href="../php/eliminar_pariente.php?id=<?= (int)$p['id_pariente'] ?>" class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Eliminar este pariente?')">
                                    <i class="bi bi-trash"></i> Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Registro -->
    <div class="modal fade" id="modalRegistro" tabindex="-1" aria-hidden="true" aria-labelledby="modalRegistroLabel" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <form action="../php/guardar_pariente.php" method="POST" class="modal-content" id="formRegistroPariente" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRegistroLabel"><i class="bi bi-plus-circle me-1"></i>Registrar Pariente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <!-- Relación feligrés-pariente -->
                    <div class="mb-3">
                        <label for="id_feligres" class="form-label">Feligres <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_feligres" name="id_feligres" required>
                            <option value="">Seleccione un feligrés...</option>
                            <?php foreach ($feligreses as $prod): ?>
                                <option value="<?= (int)$prod['id_feligres'] ?>"><?= htmlspecialchars($prod['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Debe seleccionar un feligrés.</div>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required maxlength="100" autocomplete="off">
                    </div>

                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" name="apellido" id="apellido" class="form-control" maxlength="100" autocomplete="off">
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-control" maxlength="20" autocomplete="off">
                    </div>

                    <div class="mb-3">
                        <label for="tipo_pariente" class="form-label">Tipo de Pariente <span class="text-danger">*</span></label>
                        <select name="tipo_pariente" id="tipo_pariente" class="form-select" required>
                            <option value="" selected disabled>Selecciona tipo</option>
                            <option value="padre">Padre</option>
                            <option value="madre">Madre</option>
                            <option value="padrino">Padrino</option>
                            <option value="madrina">Madrina</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="datos_adicionales" class="form-label">Datos Adicionales (JSON)</label>
                        <textarea name="datos_adicionales" id="datos_adicionales" class="form-control" rows="3" placeholder='Ejemplo: {"direccion":"Calle 123","nota":"Contacto preferido"}'></textarea>
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
    <?php foreach ($parientes as $p): ?>
        <div class="modal fade" id="modalEditar<?= (int)$p['id_pariente'] ?>" tabindex="-1" aria-hidden="true" aria-labelledby="modalEditarLabel<?= (int)$p['id_pariente'] ?>" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <form action="../php/editar_pariente.php" method="POST" class="modal-content" id="formEditarPariente<?= (int)$p['id_pariente'] ?>" novalidate>
                    <input type="hidden" name="id_pariente" value="<?= (int)$p['id_pariente'] ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarLabel<?= (int)$p['id_pariente'] ?>"><i class="bi bi-pencil-square me-1"></i>Editar Pariente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre<?= (int)$p['id_pariente'] ?>" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre<?= (int)$p['id_pariente'] ?>" class="form-control" 
                                value="<?= htmlspecialchars($p['nombre']) ?>" required maxlength="100" autocomplete="off">
                        </div>

                        <div class="mb-3">
                            <label for="apellido<?= (int)$p['id_pariente'] ?>" class="form-label">Apellido</label>
                            <input type="text" name="apellido" id="apellido<?= (int)$p['id_pariente'] ?>" class="form-control" 
                                value="<?= htmlspecialchars($p['apellido']) ?>" maxlength="100" autocomplete="off">
                        </div>

                        <div class="mb-3">
                            <label for="telefono<?= (int)$p['id_pariente'] ?>" class="form-label">Teléfono</label>
                            <input type="text" name="telefono" id="telefono<?= (int)$p['id_pariente'] ?>" class="form-control" 
                                value="<?= htmlspecialchars($p['telefono']) ?>" maxlength="20" autocomplete="off">
                        </div>

                        <div class="mb-3">
                            <label for="tipo_pariente<?= (int)$p['id_pariente'] ?>" class="form-label">Tipo de Pariente <span class="text-danger">*</span></label>
                            <select name="tipo_pariente" id="tipo_pariente<?= (int)$p['id_pariente'] ?>" class="form-select" required>
                                <option value="padre" <?= $p['tipo_pariente']=='padre' ? 'selected' : '' ?>>Padre</option>
                                <option value="madre" <?= $p['tipo_pariente']=='madre' ? 'selected' : '' ?>>Madre</option>
                                <option value="padrino" <?= $p['tipo_pariente']=='padrino' ? 'selected' : '' ?>>Padrino</option>
                                <option value="madrina" <?= $p['tipo_pariente']=='madrina' ? 'selected' : '' ?>>Madrina</option>
                                <option value="otro" <?= $p['tipo_pariente']=='otro' ? 'selected' : '' ?>>Otro</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="datos_adicionales<?= (int)$p['id_pariente'] ?>" class="form-label">Datos Adicionales (JSON)</label>
                            <textarea name="datos_adicionales" id="datos_adicionales<?= (int)$p['id_pariente'] ?>" class="form-control" rows="3"><?= htmlspecialchars($p['datos_adicionales']) ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Actualizar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</main>

<?php include '../includes/footer.php'; ?>
