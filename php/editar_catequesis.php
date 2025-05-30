<?php
require_once '../includes/conexion.php';

$id = (int) ($_POST['id'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$sacramento_id = (int) ($_POST['sacramento_id'] ?? 0);
$fecha_inicio = $_POST['fecha_inicio'] ?? null;
$fecha_fin = $_POST['fecha_fin'] ?? null;
$catequista_id = $_POST['catequista_id'] ?? null;
$observaciones = trim($_POST['observaciones'] ?? '');

if ($id <= 0) {
    die("ID inválido");
}

$errores = [];
if ($nombre === '') $errores[] = "El nombre es obligatorio.";
if ($sacramento_id <= 0) $errores[] = "Debe seleccionar un sacramento.";

if (count($errores) > 0) {
    die("Errores: " . implode(', ', $errores));
}

try {
    $sql = "UPDATE catequesis SET nombre=:nombre, sacramento_id=:sacramento_id, fecha_inicio=:fecha_inicio,
            fecha_fin=:fecha_fin, catequista_id=:catequista_id, observaciones=:observaciones WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':sacramento_id' => $sacramento_id,
        ':fecha_inicio' => $fecha_inicio ?: null,
        ':fecha_fin' => $fecha_fin ?: null,
        ':catequista_id' => $catequista_id ?: null,
        ':observaciones' => $observaciones,
        ':id' => $id,
    ]);

    header('Location: ../admin/catequesis.php?mensaje=editado');
    exit;
} catch (PDOException $e) {
    die("Error al editar catequesis: " . $e->getMessage());
}
