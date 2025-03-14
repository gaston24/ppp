
<?php 
session_start(); 

if(!isset($_SESSION['username']) || $_SESSION['permisos']!= 4 ){
    header("Location:../../sistemas/login.php");
} else {
    // Incluir la clase Venta
    require_once '../Class/indicadores.php';
    $datosCreditoClientes = new Venta();
?>  
<!doctype html>
<html lang="es">
<head>
    <?php include '../../css/header.php'; ?>
    <title>Crédito Franquicias</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 2rem;
        }
        
        /* Dashboard Title Styles */
        .dashboard-title {
            background: linear-gradient(135deg, #3a6073 0%, #16222a 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .dashboard-title i {
            font-size: 2rem;
            margin-right: 20px;
            color: #fff;
        }
        
        .dashboard-title h4 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        /* Information Box Styles */
        .formula-box {
            background-color: #e8f5e9;
            border-left: 5px solid #43a047;
            border-radius: 6px;
            padding: 18px;
            margin-bottom: 25px;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }
        
        .formula-box i {
            color: #2e7d32;
        }
        
        /* Search Box Styles */
        .search-box {
            max-width: 450px;
        }
        
        .search-box .input-group {
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .search-box .input-group-text {
            background-color: #f8f9fa;
        }
        
        /* Table Container Styles */
        .table-container {
            height: calc(100vh - 300px);
            min-height: 400px;
            overflow-y: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        /* Fixed Header Styles */
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            position: sticky;
            top: 0;
            z-index: 1000;
            font-weight: 600;
            font-size: 0.85rem;
            text-align: center;
            vertical-align: middle;
            padding: 15px 10px;
            white-space: nowrap;
            border-bottom: 2px solid #dee2e6;
        }
        
        .table thead th.bg-cupo,
        .table tbody td.bg-cupo,
        .table tfoot td.bg-cupo {
            background-color: #c8e6c9 !important;
        }
        
        .table thead th.bg-deuda,
        .table tbody td.bg-deuda,
        .table tfoot td.bg-deuda {
            background-color: #ffcdd2 !important;
        }
        
        .table thead th.bg-disponible,
        .table tbody td.bg-disponible,
        .table tfoot td.bg-disponible {
            background-color: #bbdefb !important;
        }
        
        .table tbody td {
            vertical-align: middle;
            font-size: 0.9rem;
            padding: 12px 10px;
        }
        
        /* Negative disponible values */
        .text-danger {
            color: #d32f2f !important;
        }
        
        /* Alert Card Styles */
        .card-vencidas {
            background-color: #ffcdd2;
            color: #b71c1c;
            padding: 6px 10px;
            border-radius: 4px;
            display: inline-block;
            font-weight: 500;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .card-cheques-10 {
            background-color: #c8e6c9;
            color: #1b5e20;
            padding: 6px 10px;
            border-radius: 4px;
            display: inline-block;
            font-weight: 500;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        /* Link Styles */
        .cliente-link {
            color: inherit;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .cliente-link:hover {
            text-decoration: underline;
            color: #1976d2;
        }
        
        /* Special Formatting */
        .cliente-no-plazo {
            color: #f44336;
            font-weight: bold;
        }
        
        .totals-row {
            font-weight: bold;
            background-color: #f5f5f5;
        }
        
        .totals-row td {
            border-top: 2px solid #dee2e6;
        }

        /* Enhanced Link Styles */
        .cliente-link {
            color: #1976d2;
            text-decoration: none;
            transition: all 0.2s;
            position: relative;
            display: inline-block;
            padding-right: 20px;
        }

        .cliente-link:hover {
            text-decoration: underline;
        }

        /* Add a visual indicator to show it's clickable */
        .cliente-link::after {
            content: "\f061"; /* Font Awesome arrow-right icon */
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.8rem;
            opacity: 0.7;
        }

        /* Add slight highlight on hover */
        .cliente-link:hover {
            background-color: rgba(25, 118, 210, 0.05);
            border-radius: 4px;
        }

        /* Add a subtle dotted underline to indicate it's a link */
        .cliente-link {
            border-bottom: 1px dotted #1976d2;
        }
    </style>
</head>
<body>

<div class="container-fluid py-4">
    <!-- Obtener datos utilizando la clase Venta -->
    <?php
    $result = $datosCreditoClientes->traerDatosCredito();
    ?>
    
    <div class="row mb-4">
        <div class="col">
            <!-- Título mejorado con ícono -->
            <div class="dashboard-title">
                <i class="fas fa-chart-line"></i>
                <h4>Panel Crédito Franquicias</h4>
            </div>
            
            <!-- Fórmulas Informativas con nuevo color -->
            <div class="formula-box">
                <div class="row">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <i class="fas fa-calculator me-2"></i> <strong>Saldo CC + Total Cheques = Total Deuda</strong>
                    </div>
                    <div class="col-md-6">
                        <i class="fas fa-calculator me-2"></i> <strong>Cupo Cred - Total Deuda - Pedidos Abiertos - Ordenes Pendientes = Disponible</strong>
                    </div>
                </div>
            </div>
            
            <!-- Búsqueda Rápida y Botón de Agregar Cliente en la misma línea -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="search-box">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" id="textBox" class="form-control" placeholder="Busqueda rápida..." onkeyup="myFunction()">
                    </div>
                </div>
                <a href="../../../ppp/clientes/" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>Administrar Clientes
                </a>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table class="table table-striped table-hover" id="table">
            <thead>
            <tr>
                <th>CLIENTE</th>
                <th>RAZON SOCIAL</th>
                <th>
                    PPP<br>12 meses
                </th>
                <th class="bg-cupo">CUPO<br>CREDITO</th>
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
                $monto_ordenes = 0;
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
                    
                    <td>
                        <?php if($v['ES_GRUPO']=='SI'){ ?>
                            <a href="pppGrupo.php?cliente=<?= $v['COD_CLIENTE']; ?>" class="cliente-link">
                                <?php echo $v['RAZON_SOCIAL']; ?>
                            </a>
                        <?php } else { ?>
                            <a href="pppDetalle.php?cliente=<?= $v['COD_CLIENTE']; ?>" class="cliente-link">
                                <?php echo $v['RAZON_SOCIAL']; ?>
                            </a>
                        <?php } ?>
                    </td>

                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center">
                            <?= $v['PPP']; ?>
                            <a href="#" class="btn-evolucion-ppp ms-2 btn btn-sm btn-outline-info" 
                            data-bs-toggle="modal" data-bs-target="#modalEvolucionPPP" 
                            data-cliente="<?= $v['COD_CLIENTE']; ?>"
                            data-razon="<?= htmlspecialchars($v['RAZON_SOCIAL']); ?>"
                            title="Ver evolución del PPP">
                                <i class="fas fa-chart-line"></i>
                            </a>
                        </div>
                    </td>
                    
                    <td class="text-end bg-cupo"><?= number_format($v['CUPO_CRED'], 0, '', '.'); ?></td>
                    
                    <td class="text-end"><?= number_format($v['SALDO_CC'], 0, '', '.'); ?></td>
                    
                    <td class="text-end">
                        <?php 
                            if($v['CHEQUE']>0) {
                                if($v['ES_GRUPO']=='SI') {
                                    echo '<a href="pppGrupoCheque.php?cliente='.$v['COD_CLIENTE'].'" class="cliente-link">';
                                } else {    
                                    echo '<a href="pppCheque.php?cliente='.$v['COD_CLIENTE'].'" class="cliente-link">';
                                }    
                            } 
                            echo number_format($v['CHEQUE'], 0, '', '.');
                            if($v['CHEQUE']>0) {
                                echo '</a>';
                            }
                        ?> 
                    </td>
                    
                    <td class="text-end bg-deuda fw-bold"><?= number_format($v['TOTAL_DEUDA'], 0, '', '.'); ?></td>
                    
                    <td class="text-end"><?= number_format($v['MONTO_PEDIDOS'], 0, '', '.'); ?></td>

                    <td class="text-end"><?= number_format($v['MONTO_ORDENES'], 0, '', '.'); ?></td>
                    
                    <td class="text-end bg-disponible <?php echo ($v['TOTAL_DISPONIBLE'] < 0) ? 'text-danger fw-bold' : ''; ?>">
                        <?= number_format($v['TOTAL_DISPONIBLE'], 0, '', '.'); ?>
                    </td>
                    
                    <td>
                        <div class="d-flex gap-2 flex-wrap">
                            <?php if($v['VENCIDAS'] > 0) { ?>
                                <div class="card-vencidas">
                                    <small>Vencidas: <?= number_format($v['VENCIDAS'], 0, '', '.'); ?></small>
                                </div>
                            <?php } ?>
                            
                            <?php if($v['CHEQUES_10_DIAS'] > 0) { ?>
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
                    $monto_ordenes += $v['MONTO_ORDENES'];
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
                    <td class="text-end bg-cupo"></td>
                    <td class="text-end"><?= number_format($saldo_cc, 0, '', '.'); ?></td>
                    <td class="text-end"><?= number_format($total_cheques, 0, '', '.'); ?></td>
                    <td class="text-end bg-deuda"><?= number_format($total_deuda, 0, '', '.'); ?></td>
                    <td class="text-end"><?= number_format($monto_pedidos, 0, '', '.'); ?></td>
                    <td class="text-end"><?= number_format($monto_ordenes, 0, '', '.'); ?></td>
                    <td class="text-end bg-disponible <?php echo ($disponible < 0) ? 'text-danger' : ''; ?>"><?= number_format($disponible, 0, '', '.'); ?></td>
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

<!-- Incluir el modal -->
<?php include 'modal-evolucion-ppp.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script src="../js/main.js"></script>

<script>
function myFunction() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("textBox");
    filter = input.value.toUpperCase();
    table = document.getElementById("table");
    tr = table.getElementsByTagName("tr");
    
    for (i = 0; i < tr.length; i++) {
        var visible = false;
        // Skip header row
        if (tr[i].getElementsByTagName("th").length > 0) {
            continue;
        }
        
        // Loop through all cells in this row
        td = tr[i].getElementsByTagName("td");
        for (j = 0; j < td.length; j++) {
            if (td[j]) {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    visible = true;
                    break;
                }
            }
        }
        
        if (visible) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}

// Ensure table remains scrollable after loading
document.addEventListener('DOMContentLoaded', function() {
    // Force repaint of the table container to ensure proper scrolling
    const tableContainer = document.querySelector('.table-container');
    if (tableContainer) {
        tableContainer.style.display = 'none';
        setTimeout(() => {
            tableContainer.style.display = '';
        }, 5);
    }
});
</script>

</body>
</html>
<?php
}
?>