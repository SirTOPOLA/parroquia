<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_pariente'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $tipo_pariente = $_POST['tipo_pariente'] ?? '';
    $datos_adicionales = trim($_POST['datos_adicionales'] ?? '');

    if ($id <= 0 || $nombre === '' || !in_array($tipo_pariente, ['padre', 'madre', 'padrino', 'madrina', 'otro'])) {
        header('Location: ../admin/parientes.php?mensaje=error');
        exit;
    }

    if ($datos_adicionales !== '') {
        json_decode($datos_adicionales);
        if (json_last_error() !== JSON_ERROR_NONE) {
            header('Location: ../admin/parientes.php?mensaje=error_json');
            exit;
        }
    } else {
        $datos_adicionales = null;
    }

    $sql = "UPDATE parientes SET 
                nombre = :nombre, 
                apellido = :apellido, 
                telefono = :telefono, 
                tipo_pariente = :tipo_pariente,
                datos_adicionales = :datos_adicionales
            WHERE id_pariente = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nombre' => $nombre,
        'apellido' => $apellido,
        'telefono' => $telefono,
        'tipo_pariente' => $tipo_pariente,
        'datos_adicionales' => $datos_adicionales,
        'id' => $id
    ]);

    header('Location: ../admin/parientes.php?mensaje=editado');
    exit;
}
header('Location: ../admin/parientes.php');
exit;
