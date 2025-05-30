<?php
// Se usa dentro de personas.php en modales
$modo = $_SERVER['PHP_SELF'] === '../php/guardar_persona.php' ? 'registrar' : 'editar';

$nombre = $p['nombres'] ?? '';
$apellido = $p['apellidos'] ?? '';
$fecha = $p['fecha_nacimiento'] ?? '';
$direccion = $p['direccion'] ?? '';
$telefono = $p['telefono'] ?? '';
$correo = $p['correo'] ?? '';
$genero = $p['genero'] ?? '';
?>

<div class="col-md-6">
    <label class="form-label">Nombres</label>
    <input type="text" class="form-control" name="nombres" value="<?= $nombre ?>" required>
</div>
<div class="col-md-6">
    <label class="form-label">Apellidos</label>
    <input type="text" class="form-control" name="apellidos" value="<?= $apellido ?>" required>
</div>
<div class="col-md-4">
    <label class="form-label">Fecha de Nacimiento</label>
    <input type="date" class="form-control" name="fecha_nacimiento" value="<?= $fecha ?>">
</div>
<div class="col-md-4">
    <label class="form-label">Teléfono</label>
    <input type="text" class="form-control" name="telefono" value="<?= $telefono ?>">
</div>
<div class="col-md-4">
    <label class="form-label">Correo</label>
    <input type="email" class="form-control" name="correo" value="<?= $correo ?>">
</div>
<div class="col-md-8">
    <label class="form-label">Dirección</label>
    <input type="text" class="form-control" name="direccion" value="<?= $direccion ?>">
</div>
<div class="col-md-4">
    <label class="form-label">Género</label>
    <select class="form-select" name="genero">
        <option value="">Seleccione...</option>
        <option value="masculino" <?= $genero === 'masculino' ? 'selected' : '' ?>>Masculino</option>
        <option value="femenino" <?= $genero === 'femenino' ? 'selected' : '' ?>>Femenino</option>
        <option value="otro" <?= $genero === 'otro' ? 'selected' : '' ?>>Otro</option>
    </select>
</div>
