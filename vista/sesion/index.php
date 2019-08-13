<?php
require_once '../build/validar-sesion.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require_once '../build/metas.vista.php'; ?>
        <title>INICIAR SESIÓN</title>
        <?php require_once '../build/estilos.vista.php'; ?>        
    </head>

    <body class="login">
        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">                    
                    <form name="frm-sesion" id="frm-sesion"  role="form">
                        <h1>INICIAR SESIÓN</h1>
                        <div class="row">
                            <div class="col-xs-12">
                                <input type="text"name="txtusuario" id="txtusuario" class="form-control" placeholder="Ingrese usuario" required="" autofocus=""/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <input type="password" name="txtclave" id="txtclave" class="form-control" placeholder="Ingrese clave" required="" minlength="6" maxlength="20"  />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">                            
                                <button type="submit" class="btn btn-success btn-sm">Ingresar</button>                                                         
                            </div>
                        </div>                      
                    </form>                   
                </section>
            </div>

        </div> 

        <!-- MODAL PARA REGISTRAR CLIENTE -->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
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
                                <div class="col-xs-12">
                                    Direccion<input type="text" name="txtdireccion" id="txtdireccion" class="form-control input-sm" style="text-transform:uppercase;"/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-6">
                                    Teléfono móvil<input type="text" name="txttelefono_movil" id="txttelefono_movil" class="form-control input-sm" />
                                </div>
                                <div class="col-xs-6">
                                    Correo electrónico<input type="text" name="txtemail" id="txtemail" class="form-control input-sm"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    Usuario<input type="text" name="txtuser_cliente" id="txtuser_cliente" class="form-control input-sm" />
                                </div>
                                <div class="col-xs-6">
                                    Clave<input type="password" name="txtclave_cliente" id="txtclave_cliente" class="form-control input-sm" />
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


            
              
        <?php
        require_once '../build/scripts.vista.php';        
        ?>
        
        <script src="../../util/js/util.js"></script>          
        <script src="index.js"></script>
    </body>
</html>
