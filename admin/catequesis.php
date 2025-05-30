<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<?php
// Conexión PDO en $pdo
$buscar = $_GET['buscar'] ?? '';

// Consulta con JOIN para traer catequesis junto con sacramento y catequista
$sql = "SELECT c.*, s.nombre AS sacramento_nombre, 
        CONCAT(p.nombres, ' ', p.apellidos) AS catequista_nombre
        FROM catequesis c
        INNER JOIN sacramento s ON c.sacramento_id = s.id
        LEFT JOIN persona p ON c.catequista_id = p.id
        WHERE c.nombre LIKE :buscar
        ORDER BY c.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['buscar' => "%$buscar%"]);
$catequesis_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traer todos los sacramentos para el formulario
$sacrs = $pdo->query("SELECT * FROM sacramento")->fetchAll(PDO::FETCH_ASSOC);

// Traer personas para catequistas
$personas = $pdo->query("SELECT id, nombres, apellidos 
    FROM persona 
    WHERE id NOT IN (
        SELECT persona_id FROM catequistas
        UNION
        SELECT persona_id FROM participante_catequesis
    )
    ORDER BY apellidos, nombres")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content">
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars(ucfirst($_GET['mensaje'])) ?> correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="container mt-4">
        <h2>Gestión de Catequesis</h2>

        <div class="d-flex justify-content-between mb-3">
            <form class="d-flex" method="GET">
                <input class="form-control me-2" type="search" name="buscar" value="<?= htmlspecialchars($buscar) ?>" placeholder="Buscar catequesis...">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
            </form>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistro">+ Nueva Catequesis</button>
        </div>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Sacramento</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Catequista</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($catequesis_list as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= htmlspecialchars($c['nombre']) ?></td>
                    <td><?= htmlspecialchars($c['sacramento_nombre']) ?></td>
                    <td><?= $c['fecha_inicio'] ?></td>
                    <td><?= $c['fecha_fin'] ?></td>
                    <td><?= htmlspecialchars($c['catequista_nombre'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($c['observaciones']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $c['id'] ?>">Editar</button>
                        <a href="../php/eliminar_catequesis.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta catequesis?')">Eliminar</a>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalDetalles<?= $c['id'] ?>">Detalle</button>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Registro -->
    <div class="modal fade" id="modalRegistro" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="../php/guardar_catequesis.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Catequesis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php
                    // Valores vacíos para registro
                    $modo = 'registrar';
                    $nombre = $fecha_inicio = $fecha_fin = $observaciones = '';
                    $sacramento_id = $catequista_id = null;
                    include '../components/form_catequesis.php';
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
    <?php foreach ($catequesis_list as $c): ?>
    <div class="modal fade" id="modalEditar<?= $c['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="../php/editar_catequesis.php" method="POST" class="modal-content">
                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Catequesis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $modo = 'editar';
                    $nombre = $c['nombre'];
                    $fecha_inicio = $c['fecha_inicio'];
                    $fecha_fin = $c['fecha_fin'];
                    $observaciones = $c['observaciones'];
                    $sacramento_id = $c['sacramento_id'];
                    $catequista_id = $c['catequista_id'];
                    include '../components/form_catequesis.php';
                    ?>
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
