<?php 
session_start(); 

if(!isset($_SESSION['username']) || $_SESSION['permisos']!= 4 ){

	header("Location:../sistemas/login.php");

}else{

$dsn = "1 - CENTRAL";
$user = "sa";
$pass = "Axoft1988";

$cid = odbc_connect($dsn, $user, $pass);

$sql="UPDATE SJ_PPP_EXCLUYE_CLIENTE SET SELEC = 0, EXCLUYE_PEDIDOS = 0";
ini_set('max_execution_time', 300);
odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));



if(isset($_POST['estado'])){
	
	foreach($_POST['estado'] as $a ){
		$sql="UPDATE SJ_PPP_EXCLUYE_CLIENTE SET SELEC = 1 WHERE COD_CLIENT = '$a'";
		ini_set('max_execution_time', 300);
		odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
	}

	foreach($_POST['habilitado'] as $a ){
		$sql="UPDATE SJ_PPP_EXCLUYE_CLIENTE SET EXCLUYE_PEDIDOS = 1 WHERE COD_CLIENT = '$a'";
		ini_set('max_execution_time', 300);
		odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
	}

}




}

header('Location: index.php');
?>