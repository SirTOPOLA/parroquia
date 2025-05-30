<?php
session_start();

// Si no hay sesión iniciada o no hay usuario, redirigir al login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}
?>
