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



?>