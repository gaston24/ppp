<?php 
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:../../sistemas/login.php");

}else{
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cheques</title>
    <?php include '../../css/header_simple.php'; ?>
</head>
<body>
    
</body>
</html>

<?php
}
?>