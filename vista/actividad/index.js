var DOM = {};

$(document).ready(function () {
    setDOM();
    setEventos();
    listar();
    cargarMaquina();
    
});

function setDOM() {
    DOM.form = $("#frm-grabar"),
    DOM.listado = $("#listado"),
    DOM.self = $("#myModal"),
    DOM.btnAgregar = $("#btnAgregar"),
    DOM.operacion = $("#txtoperacion"),


    DOM.codigo_actividad = $("#txtcodigo_actividad"),

    DOM.nombre = $("#txtnombre"),
    DOM.cantidad_hombres = $("#txtcantidad_hombres"),
    DOM.costo_hora_hombre = $("#txtcosto_hora_hombre_base"),
    DOM.cantidad_hora_hombre = $("#txtcantidad_hora_hombres"),
    DOM.codigo_maquina = $("#txtcodigo_maquina"),
    DOM.descripcion = $("#txtdescripcion");
}

function limpiar() {
    DOM.codigo_actividad.val("");
    DOM.nombre.val("");
    DOM.cantidad_hombres.val("");
    DOM.costo_hora_hombre.val("");
    DOM.cantidad_hora_hombre.val("");
    DOM.descripcion.val("");
}

function validar() {
    DOM.nombre.keypress(function (e) {
        return Util.soloLetras(e);
    });
    DOM.cantidad_hombres.keypress(function (e) {
        return Util.soloNumeros(e);
    });

    DOM.costo_hora_hombre.keypress(function (e) {
        var valor = DOM.costo_hora_hombre.val();
        return Util.soloDecimal(e,valor,2);
    });
    DOM.cantidad_hora_hombre.keypress(function (e) {
        var valor = DOM.cantidad_hora_hombre.val();
        return Util.soloDecimal(e,valor,1);
    });

}


function setEventos() {
    validar();

    DOM.btnAgregar.on("click", function () {
        DOM.self.find(".modal-title").text("Agregar nuevo actividad");
        DOM.operacion.val("agregar");
        limpiar();
    });


    DOM.form.submit(function (evento) {
        evento.preventDefault();

        if ( parseFloat(DOM.costo_hora_hombre.val()) >= 0.000 && parseFloat(DOM.costo_hora_hombre.val()) <= 0.009) {
            Util.alerta('warning','La costo hora hombre debe ser mayor de cero y menor que 0.009',2000);
            return 0;
        };

        if ( parseFloat(DOM.cantidad_hora_hombre.val()) >= 0.00 && parseFloat(DOM.cantidad_hora_hombre.val()) <= 0.09) {
            Util.alerta('warning','La cantidad hora hombre debe ser mayor que cero y menor que 0.09',2000);
            return 0;
        };

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
            modelo: "Actividad",
            metodo: DOM.operacion.val(),                            
            data_in: {
                p_cod_actividad : DOM.codigo_actividad.val(),
                p_nombre : DOM.nombre.val().toUpperCase(),
                p_cantidad_hombres_base : DOM.cantidad_hombres.val(),
                p_costo_hora_hombre_base :  DOM.costo_hora_hombre.val(),
                p_cantidad_horas_maquina_base : DOM.cantidad_hora_hombre.val(),
                p_cod_maquina_usada : DOM.codigo_maquina.val(),
                p_descripcion_costos_indirectos : DOM.descripcion.val().toUpperCase()
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
                html += '<th style="text-align: center">Cantidad hombres</th>';           
                html += '<th style="text-align: center">Costo hombres</th>';           
                html += '<th style="text-align: center">Cantidad hora maquina</th>';           
                html += '<th style="text-align: center">Maquina</th>';  
                html += '<th style="text-align: center">OPCIONES</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';                
                $.each(resultado.datos.msj, function (i, item) {                 
                    html += '<tr>';
                    html += '<td align="center">' + (i+1) + '</td>';                
                    html += '<td align="center">' + item.nombre + '</td>';            
                    html += '<td align="center">' + item.cantidad_hombres_base + '</td>';            
                    html += '<td align="center">' + item.costo_hora_hombre_base + '</td>'; 
                    html += '<td align="center">' + item.cantidad_horas_maquina_base + '</td>'; 
                    html += '<td align="center">' + item.maquina + '</td>'; 
                    html += '<td align="center">';
                    html += '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal" onclick="editar(' + item.cod_actividad + ')" title="Editar"><i class="fa fa-pencil"></i></button>';
                    html += '&nbsp;&nbsp;';

                    html += '<button type="button" class="btn btn-xs btn-danger" onclick="darBaja(' + item.cod_actividad + ',' + "'false'" + ')" title="Eliminar"><i class="fa fa-times"></i></button>';                
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
        modelo: "Actividad",
        metodo: "listar"
    }, funcion);
}

function editar(p_codigo) {
    DOM.self.find(".modal-title").text("Editar actividad");
    DOM.operacion.val("editar");
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                $.each(resultado.datos.msj, function (i, item) {
                    DOM.codigo_actividad.val(item.cod_actividad);
                    DOM.nombre.val(item.nombre);
                    DOM.cantidad_hombres.val(item.cantidad_hombres_base);
                    DOM.costo_hora_hombre.val(item.costo_hora_hombre_base);
                    DOM.cantidad_hora_hombre.val(item.cantidad_horas_maquina_base);
                    DOM.codigo_maquina.val(item.cod_maquina_usada).select2();
                    DOM.descripcion.val(item.descripcion_costos_indirectos);
                });
            }else{
                Util.alertaB(resultado.datos);
            }
        }
    };

    new Ajex.Api({
        modelo: "Actividad",
        metodo: "leerDatos",
        data_in: {
            p_cod_actividad: p_codigo
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
        modelo: "Actividad",
        metodo: "habilitar",
        data_in: {
            p_cod_actividad: p_codigo,
            p_estado_mrcb: p_estado
        } 
    }; 
    Util.notificacion(entradas,funcion, texto);    
}

function cargarMaquina(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if (resultado.datos.rpt === true) {
                var html = "<option></option>";
                for (var i = 0; i < datos.length; i++) {
                    html += "<option value=" + datos[i].cod_maquina + ">" + datos[i].nombre + "</option>";
                }
                DOM.codigo_maquina.html(html).select2({
                      placeholder: "Seleccionar materia prima",
                      allowClear: true
                });
            }else{
                Util.alertaB(datos);
            }
        }
    };
    new Ajex.Api({
        modelo : "Maquina",
        metodo : "cbListar"
    }, funcion);
}
