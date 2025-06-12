<?php
include '../config/conexion.php'; 

$id_feligres = $_POST['id_feligres'];
$id_curso = $_POST['id_curso'];

$estados = ['pendiente', 'en_proceso', 'completado'];
$stmt = $pdo->prepare("SELECT estado FROM curso_feligres WHERE id_feligres = ? AND id_curso = ?");
$stmt->execute([$id_feligres, $id_curso]);
$estado_actual = $stmt->fetchColumn();

$nuevo_estado = $estados[(array_search($estado_actual, $estados) + 1) % 3];

$stmt = $pdo->prepare("UPDATE curso_feligres SET estado = ? WHERE id_feligres = ? AND id_curso = ?");
$stmt->execute([$nuevo_estado, $id_feligres, $id_curso]);

echo json_encode(['estado' => $nuevo_estado, 'color' => estadoColor($nuevo_estado)]);

function estadoColor($estado) {
  return match($estado) {
    'pendiente' => 'secondary',
    'en_proceso' => 'warning',
    'completado' => 'success',
    default => 'dark',
  };
}
?>
