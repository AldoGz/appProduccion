<?php
require_once '../../datos/local_config.php';
require_once '../build/Acceso.clase.php';
$objAcceso = new Acceso(array(1));
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once '../build/metas.vista.php'; ?>
        <title>ACTIVIDAD DE PRODUCCIÓN</title>
        <?php require_once '../build/estilos.vista.php'; ?>
        <?php require_once '../build/estilos.dataTables.vista.php'; ?>
        <link href="../../util/select/select2-bootstrap.css" rel="stylesheet">
        <link href="../../util/select/select2.css" rel="stylesheet">
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
                            <h4 style="margin-top: 0px; margin-bottom: 0px"> Mantenimiento de actividad de producción </h4>            
                        </div>

                        <div class="panel-body">

                          
                            <div class="row">
                                <div class="col-xs-12">
                                    <div id="listado">
                                        <!--imprimir el listado de articulos -->
                                    </div>
                                </div>
                            </div>



                            <!-- Modal -->
                            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title"></h4>
                                        </div>
                                        <form name="frm-grabar" id="frm-grabar"  role="form">
                                            <div class="modal-body">
                                                
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <input type="hidden" name="txtcodigo_actividad_produccion" id="txtcodigo_actividad_produccion" class="form-control input-sm" readonly=""/>
                                                        <input type="hidden" name="txtoperacion" id="txtoperacion" class="form-control input-sm" readonly=""/>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        Fase
                                                        <select id="txtcodigo_fases" class="form-control input-sm" name="txtcodigo_fases">  
                                                            
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        Tiempo duración
                                                        <input type="text" name="txttiempoduracion" id="txttiempoduracion" class="form-control input-sm" />
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div id="listado-actividades">
                                                            <!--imprimir el listado de actividades -->
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <input id="check-all" class="checkall_box" title="Seleccionar todo" type="checkbox">
                                                        <label>Seleccionar todo</label>
                                                    </div>                                                    
                                                </div>                                                
                                            </div>
                                            <div class="modal-footer">
                                                <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                                                <button class="btn btn-success" type="submit">Grabar</button>
                                            </div>
                                        </form>


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

        <script src="../../util/select/select2.min.js"></script>
        <script src="../../util/select/select2_locale_es.js"></script>
    </body>
</html>
