<?php
// Variables usadas: $modo, $nombre, $fecha_inicio, $fecha_fin, $observaciones, $sacramento_id, $catequista_id
// Además existen $sacrs (sacramentos) y $personas (posibles catequistas) en scope
?>

<div class="row g-3">
    <div class="col-md-6">
        <label for="nombre" class="form-label">Nombre de la Catequesis</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre) ?>" required>
    </div>

    <div class="col-md-6">
        <label for="sacramento_id" class="form-label">Sacramento</label>
        <select class="form-select" id="sacramento_id" name="sacramento_id" required>
            <option value="">Seleccione...</option>
            <?php foreach ($sacrs as $s): ?>
                <option value="<?= $s['id'] ?>" <?= $s['id'] == $sacramento_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars(ucfirst($s['nombre'])) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-6">
        <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= $fecha_inicio ?>">
    </div>

    <div class="col-md-6">
        <label for="fecha_fin" class="form-label">Fecha de Fin</label>
        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?= $fecha_fin ?>">
    </div>

    <div class="col-md-6">
        <label for="catequista_id" class="form-label">Catequista</label>
        <select class="form-select" id="catequista_id" name="catequista_id">
            <option value="">Seleccione...</option>
            <?php foreach ($personas as $p): ?>
                <option value="<?= $p['id'] ?>" <?= $p['id'] == $catequista_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['nombres'] . ' ' . $p['apellidos']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-12">
        <label for="observaciones" class="form-label">Observaciones</label>
        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?= htmlspecialchars($observaciones) ?></textarea>
    </div>
</div>
