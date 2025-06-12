<?php
require '../config/conexion.php'; 

// Filtrado opcional
$filtroSacramento = isset($_GET['id_sacramento']) ? (int)$_GET['id_sacramento'] : null;

$sql = "
    SELECT 
        f.id_feligres,
        f.nombre,
        f.apellido,
        f.fecha_nacimiento,
        f.genero,
        f.telefono,
        f.direccion,
        s.nombre AS sacramento,
        fs.fecha,
        fs.lugar
    FROM feligreses f
    INNER JOIN feligres_sacramento fs ON f.id_feligres = fs.id_feligres
    INNER JOIN sacramentos s ON fs.id_sacramento = s.id_sacramento
    WHERE fs.estado = 'completado'
";

$params = [];
if ($filtroSacramento) {
    $sql .= " AND fs.id_sacramento = ?";
    $params[] = $filtroSacramento;
}

$sql .= " ORDER BY f.apellido, f.nombre";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($resultado);
