<?php 


// archivo: obtener_dni_parroquial.php

require '../config/conexion.php';
$idFeligres = $_GET['id'] ?? null;

if (!$idFeligres) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de feligrés no proporcionado']);
    exit;
}

// Buscar datos del feligrés
$sqlFeligres = "SELECT f.*, p.nombre AS parroquia
                FROM feligreses f
                LEFT JOIN parroquias p ON f.id_parroquia = p.id_parroquia
                WHERE f.id_feligres = ?";
$stmt = $pdo->prepare($sqlFeligres);
$stmt->execute([$idFeligres]);
$feligres = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$feligres) {
    echo json_encode(['error' => 'Feligres no encontrado']);
    exit;
}

// Obtener sacramentos pendientes o en progreso
$sqlSacramentos = "SELECT fs.*, s.nombre 
                   FROM feligres_sacramento fs
                   INNER JOIN sacramentos s ON fs.id_sacramento = s.id_sacramento
                   WHERE fs.id_feligres = ? AND fs.estado IN ('pendiente','en_proceso')";
$stmt = $pdo->prepare($sqlSacramentos);
$stmt->execute([$idFeligres]);
$sacramentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Si no hay sacramentos en curso
if (empty($sacramentos)) {
    echo json_encode(['error' => 'No tiene sacramentos en curso']);
    exit;
}

// Obtener teléfono del feligrés o de sus padres si está vacío
$telefono = $feligres['telefono'];

if (!$telefono) {
    $stmt = $pdo->prepare("SELECT p.telefono FROM feligres_parientes fp
                           INNER JOIN parientes p ON fp.id_pariente = p.id_pariente
                           WHERE fp.id_feligres = ? AND fp.tipo_relacion IN ('padre','madre') LIMIT 1");
    $stmt->execute([$idFeligres]);
    $pariente = $stmt->fetch(PDO::FETCH_ASSOC);
    $telefono = $pariente['telefono'] ?? 'No registrado';
}

// Retornar toda la información como JSON
echo json_encode([
    'feligres' => $feligres,
    'telefono_final' => $telefono,
    'sacramentos' => $sacramentos
]);
