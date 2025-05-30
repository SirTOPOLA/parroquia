<?php
require '../includes/conexion.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $sql = "DELETE FROM participante_catequesis WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    header('Location: ../admin/participante.php?mensaje=eliminado');
} else {
    header('Location: ../admin/participante.php?mensaje=error');
}
