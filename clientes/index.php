<?php 
session_start(); 

if(!isset($_SESSION['username']) || $_SESSION['username']!= 'directores' ){

	header("Location:../sistemas/login.php");

}else{
?>	
<!doctype html>
<html>
<head>
<title>PPP - Administrar Clientes</title>
<meta charset="utf-8">
<link rel="shortcut icon" href="../../../css/icono.jpg" />

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<link rel="stylesheet" href="style.css">

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
  
  <table class="table table-striped table-fh table-11c table-sm">
	
	<thead>
		<tr >
			<th style="width: 5%"><h6>CODIGO</h6></th>
			<th style="width: 15%"><h6>RAZON SOCIAL</h6></th>
			<th style="width: 5%"><h6>TILDE<br>NO MUESTRA<br>EN PPP</h6></th>
			<th style="width: 5%"><h6>HABILITADO<br>PEDIDOS</h6></th>
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
		<td style="width: 5%"> <input name="estado[]" type="checkbox" id="chkPpp" onClick="ppp('<?= $v['COD_CLIENT']?>')" <?php if($v['SELEC']==1){ echo 'checked'; }?> value="<?= $v['COD_CLIENT']?>"> </td>
		<td style="width: 5%"> <input name="habilitado[]" type="checkbox" id="chkPedido" onClick="pedidos('<?= $v['COD_CLIENT']?>')" <?php if($v['EXCLUYE_PEDIDOS']==1){ echo 'checked'; }?> value="<?= $v['COD_CLIENT']?>"> </td>
	</tr>
		
		
	<?php

}

?>

</tbody>
</div>
</table>

</div>

</div>

<script src="main.js"></script>
		
</body>
</html>

		
<?php
}
?>
