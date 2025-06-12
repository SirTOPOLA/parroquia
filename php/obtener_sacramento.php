
<?php
require '../config/conexion.php';
$stmt = $pdo->query("SELECT id_sacramento, nombre FROM sacramentos ORDER BY nombre");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
