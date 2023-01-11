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
<title>PPP - Franq</title>
</head>
<body>

<div>
<?php

$dsn = "1 - CENTRAL";
$user = "sa";
$pass = "Axoft1988";



$cid = odbc_connect($dsn, $user, $pass);


$sql="
SET DATEFORMAT YMD

EXEC SJ_PPP_DETALLADO_FR

";


ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>
<?php include '../agrega_clientes.php'?>

	<div class="ml-2 mt-2 mb-4" id="busqRapida">
		<label id="textBusqueda">Busqueda rapida:</label>
		<input type="text" id="textBox"  placeholder="Sobre cualquier campo..." onkeyup="myFunction()"  class="form-control form-control-sm" style="max-width: 40vh;"></input>  
	</div>

<div>
  
  <table class="table table-striped table-fh table-11c">
	
	<thead>
		<tr >
			<th style="width: 5%"><h6>CLIENTE</h6></th>
			<th style="width: 15%"><h6>RAZON SOCIAL</h6></th>
			<th style="width: 5%"><h6>PPP<br>12 meses</h6></th>
			<th style="width: 5%"><h6>CUPO<br>CRED</h6></th>
			<th style="width: 5%"><h6>SALDO<br>CC</h6></th>
			<th style="width: 5%"><h6>VENCIDAS</h6></th>
			<th style="width: 5%"><h6>PEDIDOS<br>ABIERTOS</h6></th>
			<th style="width: 5%"><h6>TOTAL<br>CHEQUES</h6></th>
			<th style="width: 5%"><h6>CHEQUES<br>10 DIAS</h6></th>
			<th style="width: 5%"><h6>TOTAL<br>DEUDA</h6></th>
			<th style="width: 5%"><h6>DISPONIBLE</h6></th>
		</tr>
	</thead>

	<div >
	<tbody id="table" style="max-height: 80vh;">
<?php

	$saldo_cc = 0;
	$vencidas = 0;
	$monto_pedidos = 0;
	$total_cheques = 0;
	$cheques_diez = 0;
	$total_deuda = 0;
	$disponible = 0;

 while($v=odbc_fetch_array($result)){

	?>
	<tr>

			<td style="width: 5%"> 
				<?php if ($v['PLAZO']=='NO'){ echo '<strong><a style="color:red;">'.$v['COD_CLIENTE'].'</a></strong>';} else{echo $v['COD_CLIENTE'];}?>  
			</td>
			
			<td style="width: 15%">
				<?php if($v['ES_GRUPO']=='SI'){
					?>
					<a href="pppGrupo.php?cliente=<?= $v['COD_CLIENTE'] ; ?>"</a> 
					<?php
				}else{
					?>
					<a href="pppDetalle.php?cliente=<?= $v['COD_CLIENTE'] ; ?>"</a> 
					<?php 
				} echo '<font size="2">'.$v['RAZON_SOCIAL'].'</font>' ;?> 
			</td>

			<td style="width: 5%"> <?= $v['PPP'] ;?> </td>
			
			<td style="width: 5%"> <?= number_format($v['CUPO_CRED'], 0, '', '.') ;?> </td>
			
			<td style="width: 5%"> <?= '<strong>'.number_format($v['SALDO_CC'], 0, '', '.').'</strong>' ;?> </td>
			
			<td style="width: 5%">
			<?php if ($v['PLAZO']=='NO'){ echo '<strong><a style="color:red;">'.number_format($v['VENCIDAS'], 0, '', '.').'</a></strong>';} else{echo number_format($v['VENCIDAS'], 0, '', '.');}?>  
			</td>
			
			<td style="width: 5%"> <?= number_format($v['MONTO_PEDIDOS'], 0, '', '.')?> </td>
			
			<td style="width: 5%"> 
				<?php 
					if($v['CHEQUE']>0)
					{
						if($v['ES_GRUPO']=='SI')
						{
							echo '<a href="pppGrupoCheque.php?cliente='.$v['COD_CLIENTE'].'"</a>';
						}else{	
							echo '<a href="pppCheque.php?cliente='.$v['COD_CLIENTE'].'"</a>';
						}	
					} 
					echo number_format($v['CHEQUE'], 0, '', '.') ;
				?> 
			</td>
			
			<td style="width: 5%">
				<?php if ($v['CHEQUES_10_DIAS']>0){ echo '<strong><a style="color:LimeGreen ;">'.number_format($v['CHEQUES_10_DIAS'], 0, '', '.').'</a></strong>';} else{echo number_format($v['CHEQUES_10_DIAS'], 0, '', '.');}?>  
			</td>
			
			<td style="width: 5%"> <?= '<strong>'.number_format($v['TOTAL_DEUDA'], 0, '', '.').'</strong>' ;?> </td>
		
			<td style="width: 5%"> <?= number_format($v['TOTAL_DISPONIBLE'], 0, '', '.') ;?> </td>
		</tr>
		
		
	<?php
	
	$saldo_cc += $v['SALDO_CC'];
	$vencidas += $v['VENCIDAS'];
	$monto_pedidos += $v['MONTO_PEDIDOS'];
	$total_cheques += $v['CHEQUE'];
	$cheques_diez += $v['CHEQUES_10_DIAS'];
	$total_deuda += $v['TOTAL_DEUDA'];
	$disponible += $v['TOTAL_DISPONIBLE'];
 }

?>
	<tr>
		<td style="width: 5%"> </td>
		<td style="width: 15%"><h5 align="center">TOTAL</h6></td>
		<td style="width: 5%"> </td>
		<td style="width: 5%"> </td>
		<td style="width: 5%"><h6><?= number_format($saldo_cc, 0, '', '.') ;?></h6></td>
		<td style="width: 5%"><h6><?= number_format($vencidas, 0, '', '.') ;?></h6></td>
		<td style="width: 5%"><h6><?= number_format($monto_pedidos, 0, '', '.') ;?></h6></td>
		<td style="width: 5%"><h6><?= number_format($total_cheques, 0, '', '.') ;?></h6></td>
		<td style="width: 5%"><h6><?= number_format($cheques_diez, 0, '', '.') ;?></h6></td>
		<td style="width: 5%"><h6><?= number_format($total_deuda, 0, '', '.') ;?></h6></td>
		<td style="width: 5%"><h6><?= number_format($disponible, 0, '', '.') ;?></h6></td>
	</tr>
</tbody>
</div>
</table>

</div>

</div>
</div>

</body>

		
</body>
</html>

		
<?php
}
?>

<script src="../js/main.js"></script>

