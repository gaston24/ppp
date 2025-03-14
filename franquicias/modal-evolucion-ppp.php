
<?php
// Esta función se ejecutará solo cuando este archivo se llame directamente vía AJAX
if (isset($_GET['cliente'])) {
    // Iniciar sesión
    session_start();
    
    if (!isset($_SESSION['username']) || $_SESSION['permisos'] != 4) {
        header("HTTP/1.0 403 Forbidden");
        echo json_encode(['success' => false, 'error' => 'Acceso no autorizado']);
        exit;
    }
    
    // Incluir la clase Venta
    require_once '../Class/indicadores.php';
    $datosCreditoClientes = new Venta();
    
    // Sanitizar el código del cliente
    $codCliente = preg_replace('/[^a-zA-Z0-9]/', '', $_GET['cliente']);
    
    try {
        // Obtener datos PPP del cliente
        $datosPPP = $datosCreditoClientes->obtenerEvolucionPPPCliente($codCliente);
        
        // Preparar datos para el gráfico
        $labels = [];
        $dataValues = [];
        $cantidadOps = [];
        
        foreach ($datosPPP as $dato) {
            // Formatear fechas para que sean más legibles
            $fecha = new DateTime($dato['MES'] . '-01');
            $mesFormateado = $fecha->format('M Y');
            
            $labels[] = $mesFormateado;
            $dataValues[] = $dato['PPP_PROMEDIO'];
            $cantidadOps[] = $dato['CANTIDAD_OPERACIONES'];
        }
        
        // Enviar respuesta como JSON
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'labels' => $labels,
            'values' => $dataValues,
            'operations' => $cantidadOps
        ]);
    } catch (Exception $e) {
        // Manejar errores
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    exit;
}
?>

