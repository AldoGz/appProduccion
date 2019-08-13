<?php
require_once '../build/Acceso.clase.php';
require_once '../build/validar-user.php';
$objAcceso = new Acceso(array(1));
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once '../build/metas.vista.php'; ?>
        <title>Ver Almacen</title>
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

                    <div class="col-md-offset-2 col-md-8 col-sm-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><i class="fa fa-paste"></i> Ver Almacen</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">


                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class=""><a href="#tab_productos" id="productos-tab" role="tab" data-toggle="tab" aria-expanded="false">Productos</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_piezas" role="tab" id="piezas-tab" data-toggle="tab" aria-expanded="false">Piezas</a>
                        </li>
                        <li role="presentation" class="active"><a href="#tab_mp" role="tab" id="mp-tab" data-toggle="tab" aria-expanded="true">Materia Prima</a>
                        </li>
                      </ul>
                      <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade " id="tab_productos" aria-labelledby="productos-tab">
                            <table class="table table-bordered">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Codigo</th>
                                  <th>Nombre</th>
                                  <th>Cantidad</th>
                                </tr>
                              </thead>
                              <tbody id="tbl-productos">
                                
                              </tbody>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab_piezas" aria-labelledby="piezas-tab">
                            <table class="table table-bordered">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Codigo</th>
                                  <th>Nombre</th>
                                  <th>Cantidad</th>
                                </tr>
                              </thead>
                              <tbody id="tbl-piezas">
                                
                              </tbody>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane fade active in" id="tab_mp" aria-labelledby="mp-tab">
                              <table class="table table-bordered">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Codigo</th>
                                  <th>Nombre</th>
                                  <th>Cantidad</th>
                                </tr>
                              </thead>
                              <tbody id="tbl-mp">
                                
                              </tbody>
                            </table>
                        </div>
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
