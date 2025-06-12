<?php
require '../config/conexion.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("
    SELECT s.nombre, fs.fecha, fs.lugar, fs.observaciones
    FROM feligres_sacramento fs
    JOIN sacramentos s ON fs.id_sacramento = s.id_sacramento
    WHERE fs.id_feligres = ?
    ORDER BY fs.fecha ASC
");
$stmt->execute([$id]);
$sacramentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$sacramentos) {
  echo "<p><em>Sin sacramentos asignados.</em></p>";
  exit;
}

echo "<table class='table table-bordered'>";
echo "<thead><tr><th>Sacramento</th><th>Fecha</th><th>Lugar</th><th>Observaciones</th></tr></thead><tbody>";
foreach ($sacramentos as $s) {
  echo "<tr>
          <td>{$s['nombre']}</td>
          <td>" . date('d/m/Y', strtotime($s['fecha'])) . "</td>
          <td>{$s['lugar']}</td>
          <td>{$s['observaciones']}</td>
        </tr>";
}
echo "</tbody></table>";
