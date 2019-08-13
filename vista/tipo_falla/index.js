var DOM = {};



$(document).ready(function () {
    setDOM();
    setEventos();
    listar();
    
});

function setDOM() {
    DOM.form = $("#frm-grabar"),
    DOM.listado = $("#listado"),
    DOM.self = $("#myModal"),
    DOM.btnAgregar = $("#btnAgregar"),
    DOM.comboEstado = $("#cboEstado"),
    DOM.operacion = $("#txtoperacion"),
    DOM.codigo_falla = $("#txtcodigo_falla"),
    DOM.nombre = $("#txtnombre"),
    DOM.descripcion = $("#txtdescripcion");
}

function limpiar() {
    DOM.codigo_falla.val("");
    DOM.nombre.val("");
    DOM.descripcion.val("");
  
}

function validar() {
    DOM.descripcion.keypress(function (e) {
        return Util.soloLetras(e);
    });
}


function setEventos() {
    validar();

    DOM.btnAgregar.on("click", function () {
        DOM.self.find(".modal-title").text("Agregar nuevo tipo de falla");
        DOM.operacion.val("agregar");
        limpiar();
    });


    DOM.form.submit(function (evento) {
        evento.preventDefault();
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
            modelo: "TipoFalla",
            metodo: DOM.operacion.val(), 
            data_in :{
                p_cod_falla : DOM.codigo_falla.val(),
                p_nombre : DOM.nombre.val().toUpperCase(),
                p_descripcion : DOM.descripcion.val().toUpperCase()
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
                html += '<th style="text-align: center">Nombre</th>';
                html += '<th style="text-align: center">Descripción</th>';
                html += '<th style="text-align: center">OPCIONES</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';                
                $.each(resultado.datos.msj, function (i, item) {                 
                    html += '<tr>';
                    html += '<td align="center">' + (i+1) + '</td>';                
                    html += '<td align="center">' + item.nombre + '</td>';                
                    html += '<td align="center">' + item.descripcion + '</td>'; 

                    html += '<td align="center">';
                    html += '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal" onclick="editar(' + item.cod_falla + ')" title="Editar"><i class="fa fa-pencil"></i></button>';
                    html += '&nbsp;&nbsp;';

                    html += '<button type="button" class="btn btn-xs btn-danger" onclick="darBaja(' + item.cod_falla + ',' + "'false'" +')" title="Eliminar"><i class="fa fa-times"></i></button>';                
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
        modelo: "TipoFalla",
        metodo: "listar"
    }, funcion);
}

function editar(p_codigo) {    
    DOM.self.find(".modal-title").text("Editar tipo de falla");
    DOM.operacion.val("editar");
    
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                $.each(resultado.datos.msj, function (i, item) {
                    DOM.codigo_falla.val(item.cod_falla);
                    DOM.descripcion.val(item.descripcion);
                    DOM.nombre.val(item.nombre);
                });
            }else{
                Util.alertaB(resultado.datos);
            }
        } 
    };

    new Ajex.Api({
        modelo: "TipoFalla",
        metodo: "leerDatos",
        data_in: {
            p_cod_falla : p_codigo
        }
    },funcion);
}

function darBaja(p_codigo,p_estado) {
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
        modelo: "TipoFalla",
        metodo: "habilitar",
        data_in: {
            p_cod_falla : p_codigo,
            p_estado_mrcb : p_estado
        }  
    }; 
    Util.notificacion(entradas,funcion, texto); 
}

