<?php
require_once '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_parroquia'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');

    if ($id > 0 && $nombre !== '') {
        $sql = "UPDATE parroquias SET nombre = :nombre, direccion = :direccion, telefono = :telefono WHERE id_parroquia = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header('Location: ../admin/parroquias.php?mensaje=actualizado');
            exit;
        }
    }
}

header('Location: ../admin/parroquias.php?mensaje=error');
exit;
