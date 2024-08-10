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
$sql= "SET DATEFORMAT YMD 
       SELECT * FROM RO_PPP_OPTIMIZADO";

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
