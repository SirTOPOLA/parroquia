<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<?php
require_once '../includes/conexion.php'; // conexión PDO

$buscar = $_GET['buscar'] ?? '';

// Consulta: obtener personas con rol parroco y filtrar por nombre/apellido si hay búsqueda
$sql = "SELECT 
            persona.id,
            persona.nombres,
            persona.apellidos,
            persona.fecha_nacimiento,
            persona.direccion,
            persona.telefono,
            persona.correo,
            persona.genero,
            usuarios.estado
        FROM persona
        INNER JOIN usuarios ON persona.id = usuarios.persona_id
        WHERE usuarios.rol = 'parroco'
        AND CONCAT(persona.nombres, ' ', persona.apellidos) LIKE :buscar
        ORDER BY persona.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['buscar' => "%$buscar%"]);
$parrocos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Función para calcular la edad a partir de la fecha de nacimiento
function calcularEdad($fechaNacimiento) {
    $nacimiento = new DateTime($fechaNacimiento);
    $hoy = new DateTime();
    $edad = $hoy->diff($nacimiento)->y;
    return $edad;
}
?>

<main class="content">
    <div class="container mt-4">
        <h2>Listado de Párrocos</h2>

        <div class="d-flex justify-content-between mb-3">
            <form class="d-flex" method="GET">
                <input class="form-control me-2" type="search" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
                    placeholder="Buscar párroco...">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
            </form>
        </div>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Edad</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Género</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($parrocos) > 0): ?>
                    <?php foreach ($parrocos as $p): ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td><?= htmlspecialchars($p['nombres'] . ' ' . $p['apellidos']) ?></td>
                            <td><?= calcularEdad($p['fecha_nacimiento']) ?> años</td>
                            <td><?= htmlspecialchars($p['direccion']) ?></td>
                            <td><?= htmlspecialchars($p['telefono']) ?></td>
                            <td><?= htmlspecialchars($p['correo']) ?></td>
                            <td><?= ucfirst($p['genero']) ?></td>
                            <td><?= $p['estado'] ? 'Activo' : 'Inactivo' ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#modalEditar<?= $p['id'] ?>">Editar</button>
                                <a href="../php/eliminar_parroco.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Eliminar este párroco?')">Eliminar</a>
                            </td>
                        </tr> 
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">No se encontraron párrocos.</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
