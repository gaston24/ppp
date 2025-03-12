<?php 
session_start(); 

if(!isset($_SESSION['username']) || $_SESSION['permisos']!= 4 ){
    header("Location:../sistemas/login.php");
} else {
    // Incluir la clase Venta
    require_once '../Class/indicadores.php';
    $datosCreditoClientes = new Venta();
    
    // Obtener lista de clientes
    $result = $datosCreditoClientes->traerClientesAdmin();
?>  
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PPP - Administrar Clientes</title>
    <link rel="shortcut icon" href="../../../css/icono.jpg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Importante: Agregar jQuery explícitamente ya que main.js lo utiliza -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 1.5rem;
            background-color: #f8f9fa;
        }
        
        .admin-header {
            background: linear-gradient(135deg, #3a6073 0%, #16222a 100%);
            color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .admin-header h4 {
            margin: 0;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
        }
        
        .admin-header i {
            margin-right: 12px;
            font-size: 1.6rem;
        }
        
        .table-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            overflow: hidden;
        }
        
        .table-responsive {
            max-height: calc(100vh - 250px);
            overflow-y: auto;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #f0f2f5;
            font-weight: 600;
            font-size: 0.9rem;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            padding: 1rem 0.75rem;
            text-align: center;
            vertical-align: middle;
        }
        
        .table tbody td {
            vertical-align: middle;
            padding: 0.75rem;
            font-size: 0.95rem;
        }
        
        .form-check {
            display: flex;
            justify-content: center;
            margin: 0;
        }
        
        .form-check-input {
            width: 1.2rem;
            height: 1.2rem;
            cursor: pointer;
        }
        
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0,0,0,0.02);
        }
        
        .navigation {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        
        .search-box {
            display: flex;
            margin-bottom: 1.5rem;
        }
        
        .search-box .form-control {
            max-width: 300px;
            margin-right: 10px;
        }
        
        /* Switch checkbox style */
        .form-switch {
            padding-left: 2.5em;
        }
        
        .form-switch .form-check-input {
            width: 2em;
            margin-left: -2.5em;
        }
        
        /* Estilo para notificaciones */
        #notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .notification {
            margin-bottom: 10px;
            padding: 12px 15px;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            min-width: 250px;
            transition: opacity 0.3s ease-in-out;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="admin-header">
        <h4><i class="fas fa-users-cog"></i> Administración de Clientes</h4>
        <div class="navigation">
            <div class="btn-group" role="group">
                <a href="../ppp.php" class="btn btn-light">
                    <i class="fas fa-home me-2"></i>Inicio
                </a>
                <a href="../franquicias/ppp.php" class="btn btn-light">
                    <i class="fas fa-store me-2"></i>Franquicias
                </a>
                <a href="../mayoristas/index.php" class="btn btn-light">
                    <i class="fas fa-warehouse me-2"></i>Mayoristas
                </a>
            </div>
        </div>
    </div>
    
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="search-box">
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar cliente...">
                <button class="btn btn-primary" onclick="searchClient()">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <div class="col-md-6 text-end">
            <div class="form-text text-muted">
                <i class="fas fa-info-circle me-1"></i> 
                Seleccione las casillas para habilitar/deshabilitar clientes
            </div>
        </div>
    </div>
    
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="clientTable">
                <thead>
                    <tr>
                        <th style="width: 15%">CÓDIGO</th>
                        <th style="width: 50%">RAZÓN SOCIAL</th>
                        <th style="width: 17.5%">NO MOSTRAR<br>EN PPP</th>
                        <th style="width: 17.5%">HABILITAR<br>PEDIDOS</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach($result as $v){
                ?>
                    <tr>
                        <td class="text-center"><?= $v['COD_CLIENT'] ?></td>
                        <td><?= $v['RAZON_SOCI'] ?></td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                    id="ppp_<?= $v['COD_CLIENT'] ?>" 
                                    onclick="ppp('<?= $v['COD_CLIENT'] ?>')" 
                                    <?php if($v['SELEC']==1){ echo 'checked'; } ?>>
                            </div>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                    id="pedido_<?= $v['COD_CLIENT'] ?>" 
                                    onclick="pedidos('<?= $v['COD_CLIENT'] ?>')" 
                                    <?php if($v['EXCLUYE_PEDIDOS']==1){ echo 'checked'; } ?>>
                            </div>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Contenedor de notificaciones -->
<div id="notification-container"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Función para buscar cliente
function searchClient() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toUpperCase();
    const table = document.getElementById('clientTable');
    const tr = table.getElementsByTagName('tr');
    
    for (let i = 0; i < tr.length; i++) {
        if (tr[i].getElementsByTagName('td').length > 0) {
            const tdCode = tr[i].getElementsByTagName('td')[0];
            const tdName = tr[i].getElementsByTagName('td')[1];
            if (tdCode || tdName) {
                const codeValue = tdCode.textContent || tdCode.innerText;
                const nameValue = tdName.textContent || tdName.innerText;
                if (codeValue.toUpperCase().indexOf(filter) > -1 || nameValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = '';
                } else {
                    tr[i].style.display = 'none';
                }
            }
        }
    }
}

// Permitir búsqueda con Enter
document.getElementById('searchInput').addEventListener('keyup', function(event) {
    if (event.key === 'Enter') {
        searchClient();
    }
});

/**
 * Muestra una notificación temporal en pantalla
 * @param {string} title - Título de la notificación
 * @param {string} message - Mensaje a mostrar
 * @param {string} type - Tipo de notificación ('success', 'error', 'warning', 'info')
 */
function showNotification(title, message, type = 'info') {
    // Verificar si ya existe el contenedor de notificaciones
    let notifContainer = document.getElementById('notification-container');
    if (!notifContainer) {
        notifContainer = document.createElement('div');
        notifContainer.id = 'notification-container';
        document.body.appendChild(notifContainer);
    }
    
    // Crear notificación
    const notification = document.createElement('div');
    notification.className = 'notification ' + type;
    notification.style.backgroundColor = type === 'success' ? '#d4edda' : 
                                         type === 'error' ? '#f8d7da' : 
                                         type === 'warning' ? '#fff3cd' : '#d1ecf1';
    notification.style.color = type === 'success' ? '#155724' : 
                               type === 'error' ? '#721c24' : 
                               type === 'warning' ? '#856404' : '#0c5460';
    notification.style.border = '1px solid ' + (type === 'success' ? '#c3e6cb' : 
                                               type === 'error' ? '#f5c6cb' : 
                                               type === 'warning' ? '#ffeeba' : '#bee5eb');
    notification.style.borderRadius = '4px';
    notification.style.padding = '10px 15px';
    notification.style.marginBottom = '10px';
    notification.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
    notification.style.minWidth = '250px';
    notification.style.transition = 'opacity 0.3s ease-in-out';
    
    // Añadir contenido
    notification.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <strong>${title}</strong>
                <div>${message}</div>
            </div>
            <button type="button" style="background: none; border: none; cursor: pointer; font-size: 16px; font-weight: bold;">&times;</button>
        </div>
    `;
    
    // Añadir al contenedor
    notifContainer.appendChild(notification);
    
    // Configurar botón de cierre
    const closeBtn = notification.querySelector('button');
    closeBtn.addEventListener('click', function() {
        notification.style.opacity = '0';
        setTimeout(() => {
            notifContainer.removeChild(notification);
        }, 300);
    });
    
    // Auto-cerrar después de 5 segundos
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
            if (notification.parentNode === notifContainer) {
                notifContainer.removeChild(notification);
            }
        }, 300);
    }, 5000);
}

// Implementación directa de las funciones principales aquí para evitar problemas de carga
function ppp(clientId) {
    // Mostrar indicador visual de carga
    const checkbox = document.getElementById('ppp_' + clientId);
    const originalState = checkbox.checked;
    
    // Deshabilitar temporalmente el checkbox durante la actualización
    checkbox.disabled = true;
    
    $.ajax({
        url: 'pppUpdate.php',
        method: 'POST',
        data: {
            codClient: clientId
        },
        success: function(response) {
            try {
                // Intentar parsear la respuesta como JSON
                const data = typeof response === 'string' ? JSON.parse(response) : response;
                
                // Verificar si la respuesta es exitosa
                if (data && data.success) {
                    // Si hay mensaje, mostrarlo
                    if (data.message) {
                        showNotification('Éxito', data.message, 'success');
                    } else {
                        showNotification('Éxito', 'Cliente actualizado correctamente', 'success');
                    }
                } else {
                    // Revertir si hay error
                    checkbox.checked = !originalState;
                    showNotification('Error', data.message || 'No se pudo actualizar el estado', 'error');
                    console.error('Error en la actualización:', data);
                }
            } catch (e) {
                // Error al parsear JSON
                console.error('Error al procesar la respuesta:', e, response);
                checkbox.checked = !originalState;
                showNotification('Error', 'Error al procesar la respuesta del servidor', 'error');
            }
        },
        error: function(xhr, status, error) {
            // Revertir el checkbox en caso de error
            checkbox.checked = !originalState;
            showNotification('Error', 'Error en la actualización: ' + error, 'error');
            console.error('Error en la actualización:', error, xhr.responseText);
        },
        complete: function() {
            // Habilitar el checkbox nuevamente
            checkbox.disabled = false;
        }
    });
}

function pedidos(clientId) {
    // Mostrar indicador visual de carga
    const checkbox = document.getElementById('pedido_' + clientId);
    const originalState = checkbox.checked;
    
    // Deshabilitar temporalmente el checkbox durante la actualización
    checkbox.disabled = true;
    
    $.ajax({
        url: 'pedidosUpdate.php',
        method: 'POST',
        data: {
            codClient: clientId
        },
        success: function(response) {
            try {
                // Intentar parsear la respuesta como JSON
                const data = typeof response === 'string' ? JSON.parse(response) : response;
                
                // Verificar si la respuesta es exitosa
                if (data && data.success) {
                    // Si hay mensaje, mostrarlo
                    if (data.message) {
                        showNotification('Éxito', data.message, 'success');
                    } else {
                        showNotification('Éxito', 'Estado de pedidos actualizado correctamente', 'success');
                    }
                } else {
                    // Revertir si hay error
                    checkbox.checked = !originalState;
                    showNotification('Error', data.message || 'No se pudo actualizar el estado', 'error');
                    console.error('Error en la actualización:', data);
                }
            } catch (e) {
                // Error al parsear JSON
                console.error('Error al procesar la respuesta:', e, response);
                checkbox.checked = !originalState;
                showNotification('Error', 'Error al procesar la respuesta del servidor', 'error');
            }
        },
        error: function(xhr, status, error) {
            // Revertir el checkbox en caso de error
            checkbox.checked = !originalState;
            showNotification('Error', 'Error en la actualización: ' + error, 'error');
            console.error('Error en la actualización:', error, xhr.responseText);
        },
        complete: function() {
            // Habilitar el checkbox nuevamente
            checkbox.disabled = false;
        }
    });
}

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página de administración de clientes cargada correctamente');
});
</script>
</body>
</html>
<?php
}
?>