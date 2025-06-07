<?php
require '../includes/conexion.php'; // Archivo donde tienes la conexión PDO en $pdo

// Recoger y sanitizar datos
$id_parroquia = $_POST['id_parroquia'] ?? null;
$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
$genero = $_POST['genero'] ?? null;
$direccion = trim($_POST['direccion'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$estado_civil = $_POST['estado_civil'] ?? null;

// Validaciones básicas
if (!$id_parroquia || !$nombre || !$apellido) {
    header('Location: ../admin/feligreses.php?mensaje=error');
    exit;
}

$sql = "INSERT INTO feligreses (id_parroquia, nombre, apellido, fecha_nacimiento, genero, direccion, telefono, estado_civil) 
        VALUES (:id_parroquia, :nombre, :apellido, :fecha_nacimiento, :genero, :direccion, :telefono, :estado_civil)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id_parroquia' => $id_parroquia,
    ':nombre' => $nombre,
    ':apellido' => $apellido,
    ':fecha_nacimiento' => $fecha_nacimiento ?: null,
    ':genero' => $genero ?: null,
    ':direccion' => $direccion,
    ':telefono' => $telefono ?: null,
    ':estado_civil' => $estado_civil ?: null,
]);

header('Location: ../admin/feligreses.php?mensaje=registrado');
exit;
