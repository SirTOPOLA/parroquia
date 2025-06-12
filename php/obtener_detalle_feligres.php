<?php
require_once '../config/conexion.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

// Datos personales
$stmt = $pdo->prepare("SELECT * FROM feligreses WHERE id_feligres = ?");
$stmt->execute([$id]);
$fel = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$fel) {
    echo json_encode(['error' => 'Feligres no encontrado']);
    exit;
}

// Sacramentos
$stmt = $pdo->prepare("
 SELECT fs.id_sacramento, s.nombre, fs.fecha, fs.lugar, fs.estado
 FROM feligres_sacramento fs
 JOIN sacramentos s USING (id_sacramento)
 WHERE fs.id_feligres = ?
");
$stmt->execute([$id]);
$sacr = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Parientes
$stmt = $pdo->prepare("
 SELECT fp.tipo_relacion, p.nombre, p.apellido, sp.nombre AS sacramento
 FROM feligres_parientes fp
 JOIN parientes p USING(id_pariente)
 LEFT JOIN sacramentos sp ON fp.id_sacramento = sp.id_sacramento
 WHERE fp.id_feligres = ?
");
$stmt->execute([$id]);
$pars = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cursos inscritos y sus catequistas
$stmt = $pdo->prepare("
 SELECT c.id_curso, c.nombre, c.fecha_inicio, c.fecha_fin, cf.estado
 FROM curso_feligres cf
 JOIN cursos c USING(id_curso)
 WHERE cf.id_feligres = ?
");
$stmt->execute([$id]);
$cursos_ins = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($cursos_ins as &$cr) {
    $stmt2 = $pdo->prepare("
      SELECT cat.nombre, cat.apellido
      FROM curso_catequistas cc
      JOIN catequistas cat USING(id_catequista)
      WHERE cc.id_curso = ?
    ");
    $stmt2->execute([$cr['id_curso']]);
    $cats = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    $cr['catequistas'] = array_column($cats, 'nombre');
}
unset($cr);

// Respuesta JSON
echo json_encode([
  'nombre' => $fel['nombre'],
  'apellido' => $fel['apellido'],
  'fecha_nacimiento' => $fel['fecha_nacimiento'],
  'genero' => $fel['genero'],
  'direccion' => $fel['direccion'],
  'telefono' => $fel['telefono'],
  'estado_civil' => $fel['estado_civil'],
  'sacramentos' => $sacr,
  'parientes' => $pars,
  'cursos' => $cursos_ins
]);
