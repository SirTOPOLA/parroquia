<?php
require_once '../includes/conexion.php'; // Aquí asumo que $pdo está inicializado

// Sanitizar y validar
$persona_id = filter_input(INPUT_POST, 'persona_id', FILTER_VALIDATE_INT);
$sacramento_id = filter_input(INPUT_POST, 'sacramento_id', FILTER_VALIDATE_INT);
$parroquia_id = filter_input(INPUT_POST, 'parroquia_id', FILTER_VALIDATE_INT);
$parroco_id = filter_input(INPUT_POST, 'parroco_id', FILTER_VALIDATE_INT);
$fecha = $_POST['fecha'] ?? '';
$libro = trim($_POST['libro'] ?? '');
$folio = trim($_POST['folio'] ?? '');
$partida = trim($_POST['partida'] ?? '');
$observaciones = trim($_POST['observaciones'] ?? '');
$certificado_emitido = isset($_POST['certificado_emitido']) ? (int)$_POST['certificado_emitido'] : 0;

// Validaciones básicas
$errores = [];
if (!$persona_id) $errores[] = "Persona inválida";
if (!$sacramento_id) $errores[] = "Sacramento inválido";
if (!$fecha || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) $errores[] = "Fecha inválida";

// Si errores, regresar con error
if ($errores) {
    // Aquí puedes manejar el error como gustes (json, session, etc)
    die("Errores: " . implode(", ", $errores));
}

try {
    $pdo->beginTransaction();

    // Insertar acto sacramental
    $stmt = $pdo->prepare("INSERT INTO acto_sacramental (persona_id, sacramento_id, parroquia_id, parroco_id, fecha, libro, folio, partida, observaciones, certificado_emitido)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $persona_id, $sacramento_id, $parroquia_id ?: null, $parroco_id ?: null, $fecha, $libro ?: null, $folio ?: null, $partida ?: null, $observaciones ?: null, $certificado_emitido
    ]);

    $acto_id = $pdo->lastInsertId();

    // Guardar relaciones (opcional, si vienen)
    if (!empty($_POST['relaciones']) && is_array($_POST['relaciones'])) {
        $stmtRel = $pdo->prepare("INSERT INTO relaciones_persona (acto_sacramental_id, persona_id, rol) VALUES (?, ?, ?)");
        foreach ($_POST['relaciones'] as $rel) {
            $pid = filter_var($rel['persona_id'], FILTER_VALIDATE_INT);
            $rol = $rel['rol'] ?? '';
            if ($pid && in_array($rol, ['padre', 'madre', 'padrino', 'madrina'])) {
                $stmtRel->execute([$acto_id, $pid, $rol]);
            }
        }
    }

    $pdo->commit();
    header("Location: ../admin/acto.php?msg=guardado");
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    die("Error al guardar: " . $e->getMessage());
}
