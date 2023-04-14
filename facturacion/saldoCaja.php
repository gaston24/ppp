
<?php

include '../Class/caja.php';

$saldos= new Saldos();

$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date("Y-m-d", strtotime("-1 days"));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control Gastos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" class="rel">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css" class="rel">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css"></link>
    

</head>
<body>

        <div class="alert alert-primary">        
            <div class="row">
                <div id="titlePrincipal" class="col-md-auto">
                    <h3 class="title"><i class="bi bi-coin"></i> Saldos caja sucursales</h3>
                </div>
                <div class="form-row">
                    <form>
                        <div class="contenedor" style="display: flex;">
                            <div class="col-" style="margin-left: 10rem;">
                                <label>Fecha:</label>
                                <input type="date" class="form-control form-control-sm" name="fecha" value="<?= $fecha?>">
                            </div>
                            <div>
                                <button type="submit" name="submit" class="btn btn-sm btn-primary" id="search" style="margin-top: 2rem; margin-left: 0.5rem;">Filtrar <i class="bi bi-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <h3 style="margin-left: 30rem; font-size:40px"><i style="color: #dc3545;" class="bi bi-info-circle" data-toggle="tooltip" data-placement="right" title="Los saldos corresponden a la cuenta caja"></i></h3>
            </div>     
        </div>
    
        

<?php
    if (isset($_GET['fecha'])) {
        $todosLosSaldos = $saldos->traerSaldosCajas($fecha);
    ?>
    <div class="contTabla" style="width:90%; margin-left:2rem">
        <table class="table table-striped table-bordered display mt-2" id="myTable" >
            <thead style="position: sticky; top: 0; z-index: 10; color: wheat; background-color: #343a40; text-align: center;">
                <tr>
                    <th style="text-align: center;">FECHA</th>
                    <th style="text-align: center;">NRO. SUCURSAL</th>
                    <th style="text-align: center;">SUCURSAL</th>
                    <th style="text-align: center;">SALDO CAJA</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                $saldo_caja = 0;

                $todosLosSaldos = json_decode($todosLosSaldos);
                foreach ($todosLosSaldos as $valor => $key) {
                ?>
                    <tr>
                        <td><span><?= substr($key->FECHA->date, 0, 10); ?></span></td>
                        <td><span><?= $key->NRO_SUCURSAL ?></span></td>
                        <td><span><?= $key->DESC_SUCURSAL ?></span></td>
                        <td><span><?= number_format($key->SALDO_CAJA, 2, '.', ',') ?></span></td>
                    </tr>
                    
            <?php
	
            $saldo_caja += $key->SALDO_CAJA;
               
            }
            ?>

            </tbody>

            <tr>
                <td><h5 align="center">TOTAL</h5></td>
                <td></td>
                <td></td>
			<td><h5><?= number_format($saldo_caja, 2, '.', ',') ;?></h5></td>
    	</tr>
        <?php
        }
        ?>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
    <!-- Incluir para que funcione el tooltip -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script>

    $(document).ready(function () {
        $('#myTable').DataTable({
        order: [[3, "desc"]],
        pageLength: 50
        }
        );
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

</script>

</body>
</html>