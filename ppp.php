<?php 
session_start(); 

if(!isset($_SESSION['username']) || $_SESSION['permisos']!= 4 ){

	header("Location:../sistemas/login.php");

}else{
?>	
<!doctype html>
<head>
	
<title>PPP - Canal</title>
<?php include '../css/header.php'; ?>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/style.css">

</head>
<body>

</br>
<div class="container-fluid">


<h2 align="center">Seleccionar Canal</h2></br>

<nav style="margin-left:20%; margin-right:20%">
<div class="btnGroup">
	<button class="btn btn-success btn-lg btn-block mb-4" id="btn_refresh" >Actualizar Credito</button>
	<button type="button" class="btn btn-primary btn-lg btn-block spinner" onclick="location.href='mayoristas/index.php'">Mayoristas</button>
	<button type="button" class="btn btn-secondary btn-lg btn-block spinner" onclick="location.href='franquicias/ppp.php'">Franquicias</button>
	<!-- <button type="button" class="btn btn-danger btn-lg btn-block" onclick="location.href='cheques/index.php'">Cheques (Beta)</button> -->
	<!-- spinner -->
	<div id="boxLoading"></div>
</div>
</nav>





<?php

$dsn = "1 - CENTRAL";
$user = "sa";
$pass = "Axoft1988";



$cid = odbc_connect($dsn, $user, $pass);
$sql= "
SET DATEFORMAT YMD

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
		AND B.COD_CLIENT NOT IN (SELECT COD_CLIENT FROM SJ_PPP_EXCLUYE_CLIENTE WHERE SELEC = 1)
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
ORDER BY 1
";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
?>
<br>
<div style="width:100%">
  
  <table class="table table-striped table-fh table-9c">
	
	<thead>
		<tr >
			<th style="width: 5%">CLIENTE</th>
			<th style="width: 5%">SALDO CC</th>
			<th style="width: 5%">VENCIDO</th>
			<th style="width: 5%">A VENCER</th>
			<th style="width: 5%">TOTAL<br>CHEQUES</th>
			<th style="width: 5%">CHEQUES<br>10 DIAS</th>
			<th style="width: 5%">TOTAL<br>DEUDA</th>
		</tr>
	</thead>

	<div >
	<tbody >
<?php

	$saldo_cc = 0;
	$vencidas = 0;
	$a_vencer = 0;
	$total_cheques = 0;
	$cheques_diez = 0;
	$total_deuda = 0;

 while($v=odbc_fetch_array($result)){

	?>
	<tr>

			<td style="width: 5%"> <?php echo $v['COD_CLIENT'];?> </td>
			
			<td style="width: 5%"> <?php echo number_format($v['SALDO_CC'], 0, '', '.') ;?> </td>
			
			<td style="width: 5%"> <?php echo '<a style="color:red;">'.number_format($v['VENCIDAS'], 0, '', '.').'</a>' ;?> </td>
			
			<td style="width: 5%"> <?php echo number_format($v['A_VENCER'], 0, '', '.') ;?> </td>
			
			<td style="width: 5%"> <?php echo number_format($v['CHEQUE'], 0, '', '.') ; ?> </td>
			
			<td style="width: 5%">
				<?php if ($v['CHEQUES_10_DIAS']>0){ echo '<strong><a style="color:LimeGreen ;">'.number_format($v['CHEQUES_10_DIAS'], 0, '', '.').'</a></strong>';} else{echo number_format($v['CHEQUES_10_DIAS'], 0, '', '.');}?>  
			</td>
			
			<td style="width: 5%"> <?php echo '<strong>'.number_format($v['TOTAL_DEUDA'], 0, '', '.').'</strong>' ;?> </td>
		
			
		</tr>

	<?php
	
	$saldo_cc += $v['SALDO_CC'];
	$vencidas += $v['VENCIDAS'];
	$a_vencer += $v['A_VENCER'];
	$total_cheques += $v['CHEQUE'];
	$cheques_diez += $v['CHEQUES_10_DIAS'];
	$total_deuda += $v['TOTAL_DEUDA'];
	
 }

?>

	<tr>

			<td style="width: 5%"><h5 align="center">TOTAL</h5></td>
			<td style="width: 5%"><h5><?php echo number_format($saldo_cc, 0, '', '.') ;?></h5></td>
			<td style="width: 5%"><h5><?php echo '<a style="color:red;">'.number_format($vencidas, 0, '', '.').'</a>' ;?></h5></td>
			<td style="width: 5%"><h5><?php echo number_format($a_vencer, 0, '', '.') ;?></h5></td>
			<td style="width: 5%"><h5><?php echo number_format($total_cheques, 0, '', '.') ;?></h5></td>
			<td style="width: 5%"><h5><?php echo '<a style="color:LimeGreen ;">'.number_format($cheques_diez, 0, '', '.').'</a>' ;?></h5></td>
			<td style="width: 5%"><h5><?php echo number_format($total_deuda, 0, '', '.') ;?></h5></td>

	</tr>	
	
</tbody>
</div>
</table>

</div>


</div>
<?php include 'agrega_clientes.php'?>


</body>

<script src="js/main.js" charset="utf-8"></script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</html>
<?php
}
?>

<script>

	//Spinner//
	var btn = document.querySelectorAll('.spinner');
   btn.forEach(el => {
     el.addEventListener("click", ()=>{$("#boxLoading").addClass("loading")});
   })

</script>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
