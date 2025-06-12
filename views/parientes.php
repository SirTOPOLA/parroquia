<?php


$buscar = $_GET['buscar'] ?? '';

$sql = "SELECT 
            f.id_feligres, 
            f.nombre, 
            f.apellido, 
            f.fecha_nacimiento, 
            f.genero,
            -- Parientes asociados
            GROUP_CONCAT(DISTINCT CONCAT(par.tipo_pariente, ': ', par.nombre, ' ', par.apellido) SEPARATOR '<br>') AS parientes,
            -- Sacramentos recibidos por el feligrés
            GROUP_CONCAT(DISTINCT s.nombre ORDER BY s.id_sacramento ASC SEPARATOR ', ') AS sacramentos
        FROM feligreses f
        -- Parientes relacionados
        LEFT JOIN feligres_parientes fp ON f.id_feligres = fp.id_feligres
        LEFT JOIN parientes par ON fp.id_pariente = par.id_pariente
        -- Sacramentos del feligrés
        LEFT JOIN feligres_sacramento fs ON f.id_feligres = fs.id_feligres
        LEFT JOIN sacramentos s ON fs.id_sacramento = s.id_sacramento
        WHERE f.nombre LIKE :buscar OR f.apellido LIKE :buscar
        GROUP BY f.id_feligres
        ORDER BY f.id_feligres DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['buscar' => "%$buscar%"]);
$feligreses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main id="content">
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= ucfirst($_GET['mensaje']) ?> correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="container mt-4">
        <h2><i class="bi bi-people-fill me-2"></i>Gestión de Parientes</h2>

        <div class="d-flex justify-content-between mb-3">
            <form class="d-flex" method="GET">
                <input class="form-control me-2" type="search" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
                    placeholder="Buscar feligrés...">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
            </form>
            <a href="registro_pariente.php" class="btn btn-success">
                <i class="bi bi-person-plus me-1"></i>Nuevo Pariente
            </a>
        </div>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre del Feligrés</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Género</th>
                    <th>Parientes Asociados</th>
                    <th>Sacramentos</th> <!-- NUEVA COLUMNA -->
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($feligreses as $f): ?>
                    <tr>
                        <td><?= $f['id_feligres'] ?></td>
                        <td><?= htmlspecialchars($f['nombre'] . ' ' . $f['apellido']) ?></td>
                        <td><?= date('d/m/Y', strtotime($f['fecha_nacimiento'])) ?></td>
                        <td><?= $f['genero'] ?></td>
                        <td><?= $f['parientes'] ?: '<em>Sin parientes</em>' ?></td>
                        <td><?= $f['sacramentos'] ?: '<em>Sin sacramentos</em>' ?></td>
                        <td class="text-center">
                            <!--  <a href="ver_parientes.php?id=<?= $f['id_feligres'] ?>" class="btn btn-sm btn-info">
                    <i class="bi bi-eye"></i> Ver
                </a> -->
                            <a href="editar_parientes.php?id=<?= $f['id_feligres'] ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>


        </table>
    </div>
</main>