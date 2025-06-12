<?php
require '../config/conexion.php';

$id_feligres = $_POST['id_feligres'];
$id_sacramento = $_POST['id_sacramento'];
$fecha = $_POST['fecha'];
$lugar = $_POST['lugar'];
$observaciones = $_POST['observaciones'];

$stmt = $pdo->prepare("INSERT INTO feligres_sacramento (id_feligres, id_sacramento, fecha, lugar, observaciones)
                       VALUES (?, ?, ?, ?, ?)");
$ok = $stmt->execute([$id_feligres, $id_sacramento, $fecha, $lugar, $observaciones]);

echo json_encode(['success' => $ok]);
