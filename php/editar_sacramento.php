<?php
include '../admin/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_sacramento'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');

    if ($id <= 0 || $nombre === '') {
        header('Location: ../admin/sacramentos.php?mensaje=error');
        exit;
    }

    $sql = "UPDATE sacramentos SET nombre = :nombre WHERE id_sacramento = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['nombre' => $nombre, 'id' => $id]);

    header('Location: ../admin/sacramentos.php?mensaje=editado');
    exit;
}

header('Location: ../admin/sacramentos.php');
exit;
