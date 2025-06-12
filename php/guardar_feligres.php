<?php
require_once '../config/conexion.php';

function safeRollback(PDO $pdo)
{
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
}

header('Content-Type: application/json');

try {
    // Iniciar transacción
    $pdo->beginTransaction();

    // Sanitizar entrada
    $id_feligres = $_POST['id_feligres'] ?? null;
    $id_parroquia = $_POST['id_parroquia'] ?? null;
    $nombre = $_POST['nombre'] ?? null;
    $apellido = $_POST['apellido'] ?? null;
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
    $genero = $_POST['genero'] ?? null;
    $direccion = $_POST['direccion'] ?? null;
    $telefono = $_POST['telefono'] ?? null;
    $estado_civil = $_POST['estado_civil'] ?? null;
    $matrimonio = $_POST['matrimonio'] ?? null;
$id_sacramento = isset($_POST['sacramento']) ? intval($_POST['sacramento']) : null;
    // Validaciones obligatorias
    if (!$id_parroquia || !$nombre || !$apellido || !$fecha_nacimiento || !$genero) {
        throw new Exception("Debe completar los campos obligatorios.");
    }



    $valoresGenero = ['M', 'F', 'Otro'];
    if (!in_array($genero, $valoresGenero, true)) {
        throw new Exception("El valor del género es inválido.");
    }

    // Formatear JSON si hay datos de matrimonio
    $matrimonio_json = $matrimonio ? json_encode($matrimonio) : null;

    /** INSERTAR O ACTUALIZAR FELIGRÉS **/
    if ($id_feligres) {
        // Actualización
        $sql = "UPDATE feligreses SET 
                    id_parroquia = ?, nombre = ?, apellido = ?, fecha_nacimiento = ?, 
                    genero = ?, direccion = ?, telefono = ?, estado_civil = ?, matrimonio = ?
                WHERE id_feligres = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $id_parroquia,
            $nombre,
            $apellido,
            $fecha_nacimiento,
            $genero,
            $direccion,
            $telefono,
            $estado_civil,
            $matrimonio_json,
            $id_feligres
        ]);

        // Limpiar relaciones anteriores
        $pdo->prepare("DELETE FROM feligres_parientes WHERE id_feligres = ?")->execute([$id_feligres]);
        $pdo->prepare("DELETE FROM feligres_sacramento WHERE id_feligres = ?")->execute([$id_feligres]);

    } else {
        // Inserción
        $stmt = $pdo->prepare("INSERT INTO feligreses (id_parroquia, nombre, apellido, fecha_nacimiento, genero, direccion, telefono, estado_civil, matrimonio)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $id_parroquia,
            $nombre,
            $apellido,
            $fecha_nacimiento,
            $genero,
            $direccion,
            $telefono,
            $estado_civil,
            $matrimonio_json
        ]);
        $id_feligres = $pdo->lastInsertId();
    }

    /** GUARDAR PARIENTES **/
    $parientesJson = $_POST['parientes'] ?? '[]';

    // Decodificar el JSON recibido
    $parientes = json_decode($parientesJson, true); // true = convierte en array asociativo

    // Validar que sea un array y que no haya errores de decodificación
    if (!is_array($parientes) || json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Los datos de parientes no tienen el formato correcto: " . json_last_error_msg());
    }

    // Procesar cada pariente
    foreach ($parientes as $p) {
        $id_pariente = $p['id_pariente'] ?? null;

        // Insertar pariente si no existe
        if (!$id_pariente) {
            $stmt = $pdo->prepare("INSERT INTO parientes (nombre, apellido, telefono, tipo_pariente, datos_adicionales)
                               VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $p['nombre'] ?? '',
                $p['apellido'] ?? '',
                $p['telefono'] ?? '',
                $p['tipo'] ?? 'padre', // del frontend se envía como 'tipo'
                json_encode($p['datos_adicionales'] ?? [], JSON_UNESCAPED_UNICODE)
            ]);
            $id_pariente = $pdo->lastInsertId();
        }

        // Insertar relación feligrés-pariente
        $stmt = $pdo->prepare("INSERT INTO feligres_parientes (id_feligres, id_pariente, tipo_relacion, id_sacramento)
                           VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $id_feligres,
            $id_pariente,
            $p['tipo'] ?? 'padre', // usar el mismo tipo del frontend
            $id_sacramento 
        ]);
    }

    /** GUARDAR SACRAMENTO (solo uno) **/    
    $fecha_sacramento = $_POST['fecha_sacramento'] ?? null;
    $lugar_sacramento = $_POST['lugar_sacramento'] ?? null;
    $observaciones_sacramento = $_POST['observaciones_sacramento'] ?? null;

    if (!empty($id_sacramento)) {
        $stmt = $pdo->prepare("INSERT INTO feligres_sacramento (id_feligres, id_sacramento, fecha, lugar, observaciones)
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $id_feligres,
            $id_sacramento,
            $fecha_sacramento,
            $lugar_sacramento,
            $observaciones_sacramento
        ]);
    }

    /** Finalizar transacción **/
    $pdo->commit();

    echo json_encode(['status' => true, 'message' => 'Feligres registrado correctamente ID: '. $id_feligres]);

} catch (Exception $e) {
    safeRollback($pdo);
    http_response_code(500);
    echo json_encode(['status' => false, 'message' => $e->getMessage()]);
}
