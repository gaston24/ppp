
<?php

class Saldos
{

    public function traerSaldosCajas($fecha){
        try {

            $servidor_locales = 'LAKERBIS';
            $conexion_locales = array( "Database"=>"LOCALES_LAKERS", "UID"=>"sa", "PWD"=>"Axoft", "CharacterSet" => "UTF-8");
            $cid_central = sqlsrv_connect($servidor_locales, $conexion_locales);
             
         } catch (PDOException $e){
                 echo $e->getMessage();
         }

        $sql = "SELECT * FROM RO_V_AUDITORIA_CAJA_SUCURSALES WHERE FECHA = '$fecha'
        
        ";
        $stmt = sqlsrv_query( $cid_central, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        $json = json_encode($rows);
            
        return $json;
        // var_dump($json);

    }

}  