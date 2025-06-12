<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config/conexion.php';

header('Content-Type: application/json');

$id_feligres = $_POST['id_feligres'] ?? null;
$id_curso = $_POST['id_curso'] ?? null;

if (!$id_feligres || !$id_curso) {
    echo json_encode(['error' => 'Faltan parámetros id_feligres o id_curso']);
    exit;
}

$estados = ['pendiente', 'en_proceso', 'completado'];

$stmt = $pdo->prepare("SELECT estado FROM curso_feligres WHERE id_feligres = ? AND id_curso = ?");
$stmt->execute([$id_feligres, $id_curso]);
$estado_actual = $stmt->fetchColumn();

if (!$estado_actual) {
    echo json_encode(['error' => "No se encontró curso_feligres con id_feligres=$id_feligres y id_curso=$id_curso"]);
    exit;
}

if (!in_array($estado_actual, $estados)) {
    echo json_encode(['error' => "Estado inválido encontrado: $estado_actual"]);
    exit;
}

$nuevo_estado = $estados[(array_search($estado_actual, $estados) + 1) % count($estados)];

$stmt = $pdo->prepare("UPDATE curso_feligres SET estado = ? WHERE id_feligres = ? AND id_curso = ?");
$actualizado = $stmt->execute([$nuevo_estado, $id_feligres, $id_curso]);

if ($actualizado) {
    echo json_encode(['estado' => $nuevo_estado, 'color' => estadoColor($nuevo_estado)]);
} else {
    echo json_encode(['error' => 'No se pudo actualizar el estado']);
}

function estadoColor($estado) {
    switch ($estado) {
        case 'pendiente':
            return 'secondary';
        case 'en_proceso':
            return 'warning';
        case 'completado':
            return 'success';
        default:
            return 'dark';
    }
}
?>
