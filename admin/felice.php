<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<?php
require_once '../includes/conexion.php';

$buscar = $_GET['buscar'] ?? '';

// Consulta completa
$sql = "SELECT 
            pc.id AS id_participante,
            p.nombres,
            p.apellidos,
            p.fecha_nacimiento,
            p.telefono,
            p.correo,
            c.nombre AS nombre_catequesis,
            pc.fecha_inscripcion
        FROM participante_catequesis pc
        INNER JOIN persona p ON pc.persona_id = p.id
        INNER JOIN catequesis c ON pc.catequesis_id = c.id
        WHERE CONCAT(p.nombres, ' ', p.apellidos) LIKE :buscar
        ORDER BY pc.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['buscar' => "%$buscar%"]);
$participantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Función para calcular edad
function calcularEdad($fechaNacimiento) {
    $nacimiento = new DateTime($fechaNacimiento);
    $hoy = new DateTime();
    return $hoy->diff($nacimiento)->y;
}
?>

<main class="content">
    <div class="container mt-4">
        <h2>Listado de Participantes en Catequesis</h2>

        <div class="d-flex justify-content-between mb-3">
            <form class="d-flex" method="GET">
                <input class="form-control me-2" type="search" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
                    placeholder="Buscar participante...">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
            </form>
        </div>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Edad</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Catequesis</th>
                    <th>Fecha Inscripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($participantes) > 0): ?>
                    <?php foreach ($participantes as $p): ?>
                        <tr>
                            <td><?= $p['id_participante'] ?></td>
                            <td><?= htmlspecialchars($p['nombres'] . ' ' . $p['apellidos']) ?></td>
                            <td><?= calcularEdad($p['fecha_nacimiento']) ?> años</td>
                            <td><?= htmlspecialchars($p['telefono']) ?></td>
                            <td><?= htmlspecialchars($p['correo']) ?></td>
                            <td><?= htmlspecialchars($p['nombre_catequesis']) ?></td>
                            <td><?= date('d/m/Y', strtotime($p['fecha_inscripcion'])) ?></td>
                            <td>
                                <a href="../php/eliminar_participante.php?id=<?= $p['id_participante'] ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('¿Eliminar este participante?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No se encontraron participantes.</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
