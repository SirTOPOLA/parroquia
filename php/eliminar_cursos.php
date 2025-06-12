<?php
require '../config/conexion.php';
 

$id_curso = $_GET['id'] ?? null;

if ($id_curso) {
    try {
        // Si deseas también eliminar relaciones con catequistas:
        $pdo->prepare("DELETE FROM curso_catequistas WHERE id_curso = :id")->execute(['id' => $id_curso]);

        $pdo->prepare("DELETE FROM cursos WHERE id_curso = :id")->execute(['id' => $id_curso]);

        header("Location: ../index.php?vista=cursos");
        exit;
    } catch (PDOException $e) {
        echo "Error al eliminar curso: " . $e->getMessage();
    }
}
