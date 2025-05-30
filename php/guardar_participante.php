<?php
require '../includes/conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $persona_id = $_POST['persona_id'] ?? null;
    $catequesis_id = $_POST['catequesis_id'] ?? null;
    $fecha_inscripcion = $_POST['fecha_inscripcion'] ?? date('Y-m-d');

    if (!$persona_id || !$catequesis_id) {
        header('Location: ../admin/participante.php?mensaje=error');
        exit;
    }

    $sql = "INSERT INTO participante_catequesis (persona_id, catequesis_id, fecha_inscripcion) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$persona_id, $catequesis_id, $fecha_inscripcion]);

    header('Location: ../admin/participante.php?mensaje=registrado');
}
