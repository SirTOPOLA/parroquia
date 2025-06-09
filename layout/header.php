<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">



    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            overflow: hidden;
            background-color: #f8f9fa;

        }

        .wrapper {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* ---------------- SIDEBAR ---------------- */
        .sidebar {
            width: 250px;
            background-color: #1e293b;
            color: #fff;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 1050;
            position: relative;
            padding: 10px;
            scrollbar-width: thin;
            scrollbar-color: #1e293b #0f172a;
        }

        .sidebar::-webkit-scrollbar {
            width: 10px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.74);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #1e293b;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #334155;
        }

        .sidebar.collapsed {
            width: 80px;
            background-color: #1d2124;
        }

        .sidebar.collapsed .link-text {
            display: none; /* ocultar las letras en menu colapsado  */
        }

        .sidebar .nav-link {
            color: #fff;
            transition: all 0.2s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgb(7, 88, 169);
            color: rgb(160, 160, 160);
        }

        .sidebar .nav-link i {
            font-size: 1.2rem;
        }

        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed h5 {
            display: none;
        }

        @media (max-width: 767.98px) {
            .sidebar {
                position: fixed;
                height: calc(100vh - 80px);
                margin-top: 60px;
                left: -250px;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.5);
            }

            .sidebar.show {
                left: 0;
            }
        }


        /* ---------------- CONTENIDO PRINCIPAL ---------------- */
        #content {
            flex-grow: 1;
            overflow-y: auto;
            height: calc(100vh - 80px); 
            margin-left: 250px;
            padding: 1rem;
            transition: margin-left 0.3s ease;
            /*   border: solid 2px #52a552; */
        }

        #content.collapsed {
            margin-left: 80px;
        }


  
        

        
        /* ---------------- TABLA RESPONSIVA ---------------- */

        /* Ajuste de los iconos y texto en dispositivos móviles */
        @media (max-width: 768px) {
            .table {
                display: block;
                width: 100%;
            }

            .table thead {
                display: none;
                /* Ocultamos encabezados en móviles */

            }

            .table tbody {
                display: block;
                width: 100%;
            }

            .table tbody tr {
                display: flex;
                flex-direction: column;
                background: #fff;
                margin-bottom: 1rem;
                padding: 1rem;
                border-radius: 0.75rem;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .table tbody tr td {
                display: flex;
                justify-content: flex-start;
                /* Alineación a la izquierda */
                align-items: center;
                padding: 0.5rem 0;
                border-bottom: 1px solid #080606;
                font-size: 0.95rem;
            }

            .table tbody tr td:last-child {
                border-bottom: none;
            }

            .table tbody tr td::before {
                content: attr(data-label);
                flex: 0 0 30%;
                /* Reducimos el espacio del título */
                font-weight: 600;
                color: #555;
                text-align: left;
                padding-right: 20px;
                /* Menos espacio entre el icono y el texto */
                font-size: 1rem;
            }

            .table tbody tr td span,
            .table tbody tr td a {
                flex: 1;
                text-align: left;
                /* Alineamos el contenido a la izquierda */
                font-size: 1rem;
                /* Mejor legibilidad */
            }

            /* Para los iconos en data-label, cambiamos el tamaño */
            .table tbody tr td::before {
                font-size: 1.1rem;
                /* Mayor tamaño de los iconos */
                margin-right: 10px;
                /* Separar más los iconos del texto */
            }

            /* Ajuste en el diseño de los enlaces */
            .table tbody tr td a {
                display: inline-block;
                width: 100%;
                text-align: center;
                margin-top: 5px;
            }

            /* Estilo para los iconos tipo figura */
            .table tbody tr td::before {
                font-size: 1.5rem;
                /* Ajustar tamaño de los iconos */
                margin-right: 8px;
                /* Espacio entre el icono y el texto */
            }

            /* Para las acciones de edición */
            .table tbody tr td a {
                font-size: 1.2rem;
                /* Ajustar tamaño del icono de la acción */
                display: inline-flex;
                align-items: center;
                justify-content: center;
                margin-left: 8px;
            }
        }



        .card {
            border-radius: 1rem;
            border: none;
            background: #ffffff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: linear-gradient(90deg, #495057, #343a40);
            color: #f8f9fa;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            padding: 1rem 1.5rem;
        }

        .card-header h4 {
            margin: 0;
            font-weight: 600;
        }

        .table-custom thead {
            background-color: #e9ecef;
            color: rgb(255, 255, 255);
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        .table-custom th i {
            color: #6c757d;
        }

        .table-custom td,
        .table-custom th {
            vertical-align: middle;
            border-color: #dee2e6;
            white-space: nowrap;
        }

        .table-custom tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn-success {
            background-color: #198754;
            border-color: #198754;
        }

        .form-control::placeholder {
            color: #adb5bd;
        }

        .input-group .form-control {
            border-radius: 0.5rem;
        }

        /* En dispositivos móviles, mostramos los data-label */
        @media (max-width: 768px) {
            .table tbody tr td::before {
                display: block;
                /* Mostramos el data-label como bloque */
                content: attr(data-label);
                /* Extraemos el contenido del data-label */
                font-weight: bold;
                /* Hacemos que los labels sean más visibles */
                margin-bottom: 5px;
                /* Espaciamos un poco */
                font-size: 1rem;
                /* Ajustamos el tamaño de fuente */
            }
        }

 
 

</style>

</head>

<body>