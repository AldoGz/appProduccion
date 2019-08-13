var DOM = {};

$(document).ready(function () {
    setDOM();
    setEventos();
    listar();
    vista();
    cargarDepartamento();

});



function setDOM() {
    DOM.form = $("#frm-grabar"),
    DOM.listado = $("#listado"),
    DOM.self = $("#myModal"),
    DOM.btnAgregar = $("#btnAgregar"),
    DOM.operacion = $("#txtoperacion"),

    DOM.cod_cliente = $("#txtcodigo_cliente"),
    DOM.tipo_documento = $("#txttipo_cliente"),
    DOM.documento = $("#txtdocumento"),
    DOM.razon_social = $("#txtrazon_social"),
    DOM.razon_comercial = $("#txtrazon_comercial"),
    DOM.telefono_movil = $("#txttelefono_movil"),
    DOM.correo = $("#txtemail"),
    DOM.direccion = $("#txtdireccion"),
    DOM.user = $("#txtuser_cliente"),
    DOM.user_clave = $("#txtclave_cliente"),
    DOM.nombres = $("#txtnombres"),
    DOM.apellidos = $("#txtapellidos");


    DOM.depatarmento = $("#txtdepartamento"),
    DOM.provincia = $("#txtprovincia"),
    DOM.distrito = $("#txtdistrito");

    /**/   
}

function validar() {
    DOM.documento.keypress(function (e) {
        return Util.soloNumeros(e);
    });
    DOM.nombres.keypress(function (e) {
        return Util.soloLetras(e);
    });

    DOM.apellidos.keypress(function (e) {
        return Util.soloLetras(e);
    });
    DOM.telefono_movil.keypress(function (e) {
        return Util.soloNumeros(e);
    });
    DOM.user.keypress(function (e) {
        return Util.soloLetras(e,"espacio");
    });
}


function limpiar2() {    
    DOM.documento.val("");
    DOM.razon_social.val("");
    DOM.nombres.val("");
    DOM.apellidos.val("");
    DOM.telefono_movil.val("");
    DOM.correo.val("");
    DOM.direccion.val("");  
    DOM.depatarmento.val("").select2();
    DOM.provincia.val("").select2();
    DOM.distrito.val("").select2();
}



function setEventos() {
    validar();

    DOM.depatarmento.change(function(){        
        cargarProvincia(); 
    });

    DOM.provincia.change(function(){
        cargarDistrito(); 
    });

    DOM.btnAgregar.on("click", function () {
        DOM.self.find(".modal-title").text("Registrar nuevo cliente");
        DOM.operacion.val("agregar");
        limpiar2();
    });

    DOM.tipo_documento.on("change",function(){
        vista();
    });  

    
    DOM.form.submit(function (evento) {
        evento.preventDefault();

        if (  DOM.documento.val() === '' ) {
            Util.alerta('warning','Debe ingresar el número de documento del cliente',2000);
            return 0;
        }

        switch(DOM.tipo_documento.val()){
            case '01':
                if (  DOM.nombres.val() === '' ) {
                    Util.alerta('warning','Debe ingresar un nombre para el cliente',2000);
                    return 0;
                }
                if (  DOM.apellidos.val() === '' ) {
                    Util.alerta('warning','Debe ingresar los apellidos del cliente',2000);
                    return 0;
                }
                break;
            case '06':
                if (  DOM.razon_social.val() === '' ) {
                    Util.alerta('warning','Debe ingresar la razon social del cliente',2000);
                    return 0;
                }
                break;
        }

        if (  DOM.depatarmento.val() === '' ) {
            Util.alerta('warning','Debe seleccionar un departamento de residencia del cliente',2000);
            return 0;
        }

        if (  DOM.provincia.val() === '' ) {
            Util.alerta('warning','Debe seleccionar un provincia de residencia del cliente',2000);
            return 0;
        }

        if (  DOM.distrito.val() === '' ) {
            Util.alerta('warning','Debe seleccionar un distrito de residencia del cliente',2000);
            return 0;
        }


        if (  DOM.direccion.val() === '' ) {
            Util.alerta('warning','Debe ingresar un dirección para el cliente',2000);
            return 0;
        }

        if (  DOM.telefono_movil.val() === '' ) {
            Util.alerta('warning','Debe ingresar un teléfono móvil para el cliente',2000);
            return 0;
        }

        if (  DOM.correo.val() === '' ) {
            Util.alerta('warning','Debe ingresar un correo eléctronico para el cliente',2000);
            return 0;
        }


        var funcion = function (resultado) {
            if (resultado.estado === 200) {
                if (resultado.datos.rpt === true) {                    
                    Util.alertaA(resultado.datos);
                    limpiar2(); 
                    listar();
                    DOM.self.modal("hide");                    
                }else{
                    Util.alertaB(resultado.datos);
                    DOM.self.modal("hide");          
                }                        
            } 
        };         
        var entradas = {
            modelo: "Cliente",
            metodo: DOM.operacion.val(),                            
            data_in: {
                p_cod_cliente : DOM.cod_cliente.val(),
                p_cod_tipo_documento : DOM.tipo_documento.val(),
                p_nro_documento : DOM.documento.val(),
                p_razon_social : DOM.razon_social.val().toUpperCase(),
                p_nombres : DOM.nombres.val().toUpperCase(),
                p_apellidos : DOM.apellidos.val().toUpperCase(),
                p_celular : DOM.telefono_movil.val(),
                p_correo : DOM.correo.val(),
                p_direccion : DOM.direccion.val().toUpperCase(),
                p_departamento : DOM.depatarmento.val(),
                p_provincia : DOM.provincia.val(),
                p_distrito : DOM.distrito.val()
            }                           
        };
        Util.notificacion(entradas,funcion);     
    });        
}


