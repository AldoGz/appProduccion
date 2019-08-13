var DOM = {};



$(document).ready(function () {
    setDOM();
    setEventos();
    listar();
    cargarPerfil();
    cargarColaborador();
});

function setDOM() {
    DOM.form = $("#frm-grabar"),
    DOM.listado = $("#listado"),
    DOM.self = $("#myModal"),
    DOM.btnAgregar = $("#btnAgregar"),
    DOM.comboEstado = $("#cboEstado"),
    DOM.operacion = $("#txtoperacion"),

    DOM.codigo_usuario = $("#txtcodigo_usuario"),
    DOM.codigo_perfil = $("#txtperfil"),
    DOM.codigo_colaborador = $("#txtcolaborador"),
    DOM.usuario = $("#txtusuario"),
    DOM.clave = $("#txtclave"),
    DOM.estado_acceso = $("#txtestado_acceso");


}

function limpiar() {
    DOM.codigo_usuario.val("");
    DOM.usuario.val("");
    DOM.clave.val("");
  
}


function setEventos() {
 

    DOM.btnAgregar.on("click", function () {
        DOM.self.find(".modal-title").text("Agregar nuevo usuario");
        DOM.operacion.val("agregar");
        limpiar();
    });


    DOM.form.submit(function (evento) {
        evento.preventDefault();

        if ( DOM.codigo_perfil.val() === '' ) {
            Util.alerta('warning','Debe seleccionar un perfil',2000);
            return 0;
        }

        if ( DOM.codigo_colaborador.val() === '' ) {
            Util.alerta('warning','Debe seleccionar un colaborador',2000);
            return 0;
        }

        var funcion = function (resultado) {
            if (resultado.estado === 200) {
                if (resultado.datos.rpt === true) {                    
                    Util.alertaA(resultado.datos);
                    listar();
                    limpiar();                            
                    DOM.self.modal("hide");                    
                }else{
                    Util.alertaB(resultado.datos);
                    DOM.self.modal("hide");          
                }                        
            } 
        };         
        var entradas = {
            modelo: "Usuario",
            metodo: DOM.operacion.val(), 
            data_in :{
                p_cod_usuario : DOM.codigo_usuario.val(),
                p_cod_perfil : DOM.codigo_perfil.val(),
                p_cod_colaborador : DOM.codigo_colaborador.val(),
                p_usuario : DOM.usuario.val().toUpperCase(),
                p_clave : DOM.clave.val(),
                p_estado_acceso : DOM.estado_acceso.val()
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
                html += '<th style="text-align: center">N°</th>';
                html += '<th style="text-align: center">Usuario</th>';
                html += '<th style="text-align: center">Empleado</th>';
                html += '<th style="text-align: center">Perfil</th>';
                html += '<th style="text-align: center">OPCIONES</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';                
                $.each(resultado.datos.msj, function (i, item) {                 
                    html += '<tr>';
                    html += '<td align="center">' + (i+1) + '</td>';                
                    html += '<td align="center">' + item.usuario + '</td>';
                    html += '<td align="center">' + item.user + '</td>';
                    html += '<td align="center">' + item.perfil + '</td>';
                    html += '<td align="center">';
                    html += '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal" onclick="editar(' + item.cod_usuario + ')" title="Editar"><i class="fa fa-pencil"></i></button>';
                    html += '&nbsp;&nbsp;';
                    var tmpEstado = item.estado_acceso != "A"?
                            {icon: "up", title: "Habilitar", bol: "A", boton: "btn-warning"} :
                            {icon: "down", title: "Deshabilitar", bol: "I", boton: "btn-dark"};
                    html += '<button type="button" class="btn btn-xs ' + tmpEstado.boton + '" onclick="acceso(' + item.cod_usuario + ',' + "'" + tmpEstado.bol + "'" + ')" title="' + tmpEstado.title + '"><i class="fa fa-thumbs-o-' + tmpEstado.icon + '"></i></button>';                
                    html += '&nbsp;&nbsp;';
                    html += '<button type="button" class="btn btn-xs btn-danger" onclick="darBaja(' + item.cod_usuario + ','+ "'false'" +')" title="Eliminar"><i class="fa fa-times"></i></button>';                
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
        modelo: "Usuario",
        metodo: "listar",
        data_in:{
            p_tipo_usuario : 'O'
        }
    }, funcion);
}

function editar(p_codigo) {    
    DOM.self.find(".modal-title").text("Editar tipo de falla");
    DOM.operacion.val("editar");

    $("#t_clave").hide();

    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                $.each(resultado.datos.msj, function (i, item) {
                    DOM.codigo_usuario.val(item.cod_usuario);
                    DOM.codigo_perfil.val(item.cod_perfil).select2();
                    DOM.codigo_colaborador.val(item.cod_colaborador).select2();
                    DOM.usuario.val(item.usuario);
                    DOM.estado_acceso.val(item.estado_acceso);
                });
            }else{
                Util.alertaB(resultado.datos);
            }
        }
    };
    new Ajex.Api({
        modelo: "Usuario",
        metodo: "leerDatos",
        data_in: {
            p_cod_usuario : p_codigo
        }
    },funcion);
}

function acceso(p_codigo, p_estado) { 
    var texto = p_estado != 'A' ? 'inactivar' : 'activar';    
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
        modelo: "Usuario",
        metodo: "status",
        data_in: {
            p_cod_usuario : p_codigo,
            p_estado_acceso : p_estado
        }  
    }; 
    Util.notificacion(entradas,funcion, texto); 
}


function darBaja(p_codigo, p_estado) {     
    var texto = 'anular';
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
        modelo: "Usuario",
        metodo: "habilitar",
        data_in: {
            p_cod_usuario : p_codigo,
            p_estado_mrcb : p_estado
        }
    };
    Util.notificacion(entradas,funcion,texto); 
}

function cargarPerfil(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if (resultado.datos.rpt === true) {
                var html = "<option></option>";
                for (var i = 0; i < datos.length; i++) {
                    html += "<option value=" + datos[i].cod_perfil + ">" + datos[i].nombre + "</option>";
                }
                DOM.codigo_perfil.html(html).select2({
                      placeholder: "Seleccionar perfil",
                      allowClear: true
                });
            }else{
                Util.alertaB(datos);
            }
        }
    };

    new Ajex.Api({
        modelo : "Perfil",
        metodo : "cbListar"
    }, funcion);
}

function cargarColaborador(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if (resultado.datos.rpt === true) {
                var html = "<option></option>";
                for (var i = 0; i < datos.length; i++) {
                    html += "<option value=" + datos[i].cod_colaborador + ">" + datos[i].nombre + "</option>";
                }
                DOM.codigo_colaborador.html(html).select2({
                      placeholder: "Seleccionar colaborador",
                      allowClear: true
                });
            }else{
                Util.alertaB(datos);
            }
        }
    };

    new Ajex.Api({
        modelo : "Colaborador",
        metodo : "cbListar"
    }, funcion);
}