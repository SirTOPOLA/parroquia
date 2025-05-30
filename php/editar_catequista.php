<?php
require '../includes/conexion.php';

$persona_id = $_POST['persona_id'] ?? null;
$especialidad = trim($_POST['especialidad'] ?? '');

if (!$persona_id || !$especialidad) {
    header('Location: ../admin/catequista.php?mensaje=Error al editar');
    exit;
}

$sql = "UPDATE catequistas SET especialidad = :especialidad WHERE persona_id = :persona_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['especialidad' => $especialidad, 'persona_id' => $persona_id]);

header('Location: ../admin/catequista.php?mensaje=Editado');
