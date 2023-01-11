<?php 
session_start(); 

if(!isset($_SESSION['username']) || $_SESSION['permisos']!= 4 ){

	header("Location:../../sistemas/login.php");

}else{
?>
<!doctype html>
<html>
<head>
<?php 
include '../../../css/header.php'; 
$sem = $_GET['sem'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
?>
<title>Sem - <?php echo $desde;?> al <?php echo $hasta;?></title>
</head>
<body>


<div >
<?php

$dsn = "LOCALES";
$user = "sa";
$pass = "Axoft";

$anio = $_GET['anio'];

$cid = odbc_connect($dsn, $user, $pass);


$sql="
SET DATEFORMAT YMD

SELECT * FROM SJ_BI_DIA_HISTORIAL_LOCALES
WHERE ANIO = '$anio' AND SEMANA = '$sem'

ORDER BY NRO_SUCURS

";


ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>

<div style="width:100%">
  
  <table class="table table-striped table-fh table-9c">
	
	<thead>
		<tr >
			<th style="width: 4%">NRO</th>
			<th style="width: 18%">SUCURSAL</th>			
			<th style="width: 10%" align="center">VENTAS<br>CON IVA</th>
			<th style="width: 8%" align="center">CANT<br>COMP</th>
			<th style="width: 8%">ARTICULOS</th>
			<th style="width: 8%" align="center">PROM<br>TICKET</th>
			<th style="width: 6%" align="center">2DO<br>PROD</th>
			<th style="width: 6%" align="center">PROM<br>2DO</th>
			<th style="width: 6%" align="center">3ER<br>PROD</th>
			<th style="width: 6%" align="center">PROM<br>3ER</th>
			<th style="width: 7%">CAMBIOS</th>
			<th style="width: 7%" align="center">%<br>CAMBIOS</th>
			<th style="width: 7%" align="center">%<br>INCREM</th>
		</tr>
	</thead>

	<div >
	<tbody >
<?php

	$importe = 0;
	$articulos = 0;
	$comp = 0;
	$cant_2do = 0;
	$cant_3er = 0;
	$cant_cambios = 0;
	$increm = 0;
	$cont= 0;

 while($v=odbc_fetch_array($result)){

	?>
	<tr>

		<td style="width: 4%"> <?php echo $v['NRO_SUCURS'];?> </td>
		<td style="width: 18%"> <?php echo $v['DESC_SUCURSAL'];?> </td>
		<td style="width: 10%"> <?php echo number_format($v['IMPORTE'], 0, '', '.'); ?> </td>
		<td style="width: 8%"> <?php echo number_format($v['COMP'], 0, '', '.') ;?> </td>
		<td style="width: 8%"> <?php echo number_format($v['ARTICULOS'], 0, '', '.') ;?> </td>
		<td style="width: 8%"> <?php echo (int)($v['PROM_TICKET']) ;?> </td>
		<td style="width: 6%"> <?php echo number_format($v['CANT_TICKET_2DO'], 0, '', '.') ; ?> </td>
		<td style="width: 6%"> <?php echo $v['PROM_2DO'] ;?> </td>
		<td style="width: 6%"> <?php echo number_format($v['CANT_TICKET_3ER'], 0, '', '.') ;?> </td>
		<td style="width: 6%"> <?php echo $v['PROM_3ER'] ;?> </td>
		<td style="width: 7%"> <?php echo number_format($v['CANT_CAMBIOS'], 0, '', '.') ;?> </td>
		<td style="width: 7%"> <?php echo $v['PORC_CAMBIOS'] ;?> </td>
		<td style="width: 7%"> <?php echo $v['PORC_INCREM'] ;?> </td>
			
	</tr>
		
		
	<?php
	
	$importe	 += $v['IMPORTE'];
	$articulos	 += $v['ARTICULOS'];
	$comp		 += $v['COMP'];
	$cant_2do	 += $v['CANT_TICKET_2DO'];
	$cant_3er	 += $v['CANT_TICKET_3ER'];
	$cant_cambios+= $v['CANT_CAMBIOS'];
	$increm		 += $v['PORC_INCREM'];
	$cont++;
 }

?>
	<tr>
		<td style="width: 4%"><h6></h6></td>
		<td style="width: 18%"><h6>TOTAL</h6></td>
		<td style="width: 10%"><h6><?php echo number_format($importe, 0, '', '.') ;?></h6></td>
		<td style="width: 8%"><h6><?php echo number_format($comp, 0, '', '.') ;?></h6></td>
		<td style="width: 8%"><h6><?php echo number_format($articulos, 0, '', '.') ;?></h6></td>
		<td style="width: 8%"><h6><?php echo (int)($importe/$comp) ;?></h6></td>
		<td style="width: 6%"><h6><?php echo number_format($cant_2do, 0, '', '.') ;?></h6></td>
		<td style="width: 6%"><h6><?php echo number_format((($cant_2do/$comp)*100), 1, ',', '.') ;?></h6></td>
		<td style="width: 6%"><h6><?php echo number_format($cant_3er, 0, '', '.') ;?></h6></td>
		<td style="width: 6%"><h6><?php echo number_format((($cant_3er/$comp)*100), 1, ',', '.') ;?></h6></td>
		<td style="width: 7%"><h6><?php echo number_format($cant_cambios, 0, '', '.') ;?></h6></td>
		<td style="width: 7%"><h6><?php echo number_format((($cant_cambios/$comp)*100), 1, ',', '.') ;?></h6></td>
		<td style="width: 7%"><h6><?php echo number_format(($increm/$cont), 1, ',', '.') ;?></h6></td>
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