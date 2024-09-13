
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

}