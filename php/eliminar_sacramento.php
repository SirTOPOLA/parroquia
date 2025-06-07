<?php
include '../includes/database.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $sql = "DELETE FROM sacramentos WHERE id_sacramento = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
}

header('Location: ../vistas/sacramentos.php?mensaje=eliminado');
exit;
