<?php
require_once '../config/conexion.php';
header('Content-Type: application/json');

try {
    // Validar método
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método no permitido", 405);
    }

    // Validar campos requeridos
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $accion = $_POST['accion_post'] ?? 'nada';

    if (empty($nombre)) {
        throw new Exception("El campo 'nombre' es obligatorio.");
    }

    // Iniciar transacción
    $pdo->beginTransaction();

    // Insertar catequesis
    $stmt = $pdo->prepare("INSERT INTO catequesis (nombre, descripcion) VALUES (?, ?)");
    $stmt->execute([$nombre, $descripcion]);
    $id_catequesis = $pdo->lastInsertId();

    // Si la acción es registrar curso
    if ($accion === 'curso') {
        $nombreCurso = trim($_POST['nombre_curso'] ?? '');
        $descripcionCurso = trim($_POST['descripcion_curso'] ?? '');
        $fechaInicio = $_POST['fecha_inicio'] ?? null;
        $fechaFin = $_POST['fecha_fin'] ?? null;

        if (!$nombreCurso || !$fechaInicio || !$fechaFin) {
            throw new Exception("Debes completar todos los campos del curso.");
        }

        if (strtotime($fechaFin) < strtotime($fechaInicio)) {
            throw new Exception("La fecha de fin no puede ser anterior a la de inicio.");
        }

        $stmtCurso = $pdo->prepare("INSERT INTO cursos (id_catequesis, nombre, descripcion, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?, ?)");
        $stmtCurso->execute([$id_catequesis, $nombreCurso, $descripcionCurso, $fechaInicio, $fechaFin]);
    }

    // Si la acción es asignar catequista (opcional según estructura futura)
    if ($accion === 'catequista') {
        $idCatequista = $_POST['id_catequista'] ?? null;

        if (!$idCatequista) {
            throw new Exception("Debes seleccionar un catequista.");
        }

        // Aquí supondríamos que existe una tabla intermedia como `catequesis_catequistas`:
        $stmtAsignar = $pdo->prepare("INSERT INTO catequesis_catequistas (id_catequesis, id_catequista) VALUES (?, ?)");
        $stmtAsignar->execute([$id_catequesis, $idCatequista]);
    }

    $pdo->commit();

    echo json_encode([
        "success" => true,
        "message" => "Catequesis registrada correctamente.",
        "id_catequesis" => $id_catequesis
    ]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code($e->getCode() >= 100 && $e->getCode() < 600 ? $e->getCode() : 400);

    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
