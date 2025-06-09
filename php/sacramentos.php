<?php
include_once('../config/conexion.php');

try {
    
    // Verifica si los sacramentos ya existen (por ejemplo, si hay al menos uno)
    $stmt = $pdo->query("SELECT COUNT(*) FROM sacramentos");
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        // Solo se insertan si no hay ningún sacramento
        $sacramentos = ['bautismo', 'confirmacion', 'comunion', 'matrimonio'];
        $insert = $pdo->prepare("INSERT INTO sacramentos (nombre) VALUES (:nombre)");

        foreach ($sacramentos as $nombre) {
            $insert->execute(['nombre' => $nombre]);
        }

       
    }  

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
