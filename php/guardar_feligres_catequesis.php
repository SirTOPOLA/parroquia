<?php
header('Content-Type: application/json');

require '../config/conexion.php';

// Validar datos POST
$idFeligres = $_POST['id_feligres'] ?? null;
$idCatequesis = $_POST['id_catequesis'] ?? null;
$idCurso = $_POST['id_curso'] ?? null;

if (!$idFeligres || !is_numeric($idFeligres)) {
    echo json_encode(['status' => false, 'message' => 'ID de feligrés inválido']);
    exit;
}
if (!$idCatequesis || !is_numeric($idCatequesis)) {
    echo json_encode(['status' => false, 'message' => 'ID de catequesis inválido']);
    exit;
}
// id_curso puede ser opcional o nulo
if ($idCurso !== null && $idCurso !== '' && !is_numeric($idCurso)) {
    echo json_encode(['status' => false, 'message' => 'ID de curso inválido']);
    exit;
}

try {
    
    // Verificar si ya está inscrito para evitar duplicados
    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM feligres_catequesis WHERE id_feligres = :id_feligres AND id_catequesis = :id_catequesis");
    $stmtCheck->execute([
        ':id_feligres' => $idFeligres,
        ':id_catequesis' => $idCatequesis,
    ]);
    $exists = $stmtCheck->fetchColumn();

    if ($exists) {
        echo json_encode(['status' => false, 'message' => 'El feligrés ya está inscrito en esta catequesis']);
        exit;
    }

    // Insertar inscripción
    $stmt = $pdo->prepare("INSERT INTO feligres_catequesis (id_feligres, id_catequesis, fecha_inscripcion) VALUES (:id_feligres, :id_catequesis, CURDATE())");
    $stmt->execute([
        ':id_feligres' => $idFeligres,
        ':id_catequesis' => $idCatequesis,
    ]);

    // Si necesitas relacionar curso también, deberías tener otra tabla que relacione feligres_curso,
    // o bien agregar un campo curso_id en feligres_catequesis (en este ejemplo no está definido).
    // Aquí puedes agregar la lógica si tienes esa tabla.

    echo json_encode(['status' => true, 'message' => 'Asignación guardada correctamente']);
} catch (Exception $e) {
    echo json_encode(['status' => false, 'message' => 'Error al guardar la asignación: ' . $e->getMessage()]);
}
