<?php
require_once '../build/Acceso.clase.php';
require_once '../build/validar-user.php';
$objAcceso = new Acceso(array(1));
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once '../build/metas.vista.php'; ?>
        <title>PIEZA</title>
        <?php require_once '../build/estilos.vista.php'; ?>
        <?php require_once '../build/estilos.dataTables.vista.php'; ?>
        <link rel="stylesheet" href="../../util/jquery-autocompletar/jquery.ui.css">
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
                            <h4 style="margin-top: 0px; margin-bottom: 0px"> Mantenimiento de pieza </h4>            
                        </div>

                        <div class="panel-body">

                            <div class="row">
                                <div class="col-xs-12">
                                    <a id="btnAgregar" class='btn btn-primary pull-right' style="margin-bottom: 10px" data-toggle="modal" href="#myModal" title="Nueva pieza">
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
                            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" id="myModal" class="modal fade">
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
                                                        <input type="hidden" name="txtcodigo_pieza" id="txtcodigo_pieza" class="form-control input-sm" readonly=""/>
                                                        <input type="hidden" name="txtoperacion" id="txtoperacion" class="form-control input-sm" readonly=""/>
                                                    </div>
                                                </div>

                                                
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        Pieza<input type="text" name="txtnombre" id="txtnombre" class="form-control input-sm" style="text-transform:uppercase;"/>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        Producto
                                                        <select id="txtcodigo_producto" class="form-control input-sm" name="txtcodigo_producto" >                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="x_title">
                                                        <h2>Fundición</h2>                    
                                                        <div class="clearfix"></div>
                                                    </div>

                                                    <div class="col-md-3 col-sm-3 col-xs-3"> 
                                                        CANTIDAD CHATARRA EN KG                                                
                                                    </div>  
                                                    <div class="col-md-4 col-sm-4 col-xs-4">                                             
                                                        <input type="text" name="txtcantidad" id="txtcantidad" class="form-control input-sm" />                                                             
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="x_title">
                                                        <h2>Acabado </h2>                    
                                                        <div class="clearfix"></div>
                                                    </div> 
                                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                                        <br>                                            
                                                        <button id="btnMasillado" class="form-control input-sm" type="button" title="Seleccionar materia prima masillado" data-toggle="modal" href="#myModal2" >MASILLADO</button>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                                        <br>                                            
                                                        <button id="btnPulido" class="form-control input-sm" type="button" title="Seleccionar materia prima pulido" data-toggle="modal" href="#myModal2" >PULIDO</button>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                                        <br>                                            
                                                        <button id="btnPintado" class="form-control input-sm" type="button" title="Seleccionar materia prima pintado" data-toggle="modal" href="#myModal2" >PINTADO</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">                                                
                                                <button class="btn btn-success" type="submit" id="botonGrabar">Grabar</button>
                                                <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
                                            </div>
                                        </form>


                                    </div>
                                </div>
                            </div>
                            <!-- Modal -->

                            <!-- Modal 2 -->
                            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" id="myModal2" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="btnCloset">&times;</button>
                                            <h4 class="modal-title"></h4>
                                        </div>                                        
                                        <div class="modal-body">                                                
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <input type="hidden" name="txttipo" id="txttipo" class="form-control input-sm" readonly=""/>                                                    
                                                </div>
                                            </div>

                                            
                                            <div class="row">
                                                <div class="col-xs-9">
                                                    Materia Prima
                                                    <select id="txtcodigo_materia_prima" class="form-control input-sm" name="txtcodigo_materia_prima" >                                                            
                                                    </select>
                                                </div>
                                                <div class="col-xs-3">
                                                    Cantidad<input type="text" name="txtcantidad_materia_prima" id="txtcantidad_materia_prima" class="form-control input-sm"/>
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
                                            <button class="btn btn-default carrito" type="button" id="btnC1"> Agregar</button>
                                            <button class="btn btn-default carrito" type="button" id="btnC3"> Agregar</button>

                                            <button class="btn btn-default carrito" type="button" id="btnA1"> Agregar</button>
                                            <button class="btn btn-default carrito" type="button" id="btnA2"> Agregar</button>
                                            <button class="btn btn-default carrito" type="button" id="btnA3"> Agregar</button>

                                            <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
                                            
                                        </div>
                                        


                                    </div>
                                </div>


                            </div>
                            <!-- Modal 2 -->
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
