<?php
require '../includes/conexion.php';

$acto_id = $_POST['acto_sacramental_id'];
$personas = $_POST['personas'] ?? [];

foreach ($personas as $rol => $ids) {
    foreach ($ids as $persona_id) {
        if (!empty($persona_id)) {
            $stmt = $pdo->prepare("INSERT INTO relaciones_persona (acto_sacramental_id, persona_id, rol) VALUES (?, ?, ?)");
            $stmt->execute([$acto_id, $persona_id, $rol]);
        }
    }
}

header("Location: ../admin/acto.php?msg=Personas espirituales registradas");
exit;
