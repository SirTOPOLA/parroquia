<?php
require '../config/conexion.php';
// Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Método no permitido.'];
    header('Location: ../index.php?vista=login');
    exit;
}

// Sanitización y validación
$nombre = trim($_POST['nombre'] ?? '');
$dni = trim($_POST['dni'] ?? '');
$usuario = trim($_POST['usuario'] ?? '');
$contrasena = trim($_POST['contrasena'] ?? '');
$rol = trim($_POST['rol']) ?? '';

if (!$nombre || !$dni || !$usuario || !$contrasena || !$rol) {
    $_SESSION['alerta'] = [
        'tipo' => 'warning',
        'mensaje' => 'Todos los campo son abligatorios.'
    ];
    header('Location: ../index.php?vista=usuarios');
    exit;
}

try {
    // Verificar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);

    if ($stmt->fetch()) {
        $_SESSION['alerta'] = [
            'tipo' => 'warning',
            'mensaje' => 'Ya existe este usuario.'
        ];
        header('Location: ../index.php?vista=usuarios');

        exit;
    }

    // Hashear la contraseña
    $hash = password_hash($contrasena, PASSWORD_BCRYPT);

    // Insertar usuario
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, dni, usuario, contrasena, rol) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $dni, $usuario, $hash, $rol]);
    $_SESSION['alerta'] = [
        'tipo' => 'success',
        'mensaje' => 'Un nuevo usuario fue registrado.'
    ];
    header('Location: ../index.php?vista=usuarios');
} catch (PDOException $e) {
    error_log("Error al registrar usuario: " . $e->getMessage());
    $_SESSION['alerta'] = [
        'tipo' => 'danger',
        'mensaje' => 'Hubo un error.' . $e->getMessage()
    ];
    header('Location: ../index.php?vista=usuarios');
}
