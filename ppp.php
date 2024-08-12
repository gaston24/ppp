<?php 
session_start(); 

if(!isset($_SESSION['username']) || $_SESSION['permisos'] != 4){
    header("Location:../sistemas/login.php");
    exit();
}

require_once 'Class/indicadores.php';
$indicadores = new Venta();
$datosIndicadores = $indicadores->traerIndicadores();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPP - Canal</title>
    <?php include '../css/header.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        .table th {
            font-size: 0.9rem;
        }
        .table td {
            font-size: 1rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
		<div class="alert alert-primary" role="alert">
        	<h3 class="text-center mb-1"><i class="bi bi-check2-square me-2"></i>SELECCIONAR CANAL</h3>
		</div>

        <div class="row justify-content-center mb-4 mt-4">
            <div class="col-md-8">
                <button class="btn btn-success btn-lg mb-2 w-100" id="btn_refresh">
                    <i class="fas fa-sync-alt me-2"></i>Actualizar Crédito
                </button>
                <button class="btn btn-primary btn-lg mb-2 w-100 spinner" onclick="location.href='mayoristas/index.php'">
                    <i class="fas fa-store me-2"></i>Mayoristas
                </button>
                <button class="btn btn-secondary btn-lg mb-2 w-100 spinner" onclick="location.href='franquicias/ppp.php'">
                    <i class="fas fa-handshake me-2"></i>Franquicias
                </button>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" style="max-width: 1250px; margin: auto;">
                        <thead class="table-dark">
                            <tr>
                                <th>CLIENTE</th>
                                <th>SALDO CC</th>
                                <th>VENCIDO</th>
                                <th>A VENCER</th>
                                <th>TOTAL CHEQUES</th>
                                <th>CHEQUES 10 DÍAS</th>
                                <th>TOTAL DEUDA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totales = [
                                'SALDO_CC' => 0, 'VENCIDAS' => 0, 'A_VENCER' => 0,
                                'CHEQUE' => 0, 'CHEQUES_10_DIAS' => 0, 'TOTAL_DEUDA' => 0
                            ];

                            foreach ($datosIndicadores as $v) {
                                echo "<tr>
                                    <td>{$v['COD_CLIENT']}</td>
                                    <td>$" . number_format($v['SALDO_CC'], 0, ',', '.') . "</td>
                                    <td class='text-danger'>$" . number_format($v['VENCIDAS'], 0, ',', '.') . "</td>
                                    <td>$" . number_format($v['A_VENCER'], 0, ',', '.') . "</td>
                                    <td>$" . number_format($v['CHEQUE'], 0, ',', '.') . "</td>
                                    <td class='text-success'>$" . number_format($v['CHEQUES_10_DIAS'], 0, ',', '.') . "</td>
                                    <td class='fw-bold'>$" . number_format($v['TOTAL_DEUDA'], 0, ',', '.') . "</td>
                                </tr>";

                                foreach ($totales as $key => $value) {
                                    $totales[$key] += $v[$key];
                                }
                            }
                            ?>
                            <tr class="table-primary">
                                <td class="fw-bold">TOTAL</td>
                                <?php
                                foreach ($totales as $key => $value) {
                                    $class = $key == 'VENCIDAS' ? 'text-danger' : ($key == 'CHEQUES_10_DIAS' ? 'text-success' : '');
                                    echo "<td class='fw-bold {$class}'>$" . number_format($value, 0, ',', '.') . "</td>";
                                }
                                ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="boxLoading" class="loading-overlay"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/main.js"></script>
    <script>
        document.querySelectorAll('.spinner').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('boxLoading').classList.add('show');
            });
        });
    </script>
</body>
</html>