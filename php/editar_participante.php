<?php
require '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $persona_id = $_POST['persona_id'] ?? null;
    $catequesis_id = $_POST['catequesis_id'] ?? null;
    $fecha_inscripcion = $_POST['fecha_inscripcion'] ?? null;

    if (!$id || !$persona_id || !$catequesis_id || !$fecha_inscripcion) {
        header('Location: ../admin/participante.php?mensaje=error');
        exit;
    }

    // Actualizamos el registro
    $sql = "UPDATE participante_catequesis 
            SET persona_id = ?, catequesis_id = ?, fecha_inscripcion = ?
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$persona_id, $catequesis_id, $fecha_inscripcion, $id]);

    header('Location: ../admin/participante.php?mensaje=editado');
} else {
    header('Location: ../admin/participante.php');
}
