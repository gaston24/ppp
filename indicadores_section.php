
<?php
// Incluye aquí la lógica para obtener los datos de ventas e indicadores
require_once 'Class/indicadores.php';
$indicadores = new Venta();
$indicadoresTodos = $indicadores->traerIndicadores();

// Asumimos que traerIndicadores() devuelve un array con un solo elemento
$indicador = $indicadoresTodos[0];

?>

<style>
    .container-fluid {
        padding-left: 5px;  /* Reducido de 10px */
        padding-right: 5px; /* Reducido de 10px */
    }
    .sales-card {
        border: none;
        border-radius: 10px;
        background-color: #6f42c1;
        padding: 1.2rem;
        color: white;
        margin-left: 0.2rem;
        margin-right: 0.2rem;
        margin-bottom: 2px; /* Aumentado para dar más espacio a la sombra */
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3), 0 6px 6px rgba(0, 0, 0, 0.23);
        position: relative;
        z-index: 1;
    }
    .sales-card p {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 0;
    }
    .custom-col {
        padding-left: 2px;
        padding-right: 2px;
    }
</style>

<h3 class="text-center mb-3"><I class="bi bi-graph-up  me-2"></I>Indicadores Contables</h3>
<div class="row g-2">

    <!-- Saldo Cuenta Corriente -->
    <div class="col-12 mb-2">
        <div class="sales-card">
            <h2><i class="bi bi-wallet2 me-2"></i>Saldo Cuenta Corriente</h2>
            <p><?php echo "$".number_format($indicador['SALDO_CC'], 0, ',', '.'); ?></p>
        </div>
    </div>

    <!-- Facturas Vencidas -->
    <div class="col-12 mb-2">
        <div class="sales-card">
            <h3><i class="bi bi-exclamation-triangle me-2"></i>Facturas Vencidas</h3>
            <p><?php echo "$".number_format($indicador['VENCIDAS'], 0, ',', '.'); ?></p>
        </div>
    </div>

    <!-- Facturas a Vencer -->
    <div class="col-12 mb-2">
        <div class="sales-card">
            <h3><i class="bi bi-calendar-check me-2"></i>Facturas a Vencer</h3>
            <p><?php echo "$".number_format($indicador['A_VENCER'], 0, ',', '.'); ?></p>
        </div>
    </div>

    <!-- Cheques -->
    <div class="col-12 mb-2">
        <div class="sales-card">
            <h3><i class="bi bi-credit-card me-2"></i>Cheques</h3>
            <p><?php echo "$".number_format($indicador['CHEQUE'], 0, ',', '.'); ?></p>
        </div>
    </div>

    <!-- Cheques a 10 días -->
    <div class="col-12 mb-2">
        <div class="sales-card">
            <h3><i class="bi bi-calendar2-week me-2"></i>Cheq. a 10 días</h3>
            <p><?php echo "$".number_format($indicador['CHEQUES_10_DIAS'], 0, ',', '.'); ?></p>
        </div>
    </div>

</div>