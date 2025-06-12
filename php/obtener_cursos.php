<?php
header('Content-Type: application/json');

require '../config/conexion.php';

if (!isset($_GET['id_catequesis']) || !is_numeric($_GET['id_catequesis'])) {
    echo json_encode(['status' => false, 'message' => 'ID de catequesis inválido']);
    exit;
}

$idCatequesis = (int)$_GET['id_catequesis'];

try {
   

    $stmt = $pdo->prepare("SELECT id_curso, nombre, fecha_inicio, fecha_fin FROM cursos WHERE id_catequesis = :id_catequesis ORDER BY fecha_inicio");
    $stmt->bindValue(':id_catequesis', $idCatequesis, PDO::PARAM_INT);
    $stmt->execute();

    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => true,
        'cursos' => $cursos
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => false,
        'message' => 'Error al obtener cursos: ' . $e->getMessage()
    ]);
}
