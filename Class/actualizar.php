<?php


class actualizar
{

    private function ejecutarQuery($sqlEnviado)
    {

        require_once 'Conexion.php';

        $cid = new Conexion();
        $cid_central = $cid->conectar();
        $sql = $sqlEnviado;

        $stmt = sqlsrv_query($cid_central, $sql);
        sqlsrv_execute($stmt);
    }

    public function update()
    {

        try {
            //code...
            $sql = "EXEC SJ_PPP_DETALLADO_FR_ACTUA";

            return $this->ejecutarQuery($sql);
        } catch (Exception $th) {
            //throw $th;
            return $th;
        }
    }
}

if($_GET['estado']==1)
{
$a=new actualizar();

$a->update();
}