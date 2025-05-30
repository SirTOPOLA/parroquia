<?php
require '../includes/conexion.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: ../admin/catequista.php?mensaje=Error al eliminar');
    exit;
}

$stmt = $pdo->prepare("DELETE FROM catequistas WHERE persona_id = :id");
$stmt->execute(['id' => $id]);

header('Location: ../admin/catequista.php?mensaje=Eliminado');
