<?php 
session_start(); 

if(!isset($_SESSION['username']) || $_SESSION['username']!= 'directores' ){

	header("Location:../../sistemas/login.php");

}else{
?>
	
<!doctype html>

<head>
<title>Canal - Ecommerce</title>
<?php include '../css/header.php'; ?>
</head>
<body>

</br>
<div class="container-fluid">

<h2 align="center">Ecommerce</h2></br>

<?php

$dsn = "LOCALES";
$user = "sa";
$pass = "Axoft";

$cid = odbc_connect($dsn, $user, $pass);
$sql= "
SET DATEFORMAT YMD

SELECT * FROM SJ_FACTURACION_PPP ORDER BY 1
";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
?>
<br>
<div style="width:100%">
  
  <table class="table table-striped table-fh table-9c">
	
	<thead>
		<tr >
			<th style="width: 5%">CANAL</th>
			<th style="width: 5%">MES ACTUAL</th>
			<th style="width: 5%">MES AÑO PASADO</th>
			<th style="width: 5%">DIF MES</th>
			<th style="width: 5%">AÑO ACTUAL</th>
			<th style="width: 5%">AÑO ANTERIOR</th>
			<th style="width: 5%">DIF AÑO</th>
			
		</tr>
	</thead>

	<div >
	<tbody >
<?php

	$mes_ac = 0;
	$mes_an = 0;
	$anio_ac = 0;
	$anio_an = 0;


 while($v=odbc_fetch_array($result)){

	?>
	<tr>

			<td style="width: 5%"> <?php echo $v['CANAL'];?> </td>
			
			<td style="width: 5%"> <?php echo number_format($v['MES_ACTUAL'], 0, '', '.') ;?> </td>
			
			<td style="width: 5%"> <?php echo number_format($v['MES_ANTERIOR'], 0, '', '.') ;?> </td>
			
			<td style="width: 5%"> <?php echo number_format($v['DIF_MES'], 0, '', '.').' %' ;?> </td>
			
			<td style="width: 5%"> <?php echo number_format($v['ANIO_ACTUAL'], 0, '', '.') ;?> </td>
			
			<td style="width: 5%"> <?php echo number_format($v['ANIO_ANTERIOR'], 0, '', '.') ;?> </td>
			
			<td style="width: 5%"> <?php echo number_format($v['DIF_ANIO'], 0, '', '.').' %' ;?> </td>
			
		</tr>

	<?php
	
	$mes_ac += $v['MES_ACTUAL'];
	$mes_an += $v['MES_ANTERIOR'];
	$anio_ac += $v['ANIO_ACTUAL'];
	$anio_an += $v['ANIO_ANTERIOR'];

	
 }

?>

	<tr>

			<td style="width: 5%"><h5 align="center">TOTAL</h5></td>
			<td style="width: 5%"><h5><?php echo number_format($mes_ac, 0, '', '.') ;?></h5></td>
			<td style="width: 5%"><h5><?php echo number_format($mes_an, 0, '', '.') ;?></h5></td>
			<td style="width: 5%"><h5><?php echo number_format((($mes_ac/$mes_an)-1)*100, 2, ',', '.').' %' ;?></h5></td>
			<td style="width: 5%"><h5><?php echo number_format($anio_ac, 0, '', '.') ;?></h5></td>
			<td style="width: 5%"><h5><?php echo number_format($anio_an, 0, '', '.') ;?></h5></td>
			<td style="width: 5%"><h5><?php echo number_format((($anio_ac/$anio_an)-1)*100, 2, ',', '.').' %' ;?></h5></td>
	</tr>	
	
</tbody>
</div>
</table>

</div>


</div>

</body>
</html>

<?php
}
?>