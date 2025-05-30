<?php
require_once '../includes/conexion.php'; // $pdo conexión PDO

// Sanear y validar
$nombre = trim($_POST['nombre'] ?? '');
$sacramento_id = (int) ($_POST['sacramento_id'] ?? 0);
$fecha_inicio = $_POST['fecha_inicio'] ?? null;
$fecha_fin = $_POST['fecha_fin'] ?? null;
$catequista_id = $_POST['catequista_id'] ?? null;
$observaciones = trim($_POST['observaciones'] ?? '');

// Validaciones básicas
$errores = [];
if ($nombre === '') $errores[] = "El nombre es obligatorio.";
if ($sacramento_id <= 0) $errores[] = "Debe seleccionar un sacramento.";

// Opcional validar fechas y catequista si se requieren

if (count($errores) > 0) {
    // Aquí puedes redirigir con error o mostrar mensaje
    die("Errores: " . implode(', ', $errores));
}

try {
    $sql = "INSERT INTO catequesis (nombre, sacramento_id, fecha_inicio, fecha_fin, catequista_id, observaciones)
            VALUES (:nombre, :sacramento_id, :fecha_inicio, :fecha_fin, :catequista_id, :observaciones)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':sacramento_id' => $sacramento_id,
        ':fecha_inicio' => $fecha_inicio ?: null,
        ':fecha_fin' => $fecha_fin ?: null,
        ':catequista_id' => $catequista_id ?: null,
        ':observaciones' => $observaciones,
    ]);

    header('Location: ../admin/catequesis.php?mensaje=registrado');
    exit;
} catch (PDOException $e) {
    die("Error al guardar catequesis: " . $e->getMessage());
}
