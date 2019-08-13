<?php
require_once '../build/Acceso.clase.php';
require_once '../build/validar-user.php';
$objAcceso = new Acceso(array(1));

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once '../build/metas.vista.php'; ?>
        <title>DETALLE PLAN PRODUCCIÓN</title>
        <?php require_once '../build/estilos.vista.php'; ?>
        <?php require_once '../build/estilos.dataTables.vista.php'; ?>
        <link rel="stylesheet" type="text/css" href="../../util/noty/lib/noty.css" />   
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
                        <div class="panel-body">
                            <div class="row">
                              <div class="col-md-10">
                                <h1><strong>Plan de Producción: <i id="txtnombre_plan_produccion"></i></strong></h1>
                              </div>                              
                            </div>
                            <div class="row">
                              <div class="x_panel col-xs-12">
                                  <div class="x_content">
                                    <div class="row">
                                      <div class="col-xs-4">
                                        Fecha Creación: 
                                        <p id="txtfecha_creacion"></p>
                                      </div>
                                      <div class="col-xs-4">  
                                        Fecha Finalización:                                       
                                        <p id="txtfecha_finalizacion"></p>
                                      </div>
                                      <div class="col-xs-4"> 
                                        Estado actual:                                        
                                        <p id="txtestado_proceso"></p>
                                      </div>                    
                                    </div>
                                  </div>
                              </div>
                            </div>

                            <div class="row" id="blk-listado-pedido">
                                <div class="x_panel col-xs-12">
                                    <div class="x_title">
                                        <h3>Listado de Pedidos
                                          <ul class="nav navbar-right panel_toolbox">
                                          <li id="btnAgregarPedido"><span style="color:white" class="right btn btn-dark" data-toggle="modal" href="#myModal"><i class="fa fa-plus"></i> Agregar Pedidos</span></li>
                                          </ul>
                                        </h3>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                      <table class="table table-responsive">
                                        <thead>
                                          <th class="text-center">#</th>
                                          <th class="text-center">Cliente</th>
                                          <th class="text-center">Fecha Aceptación</th>
                                          <th class="text-center">Monto Pedido (S/)</th>                          
                                          <th class="text-center">OPC</th>
                                        </thead>
                                        <tbody id="detalle-pedidos-produccion">
                                        </tbody>
                                      </table>
                                    </div>      
                                </div>
                            </div>
                            
                            <!-- INICIO : MODAL SELECCION DE PEDIDO -->
                            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" id="myModal" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">Seleccionar pedido</h4>
                                        </div>
                                        <div class="modal-body">                                            
                                            <div class="row">
                                                <div class="col-xs-12 ">
                                                    <table id="tabla-listados-pedidos-aceptado" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>                                                                    
                                                                <th class="text-center">Código</th>
                                                                <th class="text-center">Pedido</th>
                                                                <th class="text-center">Monto (S/)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="listar-pedidos-aceptado">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- FIN : MODAL SELECCION DE PEDIDO -->


                             <!-- INICIO : VER DETALLE DE PEDIDO -->
                            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" id="myModal2" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title"></h4>
                                        </div>
                                        <div class="modal-body">                                                
                                            <div class="row">
                                                <div class="col-xs-12 ">
                                                    <table id="tabla-listado-detalle-pedido" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr> 
                                                                <th class="text-center">N°</th>                                                                   
                                                                <th class="text-center">Producto</th>
                                                                <th class="text-center">Cantidad (unid.)</th>
                                                                <th class="text-center">Precio (S/)</th>
                                                                <th class="text-center">Importe (S/)</th>                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody id="ver-detalle-pedido">
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="4" class="text-center"><b>TOTAL :</b></td>
                                                                <td class="text-center"><p id="txtimporteneto"></p></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- INICIO : VER DETALLE DE PEDIDO -->

                            <div class="row" id="blk-listado-requerimiento-interno">
                                <div class="x_panel col-xs-12">
                                    <div class="x_title">
                                        <h3>Requisitos Internos
                                          <ul class="nav navbar-right panel_toolbox">
                                          <li id="btnInterior"><span style="color:white" class="right btn btn-dark" data-toggle="modal" href="#myModal3"><i class="fa fa-plus"></i> Agregar req. interno</span></li>
                                          </ul>
                                        </h3>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                      <table class="table table-responsive">
                                        <thead>
                                          <th class="text-center">#</th>
                                          <th class="text-center">Motivo</th>
                                          <th class="text-center">Costo (S/)</th>
                                          <th class="text-center">OPC</th> 
                                        </thead>
                                        <tbody id="ver-reque-interno">                                  
                                        </tbody>
                                      </table>
                                    </div>   
                                </div>
                            </div> 

                            

                            <!-- INICIO : MODAL REQUERIMIENTO INTERNO -->
                            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" id="myModal3" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title"></h4>
                                        </div>
                                        <form name="frm-grabar-requisitos" id="frm-grabar-requisitos"  role="form">
                                          <div class="modal-body">                                            
                                              
                                              <div class="row">
                                                  <div class="col-xs-12">
                                                      Pieza                                                     
                                                      <select id="txtcodigo_pieza" class="form-control input-sm" name="txtcodigo_pieza" >                                                            
                                                      </select>
                                                  </div>                                           
                                              </div>
                                              <div class="row">     
                                                  <div class="col-xs-6">
                                                      Precio<input type="text" name="txtprecio" id="txtprecio" class="form-control input-sm" />                                                        
                                                  </div>
                                                  <div class="col-xs-6">
                                                      Cantidad<input type="number" name="txtcantidad" id="txtcantidad" class="form-control input-sm" min="1"/>                                                        
                                                  </div>                                         
                                              </div>
                                              <hr>
                                              <div class="row">
                                                  <div class="col-xs-12">
                                                    Observaciones<textarea class="form-control input-sm" rows="2" name="txtmotivo" id="txtmotivo" required=""></textarea>                                                    
                                                  </div>
                                              </div>
                                              <br> 
                                              <div class="row">
                                                  <div class="col-xs-12 ">
                                                      <table id="tabla-listado-detalle" class="table table-bordered table-striped">
                                                          <thead>
                                                              <tr> 
                                                                  <th class="text-center">ITEM</th>  
                                                                  <th class="text-center">Pieza</th>
                                                                  <th class="text-center">Precio (S/)</th>
                                                                  <th class="text-center">Cantidad (unid.)</th>
                                                                  <th class="text-center">Importe (S/)</th>
                                                              </tr>
                                                          </thead>
                                                          <tbody id="detalle-pedido-pieza">

                                                          </tbody>
                                                          <tfoot>
                                                            <tr>
                                                                <td colspan="4" class="text-center"><b>TOTAL :</b></td>
                                                                <td class="text-center"><p id="txtimportenetopieza"></p></td>
                                                            </tr>
                                                          </tfoot>
                                                      </table>
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="modal-footer">
                                              <button id="btnCarritoPieza" class="btn btn-primary" title="Agregar a Plan de Producción" type="button">Agregar a Plan de Producción</button>
                                              <button class="btn btn-success" type="submit">Grabar</button>
                                          </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- FIN : MODAL SELECCION DE PIEZA -->


                            <!-- INICIO : MODAL BUSCAR PIEZA -->
                            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" id="myModal4" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">Seleccionar pieza</h4>
                                        </div>
                                        <div class="modal-body">
                                            <!-- LISTADO DE PRODUCTO -->
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    Buscar pieza
                                                    <input type="text" name="txtbuscar" id="txtbuscar" class="form-control input-sm" style="text-transform:uppercase;"/>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-xs-12 ">
                                                    <table id="tabla-listados-productos" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>                                                                    
                                                                <th class="text-center">CÓDIGO</th>
                                                                <th class="text-center">PRODUCTO</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="filtro-piezas">                                                         
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- LISTADO DE PRODUCTO -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- FIN : MODAL BUSCAR PIEZA -->

                            <!-- INICIO : MODAL BUSCAR PIEZA -->
                            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" id="myModal5" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title"></h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-xs-12 ">
                                                    <table id="tabla-listados-productos" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>                                                                    
                                                                <th class="text-center">CÓDIGO</th>
                                                                <th class="text-center">REQ. INTERNO (unid.)</th>
                                                                <th class="text-center">COSTO (S/)</th>
                                                                <th class="text-center">CANTIDAD </th>
                                                                <th class="text-center">IMPORTE (S/)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="ver-requisito-interno">                                                         
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- LISTADO DE PRODUCTO -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- FIN : MODAL BUSCAR PIEZA -->
                            <!-- INICIO : LISTADO DE PRODUCTOS E PIEZAS -->
                            <div class="row">
                              <div class="col-sm-6 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h3>Listado de Productos</h3>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                      <table class="table table-responsive">
                                        <thead>
                                          <th class="text-center">#</th>
                                          <th class="text-center">Producto</th>
                                          <th class="text-center">Cantidad (unid.)</th>
                                        </thead>
                                        <tbody id="ver-plan-producto">                          
                                        </tbody>
                                      </table>
                                    </div>      
                                </div>
                              </div>


                              <div class="col-sm-6 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h3>Listado de Piezas</h3>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                      <table class="table table-responsive">
                                        <thead>
                                          <th class="text-center">#</th>
                                          <th class="text-center">Pieza</th>
                                          <th class="text-center">Cantidad (unid.)</th>
                                        </thead>
                                        <tbody id="ver-plan-pieza">
                                          
                                      </table>
                                    </div>      
                                </div>
                              </div>
                            </div> 

                            <div class="text-left">
                              <button class="btn btn-lg btn-success" id="btnIniciar">Registrar pedidos</button>
                            </div>
                            <hr>
                            <div class="row" style="display: none;" id="blk-fase-acondicionamiento">
                              <div class="col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h3>Fase de Acondicionamiento</h3>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                      <div class="row">
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group row">
                                              <div class="col-sm-4">
                                                <label>Costo Extra (S/):</label>
                                                <input id="txtcosto_extra" type="text" class="form-control"/>
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <div class="col-sm-6">
                                                <label>Costo Materia Prima a Ahorrar(S/):</label>
                                                <h5 class="text-center" id="txtcosto_materia_prima_ahorro"></h5>
                                              </div>
                                              <div class="col-sm-6">
                                                <label>Costo Materia Prima a Comprar(S/):</label>
                                                <h5 class="text-center" id="txtcosto_materia_prima"></h5>
                                              </div>
                                            </div>
                                            <div class="form-group">
                                              <label>Comentario: </label>
                                              <textarea id="txtmotivo_costo" rows="5" class="form-control" style="text-transform:uppercase;"></textarea/>
                                            </div>
                                            <div class="form-group row">
                                              <div class="col-sm-10">
                                                <label>Fecha Acondicionamiento</label>
                                                <input id="txtfecha_acondicionamiento" type="datetime-local" class="form-control"/>
                                                <input id="fa_formateada" type="text" class="form-control" readonly />
                                              </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-8 col-xs-12">
                                          <b>Materia Prima Fundición</b>
                                            <table class="table table-responsive">
                                              <thead>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Materia Prima (unid.)</th>
                                                <th class="text-center">Precio Unitario (S/)</th>
                                                <th class="text-center">Cant. Necesaria </th>
                                                <th class="text-center">Cant. Comprar </th>
                                                <th class="text-center bg-success">Cant. Usada de Almacén</th>
                                                <th class="text-center">Total Recuperado (S/) </th>
                                                <th class="text-center">Total a Comprar (S/) </th>
                                              </thead>
                                              <tbody id="acondicionamiento-fundicion-detalle-mp">
                                              </tbody>
                                            </table>
                                            <b>Materia Prima Acabado</b>
                                             <table class="table table-responsive">
                                              <thead>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Materia Prima (unid.)</th>
                                                <th class="text-center">Precio Unitario (S/)</th>
                                                <th class="text-center">Cant. Necesaria </th>
                                                <th class="text-center">Cant. Comprar </th>
                                                <th class="text-center bg-success">Cant. Usada de Almacén</th>
                                                <th class="text-center">Total Recuperado (S/) </th>
                                                <th class="text-center">Total a Comprar (S/) </th>
                                              </thead>
                                              <tbody id="acondicionamiento-detalle-mp">
                                              </tbody>
                                            </table>
                                        </div>  
                                      </div>    
                                      <div class="text-left">
                                        <button class="btn btn-lg btn-success" id="btnAcondicionamiento">Fin acondicionamiento</button>
                                      </div>
                                    </div>
                                </div>
                              </div>
                            </div>
                            <div class="row" style="display: none;" id="blk-fase-fundicion">
                              <div class="col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h3>Fase de Fundición</h3>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                      <div class="row">
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group row">
                                              <div class="col-sm-6">
                                                <label>Tiempo Fundición (horas):</label>
                                                <input type="text" class="form-control" id="txtminutos_fundicion"/>
                                              </div>
                                              <div class="col-sm-6">
                                                <label>Costos Fundición (S/)</label>
                                                <input type="text" class="form-control" id="txtcosto_fundicion"/>
                                              </div>
                                            </div>
                                            <div class="form-group">
                                              <label>Comentarios: </label>
                                              <textarea rows="4" class="form-control" id="txtcomentario_fundicion" style="text-transform:uppercase;"></textarea/>
                                            </div>
                                            <div class="form-group row">
                                              <div class="col-sm-10">
                                                <label>Fecha Fundición</label>
                                                <input type="datetime-local" class="form-control" id="txtfecha_fundicion"/>
                                                <input id="ff_formateada" type="text" class="form-control" readonly />
                                              </div>
                                            </div>                                            
                                        </div> 
                                        <div class="col-sm-8 col-xs-12">
                                          <div class="row">
                                             <b>Piezas a generar por Fundición</b>
                                              <table class="table table-responsive">
                                                <thead>
                                                  <th class="text-center">#</th>
                                                  <th class="text-center">Piezas </th>
                                                  <th class="text-center">Cantidad (unid.)</th>
                                                </thead>
                                                <tbody id="ver-detalle-piezas">
                                                </tbody>
                                              </table>
                                          </div>
                                           <div class="row">
                                             <b>Materia Prima consumida en esta Fase</b>
                                              <table class="table table-responsive">
                                              <thead>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Materia Prima (unid.)</th>
                                                <th class="text-center">Precio Unitario S/)</th>
                                                <th class="text-center">Cant. Utilizada </th>
                                                <th class="text-center">Cant. Sobrante </th>
                                                <th class="text-center">Total Consumido(S/) </th>
                                              </thead>
                                              <tbody id="fundicion-detalle-mp">
                                              </tbody>
                                            </table>
                                          </div>
                                         
                                        </div>
                                      </div>    
                                      <div class="text-left">
                                        <button class="btn btn-lg btn-success" style="display:none" id="btnFundicion">Fin de Fase</button>
                                      </div>
                                    </div>
                                </div>
                              </div>
                            </div>



            <div class="row" style="display: none;" id="blk-fase-acabado">              
              <div class="col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h3>Fase de acabado</h3>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
 
                        <div class="x_panel" style="display: none;" id="blk-sub-fase-1">
                          <div class="x_title">
                              <h3 id="actividad-1"></h3>
                              <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <div class="row">
                              <div class="col-sm-5 col-xs-12">
                                  <div class="form-group row">
                                    <div class="col-sm-12">
                                      <label>Fecha/Hora Inicio:</label>
                                      <input type="datetime-local" class="form-control" id="txtFechaInicioA1"/>
                                      <input type="text" class="form-control" id="txtMFechaInicioA1" readonly />
                                    </div>
                                    <div class="col-sm-12">
                                      <label>Fecha/Hora Fin:</label>
                                      <input type="datetime-local" class="form-control" id="txtFechaFinA1"/>
                                      <input type="text" class="form-control" id="txtMFechaFinA1" readonly />
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label>Comentarios: </label>
                                    <textarea rows="4" class="form-control" id="txtMotivoA1"  style="text-transform:uppercase;"></textarea>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-sm-6 col-xs-12">
                                      <label># Colaboradores Ocupados:</label>
                                      <input type="text" class="form-control" id="txtColaboradorA1"/>
                                    </div>
                                  </div>
                              </div> 
                              <div class="col-sm-7 col-xs-12">
                                <table class="table table-responsive">
                                  <thead >
                                    <th class="text-center">#</th>
                                    <th class="text-center">Pieza</th>
                                    <th class="text-center">Buenas</th>                                    
                                    <th class="text-center">Malas</th>                                    
                                    <th class="text-center">% Buenas</th>
                                    <th class="text-center">OPC</th>
                                  </thead>
                                  <tbody class="text-center" id="actividad1-detalle">
                                  </tbody>
                                </table>
                              </div>
                            </div>    
                            <div class="text-left">
                              <button class="btn btn-lg btn-success" id="btnActividad01">Fin Actividad</button>
                            </div>
                          </div>                        
                        </div>

                        <div class="x_panel" style="display: none;" id="blk-sub-fase-2">
                          <div class="x_title">
                              <h3 id="actividad-2"></h3>
                              <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <div class="row">
                              <div class="col-sm-5 col-xs-12">
                                  <div class="form-group row">
                                    <div class="col-sm-12">
                                      <label>Fecha/Hora Inicio:</label>
                                      <input type="datetime-local" class="form-control" id="txtFechaInicioA2"/>
                                      <input type="text" class="form-control" id="txtMFechaInicioA2" readonly/>
                                    </div>
                                    <div class="col-sm-12">
                                      <label>Fecha/Hora Fin:</label>
                                      <input type="datetime-local" class="form-control" id="txtFechaFinA2"/>
                                      <input type="text" class="form-control" id="txtMFechaFinA2" readonly/>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label>Comentarios: </label>
                                    <textarea rows="4" class="form-control" id="txtMotivoA2" style="text-transform:uppercase;"></textarea>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-sm-6 col-xs-12">
                                      <label># Colaboradores Ocupados:</label>
                                      <input type="text" class="form-control" id="txtColaboradorA2"/>
                                    </div>
                                  </div>
                              </div> 
                              <div class="col-sm-7 col-xs-12">
                                <table class="table table-responsive">
                                  <thead >
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nombre Pieza</th>
                                    <th class="text-center">Pzas. Buenas</th>                                    
                                    <th class="text-center">Pzas. Falladas</th>                                    
                                    <th class="text-center">% Buenas </th>
                                    <th class="text-center">OPC</th>
                                  </thead>
                                  <tbody class="text-center" id="actividad2-detalle">
                                  </tbody>
                                </table>
                              </div>
                            </div>    
                            <div class="text-left">
                              <button class="btn btn-lg btn-success" id="btnActividad02">Fin Actividad</button>
                            </div>
                          </div>                        
                        </div>

                        <div class="x_panel" style="display: none;" id="blk-sub-fase-3">
                          <div class="x_title">
                              <h3 id="actividad-3"></h3>
                              <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <div class="row">
                              <div class="col-sm-5 col-xs-12">
                                  <div class="form-group row">
                                    <div class="col-sm-12">
                                      <label>Fecha/Hora Inicio:</label>
                                      <input type="datetime-local" class="form-control" id="txtFechaInicioA3"/>
                                      <input type="text" class="form-control" id="txtMFechaInicioA3" readonly/>
                                    </div>
                                    <div class="col-sm-12">
                                      <label>Fecha/Hora Fin:</label>
                                      <input type="datetime-local" class="form-control" id="txtFechaFinA3"/>
                                      <input type="text" class="form-control" id="txtMFechaFinA3" readonly/>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label>Comentarios: </label>
                                    <textarea rows="4" class="form-control" id="txtMotivoA3" style="text-transform:uppercase;"></textarea>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-sm-6 col-xs-12">
                                      <label># Colaboradores Ocupados:</label>
                                      <input type="text" class="form-control" id="txtColaboradorA3"/>
                                    </div>
                                  </div>
                              </div> 
                              <div class="col-sm-7 col-xs-12">
                                <table class="table table-responsive">
                                  <thead >
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nombre Pieza</th>
                                    <th class="text-center">Pzas. Buenas</th>                                    
                                    <th class="text-center">Pzas. Falladas</th>                                    
                                    <th class="text-center">% Buenas </th>
                                    <th class="text-center">OPC</th>
                                  </thead>
                                  <tbody class="text-center" id="actividad3-detalle">
                                  </tbody>
                                </table>
                              </div>
                            </div>    
                            <div class="text-left">
                              <button class="btn btn-lg btn-success" id="btnActividad03">Fin Actividad</button>
                            </div>
                          </div>                        
                        </div>

                        <div class="x_panel" style="display: none;" id="blk-sub-fase-4">
                          <div class="x_title">
                              <h3 id="actividad-4"></h3>
                              <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <div class="row">
                              <div class="col-sm-5 col-xs-12">
                                  <div class="form-group row">
                                    <div class="col-sm-12">
                                      <label>Fecha/Hora Inicio:</label>
                                      <input type="datetime-local" class="form-control" id="txtFechaInicioA4"/>
                                      <input type="text" class="form-control" id="txtMFechaInicioA4" readonly/>
                                    </div>
                                    <div class="col-sm-12">
                                      <label>Fecha/Hora Fin:</label>
                                      <input type="datetime-local" class="form-control" id="txtFechaFinA4"/>
                                      <input type="text" class="form-control" id="txtMFechaFinA4" readonly/>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label>Comentarios: </label>
                                    <textarea rows="4" class="form-control" id="txtMotivoA4" style="text-transform:uppercase;"></textarea>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-sm-6 col-xs-12">
                                      <label># Colaboradores Ocupados:</label>
                                      <input type="text" class="form-control" id="txtColaboradorA4"/>
                                    </div>
                                  </div>
                              </div> 
                              <div class="col-sm-7 col-xs-12">
                                <table class="table table-responsive">
                                  <thead >
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nombre Pieza</th>
                                    <th class="text-center">Pzas. Buenas</th>                                    
                                    <th class="text-center">Pzas. Falladas</th>                                    
                                    <th class="text-center">% Buenas </th>
                                    <th class="text-center">OPC</th>
                                  </thead>
                                  <tbody class="text-center" id="actividad4-detalle">
                                  </tbody>
                                </table>
                              </div>
                            </div>    
                            <div class="text-left">
                              <button class="btn btn-lg btn-success" id="btnActividad04">Fin Actividad</button>
                            </div>
                          </div>                        
                        </div>

                        <div class="x_panel" style="display: none;" id="blk-sub-fase-5">
                          <div class="x_title">
                              <h3 id="actividad-5"></h3>
                              <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <div class="row">
                              <div class="col-sm-5 col-xs-12">
                                  <div class="form-group row">
                                    <div class="col-sm-12">
                                      <label>Fecha/Hora Inicio:</label>
                                      <input type="datetime-local" class="form-control" id="txtFechaInicioA5"/>
                                      <input type="text" class="form-control" id="txtMFechaInicioA5" readonly/>
                                    </div>
                                    <div class="col-sm-12">
                                      <label>Fecha/Hora Fin:</label>
                                      <input type="datetime-local" class="form-control" id="txtFechaFinA5"/>
                                      <input type="text" class="form-control" id="txtMFechaFinA5" readonly/>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label>Comentarios: </label>
                                    <textarea rows="4" class="form-control" id="txtMotivoA5" style="text-transform:uppercase;"></textarea>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-sm-6 col-xs-12">
                                      <label># Colaboradores Ocupados:</label>
                                      <input type="text" class="form-control" id="txtColaboradorA5"/>
                                    </div>
                                  </div>
                              </div> 
                              <div class="col-sm-7 col-xs-12">
                                <table class="table table-responsive">
                                  <thead >
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nombre Pieza</th>
                                    <th class="text-center">Pzas. Buenas</th>                                    
                                    <th class="text-center">Pzas. Falladas</th>                                    
                                    <th class="text-center">% Buenas </th>
                                    <th class="text-center">OPC</th>
                                  </thead>
                                  <tbody class="text-center" id="actividad5-detalle">
                                  </tbody>
                                </table>
                              </div>
                            </div>    
                            <div class="text-left">
                              <button class="btn btn-lg btn-success" id="btnActividad05">Fin Actividad</button>
                            </div>
                          </div>                        
                        </div>

                        <div class="x_panel" style="display: none;" id="blk-sub-fase-6">
                          <div class="x_title">
                              <h3 id="actividad-6"></h3>
                              <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <div class="row">
                              <div class="col-sm-5 col-xs-12">
                                  <div class="form-group row">
                                    <div class="col-sm-12">
                                      <label>Fecha/Hora Inicio:</label>
                                      <input type="datetime-local" class="form-control" id="txtFechaInicioA6"/>
                                      <input type="text" class="form-control" id="txtMFechaInicioA6" readonly/>
                                    </div>
                                    <div class="col-sm-12">
                                      <label>Fecha/Hora Fin:</label>
                                      <input type="datetime-local" class="form-control" id="txtFechaFinA6"/>
                                      <input type="text" class="form-control" id="txtMFechaFinA6" readonly/>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label>Comentarios: </label>
                                    <textarea rows="4" class="form-control" id="txtMotivoA6" style="text-transform:uppercase;"></textarea>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-sm-6 col-xs-12">
                                      <label># Colaboradores Ocupados:</label>
                                      <input type="text" class="form-control" id="txtColaboradorA6"/>
                                    </div>
                                  </div>
                              </div> 
                              <div class="col-sm-7 col-xs-12">
                                <table class="table table-responsive">
                                  <thead >
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nombre Pieza</th>
                                    <th class="text-center">Pzas. Buenas</th>                                    
                                    <th class="text-center">Pzas. Falladas</th>                                    
                                    <th class="text-center">% Buenas </th>
                                    <th class="text-center">OPC</th>
                                  </thead>
                                  <tbody class="text-center" id="actividad6-detalle">
                                  </tbody>
                                </table>
                              </div>
                            </div>    
                            <div class="text-left">
                              <button class="btn btn-lg btn-success" id="btnActividad06">Fin Actividad</button>
                            </div>
                          </div>                        
                        </div>

                        <div class="x_panel" style="display: none;" id="blk-sub-fase-7">
                          <div class="x_title">
                              <h3 id="actividad-7"></h3>
                              <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <div class="row">
                              <div class="col-sm-5 col-xs-12">
                                  <div class="form-group row">
                                    <div class="col-sm-12">
                                      <label>Fecha/Hora Inicio:</label>
                                      <input type="datetime-local" class="form-control" id="txtFechaInicioA7"/>
                                      <input type="text" class="form-control" id="txtMFechaInicioA7" readonly/>
                                    </div>
                                    <div class="col-sm-12">
                                      <label>Fecha/Hora Fin:</label>
                                      <input type="datetime-local" class="form-control" id="txtFechaFinA7"/>
                                      <input type="text" class="form-control" id="txtMFechaFinA7" readonly/>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label>Comentarios: </label>
                                    <textarea rows="4" class="form-control" id="txtMotivoA7" style="text-transform:uppercase;"></textarea>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-sm-6 col-xs-12">
                                      <label># Colaboradores Ocupados:</label>
                                      <input type="text" class="form-control" id="txtColaboradorA7"/>
                                    </div>
                                  </div>
                              </div> 
                              <div class="col-sm-7 col-xs-12">
                                <table class="table table-responsive">
                                  <thead >
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nombre Pieza</th>
                                    <th class="text-center">Pzas. Buenas</th>                                    
                                    <th class="text-center">Pzas. Falladas</th>                                    
                                    <th class="text-center">% Buenas </th>
                                    <th class="text-center">OPC</th>
                                  </thead>
                                  <tbody class="text-center" id="actividad7-detalle">
                                  </tbody>
                                </table>
                              </div>
                            </div>    
                            <div class="text-left">
                              <button class="btn btn-lg btn-success" id="btnActividad07">Fin Actividad</button>
                            </div>
                          </div>                        
                        </div>

                        <div class="x_panel" style="display: none;" id="blk-sub-fase-8">
                          <div class="x_title">
                              <h3 id="actividad-8"></h3>
                              <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <div class="row">
                              <div class="col-sm-5 col-xs-12">
                                  <div class="form-group row">
                                    <div class="col-sm-12">
                                      <label>Fecha/Hora Inicio:</label>
                                      <input type="datetime-local" class="form-control" id="txtFechaInicioA8"/>
                                      <input type="text" class="form-control" id="txtMFechaInicioA8" readonly/>
                                    </div>
                                    <div class="col-sm-12">
                                      <label>Fecha/Hora Fin:</label>
                                      <input type="datetime-local" class="form-control" id="txtFechaFinA8"/>
                                      <input type="text" class="form-control" id="txtMFechaFinA8" readonly/>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label>Comentarios: </label>
                                    <textarea rows="4" class="form-control" id="txtMotivoA8" style="text-transform:uppercase;"></textarea>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-sm-6 col-xs-12">
                                      <label># Colaboradores Ocupados:</label>
                                      <input type="text" class="form-control" id="txtColaboradorA8"/>
                                    </div>
                                  </div>
                              </div> 
                              <div class="col-sm-7 col-xs-12">
                                <h4><b>Productos a obtener tras emsamblaje<b></h4>
                                 <table class="table table-responsive">
                                  <thead>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Producto</th>
                                    <th class="text-center">Cantidad (unid.)</th>
                                  </thead>
                                  <tbody id="ver_productos_finales">
                                  </tbody>
                                </table>
                                 <div class="row">
                                  <div class="col-xs-12">
                                     <h4><b>Piezas para ensamblaje<b></h4>
                                     <table class="table table-responsive">
                                      <thead>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Pieza</th>
                                        <th class="text-center">Cantidad (unid.)</th>
                                      </thead>
                                      <tbody id="ver_piezas_finales">                                         
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>    

                            <div class="text-left">
                              <button class="btn btn-lg btn-success"  id="btnActividad08">Fin Actividad</button>
                            </div>
                          </div>                        
                        </div>

                        <div class="x_panel" style="display: none;" id="blk-sub-fase-9">
                          <div class="x_title">
                              <h3 id="actividad-9"></h3>
                              <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <div class="row">
                              <div class="col-sm-5 col-xs-12">
                                  <div class="form-group row">
                                    <div class="col-sm-12">
                                      <label>Fecha/Hora Inicio:</label>
                                      <input type="datetime-local" class="form-control" id="txtFechaInicioA9"/>
                                      <input type="text" class="form-control" id="txtMFechaInicioA9" readonly/>
                                    </div>
                                    <div class="col-sm-12">
                                      <label>Fecha/Hora Fin:</label>
                                      <input type="datetime-local" class="form-control" id="txtFechaFinA9"/>
                                      <input type="text" class="form-control" id="txtMFechaFinA9" readonly/>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label>Comentarios: </label>
                                    <textarea rows="4" class="form-control" id="txtMotivoA9"  style="text-transform:uppercase;"></textarea>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-sm-6 col-xs-12">
                                      <label># Colaboradores Ocupados:</label>
                                      <input type="text" class="form-control" id="txtColaboradorA9"/>
                                    </div>
                                  </div>
                              </div> 
                              <div class="col-sm-7 col-xs-12">
                                <h4><b>Productos Empaquetados en Almacen<b></h4>
                                 <table class="table table-responsive">
                                  <thead>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Pieza (unid.)</th>
                                    <th class="text-center">Costo Producción (S/)</th>
                                    <th class="text-center">Cantidad </th>
                                    <th class="text-center">Total (S/) </th>
                                  </thead>
                                  <tbody id="ver_productos_almacen">                                    
                                  </tbody>
                                </table>
                                <h4><b>Piezas almacenadas en Almacen<b></h4>
                                 <table class="table table-responsive">
                                  <thead>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Pieza </th>
                                    <th class="text-center">Cantidad (unid.) </th>
                                  </thead>
                                  <tbody id="ver_piezas_almacen">                                    
                                  </tbody>
                                </table>
                                 <h4><b>Materia Prima aproximada retornada conservada en Almacen<b></h4>
                                 <table class="table table-responsive">
                                  <thead>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Materia Prima (unid.)</th>
                                    <th class="text-center">Precio (S/)</th>
                                    <th class="text-center">Cantidad </th>
                                    <th class="text-center">Total (S/) </th>
                                  </thead>
                                  <tbody id="ver_materia_prima_almacen">                                    
                                  </tbody>
                                </table>
                              </div>
                            </div>    

                            <div class="text-left">
                              <button class="btn btn-lg btn-success" id="btnActividad09">Fin Actividad</button>
                            </div>
                          </div>                        
                        </div>
                        <div class="col-md-2 col-md-offset-10">
                                <button class="btn btn-success form-control" style="display:none;" id="btnFinalizar">Finalizar</button>
                              </div>
                    </div>
                </div>
              </div>
