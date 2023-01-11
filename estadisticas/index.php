<?php 
session_start(); 

if(!isset($_SESSION['username']) || $_SESSION['permisos']!= 4 ){

	header("Location:../../sistemas/login.php");

}else{
?>
	
<!doctype html>
<html>
<head>
<?php include '../../css/header.php'; ?>
<title>Estadisticas</title>
</head>
<body>


<div >
<?php

$dsn = "LOCALES";
$user = "sa";
$pass = "Axoft";



$cid = odbc_connect($dsn, $user, $pass);


$sql="
SET DATEFORMAT YMD

SELECT MES, CAST(SUM(IMPORTE) AS INT) IMPORTE, SUM(ARTICULOS)ARTICULOS, SUM(COMP)COMP,
CAST(SUM(IMPORTE)/SUM(COMP) AS DECIMAL(10,2))PROM_TICKET, 
SUM(CANT_TICKET_2DO)CANT_TICKET_2DO, CAST(CAST(SUM(CANT_TICKET_2DO) AS FLOAT)/CAST(SUM(COMP) AS FLOAT)*100AS DECIMAL(10,2)) PROM_2DO,
SUM(CANT_TICKET_3ER)CANT_TICKET_3ER, CAST(CAST(SUM(CANT_TICKET_3ER) AS FLOAT)/CAST(SUM(COMP) AS FLOAT)*100AS DECIMAL(10,2)) PROM_3ER,
SUM(CANT_CAMBIOS)CANT_CAMBIOS, CAST(CAST(SUM(CANT_CAMBIOS) AS FLOAT)/CAST(SUM(COMP) AS FLOAT)*100AS DECIMAL(10,2)) PORC_CAMBIOS,
CAST(AVG(PORC_INCREM) AS DECIMAL(10,2))PORC_INCREM
FROM SJ_BI_MES A
WHERE LEFT(MES, 4) LIKE DATEPART(YEAR, GETDATE())
GROUP BY MES
ORDER BY 1

";


ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>

<div style="width:100%">
  
  <table class="table table-striped table-fh table-9c">
	
	<thead>
		<tr >
			<th style="width: 10%">MES / <a href="detalle_sem.php">SEM</a></th>
			<th style="width: 10%">VENTAS<br>CON IVA</th>
			<th style="width: 8%">CANT<br>COMP</th>
			<th style="width: 8%">ARTICULOS</th>
			<th style="width: 8%">PROM<br>TICKET</th>
			<th style="width: 8%">2DO PROD</th>
			<th style="width: 8%">PROM 2DO</th>
			<th style="width: 8%">3ER PROD</th>
			<th style="width: 8%">PROM 3ER</th>
			<th style="width: 8%">CAMBIOS</th>
			<th style="width: 8%">% CAMBIOS</th>
			<th style="width: 8%">% INCREM</th>
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

		<td style="width: 10%"> <a href="detalle_mes.php?mes=<?php echo $v['MES']; ?>"> <?php echo $v['MES'];?> </a> </td>
		<td style="width: 10%"> <?php echo number_format($v['IMPORTE'], 0, '', ','); ?> </td>
		<td style="width: 8%"> <?php echo number_format($v['COMP'], 0, '', ',') ;?> </td>
		<td style="width: 8%"> <?php echo number_format($v['ARTICULOS'], 0, '', ',') ;?> </td>
		<td style="width: 8%"> <?php echo (int)($v['PROM_TICKET']) ;?> </td>
		<td style="width: 8%"> <?php echo number_format($v['CANT_TICKET_2DO'], 0, '', '.') ; ?> </td>
		<td style="width: 8%"> <?php echo $v['PROM_2DO'] ;?> </td>
		<td style="width: 8%"> <?php echo number_format($v['CANT_TICKET_3ER'], 0, '', '.') ;?> </td>
		<td style="width: 8%"> <?php echo $v['PROM_3ER'] ;?> </td>
		<td style="width: 8%"> <?php echo number_format($v['CANT_CAMBIOS'], 0, '', '.') ;?> </td>
		<td style="width: 8%"> <?php echo $v['PORC_CAMBIOS'] ;?> </td>
		<td style="width: 8%"> <?php echo $v['PORC_INCREM'] ;?> </td>
			
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
		<td style="width: 10%"><h6>TOTAL</h6></td>
		<td style="width: 10%"><h6><?php echo number_format($importe, 0, '', ',') ;?></h6></td>
		<td style="width: 8%"><h6><?php echo number_format($comp, 0, '', ',') ;?></h6></td>
		<td style="width: 8%"><h6><?php echo number_format($articulos, 0, '', ',') ;?></h6></td>
		<td style="width: 8%"><h6><?php echo (int)($importe/$comp) ;?></h6></td>
		<td style="width: 8%"><h6><?php echo number_format($cant_2do, 0, '', ',') ;?></h6></td>
		<td style="width: 8%"><h6><?php echo number_format((($cant_2do/$comp)*100), 1, ',', '.') ;?></h6></td>
		<td style="width: 8%"><h6><?php echo number_format($cant_3er, 0, '', '.') ;?></h6></td>
		<td style="width: 8%"><h6><?php echo number_format((($cant_3er/$comp)*100), 1, ',', '.') ;?></h6></td>
		<td style="width: 8%"><h6><?php echo number_format($cant_cambios, 0, '', '.') ;?></h6></td>
		<td style="width: 8%"><h6><?php echo number_format((($cant_cambios/$comp)*100), 1, ',', '.') ;?></h6></td>
		<td style="width: 8%"><h6><?php echo number_format(($increm/$cont), 1, ',', '.') ;?></h6></td>
	</tr>
</tbody>
</div>
</table>

</div>

</div>




<h2 align="center">Años Anteriores</h2></br>

<div class="row">
	<div class="col-3">
	</div>
	<div class="col-6" >
		<div class="row justify-content-md-center">
			<!-- <button type="button" class="btn btn-primary ml-1 mr-1" onclick="location.href='historial/historial_index.php?anio=2016'">2016</button>
			<button type="button" class="btn btn-secondary ml-1 mr-1" onclick="location.href='historial/historial_index.php?anio=2017'">2017</button>
			<button type="button" class="btn btn-primary ml-1 mr-1" onclick="location.href='historial/historial_index.php?anio=2018'">2018</button>
			<button type="button" class="btn btn-secondary ml-1 mr-1" onclick="location.href='historial/historial_index.php?anio=2019'">2019</button>
			<button type="button" class="btn btn-primary ml-1 mr-1" onclick="location.href='historial/historial_index.php?anio=2020'">2020</button>
            <button type="button" class="btn btn-primary ml-1 mr-1" onclick="location.href='historial/historial_index.php?anio=2021'">2021</button>
                        -->
				<?php

					$firstYear = date("Y") - 5;
					$currentYear = date("Y");

					$status = 1;


					for($i = $firstYear ; $i < $currentYear; $i++){

						$botton = ($status % 2 == 0) ? 'secondary': 'primary';

						echo '<button type="button" class="btn btn-'.$botton.' ml-1 mr-1" onclick="location.href=\'historial/historial_index.php?anio='.$i.'\'">'.$i.'</button>';
						
						$status++;
					}

				?>
		</div>
	</div>
	<div class="col-3">
	</div>
</div>






		
</body>
</html>

	
<?php
}
?>