<?php
header('Content-Type: application/json');

require '../config/conexion.php'; // Archivo donde tengas tu clase Conexion o conexión PDO

try {
 

    $stmt = $pdo->prepare("SELECT id_catequesis, nombre FROM catequesis ORDER BY nombre");
    $stmt->execute();

    $catequesis = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => true,
        'catequesis' => $catequesis
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => false,
        'message' => 'Error al obtener catequesis: ' . $e->getMessage()
    ]);
}
