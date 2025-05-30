<?php
require '../includes/conexion.php';

$id = $_POST['id'] ?? null;
$persona_id = $_POST['persona_id'] ?? null;
$usuario = trim($_POST['usuario'] ?? '');
$contrasena = trim($_POST['contrasena'] ?? '');
$rol = $_POST['rol'] ?? null;
$estado = isset($_POST['estado']) ? 1 : 0;

if (!$id || !$persona_id || !$usuario || !$rol) {
    header('Location: ../admin/usuario.php?mensaje=Faltan datos');
    exit;
}

try {
    if (!empty($contrasena)) {
        // Si se envía nueva contraseña
        $hash = password_hash($contrasena, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE usuarios SET persona_id = ?, usuario = ?, contrasena = ?, rol = ?, estado = ? WHERE id = ?");
        $stmt->execute([$persona_id, $usuario, $hash, $rol, $estado, $id]);
    } else {
        // Si no se cambia contraseña
        $stmt = $pdo->prepare("UPDATE usuarios SET persona_id = ?, usuario = ?, rol = ?, estado = ? WHERE id = ?");
        $stmt->execute([$persona_id, $usuario, $rol, $estado, $id]);
    }

    header('Location: ../admin/usuario.php?mensaje=editado');
} catch (PDOException $e) {
    error_log("Error al editar usuario: " . $e->getMessage());
    header('Location: ../admin/usuario.php?mensaje=Error');
}
