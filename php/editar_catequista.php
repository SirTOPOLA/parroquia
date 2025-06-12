<?php
require_once '../../config/conexion.php';

$id = $_POST['id_catequista'] ?? null;
$nombre = $_POST['nombre'] ?? '';
$apellido = $_POST['apellido'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$correo = $_POST['correo'] ?? '';

if ($id) {
    $sql = "UPDATE catequistas SET nombre = :nombre, apellido = :apellido, telefono = :telefono, correo = :correo
            WHERE id_catequista = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nombre' => $nombre,
        'apellido' => $apellido,
        'telefono' => $telefono,
        'correo' => $correo,
        'id' => $id
    ]);
}
header("Location: ../index.php?vista=catequistas");
exit;
