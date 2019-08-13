<?php
require_once '../build/Acceso.clase.php';
require_once '../build/validar-user.php';
$objAcceso = new Acceso(array(1));
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once '../build/metas.vista.php'; ?>
        <title>CLIENTE</title>
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
                            <h4 style="margin-top: 0px; margin-bottom: 0px"> Mantenimiento de cliente </h4>            
                        </div>

                        <div class="panel-body">

                            <div class="row">
                                <div class="col-xs-12">                                    
                                    <a id="btnAgregar" class='btn btn-primary pull-right' style="margin-bottom: 10px" data-toggle="modal" href="#myModal" title="Nuevo cargo">
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

                            <!-- MODAL PARA REGISTRAR CLIENTE -->
                            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" id="myModal" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">Registrar usuario</h4>
                                        </div>
                                        <form name="frm-grabar" id="frm-grabar"  role="form">
                                            <div class="modal-body">                                    
                                                <div class="row">
                                                    
                                                    <div class="col-xs-6">
                                                        <input type="hidden" name="txtcodigo_cliente" id="txtcodigo_cliente" class="form-control input-sm" readonly="" />
                                                        <input type="hidden" name="txtoperacion" id="txtoperacion" class="form-control input-sm" readonly="" />
                                                        Tipo documento
                                                        <select id="txttipo_cliente" class="form-control input-sm" name="txttipo_cliente" >
                                                            <option value="01">DNI</option>
                                                            <option value="06">RUC</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        Documento<input type="text" name="txtdocumento" id="txtdocumento" class="form-control input-sm" maxlength="8"/>
                                                    </div>
                                                </div>                                            
                                              

                                                <div class="row" id="razon_social">
                                                     <div class="col-xs-12">
                                                        Razon social<input type="text" name="txtrazon_social" id="txtrazon_social" class="form-control input-sm" style="text-transform:uppercase;"/>
                                                    </div>
                                                </div>

                                                <div class="row" id="nombres_apellidos">
                                                    <div class="col-xs-6">
                                                        Nombres<input type="text" name="txtnombres" id="txtnombres" class="form-control input-sm" style="text-transform:uppercase;"/>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        Apellidos<input type="text" name="txtapellidos" id="txtapellidos" class="form-control input-sm" style="text-transform:uppercase;"/>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <br>
                                                        <select id="txtdepartamento" class="form-control input-sm" name="txtdepartamento" >                                                            
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <br>
                                                        <select id="txtprovincia" class="form-control input-sm" name="txtprovincia" >                                                            
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <br>
                                                        <select id="txtdistrito" class="form-control input-sm" name="txtdistrito" >                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">                                
                                                    <div class="col-xs-12">
                                                        Direccion<input type="text" name="txtdireccion" id="txtdireccion" class="form-control input-sm" style="text-transform:uppercase;"/>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        Teléfono móvil<input type="text" name="txttelefono_movil" id="txttelefono_movil" class="form-control input-sm" maxlength="9"/>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        Correo electrónico<input type="email" name="txtemail" id="txtemail" class="form-control input-sm"/>
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
                            <!-- MODAL PARA REGISTRAR CLIENTE --> 

                        </div>
                    </div>
                </div>
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
