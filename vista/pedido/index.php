<?php
require_once '../build/Acceso.clase.php';
require_once '../build/validar-user.php';
$objAcceso = new Acceso(array(1));
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once '../build/metas.vista.php'; ?>
        <title>Listado de Pedidos</title>                
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
                            <h4 style="margin-top: 0px; margin-bottom: 0px"> Listado pedidos </h4>            
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
                                                                <th style="text-align: center">Importe (S/)</th>                                                                
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
                                        <div class="modal-body">
                                            <div class="row">                                              
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="x_title">
                                                        <h4>Resumen de pago</h4>                                                    
                                                    <div class="clearfix"></div>
                                                    </div>
                                                    <table id="tabla-listado-resumen-pago" class="table table-bordered table-striped">
                                                        <thead style="background: gray; color: white;">
                                                            <tr> 
                                                                <th style="text-align: center">Descripción</th>   
                                                                <th style="text-align: center">Importe (S/)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tabla-resumen-pago">                                                            
                                                        </tbody>
                                                    </table>
                                                    <a href="http://localhost/appProduccion/imagenes/voucher_bcp.jpg" target="_blank"><h5><span class="label label-success" >Ver ejemplo de voucher</span></h5></a>    </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    
                                                    <div class="x_panel">
                                                        <div class="x_title">
                                                            <h4>AGENTE BCP<BR><span class="label label-success" style="color: white;">N° de cuenta : 305-37542079-0-78</span></h4>                                
                                                        <div class="clearfix"></div>
                                                        </div>
                                                        <form name="frm-grabar" id="frm-grabar"  role="form">
                                                            <input type="hidden" name="txtnum_cuenta" id="txtnum_cuenta" class="form-control input-sm" value="305-37542079-0-78" readonly/>
                                                            <input type="hidden" name="txtcodigo_pedido" id="txtcodigo_pedido" class="form-control input-sm" readonly />
                                                            <input type="hidden" name="txtmonto_total" id="txtmonto_total" class="form-control input-sm" readonly />
                                                            Fecha<input type="date" name="txtfecha_registro" id="txtfecha_registro" class="form-control input-sm" required="" />
                                                            Hora<input type="time" step="1" name="txthora_registro" id="txthora_registro" class="form-control input-sm" required="" />
                                                            Nro. Operación<input type="text" name="txtnum_operacion" id="txtnum_operacion" class="form-control input-sm" required=""/>
                                                            Monto<input type="text" name="txtmonto" id="txtmonto" class="form-control input-sm" required=""/>
                                                            <div id="foto_view">
                                                            Voucher<input type="file" name="txtfoto" id="txtfoto" class="form-control input-sm" required=""/> 
                                                            </div>
                                                            <br>                                               
                                                            <button id="btnVoucher" class="btn btn-default form-control input-sm" type="submit">Enviar voucher</button>
                                                        </form>                                                        
                                                    </div>
                                                    
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal -->
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

