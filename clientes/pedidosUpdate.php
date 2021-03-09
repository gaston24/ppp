<?php

$codClient = $_POST['codClient'];

$dsn = "1 - CENTRAL";
$user = "sa";
$pass = "Axoft1988";



$cid = odbc_connect($dsn, $user, $pass);


$sql="
UPDATE SJ_PPP_EXCLUYE_CLIENTE SET EXCLUYE_PEDIDOS = (CASE WHEN EXCLUYE_PEDIDOS = 1 THEN 0 ELSE 1 END)
WHERE COD_CLIENT = '$codClient'
";


ini_set('max_execution_time', 300);
odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));


// echo 'estoy en el php con el codigo: '.$codClient;