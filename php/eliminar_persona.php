<?php
require '../includes/conexion.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Verificar existencia
    $stmt = $pdo->prepare("SELECT id FROM persona WHERE id = ?");
    $stmt->execute([$id]);
    if ($stmt->rowCount() === 0) {
        die("Persona no encontrada.");
    }

    // Eliminar
    $stmt = $pdo->prepare("DELETE FROM persona WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: ../admin/persona.php?mensaje=eliminado');
    exit;
} else {
    die("ID inválido.");
}
?>
