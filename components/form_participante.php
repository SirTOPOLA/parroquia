<?php
// $persona_id, $catequesis_id, $fecha_inscripcion deben estar definidos antes de incluir este archivo
?>

<div class="mb-3">
    <label for="persona_id" class="form-label">Persona</label>
    <select name="persona_id" id="persona_id" class="form-select" required>
        <option value="">Seleccione una persona</option>
        <?php foreach ($personas as $persona): ?>
            <option value="<?= $persona['id'] ?>" <?= $persona['id'] == $persona_id ? 'selected' : '' ?>>
                <?= htmlspecialchars($persona['nombres'] . ' ' . $persona['apellidos']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mb-3">
    <label for="catequesis_id" class="form-label">Catequesis</label>
    <select name="catequesis_id" id="catequesis_id" class="form-select" required>
        <option value="">Seleccione una catequesis</option>
        <?php foreach ($catequesis as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $catequesis_id ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mb-3">
    <label for="fecha_inscripcion" class="form-label">Fecha de Inscripción</label>
    <input type="date" name="fecha_inscripcion" id="fecha_inscripcion" class="form-control" value="<?= $fecha_inscripcion ?>" required>
</div>
