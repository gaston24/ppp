<?php 
session_start(); 

if(!isset($_SESSION['username']) || $_SESSION['permisos']!= 4 ){
    header("Location:../sistemas/login.php");
} else {
    // Incluir la clase Venta
    require_once '../Class/indicadores.php';
    $datosCreditoClientes = new Venta();
    
    // Obtener el número de recibo desde la URL
    $recibo = $_GET['recibo'];
    
    // Obtener los datos del recibo
    $result = $datosCreditoClientes->traerDetalleRecibo($recibo);
?>  
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle del Recibo</title>
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
            max-width: 1000px;
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
        
        .back-button {
            margin-bottom: 1rem;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Ancho de columnas */
        .col-fecha { width: 25%; }
        .col-importe { width: 25%; }
        .col-tipo-comp { width: 25%; }
        .col-num-comp { width: 25%; }
        
        /* Sin datos */
        .no-data {
            padding: 2rem;
            text-align: center;
            color: #6c757d;
        }
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
            <h5><i class="fas fa-receipt"></i> Detalle del Recibo: <?php echo htmlspecialchars($recibo); ?></h5>
            <button class="btn btn-sm btn-light" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Imprimir
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <?php if(count($result) > 0): ?>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr> 
                            <th class="col-fecha">FECHA VTO</th>
                            <th class="col-importe text-end">IMP A VENCER</th>
                            <th class="col-tipo-comp">T COMP A VENCER</th>
                            <th class="col-num-comp">N COMP A VENCER</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach($result as $v){
                    ?>
                        <tr>
                            <td><?php echo $v['FECHA_VTO'] instanceof DateTime ? $v['FECHA_VTO']->format('Y-m-d') : $v['FECHA_VTO']; ?></td>
                            <td class="text-end"><?php echo number_format($v['IMP_VTO'], 0, '', '.'); ?></td>
                            <td><?php echo $v['T_COMP']; ?></td>
                            <td><?php echo $v['N_COMP']; ?></td>
                        </tr>
                    <?php
                        }
                    ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h5>No se encontraron datos para este recibo</h5>
                    <p>El recibo <?php echo htmlspecialchars($recibo); ?> no tiene detalles registrados.</p>
                </div>
                <?php endif; ?>
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