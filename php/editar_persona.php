<?php
require '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar datos
    $id = (int) $_POST['id'];
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $fecha = $_POST['fecha_nacimiento'] ?: null;
    $direccion = trim($_POST['direccion']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $genero = $_POST['genero'] ?: null;

    // Validación simple
    if (empty($nombres) || empty($apellidos)) {
        die('Nombre y apellido son obligatorios.');
    }

    $sql = "UPDATE persona SET 
                nombres = :nombres,
                apellidos = :apellidos,
                fecha_nacimiento = :fecha,
                direccion = :direccion,
                telefono = :telefono,
                correo = :correo,
                genero = :genero
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nombres' => $nombres,
        'apellidos' => $apellidos,
        'fecha' => $fecha,
        'direccion' => $direccion,
        'telefono' => $telefono,
        'correo' => $correo,
        'genero' => $genero,
        'id' => $id
    ]);

    header('Location: personas.php?mensaje=actualizado');
    exit;
}
?>
