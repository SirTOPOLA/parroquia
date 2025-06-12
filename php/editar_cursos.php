<?php
require '../config/conexion.php';


$id_curso = $_POST['id_curso'] ?? null;
$nombre = $_POST['nombre'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$fecha_inicio = $_POST['fecha_inicio'] ?? null;
$fecha_fin = $_POST['fecha_fin'] ?? null;
$id_catequesis = $_POST['id_catequesis'] ?? null;

if ($id_curso) {
    try {
        $sql = "UPDATE cursos SET 
                    nombre = :nombre,
                    descripcion = :descripcion,
                    fecha_inicio = :fecha_inicio,
                    fecha_fin = :fecha_fin,
                    id_catequesis = :id_catequesis
                WHERE id_curso = :id_curso";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'id_catequesis' => $id_catequesis,
            'id_curso' => $id_curso
        ]);

   header("Location: ../index.php?vista=cursos");
        exit;
    } catch (PDOException $e) {
        echo "Error al editar curso: " . $e->getMessage();
    }
}
