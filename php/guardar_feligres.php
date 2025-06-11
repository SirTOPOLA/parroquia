<?php
require_once '../config/conexion.php'; // ajusta según tu ruta

header('Content-Type: application/json');

try {
    // Validar campos obligatorios
    if (!isset($_POST['nombre'], $_POST['apellido'], $_POST['id_parroquia'], $_POST['sacramentos'])) {
        throw new Exception("Faltan campos obligatorios.");
    }

    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $id_parroquia = $_POST['id_parroquia'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
    $genero = $_POST['genero'] ?? null;
    $direccion = $_POST['direccion'] ?? null;
    $telefono = $_POST['telefono'] ?? null;
    $estado_civil = $_POST['estado_civil'] ?? 'soltero';
    $matrimonioJson = $_POST['matrimonio'] ?? null;

    // Insertar feligrés
    $stmt = $conn->prepare("INSERT INTO feligreses (id_parroquia, nombre, apellido, fecha_nacimiento, genero, direccion, telefono, estado_civil, matrimonio) 
                            VALUES (:id_parroquia, :nombre, :apellido, :fecha_nacimiento, :genero, :direccion, :telefono, :estado_civil, :matrimonio)");

    $stmt->bindValue(':id_parroquia', $id_parroquia, PDO::PARAM_INT);
    $stmt->bindValue(':nombre', $nombre);
    $stmt->bindValue(':apellido', $apellido);
    $stmt->bindValue(':fecha_nacimiento', $fecha_nacimiento);
    $stmt->bindValue(':genero', $genero);
    $stmt->bindValue(':direccion', $direccion);
    $stmt->bindValue(':telefono', $telefono);
    $stmt->bindValue(':estado_civil', $estado_civil);
    $stmt->bindValue(':matrimonio', $matrimonioJson);

    $stmt->execute();
    $id_feligres = $conn->lastInsertId();

    // Registrar sacramentos
    $sacramentos = json_decode($_POST['sacramentos'], true);
    foreach ($sacramentos as $sac) {
        $tipoSacramento = $sac['tipo'];

        // Buscar id del sacramento
        $sacramentoStmt = $conn->prepare("SELECT id_sacramento FROM sacramentos WHERE LOWER(nombre) = LOWER(:nombre) LIMIT 1");
        $sacramentoStmt->bindValue(':nombre', $tipoSacramento);
        $sacramentoStmt->execute();
        $sacramentoRow = $sacramentoStmt->fetch(PDO::FETCH_ASSOC);

        if ($sacramentoRow) {
            $id_sacramento = $sacramentoRow['id_sacramento'];
            $insertSac = $conn->prepare("INSERT INTO feligres_sacramento (id_feligres, id_sacramento) VALUES (:id_feligres, :id_sacramento)");
            $insertSac->bindValue(':id_feligres', $id_feligres, PDO::PARAM_INT);
            $insertSac->bindValue(':id_sacramento', $id_sacramento, PDO::PARAM_INT);
            $insertSac->execute();
        }
    }

    // Registrar parientes
    if (!empty($_POST['parientes'])) {
        $parientes = json_decode($_POST['parientes'], true);
        foreach ($parientes as $pariente) {
            $nombreP = $pariente['nombre'];
            $apellidoP = $pariente['apellido'] ?? null;
            $telefonoP = $pariente['telefono'] ?? null;
            $tipo = $pariente['tipo'];

            $insertPar = $conn->prepare("INSERT INTO parientes (nombre, apellido, telefono, tipo_pariente) VALUES (:nombre, :apellido, :telefono, :tipo)");
            $insertPar->bindValue(':nombre', $nombreP);
            $insertPar->bindValue(':apellido', $apellidoP);
            $insertPar->bindValue(':telefono', $telefonoP);
            $insertPar->bindValue(':tipo', $tipo);
            $insertPar->execute();

            $id_pariente = $conn->lastInsertId();

            // Relación feligrés-pariente
            $insertRel = $conn->prepare("INSERT INTO feligres_parientes (id_feligres, id_pariente, tipo_relacion) VALUES (:id_feligres, :id_pariente, :tipo_relacion)");
            $insertRel->bindValue(':id_feligres', $id_feligres, PDO::PARAM_INT);
            $insertRel->bindValue(':id_pariente', $id_pariente, PDO::PARAM_INT);
            $insertRel->bindValue(':tipo_relacion', $tipo);
            $insertRel->execute();
        }
    }

    echo json_encode(['success' => true, 'message' => 'Feligrés registrado correctamente.']);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
