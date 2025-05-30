<?php
include_once '../includes/conexion.php'; // Asegúrate de que esta ruta sea correcta

try { 

    // Verificamos si ya existen registros
    $stmt = $pdo->query("SELECT COUNT(*) FROM sacramento");
    $existe = $stmt->fetchColumn();

    if ($existe == 0) {
        // Insertamos solo si está vacío
        $sql = "INSERT INTO sacramento (nombre, descripcion) VALUES 
            ('bautismo', 'Sacramento de iniciación cristiana'),
            ('comunion', 'Primera Comunión del cuerpo de Cristo'),
            ('confirmacion', 'Confirmación de la fe católica'),
            ('matrimonio', 'Unión sacramental entre dos personas')";
        $pdo->exec($sql);
      //  echo "Sacramentos registrados correctamente.";
    } else {
       // echo "Los sacramentos ya están registrados.";
    }
} catch (PDOException $e) {
    echo "Error al registrar sacramentos: " . $e->getMessage();
}
?>
