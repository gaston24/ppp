
<?php

    // Encabezado PHP
    session_start(); 

    if(!isset($_SESSION['username'])){
        header("Location:../sistemas/login.php");
        exit(); // Asegura que el script se detenga después de la redirección
    }

    $nombre = isset($_SESSION['descLocal']) ? $_SESSION['descLocal'] : 'Usuario';

    require_once 'Class/indicadores.php';
    $ventas = new Venta();
    $ventasTodas = $ventas->traerImportes();

    $ventasPorCanal = [];
    $totalVentas = 0;
    foreach ($ventasTodas as $venta) {
        $canal = $venta['CANAL'];
        $ventasPorCanal[$canal] = $venta['IMPORTE_ACTUAL'];
        $totalVentas += $venta['IMPORTE_ACTUAL'];
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú XL - Dirección</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
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
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            background-color: #6f42c1;
            padding: 1rem;
            color: white;
            margin-left: 0.8rem;
            margin-right: 0.8rem;
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
            margin-bottom: 20px;
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

    <div class="container-fluid p-0">
        <!-- Sección superior -->
        <div class="p-3 welcome-card shadow-sm mb-3 position-relative">
            <h1 class="h4 mb-0"><i class="bi bi-emoji-smile"></i> Bienvenido Equipo <?php $nombreCapitalizado = ucfirst(strtolower($nombre)); echo htmlspecialchars($nombreCapitalizado); ?>!</h1>
            <i class="bi bi-box-arrow-right exit-icon" onclick="window.location='logout.php'" title="Salir"></i>
        </div>

        <h3 class="text-center mb-3">Facturación Acumulada del Mes</h3>

        <div class="row g-2">
            <!-- Total Ventas -->
            <div class="col-12">
                <div class="sales-card" style="height: 8rem; padding:1.5rem;">
                    <h2><i class="bi bi-cash-coin me-2"></i>Total Ventas</h2>
                    <p><?php echo number_format($totalVentas, 0, ',', '.'); ?></p>
                    <div class="progress mt-2">
                        <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>

            <!-- Ventas por Canal -->
            <div class="col-6">
                <div class="sales-card">
                    <h3><i class="bi bi-shop me-2"></i>Locales</h3>
                    <p><?php echo number_format($ventasPorCanal['LOCALES'], 0, ',', '.'); ?></p>
                    <div class="progress mt-1">
                        <div class="progress-bar" role="progressbar" style="width: <?php echo ($ventasPorCanal['LOCALES'] / $totalVentas) * 100; ?>%;" aria-valuenow="<?php echo ($ventasPorCanal['LOCALES'] / $totalVentas) * 100; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="percentage"><?php echo number_format(($ventasPorCanal['LOCALES'] / $totalVentas) * 100, 1, ',', '.'); ?>%</div>
                </div>
            </div>
            <div class="col-6">
                <div class="sales-card">
                    <h3><i class="bi bi-building me-2"></i>Franquicias</h3>
                    <p><?php echo number_format($ventasPorCanal['FRANQUICIAS'], 0, ',', '.'); ?></p>
                    <div class="progress mt-1">
                        <div class="progress-bar" role="progressbar" style="width: <?php echo ($ventasPorCanal['FRANQUICIAS'] / $totalVentas) * 100; ?>%;" aria-valuenow="<?php echo ($ventasPorCanal['FRANQUICIAS'] / $totalVentas) * 100; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="percentage"><?php echo number_format(($ventasPorCanal['FRANQUICIAS'] / $totalVentas) * 100, 1, ',', '.'); ?>%</div>
                </div>
            </div>
            <div class="col-6">
                <div class="sales-card">
                    <h3><i class="bi bi-cart me-2"></i>E-commerce</h3>
                    <p><?php echo number_format($ventasPorCanal['E-COMMERCE'], 0, ',', '.'); ?></p>
                    <div class="progress mt-1">
                        <div class="progress-bar" role="progressbar" style="width: <?php echo ($ventasPorCanal['E-COMMERCE'] / $totalVentas) * 100; ?>%;" aria-valuenow="<?php echo ($ventasPorCanal['E-COMMERCE'] / $totalVentas) * 100; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="percentage"><?php echo number_format(($ventasPorCanal['E-COMMERCE'] / $totalVentas) * 100, 1, ',', '.'); ?>%</div>
                </div>
            </div>
            <div class="col-6">
                <div class="sales-card">
                    <h3><i class="bi bi-boxes me-2"></i>Mayoristas</h3>
                    <p><?php echo number_format($ventasPorCanal['MAYORISTAS'], 0, ',', '.'); ?></p>
                    <div class="progress mt-1">
                        <div class="progress-bar" role="progressbar" style="width: <?php echo ($ventasPorCanal['MAYORISTAS'] / $totalVentas) * 100; ?>%;" aria-valuenow="<?php echo ($ventasPorCanal['MAYORISTAS'] / $totalVentas) * 100; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="percentage"><?php echo number_format(($ventasPorCanal['MAYORISTAS'] / $totalVentas) * 100, 1, ',', '.'); ?>%</div>
                </div>
            </div>
        </div>

        <!-- Menú de cards -->
        <div class="container">
            <div class="row">
                <!-- Ventas -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card menu-card">
                        <div class="card-header d-flex justify-content-between align-items-center" onclick="toggleCard(this)">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-cart-check menu-icon"></i>
                                <h5 class="card-title mb-0">Ventas</h5>
                            </div>
                            <i class="bi bi-plus-lg"></i>
                        </div>
                        <div class="card-body">
                            <div class="sub-card p-2">
                                <a href="../comercial/sucursales/ventasPropios.php"><i class="bi bi-shop me-2"></i>Locales propios</a>
                            </div>
                            <div class="sub-card p-2">
                                <a href="../comercial/sucursales/ventasFranquicias.php"><i class="bi bi-building me-2"></i>Franquicias</a>
                            </div>
                            <div class="sub-card p-2">
                                <a href="../ventas/todos"><i class="bi bi-grid me-2"></i>Todos</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comparar -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card menu-card">
                        <div class="card-header d-flex justify-content-between align-items-center" onclick="toggleCard(this)">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-bar-chart menu-icon"></i>
                                <h5 class="card-title mb-0">Comparar</h5>
                            </div>
                            <i class="bi bi-plus-lg"></i>
                        </div>
                        <div class="card-body">
                            <div class="sub-card p-2">
                                <a href="../ventas/localesComp"><i class="bi bi-shop-window me-2"></i>Locales propios</a>
                            </div>
                            <div class="sub-card p-2">
                                <a href="../ventas/franquiciasComp"><i class="bi bi-building-fill me-2"></i>Franquicias</a>
                            </div>
                        </div>
                    </div>
                </div>

                 <!--  -->
                 <div class="col-12 col-md-6 col-lg-4">
                    <div class="card menu-card">
                        <div class="card-header d-flex justify-content-between align-items-center" onclick="toggleCard(this)">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-credit-card menu-icon"></i>
                                <h5 class="card-title mb-0">Crédito</h5>
                            </div>
                            <i class="bi bi-plus-lg"></i>
                        </div>
                        <div class="card-body">
                            <div class="sub-card p-2">
                                <a href="ppp.php"><i class="bi bi-shop me-2"></i>Plazo promedio de pago</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Índices -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card menu-card">
                        <div class="card-header d-flex justify-content-between align-items-center" onclick="toggleCard(this)">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-graph-up menu-icon"></i>
                                <h5 class="card-title mb-0">Índices</h5>
                            </div>
                            <i class="bi bi-plus-lg"></i>
                        </div>
                        <div class="card-body">
                            <div class="sub-card p-2">
                                <a href="../ppp/estadisticas/index.php"><i class="bi bi-shop me-2"></i>Locales propios</a>
                            </div>
                            <div class="sub-card p-2">
                                <a href="/ppp/estadisticasFranquicias/index.php"><i class="bi bi-building me-2"></i>Franquicias</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tableros -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card menu-card">
                        <div class="card-header d-flex justify-content-between align-items-center" onclick="toggleCard(this)">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clipboard-data menu-icon"></i>
                                <h5 class="card-title mb-0">Tableros</h5>
                            </div>
                            <i class="bi bi-plus-lg"></i>
                        </div>
                        <div class="card-body">
                            <div class="sub-card p-2">
                                <a href="../bi/tableros/inventarios.html"><i class="bi bi-box me-2"></i>Reporte Inventarios</a>
                            </div>
                            <div class="sub-card p-2">
                                <a href="../bi/tableros/conteos.html"><i class="bi bi-list-ol me-2"></i>Reporte Conteos</a>
                            </div>
                            <div class="sub-card p-2">
                                <a href="../bi/tableros/salesSucursales.html"><i class="bi bi-graph-up-arrow me-2"></i>Sales Sucursales</a>
                            </div>
                            <div class="sub-card p-2">
                                <a href="../bi/tableros/salesSucursalesUy.html"><i class="bi bi-graph-up-arrow me-2"></i>Sales Sucursales Uruguay</a>
                            </div>
                            <div class="sub-card p-2">
                                <a href="../bi/tableros/salesFranquicias.html"><i class="bi bi-graph-up-arrow me-2"></i>Sales Franquicias</a>
                            </div>
                            <div class="sub-card p-2">
                                <a href="../bi/tableros/promociones.html"><i class="bi bi-tag me-2"></i>Promociones Sucursales</a>
                            </div>
                            <div class="sub-card p-2">
                                <a href="../bi/tableros/promocionesEcommerce.html"><i class="bi bi-tag me-2"></i>Promociones Ecommerce</a>
                            </div>
                            <div class="sub-card p-2">
                                <a href="../bi/tableros/geodatos.html"><i class="bi bi-geo-alt me-2"></i>Geodatos</a>
                            </div>
                            <div class="sub-card p-2">
                                <a href="../bi/tableros/dashEcommerce.html"><i class="bi bi-shop me-2"></i>Dashboard Ecommerce</a>
                            </div>
                            <div class="sub-card p-2">
                                <a href="../bi/tableros/dashKpiEcommerce.html"><i class="bi bi-key me-2"></i>Kpi's Ecommerce</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aplicaciones -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card menu-card">
                        <div class="card-header d-flex justify-content-between align-items-center" onclick="toggleCard(this)">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-app menu-icon"></i>
                                <h5 class="card-title mb-0">Aplicaciones</h5>
                            </div>
                            <i class="bi bi-plus-lg"></i>
                        </div>
                        <div class="card-body">
                            <div class="sub-card p-2">
                                <a href="../sistemas/controlFallas/seleccionDeSolicitudesSup.php"><i class="bi bi-tools me-2"></i>Gestión fallas</a>
                            </div>
                            <div class="sub-card p-2">
                                <a href="objetivos.php"><i class="bi bi-bullseye me-2"></i>Objetivos</a>
                            </div>
                            <div class="sub-card p-2">
                                <a href="http://extralarge.dyndns.biz:822"><i class="bi bi-cash-coin me-2"></i>Sales</a>
                            </div>
                            <div class="sub-card p-2">
                                <a href="../comercial/supervision/cargaGastos.php"><i class="bi bi-currency-dollar me-2"></i>Carga Gastos</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function toggleCard(header) {
        const body = header.nextElementSibling;
        const icon = header.querySelector('.bi-plus-lg, .bi-dash-lg');
        
        if (body && icon) {
            if (body.style.display === "none" || body.style.display === "") {
                body.style.display = "block";
                icon.classList.replace('bi-plus-lg', 'bi-dash-lg');
            } else {
                body.style.display = "none";
                icon.classList.replace('bi-dash-lg', 'bi-plus-lg');
            }
        } else {
            console.error('No se pudo encontrar el cuerpo de la card o el icono');
        }
    }
    </script>
</body>
</html>
