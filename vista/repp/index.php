<?php
require_once '../build/Acceso.clase.php';
require_once '../build/validar-user.php';
$objAcceso = new Acceso(array(1));

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once '../build/metas.vista.php'; ?>
        <title></title>
        <?php require_once '../build/estilos.vista.php'; ?>
        <?php require_once '../build/estilos.dataTables.vista.php'; ?> 
        
 
    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <div class="panel panel-default panel-icr-blue" id="moduleDetailsPanel">
                    <div class="panel-heading"> 
                        <h1 style="text-align: center">REPORTE DE PRODUCCIÓN</h1>
                        <br>
                        <h4 style="margin-top: 0px; margin-bottom: 0px" id="txtnombre"></h4>
                        <br>
                        <h4 style="margin-top: 0px; margin-bottom: 0px" id="txtfi"></h4>
                        <br>
                        <h4 style="margin-top: 0px; margin-bottom: 0px" id="txtff"></h4>         
                    </div>

                    <div class="panel-body">

                        <div class="row">
                            <div class="col-xs-12">
                                <div id="listado">

                                    <!--imprimir el listado de articulos -->
                                </div>
                            </div>
                        </div>




                    </div>
                </div>
                
            </div>
        </div>

        <?php
        require_once '../build/scripts.vista.php';
        require_once '../build/scripts.dataTables.vista.php';
        ?>
        
        <script src="../../util/js/util.js"></script>
        <script src="../../util/js/p_util.js"></script>
        <script src="index.js"></script>
        
    </body>
</html>
