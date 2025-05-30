<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<?php 

$buscar = $_GET['buscar'] ?? '';

$sql = "SELECT c.*, p.nombres, p.apellidos 
        FROM catequistas c
        JOIN persona p ON c.persona_id = p.id
        WHERE CONCAT(p.nombres, ' ', p.apellidos) LIKE :buscar 
        ORDER BY p.apellidos ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['buscar' => "%$buscar%"]);
$catequistas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sqlPersonas = "SELECT id, nombres, apellidos 
    FROM persona 
    WHERE id NOT IN (
        SELECT persona_id FROM catequistas
        UNION
        SELECT persona_id FROM participante_catequesis
    )
    ORDER BY apellidos, nombres";
$personasStmt = $pdo->query($sqlPersonas);
$personasDisponibles = $personasStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content">
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= ucfirst($_GET['mensaje']) ?> correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="container mt-4">
        <h2>Gestión de Catequistas</h2>

        <div class="d-flex justify-content-between mb-3">
            <form class="d-flex" method="GET">
                <input class="form-control me-2" type="search" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
                    placeholder="Buscar catequista...">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
            </form>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistro">+ Nuevo Catequista</button>
        </div>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Especialidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($catequistas as $c): ?>
                    <tr>
                        <td><?= $c['persona_id'] ?></td>
                        <td><?= $c['nombres'] . ' ' . $c['apellidos'] ?></td>
                        <td><?= htmlspecialchars($c['especialidad']) ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $c['persona_id'] ?>">Editar</button>
                            <a href="../php/eliminar_catequista.php?id=<?= $c['persona_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este catequista?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de Registro -->
    <div class="modal fade" id="modalRegistro" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="../php/guardar_catequista.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Catequista</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="persona_id" class="form-label">Persona</label>
                        <select name="persona_id" id="persona_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($personasDisponibles as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= $p['nombres'] . ' ' . $p['apellidos'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="especialidad" class="form-label">Especialidad</label>
                        <input type="text" name="especialidad" id="especialidad" class="form-control" required>
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
    <?php foreach ($catequistas as $c): ?>
        <div class="modal fade" id="modalEditar<?= $c['persona_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="../php/editar_catequista.php" method="POST" class="modal-content">
                    <input type="hidden" name="persona_id" value="<?= $c['persona_id'] ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Catequista</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="especialidad<?= $c['persona_id'] ?>" class="form-label">Especialidad de: (<?= $p['nombres'] . ' ' . $p['apellidos'] ?>)</label>
                            <input type="text" name="especialidad" id="especialidad<?= $c['persona_id'] ?>" class="form-control" value="<?= htmlspecialchars($c['especialidad']) ?>" required>
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
</main>

<?php include '../includes/footer.php'; ?>