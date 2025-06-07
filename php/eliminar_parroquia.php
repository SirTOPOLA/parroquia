<?php
require_once '../includes/conexion.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $sql = "DELETE FROM parroquias WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header('Location: ../admin/parroquias.php?mensaje=eliminado');
        exit;
    }
}

header('Location: ../admin/parroquias.php?mensaje=error');
exit;
