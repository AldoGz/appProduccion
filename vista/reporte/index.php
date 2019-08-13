<?php
require_once '../build/Acceso.clase.php';
require_once '../build/validar-user.php';
$objAcceso = new Acceso(array(1));

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once '../build/metas.vista.php'; ?>
        <title>REPORTE PLAN PRODUCCIÓN</title>
        <?php require_once '../build/estilos.vista.php'; ?>
        <?php require_once '../build/estilos.dataTables.vista.php'; ?> 
        
 
    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <div class="col-md-3 left_col">
                    <div class="left_col scroll-view">
                        <?php require_once '../build/menu-titulo.php'; ?>
                        <div class="clearfix"></div>

                        <!-- menu profile quick info -->
                        <?php require_once '../build/menu-perfil.php'; ?>
                        <!--/menu profile quick info--> 

                        <br />

                        <!-- sidebar menu -->
                        <?php require_once '../build/menu-izquierda.php'; ?>
                        <!-- /sidebar menu -->


                    </div>
                </div>

                <!-- top navigation -->
                <?php require_once '../build/menu-arriba.php'; ?>
                <!-- /top navigation -->

                <!-- page content -->
                <div class="right_col" role="main">

                    <div class="panel panel-default panel-icr-blue" id="moduleDetailsPanel">
                        <div class="panel-heading"> 
                            <h4 style="margin-top: 0px; margin-bottom: 0px"> Reporte de plan produccion </h4>            
                        </div>

                        <div class="panel-body">


                            <div class="row">
                                <div class="col-xs-3">
                                    Fecha inicio<input type="date" name="txtfecha_inicio" id="txtfecha_inicio" class="form-control input-sm" required="" style="text-transform:uppercase;"/>
                                </div>
                                <div class="col-xs-3">
                                    Fecha fin<input type="date" name="txtfecha_fin" id="txtfecha_fin" class="form-control input-sm" required="" style="text-transform:uppercase;"/>
                                </div>
                                <div class="col-xs-3">
                                    <br>
                                    <button class="btn btn-success" type="button" id="btnFiltro" title="Filtrar fechas"><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
                                </div>
                            </div> 
                            <br>
                          
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




                <!-- /page content -->

                <!-- footer content -->
                <footer>
                    <?php require_once '../build/pie-pagina.php'; ?>
                </footer>
                <!-- /footer content -->

            </div>
        </div>

        <?php
        require_once '../build/scripts.vista.php';
        require_once '../build/scripts.dataTables.vista.php';
        ?>
        
        <script src="../../util/js/util.js"></script>
        <script src="index.js"></script>
        
    </body>
</html>
