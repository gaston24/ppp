<?php
// Prevenir acceso directo
if (!isset($_POST['codClient'])) {
    header('HTTP/1.0 403 Forbidden');
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
    exit;
}

// Capturar y sanitizar el código del cliente
$codClient = trim(htmlspecialchars($_POST['codClient']));

// Validar que el código no esté vacío
if (empty($codClient)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Código de cliente no válido']);
    exit;
}

try {
    // Incluir la clase de conexión
    require_once '../Class/indicadores.php';
    $ventaObj = new Venta();
    
    // Ejecutar la actualización
    $sql = "UPDATE SJ_PPP_EXCLUYE_CLIENTE SET SELEC = (CASE WHEN SELEC = 1 THEN 0 ELSE 1 END) WHERE COD_CLIENT = '$codClient'";
    $result = $ventaObj->ejecutarConsulta($sql);
    
    // Determinar el nuevo estado para informar
    $sqlCheck = "SELECT SELEC FROM SJ_PPP_EXCLUYE_CLIENTE WHERE COD_CLIENT = '$codClient'";
    $checkResult = $ventaObj->ejecutarConsulta($sqlCheck);
    
    if (isset($checkResult[0]['SELEC'])) {
        $nuevoEstado = $checkResult[0]['SELEC'] == 1 ? 'no se mostrará' : 'se mostrará';
        $mensaje = "El cliente $codClient $nuevoEstado en PPP";
    } else {
        $mensaje = "Estado actualizado correctamente";
    }
    
    // Devolver respuesta exitosa
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => $mensaje]);
    
} catch (Exception $e) {
    // Manejar errores
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>