<?php 
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:../sistemas/login.php");

}else{
?>	
<!doctype html>
<html>
<head>
<title>Detalle cliente</title>
<?php include '../../css/header.php'; ?>
</head>
<body>


<div >
<?php

$dsn = "1 - CENTRAL";
$user = "sa";
$pass = "Axoft1988";

$cliente = $_GET['cliente'];

$cid = odbc_connect($dsn, $user, $pass);


$sql="
SET DATEFORMAT YMD

EXEC SJ_PPP_DETALLADO_FR_CLIENTE '$cliente'

";

$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>



<div style="width:100%">
  
  <table class="table table-striped table-fh table-6c">
	<thead>
		<tr> 
			<th> FECHA  </th>
			<th> TIPO COMP  </th>
			<th> COMPROBANTE  </th>
			<th> IMPORTE RECIBO  </th>
			<th> IMPORTE IMPUTADO  </th>
			<th> PPP  </th>
		</tr>
	</thead>
<?php

	$imp_rec = 0;
	$imp_imp = 0;
	$ppp = 0;
	$ppp_cont = 0;

while($v=odbc_fetch_array($result)){

	?>

	<div >
		
		<tr>
		
		<td align="left"> <?php echo $v['FECHA'] ;?> </td>
		
		<td align="left"> <?php echo $v['T_COMP'] ;?> </td>
		
		<td align="left"><a href="pppDetalleRec.php?recibo=<?php echo $v['N_COMP'] ; ?>"</a> <?php echo $v['N_COMP'] ;?> </td>

		<td align="left"> <?php echo number_format($v['IMPORTE_RECIBO'], 0, '', '.') ;?> </td>
		
		<td align="left"> <?php echo number_format($v['IMPORTE_IMPUTADO'], 0, '', '.') ;?> </td>
		
		<td align="left"> <?php echo number_format($v['PPP'], 0, '', '.') ;?> </td>
		
		</tr>
		
	

	<?php
	
	$imp_rec += $v['IMPORTE_RECIBO'];
	$imp_imp += $v['IMPORTE_IMPUTADO'];
	$ppp += $v['PPP'];
	$ppp_cont += 1;
}

	$ppp_avg = $ppp/$ppp_cont;
?>
	<tr>
	<td align="left"></td>
	<td align="left"></td>
	<td align="left"><h5>TOTAL</h5></td>
	<td align="left"><h5><?php echo number_format($imp_rec, 0, '', '.') ;?></h5></td>
	<td align="left"><h5><?php echo number_format($imp_imp, 0, '', '.') ;?></h5></td>
	<td align="left"><h5><?php echo number_format($ppp_avg, 0, '', '.') ;?></h5></td>
	</tr>

	</div>


</table>

</div>

</div>


		
</body>
</html>

<?php
}
?>
