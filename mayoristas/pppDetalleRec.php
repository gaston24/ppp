<?php 
session_start(); 

if(!isset($_SESSION['username']) || $_SESSION['username']!= 'directores' ){

	header("Location:../sistemas/login.php");

}else{
?>	

<!doctype html>
<html>
<head>
<?php include '../../css/header.php'; ?>
</head>
<body>


<div >
<?php

$dsn = "1 - CENTRAL";
$user = "sa";
$pass = "Axoft1988";

$rec = $_GET['recibo']	;

$cid = odbc_connect($dsn, $user, $pass);


$sql="
SET DATEFORMAT YMD

EXEC SJ_PPP_DETALLADO_MA_CLIENTE_REC '$rec'

";

$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>



<div style="width:100%">
  
  <table class="table table-striped table-fh table-4c">
<thead>
<tr> 

	<td > <strong> FECHA VTO</strong> </td>

	<td > <strong> IMP A VENCER </strong> </td>
	
	<td > <strong> T COMP A VENCER </strong> </td>
	
	<td > <strong> N COMP A VENCER</strong> </td>


</tr>
</thead>
<?php

while($v=odbc_fetch_array($result)){

	?>

	<div >
	
		
		<td align="left"> <?php echo $v['FECHA_VTO'] ;?> </td>
		
		<td align="left"> <?php echo $v['IMP_VTO'] ;?> </td>

		
		<td align="left"> <?php echo $v['T_COMP'] ;?> </td>
		
		<td align="left"> <?php echo $v['N_COMP'] ;?> </td>

		
		
		</tr>
		
	</div>

	<?php

}

?>



</table>

</div>

</div>


		
</body>
</html>


<?php
}
?>
