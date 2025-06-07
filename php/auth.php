<?php
session_start();
require_once '../includes/conexion.php';

// Validar entrada
$usuario = trim($_POST['usuario'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';

if (empty($usuario) || empty($contrasena)) {
    // Redirigir con mensaje codificado
    header("Location: ../index.php?mensaje=" . urlencode("Usuario y contraseña obligatorios"));
    exit;
}

try {
    // Buscar usuario y verificar estado
    $sql = "SELECT id, nombre, usuario, contrasena, rol, estado FROM usuarios WHERE usuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario]);
    $usuarioBD = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuarioBD) {
        header("Location: ../index.php?mensaje=" . urlencode("El usuario no existe"));
        exit;
    }

    if (!$usuarioBD['estado']) {
        header("Location: ../index.php?mensaje=" . urlencode("Usuario inactivo, contacte al administrador"));
        exit;
    }

    if (!password_verify($contrasena, $usuarioBD['contrasena'])) {
        header("Location: ../index.php?mensaje=" . urlencode("Contraseña incorrecta"));
        exit;
    }

    // Autenticación exitosa
    $_SESSION['usuario'] = [
        'id' => $usuarioBD['id'],
        'nombre' => $usuarioBD['nombre'],
        'rol' => $usuarioBD['rol']
    ];
    header("Location: ../admin/index.php");
    exit;

} catch (PDOException $e) {
    error_log("Error de login: " . $e->getMessage());
    header("Location: ../index.php?mensaje=" . urlencode("Error interno, intenta más tarde"));
    exit;
}
