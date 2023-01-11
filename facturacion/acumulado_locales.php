<?php 
session_start(); 

if(!isset($_SESSION['username']) || $_SESSION['permisos']!= 4 ){

	header("Location:../../sistemas/login.php");

}else{
?>
	
<!doctype html>

<head>
<title>PPP - Locales</title>
<?php include '../../css/header.php'; ?>
</head>
<body>


<div class="container-fluid">

<?php

$dsn = "LOCALES";
$user = "sa";
$pass = "Axoft";



$cid = odbc_connect($dsn, $user, $pass);
$sql= "
SET DATEFORMAT YMD

SELECT A.*, B.DESC_SUCURSAL FROM SJ_FACTURACION_PPP_CANAL_DIARIA A
INNER JOIN SUCURSAL B
ON A.COD_CLIENT = B.NRO_SUCURSAL
WHERE COD_CLIENT LIKE '[0-9]%' 
ORDER BY CAST(COD_CLIENT AS INT) 
";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
?>

<div style="width:100%">
  
  <table class="table table-striped table-fh table-9c">
	
	<thead>
		<tr >
			<th style="width: 5%">CODIGO</th>
			<th style="width: 15%">NOMBRE</th>
			<th style="width: 5%">MES<br>ACTUAL</th>
			<th style="width: 5%">MES<br>AÑO PASADO</th>
			<th style="width: 5%">DIF MES</th>
			<th style="width: 5%">AÑO<br>ACTUAL</th>
			<th style="width: 5%">AÑO<br>ANTERIOR</th>
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

			<td style="width: 5%"> <?php echo $v['COD_CLIENT'];?> </td>
			
			<td style="width: 15%; font-size:10pt"> <?php echo $v['DESC_SUCURSAL'];?> </td>
			
			<td style="width: 5%"> <?php echo number_format($v['MES_ACTUAL'], 0, '', ',') ;?> </td>
			
			<td style="width: 5%"> <?php echo number_format($v['MES_ANTERIOR'], 0, '', ',') ;?> </td>
			
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

			<td style="width: 5%"></td>
			<td style="width: 15%"><h5 align="center">TOTAL</h5></td>
			<td style="width: 5%"><h5><?php echo number_format($mes_ac, 0, '', ',') ;?></h5></td>
			<td style="width: 5%"><h5><?php echo number_format($mes_an, 0, '', ',') ;?></h5></td>
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