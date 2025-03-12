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
    
    // Obtener los datos de cheques del cliente
    $result = $datosCreditoClientes->traerDetalleChequeCliente($cliente);
    
    // Obtener el nombre del cliente si está disponible
    $nombreCliente = "";
    if (method_exists($datosCreditoClientes, 'obtenerNombreCliente')) {
        $infoCliente = $datosCreditoClientes->obtenerNombreCliente($cliente);
        if (!empty($infoCliente) && isset($infoCliente[0]['RAZON_SOCI'])) {
            $nombreCliente = $infoCliente[0]['RAZON_SOCI'];
        }
    }
?>  
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle Cheques</title>
    <?php include '../../css/header_simple.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            max-width: 900px;
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
            margin-right: 15px;
            font-size: 1.5rem;
        }
        
        .card-body {
            padding: 0;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #f0f2f5;
            font-weight: 600;
            font-size: 0.9rem;
            color: #495057;
            padding: 0.85rem 0.75rem;
            text-align: center;
            border-bottom: 2px solid #dee2e6;
        }
        
        .table td {
            vertical-align: middle;
            padding: 0.75rem;
            font-size: 0.95rem;
        }
        
        .table-responsive {
            max-height: calc(100vh - 220px);
            overflow-y: auto;
        }
        
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0,0,0,0.02);
        }
        
        .total-row {
            background-color: #f8d7da !important;
            color: #721c24;
            font-weight: 600;
        }
        
        .back-button {
            margin-bottom: 1rem;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        /* Ancho de columnas */
        .col-fecha { width: 30%; }
        .col-recibo { width: 40%; }
        .col-importe { width: 30%; }
        
        /* Badges para fechas próximas */
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            margin-left: 0.5rem;
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
            <h5>
                <i class="fas fa-money-check-alt"></i> 
                Detalle Cheques: <?php echo htmlspecialchars($cliente); ?>
                <?php if(!empty($nombreCliente)): ?>
                    <span class="ms-2 small">- <?php echo htmlspecialchars($nombreCliente); ?></span>
                <?php endif; ?>
            </h5>
            <button class="btn btn-sm btn-light" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Imprimir
            </button>
        </div>
        <div class="card-body">
            <?php if(count($result) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="col-fecha">FECHA CHEQUE</th>
                                <th class="col-recibo">RECIBO</th>
                                <th class="col-importe">IMPORTE</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $total_ch = 0;
                            $hoy = new DateTime();
                            
                            foreach($result as $v){
                                $total_ch += $v['IMPORTE_CH'];
                                
                                // Verificar si la fecha está en formato DateTime
                                $fecha_cheque = $v['FECHA_CHEQ'];
                                if($fecha_cheque instanceof DateTime) {
                                    $fecha_formateada = $fecha_cheque->format('Y-m-d');
                                    
                                    // Calcular días entre hoy y la fecha del cheque
                                    $intervalo = $hoy->diff($fecha_cheque);
                                    $dias_diferencia = $intervalo->days;
                                } else {
                                    $fecha_formateada = $fecha_cheque;
                                    $dias_diferencia = null;
                                }
                        ?>
                            <tr>
                                <td>
                                    <?php echo $fecha_formateada; ?>
                                    <?php if($dias_diferencia !== null && $dias_diferencia <= 10): ?>
                                        <span class="badge-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            <?php echo $dias_diferencia; ?> días
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $v['N_COMP_REC']; ?></td>
                                <td class="text-end"><?php echo number_format($v['IMPORTE_CH'], 0, '', '.'); ?></td>
                            </tr>
                        <?php
                            }
                        ?>
                            <tr class="total-row">
                                <td colspan="2" class="text-end">Total cheques</td>
                                <td class="text-end"><?php echo number_format($total_ch, 0, '', '.'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <h4>No se encontraron cheques</h4>
                    <p>No hay cheques registrados para este cliente.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
}
?>