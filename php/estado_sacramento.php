<?php
include '../config/conexion.php'; 

$id_feligres = $_POST['id_feligres'];
$id_sacramento = $_POST['id_sacramento'];

$estados = ['pendiente', 'en_proceso', 'completado'];
$stmt = $pdo->prepare("SELECT estado FROM feligres_sacramento WHERE id_feligres = ? AND id_sacramento = ?");
$stmt->execute([$id_feligres, $id_sacramento]);
$estado_actual = $stmt->fetchColumn();

$nuevo_estado = $estados[(array_search($estado_actual, $estados) + 1) % 3];

$stmt = $pdo->prepare("UPDATE feligres_sacramento SET estado = ? WHERE id_feligres = ? AND id_sacramento = ?");
$stmt->execute([$nuevo_estado, $id_feligres, $id_sacramento]);

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
