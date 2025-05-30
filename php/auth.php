<?php
session_start();
require_once '../includes/conexion.php';

// Validar entrada
$usuario = trim($_POST['usuario'] ?? '');
$contraseña = $_POST['contraseña'] ?? '';

if (empty($usuario) || empty($contraseña)) { 
    header("Location: ../index.php?=Usuario o contraseña vacíos.");
    exit;
}

// Buscar usuario y verificar que esté activo (estado = 1)
$sql = "SELECT u.id, p.nombres, p.apellidos, u.contrasena, u.rol, u.estado 
        FROM usuarios u
        JOIN persona p ON u.persona_id = p.id
        WHERE u.usuario = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$usuario]);
$usuarioBD = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuarioBD && $usuarioBD['estado'] && password_verify($contraseña, $usuarioBD['contrasena'])) {
    // Inicio de sesión exitoso
    $_SESSION['usuario_id'] = $usuarioBD['id'];
    $_SESSION['nombre'] = $usuarioBD['nombres'] . ' ' . $usuarioBD['apellidos'];
    $_SESSION['rol'] = $usuarioBD['rol'];
    header("Location: ../admin/index.php");
    exit;
} else {
    header("Location: ../index.php?=Credenciales incorrectas o usuario inactivo.");
   
}
