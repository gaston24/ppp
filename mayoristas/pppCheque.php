<?php 
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:../sistemas/login.php");

}else{
?>	
<!doctype html>
<html charset="UTF-8">
<head>
<title>Detalle Cheque</title>
<?php include '../../css/header_simple.php'; ?>
</head>
<body>

</br>
<div class="container-fluid">

<?php

$dsn = "1 - CENTRAL";
$user = "sa";
$pass = "Axoft1988";

$cid = odbc_connect($dsn, $user, $pass);

if(!$cid){echo "</br>Imposible conectarse a la base de datos!</br>";}

$cliente = $_GET['cliente'];

$sql="


SELECT CAST (FECHA_CHEQ AS DATE)FECHA_CHEQ, N_COMP_REC, CAST(IMPORTE_CH AS INT)IMPORTE_CH FROM SBA14 
WHERE FECHA_CHEQ >= GETDATE() AND ESTADO NOT IN ('X', 'R') 
AND CLIENTE = '$cliente'
order by 1;



";

$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>


<h2 align="center">Detalle Cheques de <?php echo $cliente; ?></h2></br>
<nav style="margin-left:20%; margin-right:20%">
<table class="table table-striped" id="id_tabla">

        <tr style="font-weight: bold">
				<td >FECHA CHEQUE</td>
				<td >RECIBO</td>								
				<td >IMPORTE</td>
        </tr>
		
        <?php
	    $total_ch = 0;   
		while($v=odbc_fetch_array($result)){
		
        ?>
	
        <tr >

                <td ><?php echo $v['FECHA_CHEQ'] ;?></td>
				<td ><?php echo $v['N_COMP_REC'] ;?></td>
				<td ><?php echo number_format($v['IMPORTE_CH'], 0, '', '.') ;?></td>
				
				<?php $total_ch+=$v['IMPORTE_CH']; ?> 
				
		</tr>
		
        <?php
		
        }

        ?>

		<tr style="color:red ; font-weight: bold">
				<td ></td>
				<td align="right">Total cheques</td>
				<td><?php echo number_format($total_ch, 0, '', '.'); ?></td>
		</tr >
	
</table>
</nav>
</div>
</body>
</html>

<?php
}
?>