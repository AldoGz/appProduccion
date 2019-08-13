var DOM = {};

$(document).ready(function () {
    setDOM();
    setEventos();
    listar();
    listarActividad();
    cargarFase();
});

function setDOM() {
    DOM.form = $("#frm-grabar"),
    DOM.listado = $("#listado"),
    DOM.self = $("#myModal"),
    DOM.checks = $("#check-all"),
    DOM.operacion = $("#txtoperacion"),
      
    

    DOM.codigo_fase = $("#txtcodigo_fases"),


    DOM.codigo_actividad_produccion = $("#txtcodigo_actividad_produccion"),
    DOM.tiempo_duracion = $("#txttiempoduracion");
}

function limpiar() {
    
}

function validar(){
    DOM.tiempo_duracion.keypress(function (e) {
        return Util.soloNumeros(e);
    });
}




function setEventos() {
    validar();


    DOM.checks.change("click", function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });


    DOM.form.submit(function (evento) {
        evento.preventDefault();

        swal({
            title: "Confirme",
            text: "¿Esta seguro de grabar los datos ingresados?",
            showCancelButton: true,
            confirmButtonColor: '#3d9205',
            confirmButtonText: 'Si',
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: true,
            imageUrl: "../../imagenes/pregunta.png"
        },
                function (isConfirm) {
                    if (isConfirm) { //el usuario hizo clic en el boton SI     
                        //procedo a grabar
                        if ( DOM.tiempo_duracion.val() === '') {
                            swal("Mensaje de advertencia", "Ingrese un valor para tiempo duración", "warning");
                        }

                        if ($('#detalle_actividades tr input[type=checkbox]:checked').length === 0) {
                            swal("Mensaje de advertencia", "Datos seleccionar una actividad", "warning");
                        } else {
                            var form = $('#detalle_actividades tr input:checked').map(function () {
                                return {codigo_actividad: this.checked ? this.value : "false"};
                            }).get();
                            var json = JSON.stringify(form);

                            
                            var data_in = {
                                p_tiempo_duracion : DOM.tiempo_duracion.val(),
                                p_codigo_fase: DOM.codigo_fase.val(),
                                p_codigo_actividad_produccion : DOM.codigo_actividad_produccion.val()
                            };

                            var funcion = function (resultado) {
                                if (resultado.estado === 200) {
                                    swal({
                                        title: "Exito",
                                        text: resultado.mensaje,
                                        type: "success",
                                        showCancelButton: false,
                                        confirmButtonText: 'Ok',
                                        closeOnConfirm: true
                                    },
                                            function () {                                                                                        
                                                swal("Exito", "Datos registrados", "success");                                            
                                                listar();
                                                limpiar();
                                                DOM.self.modal("hide");
                                            });
                                } else {
                                    swal("Mensaje del sistema", resultado, "warning");
                                }
                            };

                            new Ajex.Api({
                                modelo: "ActividadProduccion",
                                metodo: DOM.operacion.val(),                            
                                data_in: data_in,
                                data_out: [json]
                            }, funcion);
                        }                   
                    }
                });
    });
}