function listar() {
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                var html = "";
                html += '<table id="tabla-listado" class="table table-bordered table-striped display nowrap" cellspacing="0" width="100%">';
                html += '<thead>';
                html += '<tr>';
                html += '<th style="text-align: center">Documento</th>';
                html += '<th style="text-align: center">Cliente</th>';
                html += '<th style="text-align: center">Dirección</th>';
                html += '<th style="text-align: center">Celular</th>';
                html += '<th style="text-align: center">Correo</th>';
                html += '<th style="text-align: center">OPCIONES</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';              
                $.each(resultado.datos.msj, function (i, item) {                 
                    html += '<tr>';
                    html += '<td align="center">' + item.nro_documento + '</td>';                
                    html += '<td align="center">' + item.cliente + '</td>';                
                    html += '<td align="center">' + item.direccion + '</td>'; 
                    html += '<td align="center">' + item.celular + '</td>'; 
                    html += '<td align="center">' + item.correo + '</td>';

                    html += '<td align="center">';
                    html += '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal" onclick="editar(' + item.cod_cliente + ')" title="Editar"><i class="fa fa-pencil"></i></button>';
                    html += '&nbsp;&nbsp;';

                    var tmpEstado = item.estado_mrcb != true ?
                            {icon: "up", title: "Habilitar", bol: true, boton: "btn-warning"} :
                            {icon: "down", title: "Deshabilitar", bol: false, boton: "btn-dark"};

                    html = html + '<button type="button" class="btn btn-xs ' + tmpEstado.boton + '" onclick="darBaja(' + item.cod_cliente + ',' +  "'" + tmpEstado.bol + "'" + ')" title="' + tmpEstado.title + '"><i class="fa fa-thumbs-o-' + tmpEstado.icon + '"></i></button>';                
                    html += '&nbsp;&nbsp;';
                    html += '</td>';
                    html += '</tr>';
                });
                html += '</tbody>';
                html += '<tfoot>';            
                html += '</tfoot>';
                html += '</table>';
                $("#listado").html(html);
                $("#tabla-listado").dataTable({
                    "aaSorting": [[0, "asc"]],
                    "rowReorder": {
                        selector: 'td:nth-child(2)'
                    },
                    "responsive": true
                });
            }else{
                Util.alertaB(resultado.datos);
            }    
        } 
    };
    
    new Ajex.Api({
        modelo: "Cliente",
        metodo: "listar"
    }, funcion);
}


