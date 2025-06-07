<?php
require '../includes/conexion.php';
 
// Sanitización y validación
$nombre = trim($_POST['nombre'] ?? '');
$dni = trim($_POST['dni'] ?? '');
$usuario = trim($_POST['usuario'] ?? '');
$contrasena = trim($_POST['contrasena'] ?? '');
$rol = $_POST['rol'] ?? '';

if (!$nombre || !$dni || !$usuario || !$contrasena || !$rol) {
    header('Location: ../admin/usuarios.php?mensaje=Faltan datos');
    exit;
}

try {
    // Verificar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);

    if ($stmt->fetch()) {
        header('Location: ../admin/usuarios.php?mensaje=Usuario ya existe');
        exit;
    }

    // Hashear la contraseña
    $hash = password_hash($contrasena, PASSWORD_BCRYPT);

    // Insertar usuario
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, dni, usuario, contrasena, rol) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $dni, $usuario, $hash, $rol]);

    header('Location: ../admin/usuarios.php?mensaje=registrado');
} catch (PDOException $e) {
    error_log("Error al registrar usuario: " . $e->getMessage());
    header('Location: ../admin/usuarios.php?mensaje=error');
}
