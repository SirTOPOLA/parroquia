<?php
include '../includes/conexion.php'; // Aquí va la conexión $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');

    if ($nombre === '') {
        header('Location: ../admin/sacramentos.php?mensaje=error_nombre');
        exit;
    }

    $sql = "INSERT INTO sacramentos (nombre) VALUES (:nombre)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['nombre' => $nombre]);

    header('Location: ../admin/sacramentos.php?mensaje=registrado');
    exit;
}
header('Location: ../admin/sacramentos.php');
exit;
