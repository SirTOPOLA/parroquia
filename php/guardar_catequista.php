<?php
require_once '../config/conexion.php';

$nombre = $_POST['nombre'] ?? '';
$apellido = $_POST['apellido'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$correo = $_POST['correo'] ?? '';
$id_curso = $_POST['id_curso'] ?? '';

$sql = "INSERT INTO catequistas (nombre, apellido, telefono, correo)
        VALUES (:nombre, :apellido, :telefono, :correo)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'nombre' => $nombre,
    'apellido' => $apellido,
    'telefono' => $telefono,
    'correo' => $correo,
]);

$id_nuevo = $pdo->lastInsertId();

if ($id_curso) {
    $stmt = $pdo->prepare("INSERT INTO curso_catequistas (id_curso, id_catequista) VALUES (?, ?)");
    $stmt->execute([$id_curso, $id_nuevo]);
}

header("Location: ../index.php?vista=catequistas");
exit;
