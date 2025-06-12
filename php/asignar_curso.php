<?php
require_once '../config/conexion.php';

$id_catequista = $_POST['id_catequista'] ?? null;
$id_curso = $_POST['id_curso'] ?? null;

if ($id_catequista && $id_curso) {
    $stmt = $pdo->prepare("INSERT INTO curso_catequistas (id_curso, id_catequista) VALUES (?, ?)");
    $stmt->execute([$id_curso, $id_catequista]);
}

header("Location: ../index.php?vista=catequistas");
exit;
