<?php
include '../includes/conexion.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $sql = "DELETE FROM parientes WHERE id_pariente = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
}

header('Location: ../admin/parientes.php?mensaje=eliminado');
exit;
