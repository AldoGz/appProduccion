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
    DOM.codigo_unidad_medida = $("#txtcodigo_unidad_medida"),
    DOM.descripcion = $("#txtdescripcion"),
    DOM.abreviatura = $("#txtabreviatura");
}

function limpiar() {
    DOM.codigo_unidad_medida.val("");
    DOM.descripcion.val("");
    DOM.abreviatura.val("");
}

function validar() {
    DOM.descripcion.keypress(function (e) {
        return Util.soloLetras(e);
    });
}


function setEventos() {
    validar();

    DOM.btnAgregar.on("click", function () {
        DOM.self.find(".modal-title").text("Agregar nuevo unidad de medida");
        DOM.operacion.val("agregar");
        limpiar();
    });

    DOM.comboEstado.change("click", function () {
        listar();
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
                    DOM.self.modal("hide")          
                }                        
            } 
        };         
        var entradas = {
            modelo: "UnidadMedida",
            metodo: DOM.operacion.val(), 
            data_in :{
                p_cod_unidad_medida : DOM.codigo_unidad_medida.val(),
                p_nombre : DOM.descripcion.val().toUpperCase(),
                p_abreviatura : DOM.abreviatura.val().toUpperCase()
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
                html += '<th style="text-align: center">Descripción</th>';
                html += '<th style="text-align: center">Abreviatura</th>';
                html += '<th style="text-align: center">OPCIONES</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
                
                $.each(resultado.datos.msj, function (i, item) {                 
                    html += '<tr>';
                    html += '<td align="center">' + (i+1) + '</td>';                
                    html += '<td align="center">' + item.nombre + '</td>';                
                    html += '<td align="center">' + item.abreviatura + '</td>'; 

                    html += '<td align="center">';
                    html += '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal" onclick="editar(' + item.cod_unidad_medida + ')" title="Editar"><i class="fa fa-pencil"></i></button>';
                    html += '&nbsp;&nbsp;';

                    html += '<button type="button" class="btn btn-xs btn-danger" onclick="darBaja(' + item.cod_unidad_medida + ',' + "'false'" +')" title="Eliminar"><i class="fa fa-times"></i></button>';                
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
        modelo: "UnidadMedida",
        metodo: "listar"
    }, funcion);
}

function editar(p_codigo) {    
    DOM.self.find(".modal-title").text("Editar unidad de medida");
    DOM.operacion.val("editar");
    
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                $.each(resultado.datos.msj, function (i, item) {
                    DOM.codigo_unidad_medida.val(item.cod_unidad_medida);
                    DOM.descripcion.val(item.nombre);
                    DOM.abreviatura.val(item.abreviatura);
                });
            }else{
                Util.alertaB(resultado.datos);
            }
        } 
    };
    new Ajex.Api({
        modelo: "UnidadMedida",
        metodo: "leerDatos",
        data_in: {
            p_cod_unidad_medida : p_codigo
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
        modelo: "UnidadMedida",
        metodo: "habilitar",
        data_in: {
            p_cod_unidad_medida : p_codigo,
            p_estado_mrcb : p_estado
        } 
    }; 
    Util.notificacion(entradas,funcion, texto);      
}

