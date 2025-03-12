
<?php 
session_start(); 

if(!isset($_SESSION['username']) || $_SESSION['permisos']!= 4 ){
    header("Location:../sistemas/login.php");
} else {
    // Incluir la clase Venta
    require_once '../Class/indicadores.php';
    $datosCreditoClientes = new Venta();
    
    // Obtener el código del cliente desde la URL
    $cliente = $_GET['cliente'];
    
    // Obtener los datos del cliente
    $result = $datosCreditoClientes->traerDetalleCliente($cliente);
?>  
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle Cliente</title>
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
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
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
        
        .table {
            margin-bottom: 0;
            width: 100%;
            max-width: 100%;
            table-layout: fixed;
        }
        
        .table th {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #f0f2f5;
            font-weight: 600;
            font-size: 0.9rem;
            white-space: nowrap;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            padding: 0.85rem 0.75rem;
        }
        
        .table td {
            vertical-align: middle;
            padding: 0.75rem;
            font-size: 0.95rem;
        }
        
        .table-container {
            overflow-x: auto;
            max-height: calc(100vh - 220px);
            overflow-y: auto;
        }
        
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0,0,0,0.02);
        }
        
        .comprobante-link {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .comprobante-link:hover {
            text-decoration: underline;
            color: #0a58ca;
        }
        
        .totals-row {
            font-weight: 600;
            background-color: #e9ecef !important;
        }
        
        .totals-row td {
            border-top: 2px solid #adb5bd;
        }
        
        .back-button {
            margin-bottom: 1rem;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Ancho de columnas */
        .col-fecha { width: 12%; }
        .col-tipo { width: 12%; }
        .col-comp { width: 20%; }
        .col-importe { width: 18%; }
        .col-imputado { width: 18%; }
        .col-ppp { width: 10%; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="back-button">
        <a href="javascript:history.back()" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-user"></i> Detalle Cliente: <?php echo htmlspecialchars($cliente); ?></h5>
            <button class="btn btn-sm btn-light" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Imprimir
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr> 
                            <th class="col-fecha">FECHA</th>
                            <th class="col-tipo">TIPO COMP</th>
                            <th class="col-comp">COMPROBANTE</th>
                            <th class="col-importe text-end">IMPORTE RECIBO</th>
                            <th class="col-imputado text-end">IMPORTE IMPUTADO</th>
                            <th class="col-ppp text-end">PPP</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $imp_rec = 0;
                        $imp_imp = 0;
                        $ppp = 0;
                        $ppp_cont = 0;

                        foreach($result as $v){
                    ?>
                        <tr>
                            <td><?php echo $v['FECHA'] instanceof DateTime ? $v['FECHA']->format('Y-m-d') : $v['FECHA']; ?></td>
                            <td><?php echo $v['T_COMP']; ?></td>
                            <td>
                                <a href="pppDetalleRec.php?recibo=<?php echo urlencode($v['N_COMP']); ?>" class="comprobante-link">
                                    <?php echo $v['N_COMP']; ?>
                                </a>
                            </td>
                            <td class="text-end"><?php echo number_format($v['IMPORTE_RECIBO'], 0, '', '.'); ?></td>
                            <td class="text-end"><?php echo number_format($v['IMPORTE_IMPUTADO'], 0, '', '.'); ?></td>
                            <td class="text-end"><?php echo number_format($v['PPP'], 0, '', '.'); ?></td>
                        </tr>
                    <?php
                        $imp_rec += $v['IMPORTE_RECIBO'];
                        $imp_imp += $v['IMPORTE_IMPUTADO'];
                        $ppp += $v['PPP'];
                        $ppp_cont += 1;
                    }
                    
                    $ppp_avg = ($ppp_cont > 0) ? $ppp/$ppp_cont : 0;
                    ?>
                        <tr class="totals-row">
                            <td colspan="2"></td>
                            <td><strong>TOTAL</strong></td>
                            <td class="text-end"><strong><?php echo number_format($imp_rec, 0, '', '.'); ?></strong></td>
                            <td class="text-end"><strong><?php echo number_format($imp_imp, 0, '', '.'); ?></strong></td>
                            <td class="text-end"><strong><?php echo number_format($ppp_avg, 0, '', '.'); ?></strong></td>
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