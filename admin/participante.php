<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<?php
// Parámetro de búsqueda (por nombre persona o nombre catequesis)
$buscar = $_GET['buscar'] ?? '';

// Query que une persona y catequesis para mostrar participantes con sus datos
$sql = "SELECT pc.id, pc.fecha_inscripcion, p.nombres, p.apellidos, c.nombre AS catequesis_nombre
        FROM participante_catequesis pc
        JOIN persona p ON pc.persona_id = p.id
        JOIN catequesis c ON pc.catequesis_id = c.id
        WHERE CONCAT(p.nombres, ' ', p.apellidos) LIKE :buscar
           OR c.nombre LIKE :buscar
        ORDER BY pc.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['buscar' => "%$buscar%"]);
$participantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Para los selects del formulario
$personas = $pdo->query("SELECT id, nombres, apellidos 
FROM persona 
WHERE id NOT IN (SELECT persona_id FROM usuarios)
  AND id NOT IN (SELECT persona_id FROM catequistas)
  AND id NOT IN (SELECT persona_id FROM relaciones_persona)
ORDER BY nombres, apellidos")->fetchAll(PDO::FETCH_ASSOC);
$catequesis = $pdo->query("SELECT id, nombre FROM catequesis ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content">
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= ucfirst($_GET['mensaje']) ?> correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="container mt-4">
        <h2>Gestión de Participantes a Catequesis</h2>

        <div class="d-flex justify-content-between mb-3">
            <form class="d-flex" method="GET">
                <input class="form-control me-2" type="search" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
                    placeholder="Buscar persona o catequesis...">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
            </form>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistro">+ Nuevo Participante</button>
        </div>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Persona</th>
                    <th>Catequesis</th>
                    <th>Fecha Inscripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($participantes as $part): ?>
                    <tr>
                        <td><?= $part['id'] ?></td>
                        <td><?= htmlspecialchars($part['nombres'] . ' ' . $part['apellidos']) ?></td>
                        <td><?= htmlspecialchars($part['catequesis_nombre']) ?></td>
                        <td><?= $part['fecha_inscripcion'] ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                data-bs-target="#modalEditar<?= $part['id'] ?>">Editar</button>
                            <a href="../php/eliminar_participante.php?id=<?= $part['id'] ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Eliminar este participante?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de Registro -->
    <div class="modal fade" id="modalRegistro" tabindex="-1">
        <div class="modal-dialog">
            <form action="../php/guardar_participante.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Participante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php
                    // Variables vacías para registro
                    $persona_id = '';
                    $catequesis_id = '';
                    $fecha_inscripcion = date('Y-m-d');
                    include '../components/form_participante.php';
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Registrar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modales de edición -->
    <?php foreach ($participantes as $part): ?>
        <div class="modal fade" id="modalEditar<?= $part['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <form action="../php/editar_participante.php" method="POST" class="modal-content">
                    <input type="hidden" name="id" value="<?= $part['id'] ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Participante</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <?php
                        $persona_id = $part['persona_id'] ?? '';
                        $catequesis_id = $part['catequesis_id'] ?? '';
                        $fecha_inscripcion = $part['fecha_inscripcion'] ?? '';
                        include '../components/form_participante.php';
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
