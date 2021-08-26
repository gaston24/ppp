<?php 
session_start(); 

if(!isset($_SESSION['username']) || $_SESSION['username']!= 'directores' ){

	header("Location:../sistemas/login.php");

}else{
?>	
<!doctype html>
<html charset="UTF-8">
<head>
<meta charset="utf-8">
<title>PPP</title>
<link rel="shortcut icon" href="icono.jpg" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

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

$sql="


SELECT COD_VENDED, NOMBRE_VEN FROM GVA23
WHERE COD_VENDED LIKE 'Z%'
AND INHABILITA = 0
AND COD_VENDED != 'ZX'
ORDER BY COD_VENDED


";

$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>


<h2 align="center">Seleccionar Vendedor</h2></br>

<nav style="margin-left:20%; margin-right:20%">


<table class="table table-striped" id="id_tabla">

        <tr>
				<td  align="center"><strong>COD VENDEDOR</strong></td>
								
				<td ><strong>NOMBRE</strong></td>
        </tr>

		
        <?php
	       
		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr >

                <td align="center"><?php echo $v['COD_VENDED'] ;?></td>
				
				<td ><a href="ppp.php?cod=<?php echo $v['COD_VENDED'] ;?>"><?php echo $v['NOMBRE_VEN'];?></a></td>
										
		</tr>

		
		
        <?php
		
        }

        ?>

		
			
			
</table>

</nav>

</div>


<?php include '../agrega_clientes.php'?>

</body>
</html>

<?php
}
?>