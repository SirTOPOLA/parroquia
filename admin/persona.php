<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<?php
// Búsqueda
$buscar = $_GET['buscar'] ?? '';
$sql = "SELECT * FROM persona WHERE CONCAT(nombres, ' ', apellidos) LIKE :buscar ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['buscar' => "%$buscar%"]);
$personas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content">
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= ucfirst($_GET['mensaje']) ?> correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="container mt-4">
        <h2>Gestión de Personas</h2>

        <div class="d-flex justify-content-between mb-3">
            <form class="d-flex" method="GET">
                <input class="form-control me-2" type="search" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
                    placeholder="Buscar persona...">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
            </form>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistro">+ Nueva
                Persona</button>
        </div>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Fecha Nacimiento</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($personas as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= $p['nombres'] . ' ' . $p['apellidos'] ?></td>
                        <td><?= $p['fecha_nacimiento'] ?></td>
                        <td><?= $p['telefono'] ?></td>
                        <td><?= $p['correo'] ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                data-bs-target="#modalEditar<?= $p['id'] ?>">Editar</button>
                            <a href="../php/eliminar_persona.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Eliminar esta persona?')">Eliminar</a>
                        </td>
                    </tr>

                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de Edición -->
    <div class="modal fade" id="modalEditar<?= $p['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="../php/editar_persona.php" method="POST" class="modal-content">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Persona</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <?php include '../components/form_persona.php'; ?>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal de Registro -->
    <div class="modal fade" id="modalRegistro" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="../php/guardar_persona.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Persona</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <?php include '../components/form_persona.php'; ?>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Registrar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

</main>


<?php include '../includes/footer.php'; ?>