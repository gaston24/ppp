
<?php
// Incluye aquí la lógica para obtener los datos de ventas
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

</div>