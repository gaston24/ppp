<?php 
session_start(); 

if(!isset($_SESSION['username']) || $_SESSION['permisos']!= 4 ){
    header("Location:../sistemas/login.php");
} else {
    // Incluir la clase Venta
    require_once '../Class/indicadores.php';
    $datosCreditoClientes = new Venta();
    
    // Obtener el código del cliente grupo
    $codCliente = $_GET['cliente'];
    
    // Obtener los datos del grupo
    $result = $datosCreditoClientes->traerDetalleGrupo($codCliente);
?>  
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle Grupo - PPP</title>
    <?php include '../../css/header.php'; ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 1.5rem;
            background-color: #f8f9fa;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .card-header {
            background: linear-gradient(135deg, #3a6073 0%, #16222a 100%);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 1rem 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-header h5 {
            margin: 0;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
        }
        
        .card-header h5 i {
            margin-right: 10px;
        }
        
        .card-header .btn {
            font-size: 0.9rem;
        }
        
        .table-container {
            overflow-x: auto;
            max-height: calc(100vh - 220px);
            overflow-y: auto;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: #f0f2f5;
            font-weight: 600;
            font-size: 0.85rem;
            text-align: center;
            vertical-align: middle;
            padding: 12px 8px;
            white-space: nowrap;
            border-bottom: 2px solid #dee2e6;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .table tbody td {
            vertical-align: middle;
            font-size: 0.9rem;
            padding: 10px 8px;
            text-align: center;
        }
        
        .bg-cupo {
            background-color: #c8e6c9 !important;
        }
        
        .bg-deuda {
            background-color: #ffcdd2 !important;
        }
        
        .bg-disponible {
            background-color: #bbdefb !important;
        }
        
        .card-vencidas {
            background-color: #ffcdd2;
            color: #b71c1c;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .card-cheques-10 {
            background-color: #c8e6c9;
            color: #1b5e20;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .cliente-link {
            color: inherit;
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .cliente-link:hover {
            text-decoration: underline;
            color: #1976d2;
        }
        
        .cliente-no-plazo {
            color: #f44336;
            font-weight: bold;
        }
        
        .totals-row {
            font-weight: bold;
            background-color: #eeeeee;
        }
        
        .totals-row td {
            border-top: 2px solid #adb5bd;
        }
        
        .back-button {
            margin-bottom: 1rem;
        }
        
        .actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .table {
                font-size: 0.85rem;
            }
            
            .table th, .table td {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="actions-bar">
        <div>
            <a href="javascript:history.back()" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
        <div>
            <a href="../../../ppp/clientes/" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>Administrar Clientes
            </a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-users"></i> Detalle Grupo: <?php echo htmlspecialchars($codCliente); ?></h5>
            <button class="btn btn-sm btn-light" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Imprimir
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>CLIENTE</th>
                            <th>RAZON SOCIAL</th>
                            <th>PPP<br>12 meses</th>
                            <th class="bg-cupo">CUPO<br>GRUPO</th>
                            <th>SALDO<br>CC</th>
                            <th>TOTAL<br>CHEQUES</th>
                            <th class="bg-deuda">TOTAL<br>DEUDA</th>
                            <th>PEDIDOS<br>ABIERTOS</th>
                            <th>ORDENES<br>PENDIENTES</th>
                            <th class="bg-disponible">DISPONIBLE</th>
                            <th>ALERTAS</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $saldo_cc = 0;
                        $vencidas = 0;
                        $monto_pedidos = 0;
                        $total_cheques = 0;
                        $cheques_diez = 0;
                        $total_deuda = 0;
                        $disponible = 0;
                        
                        foreach($result as $v){
                    ?>
                        <tr>
                            <td class="<?php echo ($v['PLAZO']=='NO') ? 'cliente-no-plazo' : ''; ?>">
                                <?php echo $v['COD_CLIENTE']; ?>
                            </td>
                            
                            <td class="text-start">
                                <a href="pppDetalle.php?cliente=<?= $v['COD_CLIENTE']; ?>" class="cliente-link">
                                    <?php echo $v['RAZON_SOCIAL']; ?>
                                </a>
                            </td>
                            
                            <td><?= $v['PPP']; ?></td>
                            
                            <td class="bg-cupo"><?= number_format($v['CREDI_GRUPO'], 0, '', '.'); ?></td>
                            
                            <td class="fw-bold"><?= number_format($v['SALDO_CC'], 0, '', '.'); ?></td>
                            
                            <td>
                                <?php 
                                    if($v['CHEQUE']>0) {
                                        echo '<a href="pppCheque.php?cliente='.$v['COD_CLIENTE'].'" class="cliente-link">';
                                        echo number_format($v['CHEQUE'], 0, '', '.');
                                        echo '</a>';
                                    } else {
                                        echo number_format($v['CHEQUE'], 0, '', '.');
                                    }
                                ?>
                            </td>
                            
                            <td class="bg-deuda fw-bold"><?= number_format($v['TOTAL_DEUDA'], 0, '', '.'); ?></td>
                            
                            <td><?= number_format($v['MONTO_PEDIDOS'], 0, '', '.'); ?></td>
                            
                            <td><?= number_format($v['MONTO_ORDENES'] ?? 0, 0, '', '.'); ?></td>
                            
                            <td class="bg-disponible <?php echo ($v['TOTAL_DISPONIBLE'] < 0) ? 'text-danger fw-bold' : ''; ?>">
                                <?= number_format($v['TOTAL_DISPONIBLE'], 0, '', '.'); ?>
                            </td>
                            
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    <?php if ($v['VENCIDAS'] > 0){ ?>
                                        <div class="card-vencidas">
                                            <small>Vencidas: <?= number_format($v['VENCIDAS'], 0, '', '.'); ?></small>
                                        </div>
                                    <?php } ?>
                                    
                                    <?php if ($v['CHEQUES_10_DIAS'] > 0){ ?>
                                        <div class="card-cheques-10">
                                            <small>Cheques 10d: <?= number_format($v['CHEQUES_10_DIAS'], 0, '', '.'); ?></small>
                                        </div>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                    <?php
                        $saldo_cc += $v['SALDO_CC'];
                        $vencidas += $v['VENCIDAS'];
                        $monto_pedidos += $v['MONTO_PEDIDOS'];
                        $total_cheques += $v['CHEQUE'];
                        $cheques_diez += $v['CHEQUES_10_DIAS'];
                        $total_deuda += $v['TOTAL_DEUDA'];
                        $disponible += $v['TOTAL_DISPONIBLE'];
                    }
                    ?>
                        <tr class="totals-row">
                            <td></td>
                            <td class="text-center">TOTAL</td>
                            <td></td>
                            <td class="bg-cupo"></td>
                            <td><?= number_format($saldo_cc, 0, '', '.'); ?></td>
                            <td><?= number_format($total_cheques, 0, '', '.'); ?></td>
                            <td class="bg-deuda"><?= number_format($total_deuda, 0, '', '.'); ?></td>
                            <td><?= number_format($monto_pedidos, 0, '', '.'); ?></td>
                            <td><?= number_format($monto_ordenes ?? 0, 0, '', '.'); ?></td>
                            <td class="bg-disponible <?php echo ($disponible < 0) ? 'text-danger' : ''; ?>">
                                <?= number_format($disponible, 0, '', '.'); ?>
                            </td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    <div class="card-vencidas">
                                        <small>Total Vencidas: <?= number_format($vencidas, 0, '', '.'); ?></small>
                                    </div>
                                    <div class="card-cheques-10">
                                        <small>Total Cheques 10d: <?= number_format($cheques_diez, 0, '', '.'); ?></small>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<?php
}
?>