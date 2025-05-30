<?php
require '../includes/conexion.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: ../admin/usuario.php?mensaje=ID inválido');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: ../vistas/usuarios.php?mensaje=eliminado');
} catch (PDOException $e) {
    error_log("Error al eliminar usuario: " . $e->getMessage());
    header('Location: ../admin/usuario.php?mensaje=Error');
}
