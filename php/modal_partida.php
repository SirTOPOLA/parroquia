<?php
require_once '../config/conexion.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sacramento = isset($_GET['sacramento']) ? strtolower(trim($_GET['sacramento'])) : '';

if ($id <= 0 || !$sacramento) {
    echo '<div class="alert alert-warning">Datos incompletos o inválidos.</div>';
    exit;
}

// Obtener datos generales del feligrés y sacramento
$sql = "SELECT f.nombre, f.apellido, f.genero, f.fecha_nacimiento, f.direccion, 
               s.nombre AS sacramento, fs.fecha, fs.lugar,
               p.nombre AS parroco
        FROM feligres f
        INNER JOIN feligres_sacramentos fs ON f.id_feligres = fs.id_feligres
        INNER JOIN sacramentos s ON fs.id_sacramento = s.id_sacramento
        LEFT JOIN parrocos p ON fs.id_parroco = p.id_parroco
        WHERE f.id_feligres = ? AND LOWER(s.nombre) = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id, $sacramento]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo '<div class="alert alert-danger">No se encontró información para este feligrés.</div>';
    exit;
}

$datos_extra = '';
if ($sacramento === 'bautismo') {
    // Padres
    $sqlPadres = "SELECT padre.nombre AS padre, madre.nombre AS madre
                  FROM padres
                  LEFT JOIN feligres padre ON padres.id_padre = padre.id_feligres
                  LEFT JOIN feligres madre ON padres.id_madre = madre.id_feligres
                  WHERE padres.id_feligres = ?";
    $stmtPadres = $pdo->prepare($sqlPadres);
    $stmtPadres->execute([$id]);
    $padres = $stmtPadres->fetch(PDO::FETCH_ASSOC);

    // Padrinos
    $sqlPadrinos = "SELECT padrino.nombre AS padrino, madrina.nombre AS madrina
                    FROM padrinos
                    LEFT JOIN feligres padrino ON padrinos.id_padrino = padrino.id_feligres
                    LEFT JOIN feligres madrina ON padrinos.id_madrina = madrina.id_feligres
                    WHERE padrinos.id_feligres = ?";
    $stmtPadrinos = $pdo->prepare($sqlPadrinos);
    $stmtPadrinos->execute([$id]);
    $padrinos = $stmtPadrinos->fetch(PDO::FETCH_ASSOC);

    $datos_extra = "
    <div class='row'>
        <div class='col-md-6'>
            <label class='form-label fw-bold'>Padre:</label>
            <p class='form-control-plaintext'>" . htmlspecialchars($padres['padre'] ?? 'No registrado') . "</p>
        </div>
        <div class='col-md-6'>
            <label class='form-label fw-bold'>Madre:</label>
            <p class='form-control-plaintext'>" . htmlspecialchars($padres['madre'] ?? 'No registrada') . "</p>
        </div>
        <div class='col-md-6'>
            <label class='form-label fw-bold'>Padrino:</label>
            <p class='form-control-plaintext'>" . htmlspecialchars($padrinos['padrino'] ?? 'No registrado') . "</p>
        </div>
        <div class='col-md-6'>
            <label class='form-label fw-bold'>Madrina:</label>
            <p class='form-control-plaintext'>" . htmlspecialchars($padrinos['madrina'] ?? 'No registrada') . "</p>
        </div>
    </div>";
}
?>

<div class="px-2">
    <h5 class="text-primary mb-3">Datos del Feligres</h5>
    <div class="row">
        <div class="col-md-6">
            <label class="form-label fw-bold">Nombre completo:</label>
            <p class="form-control-plaintext"><?= htmlspecialchars($data['nombre'] . ' ' . $data['apellido']) ?></p>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold">Género:</label>
            <p class="form-control-plaintext"><?= htmlspecialchars(ucfirst($data['genero'])) ?></p>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold">Fecha Nacimiento:</label>
            <p class="form-control-plaintext"><?= htmlspecialchars($data['fecha_nacimiento']) ?></p>
        </div>
        <div class="col-md-12">
            <label class="form-label fw-bold">Dirección:</label>
            <p class="form-control-plaintext"><?= htmlspecialchars($data['direccion']) ?></p>
        </div>
    </div>

    <hr>
    <h5 class="text-primary mb-3">Datos del Sacramento</h5>
    <div class="row">
        <div class="col-md-4">
            <label class="form-label fw-bold">Sacramento:</label>
            <p class="form-control-plaintext"><?= htmlspecialchars(ucfirst($data['sacramento'])) ?></p>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">Fecha:</label>
            <p class="form-control-plaintext"><?= htmlspecialchars($data['fecha']) ?></p>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">Lugar:</label>
            <p class="form-control-plaintext"><?= htmlspecialchars($data['lugar']) ?></p>
        </div>
        <div class="col-md-12">
            <label class="form-label fw-bold">Párroco:</label>
            <p class="form-control-plaintext"><?= htmlspecialchars($data['parroco'] ?: 'No registrado') ?></p>
        </div>
    </div>

    <?php if ($sacramento === 'bautismo'): ?>
        <hr>
        <h5 class="text-primary mb-3">Parentesco</h5>
        <?= $datos_extra ?>
    <?php else: ?>
        <hr>
        <h5 class="text-primary mb-3">Detalles adicionales</h5>
        <div class="mb-3">
            <label class="form-label fw-bold">Observaciones:</label>
            <textarea class="form-control" rows="3" placeholder="Ej. Realizó la primera comunión con el grupo de la parroquia San José."></textarea>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label class="form-label fw-bold">Parroquia:</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($data['lugar']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Fecha de emisión:</label>
                <input type="date" class="form-control" value="<?= date('Y-m-d') ?>">
            </div>
        </div>
    <?php endif; ?>
</div>
