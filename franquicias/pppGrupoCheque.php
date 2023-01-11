<?php 
session_start(); 

if(!isset($_SESSION['username']) || $_SESSION['permisos']!= 4 ){

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


SELECT COD_CLIENT, RAZON_SOCI, SUM(IMPORTE_CH)TOTAL_CHEQUES
FROM (
SELECT B.COD_CLIENT, B.RAZON_SOCI, B.GRUPO_EMPR, CAST (FECHA_CHEQ AS DATE)FECHA_CHEQ, N_COMP_REC, CAST(IMPORTE_CH AS INT)IMPORTE_CH 
FROM SBA14 A
INNER JOIN GVA14 B
ON A.CLIENTE = B.COD_CLIENT
WHERE FECHA_CHEQ >= GETDATE() AND ESTADO NOT IN ('X', 'R') 
AND B.GRUPO_EMPR = '$cliente'
)A
GROUP BY COD_CLIENT, RAZON_SOCI
order by 1;



";

$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>


<h2 align="center">Detalle Cheques de <?php echo $cliente; ?></h2></br>
<nav style="margin-left:20%; margin-right:20%">
<table class="table table-striped" id="id_tabla">

        <tr style="font-weight: bold">
				<td >CODIGO</td>
				<td >CLIENTE</td>								
				<td >TOTAL CHEQUES</td>
        </tr>
		
        <?php
	    $total_ch = 0;   
		while($v=odbc_fetch_array($result)){
		
        ?>
	
        <tr >

                <td ><?php echo $v['COD_CLIENT'] ;?></td>
				<td ><?php echo $v['RAZON_SOCI'] ;?></td>
				<td ><a href="pppCheque.php?cliente=<?php echo $v['COD_CLIENT'];?>">
				<?php echo number_format($v['TOTAL_CHEQUES'], 0, '', '.') ;?></a></td>
				
				<?php $total_ch+=$v['TOTAL_CHEQUES']; ?> 
				
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