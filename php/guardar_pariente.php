<?php
include '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $tipo_pariente = $_POST['tipo_pariente'] ?? '';
    $datos_adicionales = trim($_POST['datos_adicionales'] ?? '');

    // Validaciones básicas
    if ($nombre === '' || !in_array($tipo_pariente, ['padre', 'madre', 'padrino', 'madrina', 'otro'])) {
        header('Location: ../admin/parientes.php?mensaje=error');
        exit;
    }

    // Validar JSON si no está vacío
    if ($datos_adicionales !== '') {
        json_decode($datos_adicionales);
        if (json_last_error() !== JSON_ERROR_NONE) {
            header('Location: ../admin/parientes.php?mensaje=error_json');
            exit;
        }
    } else {
        $datos_adicionales = null;
    }

    $sql = "INSERT INTO parientes (nombre, apellido, telefono, tipo_pariente, datos_adicionales) 
            VALUES (:nombre, :apellido, :telefono, :tipo_pariente, :datos_adicionales)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nombre' => $nombre,
        'apellido' => $apellido,
        'telefono' => $telefono,
        'tipo_pariente' => $tipo_pariente,
        'datos_adicionales' => $datos_adicionales
    ]);

    header('Location: ../admin/parientes.php?mensaje=registrado');
    exit;
}
header('Location: ../admin/parientes.php');
exit;
