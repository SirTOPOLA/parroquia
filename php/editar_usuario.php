<?php
require '../includes/conexion.php';

$id = $_POST['id'] ?? null;
$nombre = trim($_POST['nombre'] ?? '');
$dni = trim($_POST['dni'] ?? '');
$usuario = trim($_POST['usuario'] ?? '');
$contrasena = trim($_POST['contrasena'] ?? '');
$rol = $_POST['rol'] ?? '';
$estado = isset($_POST['estado']) ? (int)$_POST['estado'] : 1;

if (!$id || !$nombre || !$dni || !$usuario || !$rol) {
    header('Location: ../admin/usuarios.php?mensaje=Faltan datos');
    exit;
}

try {
    if (!empty($contrasena)) {
        // Si se proporcionó nueva contraseña
        $hash = password_hash($contrasena, PASSWORD_BCRYPT);
        $sql = "UPDATE usuarios SET nombre = ?, dni = ?, usuario = ?, contrasena = ?, rol = ?, estado = ? WHERE id = ?";
        $params = [$nombre, $dni, $usuario, $hash, $rol, $estado, $id];
    } else {
        // Sin cambiar contraseña
        $sql = "UPDATE usuarios SET nombre = ?, dni = ?, usuario = ?, rol = ?, estado = ? WHERE id = ?";
        $params = [$nombre, $dni, $usuario, $rol, $estado, $id];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    header('Location: ../admin/usuarios.php?mensaje=actualizado');
} catch (PDOException $e) {
    error_log("Error al actualizar usuario: " . $e->getMessage());
    header('Location: ../admin/usuarios.php?mensaje=error');
}
