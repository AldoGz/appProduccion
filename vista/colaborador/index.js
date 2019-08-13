var DOM = {};

$(document).ready(function () {
    setDOM();
    setEventos();
    listar();
    cargarCargo();
    
});

function setDOM() {
    DOM.form = $("#frm-grabar"),
    DOM.listado = $("#listado"),
    DOM.self = $("#myModal"),
    DOM.btnAgregar = $("#btnAgregar"),
    DOM.comboEstado = $("#cboEstado"),
    DOM.operacion = $("#txtoperacion"),
    DOM.codigo_colaborador = $("#txtcodigo_colaborador"),
    DOM.documento = $("#txtdocumento"),
    DOM.apellidos = $("#txtapellidos"),
    DOM.nombres = $("#txtnombres"),
    DOM.correo = $("#txtcorreo"),
    DOM.telefono_movil = $("#txtmovil"),
    DOM.fecha_nacimiento = $("#txtfecha_nacimiento"),
    DOM.codigo_cargo = $("#txtcodigo_cargo");
}

function limpiar() {
    DOM.codigo_colaborador.val("");
    DOM.documento.val("");
    DOM.apellidos.val("");
    DOM.nombres.val("");
    DOM.telefono_movil.val("");
    DOM.correo.val("");
    DOM.fecha_nacimiento.val("");
}

function validar() {
    DOM.documento.keypress(function (e) {
        return Util.soloNumeros(e);
    });
    DOM.apellidos.keypress(function (e) {
        return Util.soloLetras(e);
    });
    DOM.nombres.keypress(function (e) {
        return Util.soloLetras(e);
    });
    DOM.telefono_movil.keypress(function (e) {
        return Util.soloNumeros(e);
    });
}


function setEventos() {
    validar();

    DOM.btnAgregar.on("click", function () {
        DOM.self.find(".modal-title").text("Agregar nuevo colaborador");
        DOM.operacion.val("agregar");
        limpiar();
    });

    DOM.form.submit(function (evento) {
        evento.preventDefault();
        if (DOM.documento.val().length != 8) {
            Util.alerta('warning','Debe tener "8" digitos el DNI',2000);
            return 0; //detiene el programa                
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
            modelo: "Colaborador",
            metodo: DOM.operacion.val(),                            
            data_in: {
                p_cod_colaborador : DOM.codigo_colaborador.val(),
                p_documento : DOM.documento.val(),
                p_apellidos : DOM.apellidos.val().toUpperCase(),
                p_nombres : DOM.nombres.val().toUpperCase(),                       
                p_celular : DOM.telefono_movil.val(),                            
                p_correo : DOM.correo.val(), 
                p_fecha_nacimiento : DOM.fecha_nacimiento.val(),    
                p_cod_cargo : DOM.codigo_cargo.val()
            }                         
        };
        Util.notificacion(entradas,funcion); 
    });
}


function listar() {
    var funcion = function (resultado) {
        console.log("lista");
        console.log(resultado);
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                var html = "";
                html += '<table id="tabla-listado" class="table table-bordered table-striped display nowrap" cellspacing="0" width="100%">';
                html += '<thead>';
                html += '<tr>';
                html += '<th style="text-align: center">Documento</th>';
                html += '<th style="text-align: center">Empleado</th>';
                html += '<th style="text-align: center">Edad</th>';
                html += '<th style="text-align: center">Celular</th>';
                html += '<th style="text-align: center">Correo</th>';
                html += '<th style="text-align: center">Cargo</th>';            
                html += '<th style="text-align: center">OPCIONES</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';             
                $.each(resultado.datos.msj, function (i, item) {                 
                    html += '<tr>';
                    html += '<td align="center">' + item.dni + '</td>';                
                    html += '<td align="center">' + item.nombres + '</td>';
                    html += '<td align="center">' + item.edad + ' años</td>';
                    html += '<td align="center">' + item.celular + '</td>';                
                    html += '<td align="center">' + item.correo + '</td>';                
                    html += '<td align="center">' + item.cargo + '</td>';                
                    html += '<td align="center">';
                    html += '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal" onclick="editar(' + item.cod_colaborador + ')" title="Editar"><i class="fa fa-pencil"></i></button>';
                    html += '&nbsp;&nbsp;';
                    var tmpEstado = item.estado_laboral != "A"?
                            {icon: "up", title: "Habilitar", bol: "A", boton: "btn-warning"} :
                            {icon: "down", title: "Deshabilitar", bol: "I", boton: "btn-dark"};
                    html += '<button type="button" class="btn btn-xs ' + tmpEstado.boton + '" onclick="estado(' + item.cod_colaborador + ',' + "'" + tmpEstado.bol + "'" + ')" title="' + tmpEstado.title + '"><i class="fa fa-thumbs-o-' + tmpEstado.icon + '"></i></button>';                
                    html += '&nbsp;&nbsp;';
                    html += '<button type="button" class="btn btn-xs btn-danger" onclick="darBaja(' + item.cod_colaborador + ','+ "'false'" +')" title="Eliminar"><i class="fa fa-times"></i></button>';                
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
        modelo: "Colaborador",
        metodo: "listar"
    }, funcion);
}

function editar(p_codigo) {
    DOM.self.find(".modal-title").text("Editar colaborador");
    DOM.operacion.val("editar");
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                $.each(resultado.datos.msj, function (i, item) {
                    DOM.codigo_colaborador.val(item.cod_colaborador);
                    DOM.documento.val(item.dni);
                    DOM.apellidos.val(item.apellidos);                
                    DOM.nombres.val(item.nombres);
                    DOM.fecha_nacimiento.val(item.fecha_nacimiento);
                    DOM.telefono_movil.val(item.celular);
                    DOM.correo.val(item.correo);
                    DOM.codigo_cargo.val(item.cod_cargo);
                });
            }else{
                Util.alertaB(resultado.datos);
            }
        }
    };
    new Ajex.Api({
        modelo: "Colaborador",
        metodo: "leerDatos",
        data_in: {
            p_cod_colaborador: p_codigo
        }
    },funcion);
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
        modelo: "Colaborador",
        metodo: "habilitar",
        data_in: {
            p_cod_colaborador : p_codigo,
            p_estado_mrcb : p_estado
        } 
    }; 
    Util.notificacion(entradas,funcion, texto);     
}

function estado(p_codigo, p_estado) {
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
        modelo: "Colaborador",
        metodo: "status",
        data_in: {
            p_cod_colaborador : p_codigo,
            p_estado_laboral : p_estado
        }
    };
    Util.notificacion(entradas,funcion,texto); 
}

function cargarCargo(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if ( resultado.datos.rpt === true ) {
                var html = "";
                for (var i = 0; i < datos.length; i++) {
                    html = html + "<option value=" + datos[i].cod_cargo + ">" + datos[i].nombre + "</nombre>";
                }
                DOM.codigo_cargo.html(html);
            }else{
                Util.alertaB(datos);
            } 
            
        }
    };

    new Ajex.Api({
        modelo : "Cargo",
        metodo : "cbListar"
    }, funcion);
}

