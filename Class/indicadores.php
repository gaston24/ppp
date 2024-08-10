
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
        $sql = "SET DATEFORMAT YMD

                    SELECT SUM(SALDO_CC)SALDO_CC, SUM(VENCIDAS)VENCIDAS, SUM(A_VENCER)A_VENCER, SUM(CHEQUES_10_DIAS)CHEQUES_10_DIAS, SUM(CHEQUE)CHEQUE
                    FROM
                    (

                    SELECT CASE COD_CLIENT WHEN 'FR' THEN 'FRANQUICIAS' WHEN 'MA' THEN 'MAYORISTAS' END COD_CLIENT, SALDO_CC, CHEQUE, CHEQUES_10_DIAS, TOTAL_DEUDA,
                    A_VENCER, VENCIDAS
                    FROM
                    (

                    SELECT LEFT(COD_CLIENTE, 2) COD_CLIENT, SUM(SALDO_CC)SALDO_CC, SUM(CHEQUE)CHEQUE, SUM(CHEQUES_10_DIAS)CHEQUES_10_DIAS, SUM(TOTAL_DEUDA)TOTAL_DEUDA,
                    SUM(A_VENCER)A_VENCER, SUM(VENCIDAS)VENCIDAS
                    FROM 
                    (

                    SELECT COD_CLIENTE, GRUPO_EMPR, CASE WHEN FECHA IS NULL THEN 'OK' ELSE 'NO' END PLAZO, RAZON_SOCIAL, PPP, CUPO_CRED, SALDO_CC, CHEQUE, CHEQUES_10_DIAS, TOTAL_DEUDA, 
                    (CUPO_CRED - TOTAL_DEUDA) TOTAL_DISPONIBLE, A_VENCER, VENCIDAS
                    FROM
                    (
                        SELECT COD_CLIENTE, D.FECHA, RAZON_SOCIAL, GRUPO_EMPR, CAST(PPP AS INT)PPP, CAST(CUPO_CREDI AS int) CUPO_CRED, 
                        CAST(SALDO_CC AS INT)SALDO_CC, 
                        CAST(CASE WHEN B.CHEQUES IS NULL THEN 0 ELSE B.CHEQUES END AS INT)CHEQUE, 
                        CAST(CASE WHEN C.CHEQUES_PRONTO IS NULL THEN 0 ELSE C.CHEQUES_PRONTO END AS INT)CHEQUES_10_DIAS, 
                        CAST((SALDO_CC+(CASE WHEN B.CHEQUES IS NULL THEN 0 ELSE B.CHEQUES END)) AS INT) TOTAL_DEUDA, SUM(A_VENCER)A_VENCER, SUM(A.VENCIDAS)VENCIDAS
                        FROM
                        (
                            SELECT COD_CLIENTE, RAZON_SOCIAL, B.GRUPO_EMPR, C.NOMBRE_GRU , 
                            CAST(AVG(PPP) AS decimal(10,2)) PPP, CAST(AVG(DIAS) AS INT) DIAS, B.CUPO_CREDI, B.SALDO_CC, D.A_VENCER, D.VENCIDAS
                            FROM GC_VIEW_PPP A
                            INNER JOIN GVA14 B
                            ON A.COD_CLIENTE = B.COD_CLIENT
                            LEFT JOIN GVA62 C
                            ON B.GRUPO_EMPR = C.GRUPO_EMPR
                            LEFT JOIN SJ_SALDOS_CC D
                            ON A.COD_CLIENTE = D.COD_CLIENT
                            WHERE B.COD_CLIENT LIKE '[FM][AR]%'
                            AND FECHA_RECIBO >= GETDATE()-365
                            GROUP BY COD_CLIENTE, RAZON_SOCIAL, B.GRUPO_EMPR, C.NOMBRE_GRU, B.CUPO_CREDI, B.SALDO_CC, D.A_VENCER, D.VENCIDAS
                        )A
                        LEFT JOIN
                        (SELECT CLIENTE, SUM(IMPORTE_CH)CHEQUES FROM SBA14 WHERE FECHA_CHEQ >= GETDATE() AND ESTADO NOT IN ('X', 'R') GROUP BY CLIENTE) B
                        ON A.COD_CLIENTE = B.CLIENTE
                        LEFT JOIN
                        (SELECT CLIENTE, SUM(IMPORTE_CH)CHEQUES_PRONTO FROM SBA14 WHERE (FECHA_CHEQ >= GETDATE() AND FECHA_CHEQ <= GETDATE()+10) AND ESTADO NOT IN ('X', 'R') GROUP BY CLIENTE) C
                        ON A.COD_CLIENTE = C.CLIENTE
                        LEFT JOIN
                        (SELECT FECHA, COD_CLIENT FROM (SELECT MIN(FECHA_EMIS)FECHA, COD_CLIENT FROM GVA12 A WHERE FECHA_EMIS >= GETDATE()-45 
                        AND COD_CLIENT LIKE '[FM][AR]%' AND A.T_COMP = 'FAC' AND ESTADO = 'PEN' GROUP BY COD_CLIENT )A WHERE FECHA < GETDATE()-30)D
                        ON A.COD_CLIENTE = D.COD_CLIENT
                        GROUP BY COD_CLIENTE, D.FECHA, RAZON_SOCIAL, GRUPO_EMPR, PPP, CUPO_CREDI, SALDO_CC, (CASE WHEN B.CHEQUES IS NULL THEN 0 ELSE B.CHEQUES END), 
                        (CASE WHEN C.CHEQUES_PRONTO IS NULL THEN 0 ELSE C.CHEQUES_PRONTO END), ((SALDO_CC+(CASE WHEN B.CHEQUES IS NULL THEN 0 ELSE B.CHEQUES END)))
                    )A

                    )A

                    GROUP BY LEFT(COD_CLIENTE, 2)
                    )A

                    )A";
        return $this->retornarArray($sql);
    }

    public function traerImportes(){
        $sql = "SET DATEFORMAT YMD
                SELECT CANAL, CAST(MES_ACTUAL AS INT)IMPORTE_ACTUAL, CAST(MES_ANTERIOR AS INT)IMPORTE_ANTERIOR FROM [LAKERBIS].[LOCALES_LAKERS].[dbo].SJ_FACTURACION_PPP_DIARIA
                ORDER BY 1";
        return $this->retornarArray($sql);
    }

}