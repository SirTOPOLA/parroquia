<?php
require_once '../../config/conexion.php';

$id = $_GET['id'] ?? null;

if ($id) {
    // Eliminar relaciones de cursos
    $pdo->prepare("DELETE FROM curso_catequistas WHERE id_catequista = ?")->execute([$id]);

    // Eliminar catequista
    $pdo->prepare("DELETE FROM catequistas WHERE id_catequista = ?")->execute([$id]);
}

header("Location: ../index.php?vista=catequistas");
exit;
