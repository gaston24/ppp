<?php 
session_start(); 

if(!isset($_SESSION['username']) || $_SESSION['permisos']!= 4 ){

	header("Location:../../sistemas/login.php");

}else{
?>	
<!doctype html>

<head>
<title>Elija Dashboard</title>
<?php include '../../css/header_simple.php'; ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>

<div class="container mt-5" >

<div class="row">
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Inventarios Sucursales</h5>
        
        <a href="https://app.powerbi.com/view?r=eyJrIjoiNjc4OTdjMzktM2IxNi00OGY3LWIxZWQtYmNiOWQxYjhlZmU4IiwidCI6IjQ0Y2E2MmNkLTY4MjItNDZkNC05NTUxLTEzNDQ5N2ZmM2VjMiIsImMiOjR9" target="_blank" class="btn btn-primary">Abrir</a>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Inventario Central</h5>
        
        <a href="https://app.powerbi.com/view?r=eyJrIjoiYjYwYzA2NjktOGI1MC00MzRjLTlkZjMtYzgxMDQzMGE3ZTIzIiwidCI6IjQ0Y2E2MmNkLTY4MjItNDZkNC05NTUxLTEzNDQ5N2ZmM2VjMiIsImMiOjR9" target="_blank" class="btn btn-primary">Abrir</a>

      </div>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Presupuesto</h5>
        
        <a href="../../presupuesto/index.php" target="_blank" class="btn btn-primary">Abrir</a>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Productos Fallados</h5>
        
        <a href="https://app.powerbi.com/view?r=eyJrIjoiNjQ2OTIyMmItY2JjOS00MGUyLTkxMTMtMmZhNmUyYTQwYmM0IiwidCI6IjQ0Y2E2MmNkLTY4MjItNDZkNC05NTUxLTEzNDQ5N2ZmM2VjMiIsImMiOjR9" target="_blank" class="btn btn-primary">Abrir</a>
      </div>
    </div>
  </div>

</div>

<div class="row mt-3">
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Auditoria 599</h5>
        
        <a href="../../app/equis" target="_blank" class="btn btn-primary">Abrir</a>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Nota de Crédito</h5>
        
        <a href="https://app.powerbi.com/view?r=eyJrIjoiYjk1ZWNiNzgtZGZjMS00MzA5LTgzNzUtMDU1OTFjMTJiN2RjIiwidCI6IjQ0Y2E2MmNkLTY4MjItNDZkNC05NTUxLTEzNDQ5N2ZmM2VjMiIsImMiOjR9" target="_blank" class="btn btn-primary">Abrir</a>
        
      </div>
    </div>
  </div>

</div>

<div>



</div>

</div>

</body>
<?php
}
?>