<?php
require_once '../build/Acceso.clase.php';
require_once '../build/validar-user.php';
$objAcceso = new Acceso(array(1));
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once '../build/metas.vista.php'; ?>
        <title>PRODUCTO</title>
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
                            <h4 style="margin-top: 0px; margin-bottom: 0px"> Mantenimiento de producto </h4>            
                        </div>

                        <div class="panel-body">

                            <div class="row">
                                <div class="col-xs-12">
                                    <a id="btnAgregar" class='btn btn-primary pull-right' style="margin-bottom: 10px" data-toggle="modal" href="#myModal" title="Nuevo producto">
                                        <span class='fa fa-plus' aria-hidden='true'></span> Nuevo
                                    </a>
                                </div>
                            </div> 
                          
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
                                                        <input type="hidden" name="txtcodigo_producto" id="txtcodigo_producto" class="form-control input-sm" readonly=""/>
                                                        <input type="hidden" name="txtoperacion" id="txtoperacion" class="form-control input-sm" readonly=""/>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        Nombre<input type="text" name="txtnombre" id="txtnombre" class="form-control input-sm" required="" style="text-transform:uppercase;"/>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        Descripción
                                                        <textarea name="txtdescripcion" id="txtdescripcion" class="form-control input-sm" required="" style="text-transform:uppercase;">
                                                        </textarea>
                                                    </div>
                                                </div> 
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        Precio<input type="text" name="txtprecio" id="txtprecio" class="form-control input-sm" required="" style="text-transform:uppercase;"/>
                                                    </div>
                                                </div>
                                                <div class="row" id="imagen">
                                                    <br>
                                                    <div class="col-xs-12">
                                                        <div id="nombre_foto" class="text-center"></div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        Foto<input type="file" name="txtfoto" id="txtfoto" class="form-control input-sm" />
                                                    </div>
                                                </div>  
                                                <hr>
                                                <h4>Asignación de Materia Prima para <b>ENSAMBLADO</b></h4>
                                                <div class="row">                                                    
                                                    <div class="col-xs-5">
                                                        Materia Prima
                                                        <select id="txtcodigo_materia_prima" class="form-control input-sm" name="txtcodigo_materia_prima" >                                                            
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-5">
                                                        Cantidad<input type="text" name="txtcantidad" id="txtcantidad" class="form-control input-sm" />
                                                    </div>
                                                    <div class="col-xs-2" id="b1">
                                                        <br>
                                                        <button type="button" class="btn btn-primary btn-sm btn-block" id="btnAdicionar"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                                    </div>
                                                    <div class="col-xs-2" id="b2">
                                                        <br>
                                                        <button type="button" class="btn btn-default btn-sm btn-block" id="btnAumentar"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                                    </div>
                                                </div>  

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <br>
                                                        <table id="tabla-listado-mp" class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr> 
                                                                    <th style="text-align: center">Item</th>     
                                                                    <th style="text-align: center">Materia Prima</th>
                                                                    <th style="text-align: center">Cantidad</th>                                                                    
                                                                </tr>
                                                            </thead>
                                                            <tbody id="detalle_materia_primas">
                                                            </tbody>
                                                        </table> 
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
    </body>
</html>