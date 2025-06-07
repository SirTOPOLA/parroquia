<?php
require_once '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');

    if ($nombre !== '') {
        $sql = "INSERT INTO parroquias (nombre, direccion, telefono) VALUES (:nombre, :direccion, :telefono)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':telefono', $telefono);

        if ($stmt->execute()) {
            header('Location: ../admin/parroquias.php?mensaje=registrado');
            exit;
        }
    }
}

header('Location: ../admin/parroquias.php?mensaje=error');
exit;
