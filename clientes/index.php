<?php 
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:../sistemas/login.php");

}else{
?>	
<!doctype html>
<html>
<head>
<?php include '../../css/header.php'; ?>
<title>PPP - Administrar Clientes</title>

<style>
	
table.table-fh {
    width: 100%;
}
table.table-fh, table.table-fh > tbody > tr > td {
    border-collapse: collapse;
    border: 1px solid #000;
}
table.table-fh > thead {
    display: table;
    width: calc(100% - 17px);
}
table.table-fh > tbody {
    display: block;
    max-height: 75vh;
    overflow-y: scroll;
}

table.table-fh.table-11c > thead > tr >th, table.table-fh.table-11c > tbody > tr > td {
    width: calc(100% / 11);
}

table.table-fh > thead > tr >th, table.table-fh > tbody > tr > td {
    padding: 5px;
    word-break: break-all;
    text-align: left;
}
table.table-fh > tbody > tr {
    display: table;
    width: 100%;
}
table.table-fh > tbody > tr > td {
    border: none;
}
body {
	overflow-y: hidden;
}

</style>

</head>
<body>


<div >
<?php

$dsn = "1 - CENTRAL";
$user = "sa";
$pass = "Axoft1988";



$cid = odbc_connect($dsn, $user, $pass);


$sql="
SET DATEFORMAT YMD

SELECT * FROM SJ_PPP_EXCLUYE_CLIENTE

ORDER BY COD_CLIENT
";


ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>

<div class="container">

<div class="row">  
	<div class="col"></div>
	<div class="col">
		<div class="btn-group mt-1 mb-1" role="group" aria-label="Basic example" >
			<button type="button" class="btn btn-secondary" onclick="location.href='../ppp.php'">Inicio</button>
			<button type="button" class="btn btn-secondary" onclick="location.href='../franquicias/ppp.php'">Franquicias</button>
			<button type="button" class="btn btn-secondary" onclick="location.href='../mayoristas/index.php'">Mayoristas</button>
		</div>
	</div>
	<div class="col"></div>
</div>
  
  <form action="procesar_clientes.php" method="post">
  <table class="table table-striped table-fh table-11c table-sm">
	
	<thead>
		<tr >
			<th style="width: 5%"><h6>CODIGO</h6></th>
			<th style="width: 15%"><h6>RAZON SOCIAL</h6></th>
			<th style="width: 5%"><h6>TILDE<br>NO MUESTRA<br>EN PPP</h6></th>
			<th style="width: 5%"><h6>HABILITADO<br>PEDIDOS</h6></th>
			<th style="width: 5%"><input type="submit" value="Grabar" class="btn btn-outline-success"></th>
		</tr>
	</thead>

	<div >
	<tbody >
<?php

while($v=odbc_fetch_array($result)){

	?>
	<tr>
		<td style="width: 5%"> <a> <?= $v['COD_CLIENT']?> </a></td>
		<td style="width: 15%"> <?= $v['RAZON_SOCI']?> </td>
		<td style="width: 5%"> <input name="estado[]" type="checkbox" <?php if($v['SELEC']==1){ echo 'checked'; }?> value="<?= $v['COD_CLIENT']?>"> </td>
		<td style="width: 5%"> <input name="habilitado[]" type="checkbox" <?php if($v['EXCLUYE_PEDIDOS']==1){ echo 'checked'; }?> value="<?= $v['COD_CLIENT']?>"> </td>
		<td style="width: 5%"> </td>
	</tr>
		
		
	<?php

}

?>

</tbody>
</div>
</table>
</form>

</div>

</div>


		
</body>
</html>

		
<?php
}
?>