function listar() {
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var html = "";
            html += '<table id="tabla-listado" class="table table-bordered table-striped display nowrap" cellspacing="0" width="100%">';
            html += '<thead>';
            html += '<tr>';
            html += '<th style="text-align: center">N°</th>';
            html += '<th style="text-align: center">Cliente</th>';
            html += '<th style="text-align: center">Trabajador</th>';
            html += '<th style="text-align: center">Fecha / Hora</th>';
            html += '<th style="text-align: center">PON MIENTRAS</th>';
            html += '<th style="text-align: center">OPCIONES</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';
            
            $.each(resultado.datos, function (i, item) {
                html += '<tr>';
                html += '<td align="center">' + (i + 1) + '</td>';
                html += '<td align="center">' + item.nombre + '</td>';
                html += '<td align="center">' + item.tiempo_duracion + '</td>';
                html += '<td align="center">' + item.estado_actual + '</td>';
                html += '<td align="center">' + item.costo_actividad + '</td>';
                html += '<td align="center">';

                switch(item.estado_actual){
                    case 'INICIO':
                        html += '<button type="button" class="btn btn-success btn-xs" onclick="fase_actividad(' + item.codigo_actividad_produccion + ')" title="fase acondicionamiento"><i class="fa fa-cubes"></i></button>';
                        html += '&nbsp;&nbsp;';
                        break;
                    case 'ACONDICIONAMIENTO':
                        html += '<button type="button" class="btn btn-danger btn-xs" onclick="fase_actividad_acondicionamiento(' + item.codigo_actividad_produccion + ')" title="fase fundicion"><i class="fa fa-fire"></i></button>';
                        html += '&nbsp;&nbsp;';
                        break;
                    case 'FUNDICION':
                        html += '<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#myModal" onclick="fase_actividad_fundicion(' + item.codigo_actividad_produccion + ')" title="fase acabado"><i class="fa fa-clone"></i></button>';
                        html += '&nbsp;&nbsp;';
                        break;
                    default:
                        html += '<button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal" onclick="fase_actividad_acolordo(' + item.codigo_actividad_produccion + ')" title="fase fin"><i class="fa fa-thumbs-up"></i></button>';
                        html += '&nbsp;&nbsp;';
                        break;
                }
               

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
        } else {
            swal("Mensaje del sistema", resultado, "warning");
        }
    };

    new Ajex.Api({
        modelo: "ActividadProduccion",
        metodo: "listar"
    }, funcion);
}

function listarActividad() {
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var html = "";
            html += '<table id="tabla-listado-actividades" class="table table-bordered table-striped display nowrap" cellspacing="0" width="100%">';
            html += '<thead>';
            html += '<tr>';
            html += '<th style="text-align: center">Item</th>';
            html += '<th style="text-align: center">Actividad</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody id="detalle_actividades">';
            
            $.each(resultado.datos, function (i, item) {
                html += '<tr>';
                html += '<td align="center"><input type="checkbox" value="' + item.codigo_actividad + '"></td>';                
                html += '<td align="center">' + item.descripcion + '</td>';
                html += '</tr>';
            });
            html += '</tbody>';
            html += '<tfoot>';
            html += '</tfoot>';
            html += '</table>';
            $("#listado-actividades").html(html);            
        } else {
            swal("Mensaje del sistema", resultado, "warning");
        }
    };

    new Ajex.Api({
        modelo: "Actividad",
        metodo: "listar",
        data_in: {
            p_estado: 'true'
        }
    }, funcion);
}

function fase_actividad(p_codigo) {    
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos == true) {
                swal("Exito", "Se ha registrado correctamente acondicionamiento", "success");
            } else {
                swal("Mensaje del sistema", resultado, "warning");
            }

        } else {
            swal("Mensaje del sistema", resultado, "warning");
        }
    };

    new Ajex.Api({
        modelo: "ActividadProduccion",
        metodo: "inicio",
        data_in: {
            p_codigo_actividad_produccion: p_codigo
        }
    },funcion);
}

function fase_actividad_acondicionamiento(p_codigo) {
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos == true) {
                swal("Exito", "Se ha registrado correctamente fundición", "success");
                listar();
            } else {
                swal("Mensaje del sistema", resultado, "warning");
            }

        } else {
            swal("Mensaje del sistema", resultado, "warning");
        }
    };

    new Ajex.Api({
        modelo: "ActividadProduccion",
        metodo: "acondicionamiento",
        data_in: {
            p_codigo_actividad_produccion: p_codigo
        }
    },funcion);
}

function fase_actividad_fundicion(p_codigo) {
    DOM.self.find(".modal-title").text("Agregar nuevas actividades para producción");
    DOM.codigo_actividad_produccion.val(p_codigo);
    DOM.operacion.val('fundicion');
    cargarFase();
    limpiar();
}

function fase_actividad_acolordo(p_codigo) {
    DOM.self.find(".modal-title").text("Agregar nuevas actividades para producción");
    DOM.codigo_actividad_produccion.val(p_codigo);
    DOM.operacion.val('acabado');
    limpiar();
}

function cargarFase(){    
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos;
            var html = '';
            for (var i = 0; i < datos.length; i++) {
                html += '<option value="' + datos[i].codigo_fase + '">'+ datos[i].descripcion + '</option>';
            }            
            DOM.codigo_fase.html(html);
        }
    };    

    new Ajex.Api({
        modelo : "Fase",
        metodo : "listarCB",
        data_out : [DOM.operacion.val()]        
    }, funcion);


}
