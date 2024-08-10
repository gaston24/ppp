<?php
// Asegúrate de incluir aquí cualquier lógica PHP necesaria
?>
<h2 class="text-center mb-3">Menú</h2>
<div class="container">
    <div class="row">
        <!-- Ventas -->
        <div class="col-12 mb-3">
            <div class="card menu-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-cart-check menu-icon"></i>
                        <h5 class="card-title mb-0">Ventas</h5>
                    </div>
                    <i class="bi bi-plus-lg"></i>
                </div>
                <div class="card-body" style="display: none;">
                    <div class="sub-card p-2">
                        <a href="../comercial/sucursales/ventasPropios.php"><i class="bi bi-shop me-2"></i>Locales propios</a>
                    </div>
                    <div class="sub-card p-2">
                        <a href="../comercial/sucursales/ventasFranquicias.php"><i class="bi bi-building me-2"></i>Franquicias</a>
                    </div>
                    <div class="sub-card p-2">
                        <a href="../comercial/sucursales/ventasUruguay.php"><i class="bi bi-globe-americas me-2"></i></i>Uruguay</a>
                    </div>
                    <div class="sub-card p-2">
                        <a href="../comercial/sucursales/ventasTodos.php"><i class="bi bi-grid me-2"></i>Todos</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comparar -->
        <div class="col-12 mb-3">
            <div class="card menu-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-bar-chart menu-icon"></i>
                        <h5 class="card-title mb-0">Comparar</h5>
                    </div>
                    <i class="bi bi-plus-lg"></i>
                </div>
                <div class="card-body" style="display: none;">
                    <div class="sub-card p-2">
                        <a href="../ventas/localesComp"><i class="bi bi-shop-window me-2"></i>Locales propios</a>
                    </div>
                    <div class="sub-card p-2">
                        <a href="../ventas/franquiciasComp"><i class="bi bi-building-fill me-2"></i>Franquicias</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Crédito -->
        <div class="col-12 mb-3">
            <div class="card menu-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-credit-card menu-icon"></i>
                        <h5 class="card-title mb-0">Crédito</h5>
                    </div>
                    <i class="bi bi-plus-lg"></i>
                </div>
                <div class="card-body" style="display: none;">
                    <div class="sub-card p-2">
                        <a href="ppp.php"><i class="bi bi-shop me-2"></i>Plazo promedio de pago</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Índices -->
        <div class="col-12 mb-3">
            <div class="card menu-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-graph-up menu-icon"></i>
                        <h5 class="card-title mb-0">Índices</h5>
                    </div>
                    <i class="bi bi-plus-lg"></i>
                </div>
                <div class="card-body" style="display: none;">
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
        <div class="col-12 mb-3">
            <div class="card menu-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clipboard-data menu-icon"></i>
                        <h5 class="card-title mb-0">Tableros</h5>
                    </div>
                    <i class="bi bi-plus-lg"></i>
                </div>
                <div class="card-body" style="display: none;">
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
        <div class="col-12 mb-3">
            <div class="card menu-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-app menu-icon"></i>
                        <h5 class="card-title mb-0">Aplicaciones</h5>
                    </div>
                    <i class="bi bi-plus-lg"></i>
                </div>
                <div class="card-body" style="display: none;">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
   
<script>

// Wrapping the JavaScript in a function to be called after the content is loaded
function initializeMenuToggle() {
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

    // Remove existing click listeners to avoid duplicates
    document.querySelectorAll('.card-header').forEach(header => {
        header.removeEventListener('click', function() {
            toggleCard(this);
        });
    });

    // Add click listeners to all card headers
    document.querySelectorAll('.card-header').forEach(header => {
        header.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default action
            event.stopPropagation(); // Stop event bubbling
            toggleCard(this);
        });
    });

    // Initially hide all card bodies
    document.querySelectorAll('.card-body').forEach(body => {
        body.style.display = "none";
    });
}

// Call the function immediately if the DOM is already loaded
if (document.readyState === "complete" || document.readyState === "interactive") {
    initializeMenuToggle();
} else {
    // Otherwise, add an event listener to call the function when the DOM is loaded
    document.addEventListener('DOMContentLoaded', initializeMenuToggle);
}

// Notify the main page that the menu content has been loaded and initialized
window.parent.postMessage('menuLoaded', '*');

</script>