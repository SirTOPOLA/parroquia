<?php
$personaId = $datosUsuario['persona_id'] ?? '';
$usuario = $datosUsuario['usuario'] ?? '';
$rol = $datosUsuario['rol'] ?? '';
$estado = $datosUsuario['estado'] ?? 1;
$contrasena = ''; // Solo para el formulario, no mostrar el hash
// Obtener personas para el select
$personasStmt = $pdo->query("
    SELECT p.id, CONCAT(p.nombres, ' ', p.apellidos) AS nombre_completo
    FROM persona p
    LEFT JOIN participante_catequesis pc ON p.id = pc.persona_id
    WHERE pc.persona_id IS NULL
    ORDER BY p.nombres
");
$personasLista = $personasStmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="col-md-6">
    <label for="persona_id" class="form-label">Persona</label>
    <select name="persona_id" id="persona_id" class="form-select" required>
        <option value="">Seleccione una persona</option>
        <?php foreach ($personasLista as $p): ?>
            <option value="<?= $p['id'] ?>" <?= ($p['id'] == $personaId) ? 'selected' : '' ?>>
                <?= $p['nombre_completo'] ?>
            </option>
        <?php endforeach ?>
    </select>
</div>

<div class="col-md-6">
    <label for="usuario" class="form-label">Usuario</label>
    <input type="text" class="form-control" name="usuario" id="usuario" value="<?= htmlspecialchars($usuario) ?>"
        required>
</div>

<div class="col-md-6">
    <label for="contrasena" class="form-label">Contraseña
        <?= ($modo === 'editar') ? '(dejar en blanco si no se cambia)' : '' ?></label>
    <input type="password" class="form-control" name="contrasena" id="contrasena" <?= $modo === 'registrar' ? 'required' : '' ?>>
</div>

<div class="col-md-6">
    <label for="rol" class="form-label">Rol</label>
    <select name="rol" id="rol" class="form-select" required>
        <option value="">Seleccione un rol</option>
        <?php
        $roles = ['admin', 'secretario', 'archivista', 'parroco'];
        foreach ($roles as $r):
            ?>
            <option value="<?= $r ?>" <?= ($rol == $r) ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
        <?php endforeach ?>
    </select>
</div>

<div class="col-md-6">
    <label for="estado" class="form-label">Estado</label>
    <select name="estado" id="estado" class="form-select" required>
        <option value="1" <?= $estado ? 'selected' : '' ?>>Activo</option>
        <option value="0" <?= !$estado ? 'selected' : '' ?>>Inactivo</option>
    </select>
</div>