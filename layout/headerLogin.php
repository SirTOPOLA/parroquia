<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
  <title>Login - Sistema Parroquial</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body, html {
      height: 100%;
      margin: 0;
      padding: 0;
      background: url('img/fondo.png') no-repeat center center fixed; 
      background-size: cover;
    }

    .login-wrapper {
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }

    .login-box {
      backdrop-filter: blur(10px);
      background-color: rgba(255, 255, 255, 0.9);
      border-radius: 1rem;
      padding: 2.5rem;
      width: 100%;
      max-width: 450px;
      box-shadow: 0 0 25px rgba(0, 0, 0, 0.4);
    }

    .form-control::placeholder {
      color: #888;
    }

    .toggle-password {
      cursor: pointer;
    }

    h3 {
      font-weight: bold;
    }

    @media (max-width: 576px) {
      .login-box {
        padding: 2rem 1.5rem;
      }
    }
  </style>
</head>
<body>
