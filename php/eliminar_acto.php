<?php
require_once '../includes/conexion.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) die("ID inválido");

try {
    $stmt = $pdo->prepare("DELETE FROM acto_sacramental WHERE id=?");
    $stmt->execute([$id]);
    header("Location: ../admin/acto.php?msg=eliminado");
    exit;
} catch (Exception $e) {
    die("Error al eliminar: " . $e->getMessage());
}
