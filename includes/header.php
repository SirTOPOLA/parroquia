<?php
session_start();

// Si no hay sesión iniciada o no hay usuario, redirigir al login
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php');
    exit;
} 
require '../includes/conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Sistema Parroquial</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      min-height: 100vh;
      background-color: #f8f9fa;
    }

    .sidebar {
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      width: 250px;
      background-color: #343a40;
      color: #fff;
      transition: all 0.3s;
    }

    .sidebar a {
      color: #adb5bd;
      text-decoration: none;
    }

    .sidebar a:hover {
      color: #fff;
      background-color: #495057;
    }

    .sidebar .nav-link.active {
      color: #fff;
      background-color: #0d6efd;
    }

    .content {
      margin-left: 250px;
      padding: 2rem;
      transition: margin-left 0.3s;
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
      }
      .content {
        margin-left: 0;
      }
    }

    .navbar {
      z-index: 1050;
    }


    /* ------------------- ESTILOS PARA EL DASHBOARD CARD ------------------*/
      /* Reset básico para las cards */
  .card-dashboard {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(0,0,0,0.08);
    padding: 1.5rem 1.8rem;
    display: flex;
    align-items: center;
    gap: 1.2rem;
    transition: box-shadow 0.3s ease;
  }
  .card-dashboard:hover {
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
  }

  .card-icon {
    background: #f0f4ff;
    border-radius: 50%;
    padding: 1rem;
    width: 56px;
    height: 56px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-shrink: 0;
    color: #3b82f6; /* azul para iconos */
  }

  .card-content {
    flex-grow: 1;
  }

  .card-title {
    font-weight: 700;
    font-size: 1.1rem;
    color: #222;
    margin: 0 0 0.3rem 0;
    letter-spacing: 0.03em;
  }

  .card-value {
    font-size: 2.2rem;
    font-weight: 800;
    color: #111;
  }

  /* Colores personalizados para cada tipo */
  .card-personas .card-icon {
    color: #2563eb; /* azul intenso */
    background: #dbeafe;
  }
  .card-usuarios .card-icon {
    color: #16a34a; /* verde */
    background: #d1fae5;
  }
  .card-catequistas .card-icon {
    color: #0ea5e9; /* azul celeste */
    background: #d0f2fe;
  }
  .card-catequesis .card-icon {
    color: #ca8a04; /* amarillo dorado */
    background: #fef3c7;
  }

  /* Responsive */
  @media (max-width: 576px) {
    .card-dashboard {
      flex-direction: column;
      align-items: flex-start;
      gap: 0.5rem;
      padding: 1rem;
    }
    .card-icon {
      width: 48px;
      height: 48px;
      padding: 0.75rem;
    }
    .card-value {
      font-size: 1.8rem;
    }
  }
  </style>
</head>
<body>