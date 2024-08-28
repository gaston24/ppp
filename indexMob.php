
<?php

    // Encabezado PHP
    session_start(); 

    if(!isset($_SESSION['username'])){
        header("Location:../sistemas/login.php");
        exit(); // Asegura que el script se detenga después de la redirección
    }

    $nombre = isset($_SESSION['descLocal']) ? $_SESSION['descLocal'] : 'Usuario';

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú XL - Dirección</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 60px;
            padding-bottom: 60px;
            background-color: #f2f4f4;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .fixed-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background-color: #6610f2;
            color: white;
        }
        .fixed-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }
        .footer-icon {
            font-size: 1.5rem;
            color: #6c757d;
        }
        .footer-icon.active {
            color: #6610f2;
        }
        #content {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 70px; /* Asegura que el contenido no quede detrás del footer */
        }
        body {
            background-color: #f2f4f4;
        }
        .welcome-card {
            background-color: #6610f2;
            color: white;
        }
        /* Cards de KPIS de Venta */
        .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
        }
        .sales-card {
        border: none;
        border-radius: 10px;
        background-color: #6f42c1;
        padding: 1rem;
        color: white;
        margin-left: 0.2rem;
        margin-right: 0.2rem;
        margin-bottom: 20px; /* Aumentado para dar más espacio a la sombra */
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3), 0 6px 6px rgba(0, 0, 0, 0.23);
        position: relative;
        z-index: 1;
        }
        .sales-card h2, .sales-card h3 {
            color: #ffffff;
            margin-bottom: 0.1rem;
            font-size: 1.1rem;
        }
        .sales-card p {
            color: #f8f9fa;
            font-size: 1.4rem;
            font-weight: bold;
            margin-bottom: 0.2rem;
        }
        .progress {
            height: 8px;
            background-color: rgba(255, 255, 255, 0.2);
        }
        .progress-bar {
            background-color: #28a745;
        }
        .percentage {
            font-size: 1rem;
            margin-top: 0.2rem;
        }
        .stats-card {
            background-color: #27293d;
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 1rem;
            text-align: center;
        }
        .stats-card h4 {
            color: #9a9a9a;
            font-size: 0.9rem;
        }
        .stats-card p {
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0;
        }
        /* Cards de Menu */
        .menu-card {
            border-radius: 7px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background-color: #e6f3e6;
            margin-bottom: 5px;
            overflow: hidden;
        }
        .menu-icon {
            font-size: 2rem;
            margin-right: 0.5rem;
        }
        .sub-card {
            background-color: #c8e6c9;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: transform 0.3s ease;
        }
        .sub-card:hover {
            transform: translateY(-3px);
        }
        .sub-card a {
            color: #2e7d32;
            text-decoration: none;
        }
        .card-header {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        .card-body {
            display: none;
        }
        .exit-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.5rem;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header class="fixed-header p-3">
        <h1 class="h4 mb-0"><i class="bi bi-emoji-smile"></i> Bienvenido Equipo <?php echo htmlspecialchars(ucfirst(strtolower($nombre))); ?>!</h1>
        <i class="bi bi-box-arrow-right exit-icon" onclick="window.location='logout.php'" title="Salir"></i>
    </header>

    <div class="container-fluid p-3" id="content">
        <!-- El contenido se cargará dinámicamente aquí -->
    </div>

    <footer class="fixed-footer">
        <div class="d-flex justify-content-around py-2">
            <div class="text-center" onclick="loadSection('menu', this)">
                <i class="bi bi-list footer-icon"></i>
                <div>Menú</div>
            </div>
            <div class="text-center" onclick="loadSection('ventas', this)">
                <i class="bi bi-graph-up footer-icon"></i>
                <div>Ventas</div>
            </div>
            <div class="text-center" onclick="loadSection('indicadores', this)">
                <i class="bi bi-speedometer2 footer-icon"></i>
                <div>KPI Cobranza</div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function loadSection(section, element) {
            const contentDiv = document.getElementById('content');
            switch(section) {
                case 'menu':
                    fetch('menu_section.php')
                        .then(response => response.text())
                        .then(data => {
                            contentDiv.innerHTML = data;
                            initializeMenuToggle();
                        });
                    break;
                case 'ventas':
                    fetch('ventas_section.php')
                        .then(response => response.text())
                        .then(data => {
                            contentDiv.innerHTML = data;
                        });
                    break;
                case 'indicadores':
                    fetch('indicadores_section.php')
                        .then(response => response.text())
                        .then(data => {
                            contentDiv.innerHTML = data;
                        });
                    break;
            }
            // Actualizar íconos activos
            document.querySelectorAll('.footer-icon').forEach(icon => icon.classList.remove('active'));
            element.querySelector('.footer-icon').classList.add('active');
        }

        function initializeMenuToggle() {
            document.querySelectorAll('.card-header').forEach(header => {
                header.addEventListener('click', function(event) {
                    event.preventDefault();
                    const body = this.nextElementSibling;
                    const icon = this.querySelector('.bi-plus-lg, .bi-dash-lg');
                    
                    if (body && icon) {
                        if (body.style.display === "none" || body.style.display === "") {
                            body.style.display = "block";
                            icon.classList.replace('bi-plus-lg', 'bi-dash-lg');
                        } else {
                            body.style.display = "none";
                            icon.classList.replace('bi-dash-lg', 'bi-plus-lg');
                        }
                    }
                });
            });
        }

        // Cargar la sección de ventas por defecto
        document.addEventListener('DOMContentLoaded', function() {
            loadSection('menu', document.querySelector('[onclick="loadSection(\'menu\', this)"]'));
        });
    </script>

</body>
</html>