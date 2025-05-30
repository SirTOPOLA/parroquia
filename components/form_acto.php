<?php
// Variables esperadas para el formulario:
// $modo: 'registrar' o 'editar'
// $persona_id, $sacramento_id, $parroquia_id, $parroco_id, $fecha, $libro, $folio, $partida, $observaciones, $certificado_emitido

// Cargar listas para selects
$personas = $pdo->query("SELECT id, nombres, apellidos FROM persona ORDER BY apellidos, nombres")->fetchAll();
$sacramentos = $pdo->query("SELECT id, nombre FROM sacramento ORDER BY nombre")->fetchAll();
$parrocos = $pdo->query("
    SELECT u.id, CONCAT(p.nombres, ' ', p.apellidos) AS nombre
    FROM usuarios u
    INNER JOIN persona p ON u.persona_id = p.id
    WHERE u.rol = 'parroco'
    ORDER BY p.nombres
")->fetchAll();

?>

<div class="row ">
    <div class="col-md-6">
        <label class="form-label">Persona</label>
        <select name="persona_id" class="form-select" required>
            <option value="">Seleccione persona...</option>
            <?php foreach ($personas as $p): ?>
                <option value="<?= $p['id'] ?>" <?= ($p['id'] == ($persona_id ?? '')) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['apellidos'] . ', ' . $p['nombres']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Sacramento</label>
        <select name="sacramento_id" class="form-select" required>
            <option value="">Seleccione sacramento...</option>
            <?php foreach ($sacramentos as $s): ?>
                <option value="<?= $s['id'] ?>" <?= ($s['id'] == ($sacramento_id ?? '')) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!--   -->


    <div class="col-md-6">
        <label class="form-label">Párroco</label>
        <select name="parroco_id" class="form-select">
            <option value="">Seleccione párroco...</option>
            <?php foreach ($parrocos as $pr): ?>
                <option value="<?= $pr['id'] ?>" <?= ($pr['id'] == ($pr['id'] ?? '')) ? 'selected' : '' ?>>
                    <?= htmlspecialchars( $pr['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Fecha</label>
        <input type="date" name="fecha" class="form-control" value="<?= htmlspecialchars($fecha ?? '') ?>" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Libro</label>
        <input type="text" name="libro" class="form-control" value="<?= htmlspecialchars($libro ?? '') ?>">
    </div>

    <div class="col-md-4">
        <label class="form-label">Folio</label>
        <input type="text" name="folio" class="form-control" value="<?= htmlspecialchars($folio ?? '') ?>">
    </div>

    <div class="col-md-6">
        <label class="form-label">Partida</label>
        <input type="text" name="partida" class="form-control" value="<?= htmlspecialchars($partida ?? '') ?>">
    </div>

    <div class="col-md-6">
        <label class="form-label">Certificado Emitido</label>
        <select name="certificado_emitido" class="form-select" required>
            <option value="0" <?= (empty($certificado_emitido) || $certificado_emitido == 0) ? 'selected' : '' ?>>No
            </option>
            <option value="1" <?= (!empty($certificado_emitido) && $certificado_emitido == 1) ? 'selected' : '' ?>>Sí
            </option>
        </select>
    </div>

    <div class="col-12">
        <label class="form-label">Observaciones</label>
        <textarea name="observaciones" class="form-control"
            rows="3"><?= htmlspecialchars($observaciones ?? '') ?></textarea>
    </div>
</div>