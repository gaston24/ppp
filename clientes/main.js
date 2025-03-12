/**
 * Función para actualizar el estado de visibilidad en PPP
 * @param {string} clientId - Código del cliente a actualizar
 */
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
            // Verificar si la respuesta es exitosa
            if (response && response.success) {
                // Si hay mensaje, mostrarlo
                if (response.message) {
                    showNotification('Éxito', response.message, 'success');
                }
            } else {
                // Revertir si hay error
                checkbox.checked = !originalState;
                showNotification('Error', 'No se pudo actualizar el estado', 'error');
            }
        },
        error: function(xhr, status, error) {
            // Revertir el checkbox en caso de error
            checkbox.checked = !originalState;
            showNotification('Error', 'Error en la actualización: ' + error, 'error');
            console.error('Error en la actualización:', error);
        },
        complete: function() {
            // Habilitar el checkbox nuevamente
            checkbox.disabled = false;
        }
    });
}

/**
 * Función para actualizar el estado de habilitación de pedidos
 * @param {string} clientId - Código del cliente a actualizar
 */
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
            // Verificar si la respuesta es exitosa
            if (response && response.success) {
                // Si hay mensaje, mostrarlo
                if (response.message) {
                    showNotification('Éxito', response.message, 'success');
                }
            } else {
                // Revertir si hay error
                checkbox.checked = !originalState;
                showNotification('Error', 'No se pudo actualizar el estado', 'error');
            }
        },
        error: function(xhr, status, error) {
            // Revertir el checkbox en caso de error
            checkbox.checked = !originalState;
            showNotification('Error', 'Error en la actualización: ' + error, 'error');
            console.error('Error en la actualización:', error);
        },
        complete: function() {
            // Habilitar el checkbox nuevamente
            checkbox.disabled = false;
        }
    });
}

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
        notifContainer.style.position = 'fixed';
        notifContainer.style.top = '20px';
        notifContainer.style.right = '20px';
        notifContainer.style.zIndex = '9999';
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