function editar(p_codigo) {    
    DOM.self.find(".modal-title").text("Editar cliente");
    DOM.operacion.val("editar");
    var funcion = function (resultado) {
        console.log(resultado);
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                DOM.cod_cliente.val(resultado.datos.msj.cod_cliente);
                DOM.tipo_documento.val(resultado.datos.msj.cod_tipo_documento);
                vista();
                DOM.documento.val(resultado.datos.msj.nro_documento);
                DOM.direccion.val(resultado.datos.msj.direccion);
                DOM.telefono_movil.val(resultado.datos.msj.celular);
                DOM.correo.val(resultado.datos.msj.correo);
                DOM.nombres.val(resultado.datos.msj.nombres);
                DOM.apellidos.val(resultado.datos.msj.apellidos);  
                DOM.razon_social.val(resultado.datos.msj.razon_social); 

                DOM.depatarmento.val(resultado.datos.msj.codigo_departamento).select2();
                cargarProvincia(resultado.datos.msj.codigo_provincia,resultado.datos.msj.codigo_distrito);
                
            }else{
                Util.alertaB(resultado.datos);
            }
        }
    };
    new Ajex.Api({
        modelo: "Cliente",
        metodo: "leerDatos",
        data_in: {
            p_cod_cliente : p_codigo
        }
    },funcion);
}

function darBaja(p_codigo, p_estado) {
    var texto = p_estado != 'true' ? 'inactivar' : 'activar'; 
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                Util.alertaA(resultado.datos);
                listar();
            }else{
                Util.alertaB(resultado.datos);
            }            
        } 
    };

    var entradas = {
        modelo: "Cliente",
        metodo: "habilitar",
        data_in: {
            p_cod_cliente : p_codigo,
            p_estado_mrcb : p_estado
        }
    }; 
    Util.notificacion(entradas,funcion, texto);
}


function vista(){
    if ( DOM.tipo_documento.val() === '01') {
        DOM.documento.val("");
        DOM.razon_social.val("");
        $("#razon_social").hide();
        $("#nombres_apellidos").show();
        DOM.documento.attr("maxlength","8");
    }else{
        DOM.documento.val("");
        DOM.nombres.val("");
        DOM.apellidos.val("");
        $("#razon_social").show();
        $("#nombres_apellidos").hide();
        DOM.documento.attr("maxlength","11")
    }
}


function cargarDepartamento(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if (resultado.datos.rpt === true) {
                var html = "<option></option>";
                for (var i = 0; i < datos.length; i++) {
                    html += "<option value=" + datos[i].codigo_departamento + ">" + datos[i].nombre + "</option>";
                }
                DOM.depatarmento.html(html).select2({
                      placeholder: "Seleccionar departamento",
                      allowClear: true
                });
            }else{
                Util.alertaB(datos);
            }
        }
    };
    new Ajex.Api({
        modelo : "Ubigeo",
        metodo : "llenarDepartamento"
    }, funcion);
}

function cargarProvincia(parametro=null, parametro2=null){
    var funcion = function (resultado) {
        console.log(resultado);
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if (resultado.datos.rpt === true) {
                var html = "<option></option>";
                for (var i = 0; i < datos.length; i++) {
                    html += "<option value=" + datos[i].codigo_provincia + ">" + datos[i].nombre + "</option>";
                }

                if ( parametro === null && parametro2 === null ) {
                    DOM.provincia.html(html).select2({
                        placeholder: "Seleccionar provincia",
                        allowClear: true
                    });
                }else{
                    DOM.provincia.html(html).val(parametro).select2();
                    cargarDistrito(parametro2);

                }
            }else{
                Util.alertaB(datos);
            }
        }
    };
    new Ajex.Api({
        modelo : "Ubigeo",
        metodo : "llenarProvincia",
        data_out :  [DOM.depatarmento.val()]
    }, funcion);
}

function cargarDistrito(parametro=null){    
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if (resultado.datos.rpt === true) {
                var html = "<option></option>";
                for (var i = 0; i < datos.length; i++) {
                    html += "<option value=" + datos[i].codigo_distrito + ">" + datos[i].nombre + "</option>";
                }

                if ( parametro === null ) {
                    DOM.distrito.html(html).select2({
                        placeholder: "Seleccionar distrito",
                        allowClear: true
                    });
                }else{
                    DOM.distrito.html(html).val(parametro).select2();
                }
            }else{
                Util.alertaB(datos);
            }
        }
    };
    new Ajex.Api({
        modelo : "Ubigeo",
        metodo : "llenarDistrito",
        data_out :  [DOM.provincia.val()]
    }, funcion);
}