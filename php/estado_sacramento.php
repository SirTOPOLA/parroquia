<?php
// archivo: php/estado_sacramento.php
require_once '../config/conexion.php';

header('Content-Type: application/json');

// Recibir datos POST con nombres que usa el frontend
$idFeligres = isset($_POST['id_feligres']) ? intval($_POST['id_feligres']) : 0;
$idSacramento = isset($_POST['id_sacramento']) ? intval($_POST['id_sacramento']) : 0;
$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : null;
$lugar = isset($_POST['lugar']) ? trim($_POST['lugar']) : null;
$observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : null;

if (!$idFeligres || !$idSacramento) {
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Obtener estado actual
    $sql = "SELECT estado FROM feligres_sacramento WHERE id_feligres = ? AND id_sacramento = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idFeligres, $idSacramento]);
    $actual = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$actual) {
        echo json_encode(['error' => 'No existe el registro del sacramento']);
        $pdo->rollBack();
        exit;
    }

    // Definir nuevo estado según lógica (aquí se asume que se marca directamente como 'completado' cuando llega la fecha y lugar)
    // Pero según frontend, solo se permite "completado" al confirmar
    $nuevoEstado = 'completado';

    // Validar campos obligatorios para completar
    if (!$fecha || !$lugar) {
        echo json_encode(['error' => 'Fecha y lugar son obligatorios para completar el sacramento']);
        $pdo->rollBack();
        exit;
    }

    // Actualizar registro con datos recibidos y nuevo estado
    $sql = "UPDATE feligres_sacramento 
            SET estado = ?, fecha = ?, lugar = ?, observaciones = ?
            WHERE id_feligres = ? AND id_sacramento = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nuevoEstado, $fecha, $lugar, $observaciones, $idFeligres, $idSacramento]);

    $pdo->commit();

    // Definir color para badge
    $color = match ($nuevoEstado) {
        'pendiente' => 'secondary',
        'en_proceso' => 'warning',
        'completado' => 'success',
        default => 'dark',
    };

    echo json_encode([
        'estado' => $nuevoEstado,
        'color' => $color
    ]);

} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['error' => 'Error al actualizar el estado: ' . $e->getMessage()]);
    exit;
}
