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
    DOM.nombre_plan_produccion = $("#txtnombre"),
    DOM.fecha_incio_proceso = $("#txtfecha");
}


function correlativo() {    
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                DOM.nombre_plan_produccion.val(resultado.datos.msj);
            }else{
                Util.alertaB(resultado.datos);
            }
        } 
    };
    new Ajex.Api({
        modelo: "PlanProduccion",
        metodo: "correlativo"
    },funcion);
}

function setEventos() {
    DOM.btnAgregar.on("click", function () {
        DOM.self.find(".modal-title").text("Agregar nuevo plan de producción");
        correlativo();
    });

    DOM.form.submit(function (evento) {
        evento.preventDefault();

        var funcion = function (resultado) {
            if (resultado.estado === 200) {
                if (resultado.datos.rpt === true) {                    
                    Util.alertaA(resultado.datos);
                    listar();                          
                    DOM.self.modal("hide");                    
                }else{
                    Util.alertaB(resultado.datos);
                    DOM.self.modal("hide");          
                }                        
            } 
        };         
        var entradas = {
            modelo: "PlanProduccion",
            metodo: "agregar",                            
            data_in: {
                p_nombre : DOM.nombre_plan_produccion.val(),
                p_fecha_inicio_proceso: Util.obtenerTimestamp()
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
                html += '<th style="text-align: center">Plan Produccion</th>';           
                html += '<th style="text-align: center">Fecha & hora</th>';
                html += '<th style="text-align: center">Proceso</th>';
                html += '<th style="text-align: center">OPCIONES</th>'; 
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
                
                $.each(resultado.datos.msj, function (i, item) {                 
                    html += '<tr>';                               
                    html += '<td align="center">' + (i+1) + '</td>'; 
                    html += '<td align="center">' + item.nombre + '</td>';                           
                    html += '<td align="center">' + item.fecha_hora_registro + '</td>';
                    html += '<td align="center">' + item.proceso + '</td>';
                    html += '<td align="center">';
                    html += '<button type="button" class="btn btn-default btn-xs" onclick="ver(' +"'"+ item.nombre +"'"+ ')" title="Ver plan producción"><i class="fa fa-eye"></i></button>';
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
        modelo: "PlanProduccion",
        metodo: "listar"
    }, funcion);
}

function ver(p_nombre_plan_produccion){
    var parametro = window.btoa(p_nombre_plan_produccion);
    document.location.href = "../ver/index.php?id=" + parametro;
}