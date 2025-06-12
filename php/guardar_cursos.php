<?php
require '../config/conexion.php';

 

$nombre = $_POST['nombre'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$fecha_inicio = $_POST['fecha_inicio'] ?? null;
$fecha_fin = $_POST['fecha_fin'] ?? null;
$id_catequesis = $_POST['id_catequesis'] ?? null;

try {
    $sql = "INSERT INTO cursos (nombre, descripcion, fecha_inicio, fecha_fin, id_catequesis)
            VALUES (:nombre, :descripcion, :fecha_inicio, :fecha_fin, :id_catequesis)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nombre' => $nombre,
        'descripcion' => $descripcion,
        'fecha_inicio' => $fecha_inicio,
        'fecha_fin' => $fecha_fin,
        'id_catequesis' => $id_catequesis
    ]);

    header("Location: ../index.php?vista=cursos");
    exit;
} catch (PDOException $e) {
    echo "Error al guardar curso: " . $e->getMessage();
}
