<?php 
session_start(); 

if(!isset($_SESSION['username']) || $_SESSION['permisos']!= 4 ){
    header("Location:../sistemas/login.php");
} else {
    // Incluir la clase Venta
    require_once '../Class/indicadores.php';
    $datosCreditoClientes = new Venta();
    
    // Obtener el código del grupo de cliente desde la URL
    $grupoCliente = $_GET['cliente'];
    
    // Obtener los datos del detalle de cheques
    $result = $datosCreditoClientes->traerDetalleChequesGrupo($grupoCliente);
    
    // Obtener el nombre del grupo usando la función específica para grupos
    $nombreGrupo = "";
    if (method_exists($datosCreditoClientes, 'obtenerDatosGrupo')) {
        $infoGrupo = $datosCreditoClientes->obtenerDatosGrupo($grupoCliente);
        if (!empty($infoGrupo) && isset($infoGrupo[0]['NOMBRE_GRU'])) {
            $nombreGrupo = $infoGrupo[0]['NOMBRE_GRU'];
        }
    }
?>  
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle Cheques por Grupo</title>
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
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .cheque-link {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .cheque-link:hover {
            color: #0a58ca;
            text-decoration: underline;
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
        
        .group-name {
            margin-left: 10px;
            font-size: 1rem;
            font-weight: normal;
            opacity: 0.9;
            background-color: rgba(255, 255, 255, 0.2);
            padding: 4px 8px;
            border-radius: 4px;
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
                <i class="fas fa-money-check"></i> 
                Detalle Cheques del Grupo: <?php echo htmlspecialchars($grupoCliente); ?>
                <?php if(!empty($nombreGrupo)): ?>
                    <span class="group-name"><?php echo htmlspecialchars($nombreGrupo); ?></span>
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
                                <th>CÓDIGO</th>
                                <th>CLIENTE</th>
                                <th>TOTAL CHEQUES</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $total_ch = 0;
                            foreach($result as $v){
                                $total_ch += $v['TOTAL_CHEQUES'];
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $v['COD_CLIENT']; ?></td>
                                <td><?php echo $v['RAZON_SOCI']; ?></td>
                                <td class="text-end">
                                    <a href="pppCheque.php?cliente=<?php echo urlencode($v['COD_CLIENT']); ?>" class="cheque-link">
                                        <?php echo number_format($v['TOTAL_CHEQUES'], 0, '', '.'); ?>
                                    </a>
                                </td>
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
                    <p>No hay cheques registrados para este grupo de cliente.</p>
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