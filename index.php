<?php 
session_start(); 

if(!isset($_SESSION['username']) || $_SESSION['permisos']!= 4 ){
    header("Location:../sistemas/login.php");
    exit();
}

require_once 'Class/indicadores.php';
$indicadores = new Venta();

$datosIndicadores = $indicadores->traerIndicadoresTotal();
$saldo_cc = $datosIndicadores[0]['SALDO_CC'];
$vencidas = $datosIndicadores[0]['VENCIDAS'];
$a_vencer = $datosIndicadores[0]['A_VENCER'];
$cheques_10_dias = $datosIndicadores[0]['CHEQUES_10_DIAS'];
$cheque = $datosIndicadores[0]['CHEQUE'];

$datosFacturacion = $indicadores->traerImportes();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <meta http-equiv="Refresh" content="600">
    <?php include '../css/header_simple.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3), 0 6px 6px rgba(0, 0, 0, 0.23);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .card-img-container {
            position: relative;
            height: 220px;
            overflow: hidden;
        }
        .card-img-top {
            height: 100%;
            width: 100%;
            object-fit: cover;
            transition: opacity 0.5s ease-in-out;
        }
        .card-img-top.fade-out {
            opacity: 0;
        }
        .card-img-top.fade-in {
            opacity: 1;
        }
        .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            padding: 0.75rem 1.25rem;
        }
        .list-group-item:nth-child(odd) {
            background-color: rgba(0,0,0,.03);
        }
        .card-title {
            color: #495057;
            font-weight: 600;
        }
        .btn-action {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 0 0 8px 8px;
            transition: background-color 0.3s;
        }
        .btn-action:hover {
            background-color: #0056b3;
        }
        .btn-icon {
            margin-right: 8px;
        }
        .list-group-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .data-icon {
            font-size: 1.1rem;
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="alert alert-primary" role="alert" style="color:#007bff;">
        <h3 class="text-center mb-2"><i class="bi bi-speedometer2 me-2"></i>Indicadores Gerenciales</h3>
    </div>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <!-- Plazo Promedio de Pago Card -->
        <div class="col">
            <div class="card h-100">
                <div class="card-img-container">
                    <img class="card-img-top random-image fade-in" src="../Imagenes/ppp/100.jpg" alt="Card image cap" data-image-prefix="../Imagenes/ppp/">
                </div>
                <div class="card-body">
                    <h5 class="card-title text-center">Plazo Promedio de Pago</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <span><i class="fas fa-balance-scale data-icon"></i>Saldo Cuenta Corriente</span>
                        <span>$<?php echo number_format($saldo_cc, 0, ',', '.'); ?></span>
                    </li>
                    <li class="list-group-item">
                        <span><i class="fas fa-exclamation-circle data-icon text-danger"></i>Facturas Vencidas</span>
                        <span class="text-danger">$<?php echo number_format($vencidas, 0, ',', '.'); ?></span>
                    </li>
                    <li class="list-group-item">
                        <span><i class="fas fa-clock data-icon"></i>Facturas A vencer</span>
                        <span>$<?php echo number_format($a_vencer, 0, ',', '.'); ?></span>
                    </li>
                    <li class="list-group-item">
                        <span><i class="fas fa-money-check data-icon"></i>Cheques</span>
                        <span>$<?php echo number_format($cheque, 0, ',', '.'); ?></span>
                    </li>
                    <li class="list-group-item">
                        <span><i class="fas fa-calendar-alt data-icon"></i>Cheques a 10 Días</span>
                        <span>$<?php echo number_format($cheques_10_dias, 0, ',', '.'); ?></span>
                    </li>
                </ul>
                <button class="btn-action" onclick="location.href='ppp.php'">
                    <i class="fas fa-chart-line btn-icon"></i>Ver Detalles
                </button>
            </div>
        </div>

        <!-- Facturación Card -->
        <div class="col">
            <div class="card h-100">
                <div class="card-img-container">
                    <img class="card-img-top random-image fade-in" src="../Imagenes/ppp/112.jpg" alt="Card image cap" data-image-prefix="../Imagenes/ppp/">
                </div>
                <div class="card-body">
                    <h5 class="card-title text-center">Facturación Mes Actual</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($datosFacturacion as $facturacion): ?>
                        <li class="list-group-item">
                            <span>
                                <?php
                                $icon = 'fa-store';
                                switch ($facturacion['CANAL']) {
                                    case 'LOCALES':
                                        $icon = 'fa-store';
                                        break;
                                    case 'FRANQUICIAS':
                                        $icon = 'fa-store-alt';
                                        break;
                                    case 'E-COMMERCE':
                                        $icon = 'fa-shopping-cart';
                                        break;
                                    case 'MAYORISTAS':
                                        $icon = 'fa-warehouse';
                                        break;
                                }
                                ?>
                                <i class="fas <?php echo $icon; ?> data-icon"></i>
                                <?php echo $facturacion['CANAL']; ?>
                            </span>
                            <span>$<?php echo number_format($facturacion['IMPORTE_ACTUAL'], 0, ',', '.'); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <button class="btn-action" onclick="location.href='facturacion.php'">
                    <i class="fas fa-file-invoice-dollar btn-icon"></i>Ver Facturación
                </button>
            </div>
        </div>

        <!-- Estadísticas Card -->
        <div class="col">
            <div class="card h-100">
                <div class="card-img-container">
                    <img class="card-img-top random-image fade-in" src="../Imagenes/ppp/111.jpg" alt="Card image cap" data-image-prefix="../Imagenes/ppp/">
                </div>
                <div class="card-body">
                    <h5 class="card-title text-center">Estadísticas</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <!-- <li class="list-group-item">
                        <a class="text-decoration-none text-dark" href="estadisticas/index.php">
                            <i class="fas fa-store data-icon"></i>Ventas - Locales Propios
                        </a>
                    </li> -->
                    <!-- <li class="list-group-item">
                        <a class="text-decoration-none text-dark" href="estadisticasFranquicias/index.php">
                            <i class="fas fa-store-alt data-icon"></i>Ventas - Franquicias
                        </a>
                    </li> -->
                    <li class="list-group-item">
                        <a class="text-decoration-none text-dark" href="../ppp/facturacion/saldoCaja.php">
                            <i class="fas fa-cash-register data-icon"></i>Saldo Cajas Locales Propios
                        </a>
                    </li>
                    <li class="list-group-item">
                        <a class="text-decoration-none text-dark" href="../bi/indicadores.php">
                            <i class="fas fa-chart-pie data-icon"></i>Reporting BI
                        </a>
                    </li>
                    <li class="list-group-item">
                        <a class="text-decoration-none text-dark" href="../ventas/">
                            <i class="fas fa-file-invoice-dollar data-icon"></i>Ventas Sucursales
                        </a>
                    </li>
                </ul>
                <button class="btn-action" onclick="location.href='estadisticas/index.php'">
                    <i class="fas fa-chart-bar btn-icon"></i>Ver Estadísticas
                </button>
            </div>
        </div>
    </div>
</div>

<div id="boxLoading" class="position-fixed top-0 left-0 w-100 h-100 d-flex justify-content-center align-items-center bg-dark bg-opacity-50" style="display: none !important; z-index: 9999;">
    <div class="spinner-border text-light" role="status">
        <span class="visually-hidden">Cargando...</span>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showLoading() {
        document.getElementById('boxLoading').style.display = 'flex';
    }

    document.querySelectorAll('.btn-action').forEach(btn => {
        btn.addEventListener('click', showLoading);
    });

    document.querySelectorAll('.list-group-item a').forEach(link => {
        link.addEventListener('click', showLoading);
    });

    function changeRandomImages() {
        const images = document.querySelectorAll('.random-image');
        images.forEach(img => {
            img.classList.remove('fade-in');
            img.classList.add('fade-out');
            
            setTimeout(() => {
                const prefix = img.getAttribute('data-image-prefix');
                const randomNum = Math.floor(Math.random() * 20) + 1;
                img.src = `${prefix}${randomNum}.jpg`;
                
                img.onload = () => {
                    img.classList.remove('fade-out');
                    img.classList.add('fade-in');
                };
            }, 500);
        });
    }

    setInterval(changeRandomImages, 10000);
    changeRandomImages();
</script>

</body>
</html>