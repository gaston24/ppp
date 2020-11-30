<?php 
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:../sistemas/login.php");

}else{
?>	
<!doctype html>
<head>
<title>Inicio</title>
<meta http-equiv="Refresh" content="600">
<?php include '../css/header_simple.php'; ?>
</head>
<body>



<?php

$dsn = "1 - CENTRAL";
$user = "sa";
$pass = "Axoft1988";



$cid = odbc_connect($dsn, $user, $pass);
$sql= "
SET DATEFORMAT YMD

SELECT SUM(SALDO_CC)SALDO_CC, SUM(VENCIDAS)VENCIDAS, SUM(A_VENCER)A_VENCER, SUM(CHEQUES_10_DIAS)CHEQUES_10_DIAS, SUM(CHEQUE)CHEQUE
FROM
(

SELECT CASE COD_CLIENT WHEN 'FR' THEN 'FRANQUICIAS' WHEN 'MA' THEN 'MAYORISTAS' END COD_CLIENT, SALDO_CC, CHEQUE, CHEQUES_10_DIAS, TOTAL_DEUDA,
A_VENCER, VENCIDAS
FROM
(

SELECT LEFT(COD_CLIENTE, 2) COD_CLIENT, SUM(SALDO_CC)SALDO_CC, SUM(CHEQUE)CHEQUE, SUM(CHEQUES_10_DIAS)CHEQUES_10_DIAS, SUM(TOTAL_DEUDA)TOTAL_DEUDA,
SUM(A_VENCER)A_VENCER, SUM(VENCIDAS)VENCIDAS
FROM 
(

SELECT COD_CLIENTE, GRUPO_EMPR, CASE WHEN FECHA IS NULL THEN 'OK' ELSE 'NO' END PLAZO, RAZON_SOCIAL, PPP, CUPO_CRED, SALDO_CC, CHEQUE, CHEQUES_10_DIAS, TOTAL_DEUDA, 
(CUPO_CRED - TOTAL_DEUDA) TOTAL_DISPONIBLE, A_VENCER, VENCIDAS
FROM
(
	SELECT COD_CLIENTE, D.FECHA, RAZON_SOCIAL, GRUPO_EMPR, CAST(PPP AS INT)PPP, CAST(CUPO_CREDI AS int) CUPO_CRED, 
	CAST(SALDO_CC AS INT)SALDO_CC, 
	CAST(CASE WHEN B.CHEQUES IS NULL THEN 0 ELSE B.CHEQUES END AS INT)CHEQUE, 
	CAST(CASE WHEN C.CHEQUES_PRONTO IS NULL THEN 0 ELSE C.CHEQUES_PRONTO END AS INT)CHEQUES_10_DIAS, 
	CAST((SALDO_CC+(CASE WHEN B.CHEQUES IS NULL THEN 0 ELSE B.CHEQUES END)) AS INT) TOTAL_DEUDA, SUM(A_VENCER)A_VENCER, SUM(A.VENCIDAS)VENCIDAS
	FROM
	(
		SELECT COD_CLIENTE, RAZON_SOCIAL, B.GRUPO_EMPR, C.NOMBRE_GRU , 
		CAST(AVG(PPP) AS decimal(10,2)) PPP, CAST(AVG(DIAS) AS INT) DIAS, B.CUPO_CREDI, B.SALDO_CC, D.A_VENCER, D.VENCIDAS
		FROM GC_VIEW_PPP A
		INNER JOIN GVA14 B
		ON A.COD_CLIENTE = B.COD_CLIENT
		LEFT JOIN GVA62 C
		ON B.GRUPO_EMPR = C.GRUPO_EMPR
		LEFT JOIN SJ_SALDOS_CC D
		ON A.COD_CLIENTE = D.COD_CLIENT
		WHERE B.COD_CLIENT LIKE '[FM][AR]%'
		AND FECHA_RECIBO >= GETDATE()-365
		GROUP BY COD_CLIENTE, RAZON_SOCIAL, B.GRUPO_EMPR, C.NOMBRE_GRU, B.CUPO_CREDI, B.SALDO_CC, D.A_VENCER, D.VENCIDAS
	)A
	LEFT JOIN
	(SELECT CLIENTE, SUM(IMPORTE_CH)CHEQUES FROM SBA14 WHERE FECHA_CHEQ >= GETDATE() AND ESTADO NOT IN ('X', 'R') GROUP BY CLIENTE) B
	ON A.COD_CLIENTE = B.CLIENTE
	LEFT JOIN
	(SELECT CLIENTE, SUM(IMPORTE_CH)CHEQUES_PRONTO FROM SBA14 WHERE (FECHA_CHEQ >= GETDATE() AND FECHA_CHEQ <= GETDATE()+10) AND ESTADO NOT IN ('X', 'R') GROUP BY CLIENTE) C
	ON A.COD_CLIENTE = C.CLIENTE
	LEFT JOIN
	(SELECT FECHA, COD_CLIENT FROM (SELECT MIN(FECHA_EMIS)FECHA, COD_CLIENT FROM GVA12 A WHERE FECHA_EMIS >= GETDATE()-45 
	AND COD_CLIENT LIKE '[FM][AR]%' AND A.T_COMP = 'FAC' AND ESTADO = 'PEN' GROUP BY COD_CLIENT )A WHERE FECHA < GETDATE()-30)D
	ON A.COD_CLIENTE = D.COD_CLIENT
	GROUP BY COD_CLIENTE, D.FECHA, RAZON_SOCIAL, GRUPO_EMPR, PPP, CUPO_CREDI, SALDO_CC, (CASE WHEN B.CHEQUES IS NULL THEN 0 ELSE B.CHEQUES END), 
	(CASE WHEN C.CHEQUES_PRONTO IS NULL THEN 0 ELSE C.CHEQUES_PRONTO END), ((SALDO_CC+(CASE WHEN B.CHEQUES IS NULL THEN 0 ELSE B.CHEQUES END)))
)A

)A

GROUP BY LEFT(COD_CLIENTE, 2)
)A

)A
";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
while($v=odbc_fetch_array($result)){
	$saldo_cc = $v['SALDO_CC'];
	$vencidas = $v['VENCIDAS'];
	$a_vencer = $v['A_VENCER'];
	$cheques_10_dias = $v['CHEQUES_10_DIAS'];
	$cheque = $v['CHEQUE'];
	
}





$dsn_locales = "LOCALES";
$user_locales = "sa";
$pass_locales = "Axoft";



$cid_locales = odbc_connect($dsn_locales, $user_locales, $pass_locales);
$sql_locales= "
SET DATEFORMAT YMD

SELECT CANAL, CAST(MES_ACTUAL AS INT)IMPORTE_ACTUAL, CAST(MES_ANTERIOR AS INT)IMPORTE_ANTERIOR FROM SJ_FACTURACION_PPP_DIARIA
ORDER BY 1
";

ini_set('max_execution_time', 300);
$result_locales=odbc_exec($cid_locales,$sql_locales)or die(exit("Error en odbc_exec"));
while($v=odbc_fetch_array($result_locales)){
	
}





?>






<div class="container">

<br>

<div class="card-deck">

<div class="card" style="width: 18rem;">
	<a href="ppp.php"><img class="card-img-top" src="imagenes/<?php echo date('d')+10;?>.jpg" alt="Card image cap"></a>
	<div class="card-body">
		<h5 class="card-title" align="center"><a href="ppp.php" style="color:black;text-decoration: none">Plazo Promedio de Pago</a></h5>
	</div>
	<ul class="list-group list-group-flush">
		<li class="list-group-item"><a align="left">Saldo CC</a> 	&nbsp&nbsp	<?php echo number_format($saldo_cc, 0, '', '.') ;?>		</li>
		<li class="list-group-item"><a align="left">Vencidas</a> 	&nbsp&nbsp	<?php echo number_format($vencidas, 0, '', '.') ;?>		</li>
		<li class="list-group-item"><a align="left">A vencer</a> 	&nbsp&nbsp	<?php echo number_format($a_vencer, 0, '', '.') ;?>		</li>
		<li class="list-group-item"><a align="left">Cheques</a>	&nbsp&nbsp	<?php echo number_format($cheque, 0, '', '.') ;?>	</li>
		<li class="list-group-item"><a align="left">Cheques 10 Dias</a>	&nbsp&nbsp	<?php echo number_format($cheques_10_dias, 0, '', '.') ;?>	</li>
	</ul>
</div>

<div class="card" style="width: 18rem;">
	<a href="facturacion.php"><img class="card-img-top" src="imagenes/<?php echo date('d')+13;?>.jpg" alt="Card image cap"></a>
	<div class="card-body">
		<h5 class="card-title" align="center"><a href="facturacion.php" style="color:black;text-decoration: none">Facturacion acumulada del mes</a></h5>
	</div>
	<ul class="list-group list-group-flush">
		<?php
		$result_locales=odbc_exec($cid_locales,$sql_locales)or die(exit("Error en odbc_exec"));
		while($v=odbc_fetch_array($result_locales)){
			echo '<li class="list-group-item">'.$v['CANAL'].'&nbsp&nbsp&nbsp'.number_format($v['IMPORTE_ACTUAL'], 0, '', '.').'</li>';
		}
		?>		
		<li class="list-group-item" align="center" ><a href="../ventas" style="color:black;text-decoration: none"><strong>Ventas Sucursales</strong></a></li>
		<!--onClick="location.href='../ventas'"-->
	</ul>
</div>

<div class="card" style="width: 18rem;">
	<img class="card-img-top" src="imagenes/<?php echo date('d')+16;?>.jpg" alt="Card image cap">
	<div class="card-body">
		<h5 class="card-title" align="center">Estadisticas</h5>
	</div>
	<ul class="list-group list-group-flush">
		<li class="list-group-item"><a href="estadisticas/index.php" style="color:black;text-decoration: none">Ventas - Locales Propios</a></li>
		<li class="list-group-item"><a href="estadisticasFranquicias/index.php" style="color:black;text-decoration: none">Ventas - Franquicias</a></li>
	
		<li class="list-group-item"> <a href="../bi/indicadores.php" target="_blank" style="color:black;text-decoration: none">Ventas (BI)</a></li>
		<li class="list-group-item"> <a href="../bi/index.php" target="_blank" style="color:black;text-decoration: none">Ecommerce (BI)</a></li>
		<li class="list-group-item"> <a href="dashboard/" target="_blank" style="color:black;text-decoration: none">Administracion - Stock (BI)</a></li>
	</ul>
	
</div>

</div>



</div>

</body>
</html>
<?php
}
?>
