<?php
require '../includes/conexion.php';

// Sanitización y validación
$persona_id = $_POST['persona_id'] ?? null;
$usuario = trim($_POST['usuario'] ?? '');
$contrasena = trim($_POST['contrasena'] ?? '');
$rol = $_POST['rol'] ?? null;

if (!$persona_id || !$usuario || !$contrasena || !$rol) {
    header('Location: ../admin/usuario.php?mensaje=Faltan datos');
    exit;
}

try {
    // Verificar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);

    if ($stmt->fetch()) {
        header('Location: ../admin/usuario.php?mensaje=Usuario ya existe');
        exit;
    }

    // Hashear la contraseña
    $hash = password_hash($contrasena, PASSWORD_BCRYPT);

    // Insertar usuario
    $stmt = $pdo->prepare("INSERT INTO usuarios (persona_id, usuario, contrasena, rol) VALUES (?, ?, ?, ?)");
    $stmt->execute([$persona_id, $usuario, $hash, $rol]);

    header('Location: ../admin/usuario.php?mensaje=registrado');
} catch (PDOException $e) {
    error_log("Error al registrar usuario: " . $e->getMessage());
    header('Location: ../admin/usuario.php?mensaje=Error');
}
