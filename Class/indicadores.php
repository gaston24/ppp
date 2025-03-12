
<?php

class Venta
{
    private $cid;
    private $cid_central;

    function __construct()
    {
        require_once $_SERVER['DOCUMENT_ROOT'].'/comercial/abastecimiento/Class/Conexion.php';
        $this->cid = new Conexion();
        $this->cid_central = $this->cid->conectar('central');
    } 

    private function retornarArray($sqlEnviado){
        $sql = $sqlEnviado;
        $stmt = sqlsrv_query($this->cid_central, $sql);
        $rows = array();
        while ($v = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $rows[] = $v;
        }
        return $rows;  
    }

    public function traerIndicadores(){
        $sql = "SET DATEFORMAT YMD SELECT * FROM RO_PPP_OPTIMIZADO";
        return $this->retornarArray($sql);
    }

    public function traerIndicadoresTotal(){
        $sql = "SET DATEFORMAT YMD 
                SELECT SUM(SALDO_CC) SALDO_CC, SUM(VENCIDAS) VENCIDAS, SUM(A_VENCER) A_VENCER, SUM(CHEQUES_10_DIAS) CHEQUES_10_DIAS, 
                    SUM(CHEQUE) CHEQUE, SUM(TOTAL_DEUDA) TOTAL_DEUDA
                FROM RO_PPP_OPTIMIZADO";
        return $this->retornarArray($sql);
    }

    public function traerImportes(){
        $sql = "SET DATEFORMAT YMD
                SELECT CANAL, CAST(MES_ACTUAL AS INT)IMPORTE_ACTUAL, CAST(MES_ANTERIOR AS INT)IMPORTE_ANTERIOR FROM [LAKERBIS].[LOCALES_LAKERS].[dbo].SJ_FACTURACION_PPP_DIARIA
                ORDER BY 1";
        return $this->retornarArray($sql);
    }

    public function traerDatosCredito(){
        $sql = "SET DATEFORMAT YMD
                EXEC SJ_PPP_DETALLADO_FR";
        return $this->retornarArray($sql);
    }

   
    /**
     * Obtiene el detalle de un cliente específico
     * @param string $cliente Código del cliente
     * @return array Arreglo con los datos del detalle del cliente
     */
    public function traerDetalleCliente($cliente){
        $sql = "SET DATEFORMAT YMD
                EXEC SJ_PPP_DETALLADO_FR_CLIENTE '$cliente'";
        return $this->retornarArray($sql);
    }

    /**
     * Obtiene el detalle de un recibo específico
     * @param string $recibo Número de recibo
     * @return array Arreglo con los datos del detalle del recibo
     */
    public function traerDetalleRecibo($recibo){
        $sql = "SET DATEFORMAT YMD
                EXEC SJ_PPP_DETALLADO_FR_CLIENTE_REC '$recibo'";
        return $this->retornarArray($sql);
    }

    /**
     * Obtiene los datos de los clientes que pertenecen a un grupo específico
     * @param string $codCliente Código del cliente grupo
     * @return array Arreglo con los datos de los clientes del grupo
     */
    public function traerDetalleGrupo($codCliente){
        $sql = "SET DATEFORMAT YMD
                EXEC SJ_PPP_DETALLADO_FR_GRUPO '$codCliente'";
        return $this->retornarArray($sql);
    }

    /**
     * Obtiene la lista de clientes para administrar
     * @return array Arreglo con los datos de clientes a administrar
     */
    public function traerClientesAdmin(){
        $sql = "SET DATEFORMAT YMD
                SELECT * FROM SJ_PPP_EXCLUYE_CLIENTE
                ORDER BY COD_CLIENT";
        return $this->retornarArray($sql);
    }

    /**
     * Ejecuta una consulta SQL de cualquier tipo (SELECT, UPDATE, INSERT, DELETE)
     * @param string $sql Consulta SQL a ejecutar
     * @return array|bool Arreglo con resultados en caso de SELECT, true/false en otros casos
     */
    public function ejecutarConsulta($sql) {
        // Si es una consulta SELECT, usar retornarArray
        if (stripos(trim($sql), 'SELECT') === 0) {
            return $this->retornarArray($sql);
        }
        
        // Para otros tipos de consultas (UPDATE, INSERT, DELETE)
        $stmt = sqlsrv_query($this->cid_central, $sql);
        
        if ($stmt === false) {
            $errores = sqlsrv_errors();
            throw new Exception('Error en la consulta SQL: ' . ($errores ? $errores[0]['message'] : 'Error desconocido'));
        }
        
        // Confirmar cambios
        sqlsrv_commit($this->cid_central);
        
        return true;
    }

    /**
     * Obtiene el detalle de cheques por grupo de cliente
     * @param string $grupoCliente Código del grupo de cliente
     * @return array Arreglo con los datos del detalle de cheques por cliente
     */
    public function traerDetalleChequesGrupo($grupoCliente){
        $sql = "SELECT COD_CLIENT, RAZON_SOCI, SUM(IMPORTE_CH) AS TOTAL_CHEQUES
                FROM (
                    SELECT B.COD_CLIENT, B.RAZON_SOCI, B.GRUPO_EMPR, 
                        CAST(FECHA_CHEQ AS DATE) AS FECHA_CHEQ, 
                        N_COMP_REC, CAST(IMPORTE_CH AS INT) AS IMPORTE_CH 
                    FROM SBA14 A
                    INNER JOIN GVA14 B ON A.CLIENTE = B.COD_CLIENT
                    WHERE FECHA_CHEQ >= GETDATE() AND ESTADO NOT IN ('X', 'R') 
                    AND B.GRUPO_EMPR = '$grupoCliente'
                ) A
                GROUP BY COD_CLIENT, RAZON_SOCI
                ORDER BY COD_CLIENT";
        return $this->retornarArray($sql);
    }

    /**
     * Obtiene el detalle de cheques para un cliente específico
     * @param string $cliente Código del cliente
     * @return array Arreglo con los datos de los cheques del cliente
     */
    public function traerDetalleChequeCliente($cliente){
        $sql = "SELECT CAST(FECHA_CHEQ AS DATE) AS FECHA_CHEQ, 
                    N_COMP_REC, 
                    CAST(IMPORTE_CH AS INT) AS IMPORTE_CH 
                FROM SBA14 
                WHERE FECHA_CHEQ >= GETDATE() 
                AND ESTADO NOT IN ('X', 'R') 
                AND CLIENTE = '$cliente'
                ORDER BY FECHA_CHEQ";
        return $this->retornarArray($sql);
    }

    /**
     * Obtiene el nombre/razón social de un cliente a partir de su código
     * @param string $codigoCliente Código del cliente
     * @return array Arreglo con la información del cliente
     */
    public function obtenerNombreCliente($codigoCliente){
        $sql = "SELECT COD_CLIENT, RAZON_SOCI, DOMICILIO, LOCALIDAD
                FROM GVA14
                WHERE COD_CLIENT = '$codigoCliente'";
        return $this->retornarArray($sql);
    }

    /**
     * Obtiene la información de un grupo específico
     * @param string $codigoGrupo Código del grupo
     * @return array Arreglo con la información del grupo
     */
    public function obtenerDatosGrupo($codigoGrupo){
        $sql = "SELECT GRUPO_EMPR, NOMBRE_GRU 
                FROM GVA62
                WHERE GRUPO_EMPR = '$codigoGrupo'";
        return $this->retornarArray($sql);
    }

}