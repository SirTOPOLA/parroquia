<?php
require '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Obtener el estado actual
    $stmt = $pdo->prepare("SELECT estado FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $nuevoEstado = $usuario['estado'] ? 0 : 1;
        $stmt = $pdo->prepare("UPDATE usuarios SET estado = ? WHERE id = ?");
        $stmt->execute([$nuevoEstado, $id]);
        echo json_encode(['success' => true, 'estado' => $nuevoEstado]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Usuario no encontrado']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Petición inválida']);
}
