<?php
require_once '../build/Acceso.clase.php';
require_once '../build/validar-user.php';
$objAcceso = new Acceso(array(1));

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once '../build/metas.vista.php'; ?>
        <title>Realizar pedido cliente</title>
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
                            <h4 style="margin-top: 0px; margin-bottom: 0px"> Pedidos</h4>            
                        </div>

                        <div class="panel-body">

                            <div class="row">
                                <div class="col-xs-12">
                                    <ul class="nav nav-tabs">
                                        
                                        <li class="active"><a data-toggle="tab" href="#realizar">Realizar pedido</a></li>
                                        <li><a data-toggle="tab" href="#mispedidos">Listar pedido</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="realizar" class="tab-pane fade in active">
                                            <br>
                                            <div id="listado">
                                                <!--imprimir el listado de articulos -->
                                            </div>
                                        </div>
                                        <div id="mispedidos" class="tab-pane fade">
                                            <br>
                                            <div id="listado-pedidos">
                                                <!--imprimir el listado de articulos -->
                                            </div>
                                        </div>

                                        <!-- Modal -->
                                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" id="myModal" class="modal fade">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title">Ver detalle de pedido</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <table id="tabla-listado-detalle" class="table table-bordered table-striped">
                                                                    <thead>
                                                                        <tr> 
                                                                            <th style="text-align: center">N°</th>   
                                                                            <th style="text-align: center">Producto</th>
                                                                            <th style="text-align: center">Cantidad (unid.)</th>
                                                                            <th style="text-align: center">Precio (S/)</th>                                                        
                                                                            <th style="text-align: center">Total (S/)</th>                                                                
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
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal -->

                                        <!-- Modal -->
                                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" id="myModal2" class="modal fade">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title"></h4>
                                                    </div>
                                                    <form name="frm-grabar" id="frm-grabar"  role="form">
                                                        <div class="modal-body">
                                                            <div class="row" >
                                                                <div class="col-xs-4">
                                                                    <h4><span class="label label-danger" id="txtdocumento"></span></h4>
                                                                </div>
                                                                <div class="col-xs-8">
                                                                    <h4><span class="label label-danger" id="txtcliente"></span></h4>
                                                                </div>
                                                            </div>
                                                            <div class="row" >
                                                                <div class="col-xs-12">
                                                                    <h4><span class="label label-danger" id="txtdireccion"></span></h4>
                                                                </div>
                                                                <br>                                                                
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <input type="hidden" name="txtcodigo_pedido" id="txtcodigo_pedido" class="form-control input-sm" readonly />



                                                                    <table id="tabla-listado-resumen-pago" class="table table-bordered table-striped">
                                                                        <thead style="background: gray; color: white;">
                                                                            <tr> 
                                                                                <th style="text-align: center">Item</th>   
                                                                                <th style="text-align: center">Producto</th>
                                                                                <th style="text-align: center">Cantidad (unid.)</th>
                                                                                <th style="text-align: center">Precio (S/)</th>                                                        
                                                                                <th style="text-align: center">Total (S/)</th>  
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="tabla-resumen-pago">                                                            
                                                                        </tbody>

                                                                    </table>
                                                                </div>
                                                            </div>  
                                                            <div class="row" >
                                                                <div class="col-xs-4">
                                                                    <h4><span class="label label-danger" id="txtsub_total"></span></h4>
                                                                </div>
                                                                <div class="col-xs-4">
                                                                    <h4><span class="label label-danger" id="txtivg"></span></h4>
                                                                </div>
                                                                <div class="col-xs-4">
                                                                    <h4><span class="label label-danger" id="txttotal"></span></h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button id="btnVoucher" class="btn btn-default form-control input-sm" type="submit">Guardar Pago</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal -->



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
