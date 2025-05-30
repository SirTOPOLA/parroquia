<?php
include '../includes/conexion.php'; // tu archivo de conexión PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $direccion = trim($_POST['direccion']);
    $genero = $_POST['genero'] ?? null;

    // Validación básica
    if (empty($nombres) || empty($apellidos)) {
        echo "Los campos nombres y apellidos son obligatorios.";
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO persona (nombres, apellidos, fecha_nacimiento, direccion, telefono, correo, genero)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombres, $apellidos, $fecha_nacimiento, $direccion, $telefono, $correo, $genero]);

        header('Location: ../admin/persona.php?mensaje=registrado');
    } catch (PDOException $e) {
        echo "Error al guardar: " . $e->getMessage();
    }
}