<!-- Modal para Evolución PPP por Cliente -->
<div class="modal fade" id="modalEvolucionPPP" tabindex="-1" aria-labelledby="modalEvolucionPPPLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalEvolucionPPPLabel">
                    <i class="fas fa-chart-line me-2"></i>Evolución PPP - <span id="clienteNombre"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="loadingChart" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando datos del cliente...</p>
                </div>
                <div id="errorMessage" class="alert alert-danger mt-3" style="display:none;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span id="errorText">Error al cargar los datos.</span>
                </div>
                <div class="chart-container" style="position: relative; height:60vh; width:100%; display:none;">
                    <canvas id="pppChart"></canvas>
                </div>
                <div id="noDataMessage" class="alert alert-warning mt-3" style="display:none;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No hay datos de PPP disponibles para este cliente en los últimos 12 meses.
                </div>
                <div class="mt-3">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>PPP:</strong> Plazo Promedio de Pago (en días)
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnExportarGrafico" disabled>
                    <i class="fas fa-download me-2"></i>Exportar
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Este script solo se ejecutará una vez, incluso si se incluye múltiples veces
(function() {
    // Evitar inicializar el script si ya está inicializado
    if (window.pppEvolutionInitialized) return;
    window.pppEvolutionInitialized = true;
    
    // Cuando el documento esté cargado
    document.addEventListener('DOMContentLoaded', function() {
        // Referencia al modal
        const modalEvolucionPPP = document.getElementById('modalEvolucionPPP');
        if (!modalEvolucionPPP) return; // Salir si el modal no existe
        
        // Evento para cuando el modal se abre
        const modal = new bootstrap.Modal(modalEvolucionPPP);
        modalEvolucionPPP.addEventListener('show.bs.modal', function(event) {
            // Obtener el botón que abrió el modal
            const button = event.relatedTarget;
            
            // Obtener datos del cliente desde el botón
            const codCliente = button.getAttribute('data-cliente');
            const razonSocial = button.getAttribute('data-razon');
            
            // Actualizar el título del modal con la razón social del cliente
            document.getElementById('clienteNombre').textContent = razonSocial || codCliente;
            
            // Mostrar cargando, ocultar otros contenedores
            document.getElementById('loadingChart').style.display = 'block';
            document.getElementById('errorMessage').style.display = 'none';
            document.querySelector('.chart-container').style.display = 'none';
            document.getElementById('noDataMessage').style.display = 'none';
            document.getElementById('btnExportarGrafico').disabled = true;
            
            // Cargar datos de evolución PPP para este cliente
            cargarDatosPPPCliente(codCliente);
        });
        
        // Función para cargar datos del cliente
        function cargarDatosPPPCliente(codCliente) {
            console.log('Cargando datos para cliente:', codCliente);
            
            // Construir la URL del endpoint con el path actual
            const currentPath = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/') + 1);
            const endpointUrl = currentPath + 'modal-evolucion-ppp.php?cliente=' + encodeURIComponent(codCliente);
            
            console.log('Endpoint URL:', endpointUrl);
            
            fetch(endpointUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error HTTP: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('loadingChart').style.display = 'none';
                    
                    if (data.success && data.labels && data.labels.length > 0) {
                        // Mostrar contenedor del gráfico
                        document.querySelector('.chart-container').style.display = 'block';
                        
                        // Crear gráfico
                        crearGrafico(data.labels, data.values, data.operations);
                        
                        // Habilitar botón de exportación
                        document.getElementById('btnExportarGrafico').disabled = false;
                    } else {
                        // Mostrar mensaje de no hay datos
                        document.getElementById('noDataMessage').style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error al cargar datos:', error);
                    document.getElementById('loadingChart').style.display = 'none';
                    
                    // Mostrar mensaje de error
                    const errorMsg = document.getElementById('errorMessage');
                    document.getElementById('errorText').textContent = 'Error al cargar los datos: ' + error.message;
                    errorMsg.style.display = 'block';
                });
        }
        
        // Función para crear el gráfico
        function crearGrafico(labels, dataValues, cantidadOps) {
            // Referencia al canvas
            const ctx = document.getElementById('pppChart').getContext('2d');
            
            // Verificar si ya existe un gráfico y destruirlo
            if (window.pppChart instanceof Chart) {
                window.pppChart.destroy();
            }
            
            // Crear gradiente para el fondo del área
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(75, 192, 192, 0.5)');
            gradient.addColorStop(1, 'rgba(75, 192, 192, 0.1)');
            
            // Configuración del gráfico
            window.pppChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'PPP en días',
                            data: dataValues,
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.3
                        },
                        {
                            label: 'Cantidad de operaciones',
                            data: cantidadOps,
                            borderColor: 'rgb(153, 102, 255)',
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            fill: false,
                            tension: 0.3,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'PPP (días)',
                                color: 'rgb(75, 192, 192)',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                color: 'rgb(75, 192, 192)'
                            }
                        },
                        y1: {
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Cantidad de operaciones',
                                color: 'rgb(153, 102, 255)',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                color: 'rgb(153, 102, 255)'
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        if (context.datasetIndex === 0) {
                                            label += context.parsed.y.toFixed(2) + ' días';
                                        } else {
                                            label += context.parsed.y + ' operaciones';
                                        }
                                    }
                                    return label;
                                }
                            }
                        },
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Evolución del Plazo Promedio de Pago (PPP) - Últimos 12 meses'
                        }
                    }
                }
            });
        }
        
        // Evento para exportar el gráfico
        const btnExportarGrafico = document.getElementById('btnExportarGrafico');
        if (btnExportarGrafico) {
            btnExportarGrafico.addEventListener('click', function() {
                if (!window.pppChart) return;
                
                // Obtener el nombre del cliente para el nombre del archivo
                const clienteNombre = document.getElementById('clienteNombre').textContent;
                const nombreArchivo = 'PPP_' + clienteNombre.replace(/[^a-zA-Z0-9]/g, '_') + '_' + new Date().toISOString().slice(0, 10);
                
                // Crear una imagen del gráfico
                const url = document.getElementById('pppChart').toDataURL('image/png');
                
                // Crear un enlace temporal para descargar la imagen
                const link = document.createElement('a');
                link.download = nombreArchivo + '.png';
                link.href = url;
                link.click();
            });
        }
    });
})();
</script>