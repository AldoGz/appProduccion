<?php
require_once '../build/Acceso.clase.php';
require_once '../build/validar-user.php';
$objAcceso = new Acceso(array(1));
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once '../build/metas.vista.php'; ?>
        <title>Mis pedido</title>                
        <?php require_once '../build/estilos.vista.php'; ?>      
        

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
                    <div class="row"> 
                        <div class="col-md-6">
                            <h4><span class="label label-danger" id="txtcliente"></span></h4>
                        </div>
                                                       
                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <input type="text" class="form-control" name="txtbuscar" id="txtbuscar" placeholder="Buscar producto">
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div> 
                        <div class="col-md-2">  
                            <button id="btnAgregar" class="btn btn-success form-control" type="button" data-toggle="modal" href="#myModal" title="Carrito de compras"><i id="txtcarrito" class="fa fa-shopping-basket" aria-hidden="true"></i>&nbsp;&nbsp;</button>                                        
                        </div>    
                    </div>


                    <!-- MIS PRODUCTO EXISTENTES -->
                    <div class="row" id="listado-productos">                                                
                    </div>
                    <div class="row"> 
                        <div class="col-md-6 col-md-offset-5">                            
                            <div id="paginacion"></div>                 
                        </div>                                               
                    </div>
                    <!-- MIS PRODUCTO EXISTENTES -->
                    

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
                                        <input type="hidden" id="txttotal"/>
                                        <div id="mensaje">
                                            <p>Mi carrito compras esta vacío</p>
                                        </div>

                                        <div id="informacion">
                                            <div class="row" >
                                                <div class="col-md-4">
                                                    <h4><span class="label label-danger" id="txtdocumento"></span></h4>
                                                </div>
                                                <div class="col-md-8">
                                                    <h4><span class="label label-danger" id="txtcliente2"></span></h4>
                                                </div>
                                            </div>
                                            <div class="row" >
                                                <div class="col-md-8">
                                                    <h4><span class="label label-danger" id="txtdireccion"></span></h4>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <h4><span class="label label-danger" id="txttelefono_movil"></span></h4>
                                                </div>
                                                
                                            </div>
                                            <div class="row" >
                                                <div class="col-md-8">
                                                    <input type="checkbox" id="txtdestinatario"> Destinatario
                                                </div>
                                                
                                            </div>
                                            <div class="page-header">
                                              <h4>Destinatario</h4>
                                            </div>


                                            <div class="row">
                                                <div class="col-md-4">
                                                    <select id="txtdepartamento" class="form-control input-sm" name="txtdepartamento" >                                                            
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <select id="txtprovincia" class="form-control input-sm" name="txtprovincia" >                                                            
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <select id="txtdistrito" class="form-control input-sm" name="txtdistrito" >                                                            
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    Dirección
                                                    <input type="text" class="form-control input-sm" id="txtdireccion_destino" name="txtdireccion_destino" style="text-transform:uppercase;">
                                                </div>
                                            </div>
                                            <br>
                                        </div>
                                        <div class="row" id="tabla">
                                        <div class="col-xs-12">
                                            <table id="tabla-listado-detalle" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr> 
                                                        <th style="text-align: center">ITEM</th>   
                                                        <th style="text-align: center">Producto</th>
                                                        <th style="text-align: center">Cantidad</th>
                                                        <th style="text-align: center">Precio Unitario</th>                                                        
                                                        <th style="text-align: center">Total</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody id="detalle_productos">

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="4" style="text-align: center"><b>TOTAL :</b></td>
                                                        <td style="text-align: center"><p id="txtimporteneto"></p></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
                                        <button class="btn btn-success" type="submit" id="btnGrabar">Grabar</button>
                                    </div>
                                </form>

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

        ?>
        <script src="../../util/paginacion.js"></script>
        <script src="../../util/js/util.js"></script>
        <script src="../../util/js/p_util.js"></script>
        <script src="index.js"></script>
        


        

        


        
        
        
    </body>
</html>
