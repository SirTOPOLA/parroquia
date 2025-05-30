<?php
require '../includes/conexion.php';

$persona_id = $_POST['persona_id'] ?? null;
$especialidad = trim($_POST['especialidad'] ?? '');

if (!$persona_id || !$especialidad) {
    header('Location: ../admin/catequista.php?mensaje=Error al guardar');
    exit;
}

$sql = "INSERT INTO catequistas (persona_id, especialidad) VALUES (:persona_id, :especialidad)";
$stmt = $pdo->prepare($sql);
$stmt->execute(['persona_id' => $persona_id, 'especialidad' => $especialidad]);

header('Location: ../admin/catequista.php?mensaje=Registrado');