<!-- MODAL ACTIVIDAD -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" id="myModalActividad" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" id="txtcodigo_plan_produccion_pieza" class="form-control input-sm" readonly=""/>                                                        
                <input type="hidden" id="txtoperacion" class="form-control input-sm" readonly=""/> 
                <input type="hidden" id="txttotal_piezas" class="form-control input-sm" readonly="" />
                <input type="hidden" id="txtactividad" class="form-control input-sm" readonly=""/>
                <button id="btnCerrarFalla" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">                                            
                <div class="row">
                    <div class="col-xs-12">                         
                      Motivo<textarea class="form-control input-sm" rows="2" id="txtdescripcion" required=""></textarea>                                                    
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        Falla
                        <select id="txtcodigo_tipo_falla" class="form-control input-sm" name="txtcodigo_tipo_falla" >                                                            
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6"> 
                        Piezas buenas<input type="text" id="txttotal_lote" class="form-control input-sm" readonly="" />                                                      
                                                                            
                    </div> 
                    <div class="col-xs-6">
                        Cantidad<input type="text" id="txtcantidad_fallas" class="form-control input-sm" />                           
                    </div>                                                  
                </div>

                <br> 
                <div class="row">
                    <div class="col-xs-12 ">
                        <table id="tabla-listado-detalle" class="table table-bordered table-striped">
                            <thead>
                                <tr> 
                                    <th class="text-center">ITEM</th>  
                                    <th class="text-center">Motivo</th>
                                    <th class="text-center">Falla</th>
                                    <th class="text-center">Cantidad (unid.)</th>
                                </tr>
                            </thead>
                            <tbody id="detalle-falla-pieza">

                            </tbody>
                        </table>
                    </div>
                </div>        
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" type="button" id="btnCarritoFalla">Agregar</button>
                <button class="btn btn-success" type="button" id="btnGrabarFalla">Grabar</button>
            </div>
            
        </div>
    </div>
</div>
<!-- MODAL ACTIVIDAD -->


<!-- MODAL VER ACTIVIDAD -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" id="myModalVerActividad" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Mostrar</h4>
            </div>            
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-xs-12 ">
                        <table id="ma_tabla-listado-detalle" class="table table-bordered table-striped">
                            <thead>
                                <tr> 
                                    <th class="text-center">N°</th>  
                                    <th class="text-center">Actividad</th>
                                    <th class="text-center">Falla</th>
                                    <th class="text-center">Motivo</th>
                                    <th class="text-center">Pieza total (unid.)</th>
                                    <th class="text-center">Pieza buenas (unid.)</th>
                                    <th class="text-center">Pieza malas (unid.)</th>
                                </tr>
                            </thead>
                            <tbody id="ver_detalle-falla-pieza">

                            </tbody>
                        </table>
                    </div>
                </div>        
            </div>            
        </div>
    </div>
</div>
<!-- MODAL MOSTRAR -->



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
        <script src="../../util/noty/lib/noty.js"></script>
        <script src="../../util/js/p_util.js"></script>
        <script src="../../util/js/util.js"></script>


        <script src="index.js"></script>
    </body>
</html>




