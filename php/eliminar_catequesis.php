<?php
require_once '../includes/conexion.php';

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    die("ID inválido");
}

try {
    $sql = "DELETE FROM catequesis WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    header('Location: ../admin/catequesis.php?mensaje=eliminado');
    exit;
} catch (PDOException $e) {
    die("Error al eliminar catequesis: " . $e->getMessage());
}
