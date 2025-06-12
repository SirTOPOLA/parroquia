<?php
function postSacramentos(PDO $pdo): void
{
    try {
        // Verifica si ya hay sacramentos insertados
        $stmt = $pdo->query("SELECT COUNT(*) FROM sacramentos");
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            $sacramentos = ['bautismo', 'confirmacion', 'comunion', 'matrimonio'];
            $insert = $pdo->prepare("INSERT INTO sacramentos (nombre) VALUES (:nombre)");

            foreach ($sacramentos as $nombre) {
                $insert->execute(['nombre' => $nombre]);
            } 
        } 

    } catch (PDOException $e) {
        // Manejo de errores (mejor usar logger en producción)
        $_SESSION['alerta'] = [
            'tipo' => 'danger',
            'mensaje' => 'Hubo un error: ' . $e->getMessage()
        ];
        header('Location: ../index.php?vista=sacramentos');

    }
}



function getSacramentos(PDO $pdo): array
{
    try {
        $stmt = $pdo->query("SELECT id_sacramento, nombre FROM sacramentos ORDER BY id_sacramento ASC");
        $sacramentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $sacramentos;

    } catch (PDOException $e) {
        // Manejo de errores
        $_SESSION['alerta'] = [
            'tipo' => 'danger',
            'mensaje' => 'Error al obtener sacramentos: ' . $e->getMessage()
        ];
        header('Location: ../index.php?vista=sacramentos');
        return [];
    }
}

function obtenerTelefonoFeligres($pdo, $id_feligres) {
    // Primero intentamos obtener el teléfono del feligrés directamente
    $stmt = $pdo->prepare("SELECT telefono FROM feligreses WHERE id_feligres = :id");
    $stmt->execute(['id' => $id_feligres]);
    $telefono = $stmt->fetchColumn();

    if ($telefono) return $telefono;

    // Si no tiene teléfono, buscamos en sus parientes (padre o madre)
    $sql = "SELECT p.telefono 
            FROM feligres_parientes fp
            INNER JOIN parientes p ON fp.id_pariente = p.id_pariente
            WHERE fp.id_feligres = :id AND fp.tipo_relacion IN ('padre', 'madre') 
              AND p.telefono IS NOT NULL
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id_feligres]);
    return $stmt->fetchColumn(); // puede devolver null si ninguno tiene
}
function obtenerSacramentosPendientes($pdo, $id_feligres) {
    $sql = "SELECT fs.*, s.nombre AS nombre_sacramento
            FROM feligres_sacramento fs
            INNER JOIN sacramentos s ON fs.id_sacramento = s.id_sacramento
            WHERE fs.id_feligres = :id_feligres 
              AND fs.estado IN ('pendiente', 'en_proceso')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_feligres' => $id_feligres]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


?>