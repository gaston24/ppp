<?php
// Asegúrate de incluir aquí cualquier lógica PHP necesaria
?>
<h2 class="text-center mb-3"><i class="bi bi-list me-2"></i>Menú</h2>
<div class="container">
    <div class="row">
        <!-- Ventas -->
        <div class="col-12 mb-3">
            <div class="card menu-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-cart-check menu-icon"></i>
                        <h5 class="card-title mb-0">Ventas Sucursales</h5>
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