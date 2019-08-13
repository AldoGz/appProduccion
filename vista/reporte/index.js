var DOM = {};

$(document).ready(function () {
    setDOM();
    setEventos();

});

function setDOM() {
    DOM.btnFiltro = $("#btnFiltro"),
    DOM.p_fecha_inicio = $("#txtfecha_inicio"),
    DOM.p_fecha_fin = $("#txtfecha_fin");   
}
function limpiar(){
    DOM.p_fecha_inicio.val("");
    DOM.p_fecha_fin.val("");
}

function setEventos() {
    DOM.btnFiltro.on("click", function () {
        if ( DOM.p_fecha_inicio.val() === '' ) {
            Util.alerta('warning','Debe ingresar la fecha inicio de plan de producción',2000);
            return 0;
        }
        if ( DOM.p_fecha_fin.val() === '' ) {
            Util.alerta('warning','Debe ingresar la fecha de finalización de plan de producción',2000);
            return 0;
        }
        
        fecha_inicio = Date.parse(DOM.p_fecha_inicio.val());
        fecha1 = new Date(fecha_inicio);

        fecha_fin = Date.parse(DOM.p_fecha_fin.val());
        fecha2 = new Date(fecha_fin);

        if ( fecha2 < fecha1) {
            Util.alerta('warning','La fecha fin debe ser mayor a la fecha inicio',2000);
            return 0;
        }
        listar();
        limpiar();
    });

    $("#listado").on("click", "table tbody tr td button", function(e){        
        var encontro = this.classList.contains('reporte');

        if ( encontro ) {
            $tr = this.parentElement.parentElement; // Obtener fila padre

            $id = $tr.dataset.id; // Obtener id de la fila
            $nombre = $tr.children[0].innerHTML; // Obtener fecha inicio de la fila
            $fecha_inicio = $tr.children[1].innerHTML; // Obtener fecha inicio de la fila
            $fecha_fin = $tr.children[5].innerHTML; // Obtener fecha fin de la fila
            

            var id = window.btoa($id);
            var no = window.btoa($nombre);
            var fi = window.btoa($fecha_inicio);
            var ff = window.btoa($fecha_fin);            
            window.open("http://"+window.location.host+"/appProduccion/vista/repp/index.php?id="+id+"&user="+no+"&fi="+fi+"&ff="+ff,'_blank');            
        }
    });

    $("#listado").on("click", "table tbody tr td button", function(e){        
        var encontro = this.classList.contains('excel');

        if ( encontro ) {
            $tr = this.parentElement.parentElement; // Obtener fila padre

            $id = $tr.dataset.id; // Obtener id de la fila

            $nombre = $tr.children[0].innerHTML; // Obtener fecha inicio de la fila
            $fecha_inicio = $tr.children[1].innerHTML; // Obtener fecha inicio de la fila
            $fecha_fin = $tr.children[5].innerHTML; // Obtener fecha fin de la fila


            var funcion = function (resultado) {
                if (resultado.estado === 200) {
                    if (resultado.datos.rpt === true) {
                        var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
                        tab_text += '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
                        tab_text += '<x:Name>Test Sheet</x:Name>';
                        tab_text += '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
                        tab_text += '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head>';
                        

                        tab_text += '<table>';
                        tab_text += '<tr>';
                        tab_text += '<td>REPORTE PLAN PRODUCCION : '+ $nombre +'</td>';
                        tab_text += '<td>FECHA INICIO PROCESO: '+ $fecha_inicio +'</td>';
                        tab_text += '<td>FECHA FIN PROCESO: '+ $fecha_fin +'</td>';
                        tab_text += '</tr>';

                        tab_text += '<tr>';
                        tab_text += '<th>#</th>';
                        tab_text += '<th>Actividad</th>';
                        tab_text += '<th>Piezas</th>';
                        tab_text += '<th>Total</th>';
                        tab_text += '<th>Buenas</th>';
                        tab_text += '<th>Falladas</th>';
                        tab_text += '</tr>';
               
                        $.each(resultado.datos.msj, function (i, item) {                 
                            tab_text += '<tr>';
                            tab_text += '<td>' + item.cod_pieza + '</td>';                
                            tab_text += '<td>' + item.accion + '</td>'; 
                            tab_text += '<td>' + item.pieza + '</td>';
                            tab_text += '<td>' + item.total+'</td>';
                            tab_text += '<td>' + item.buenas+'</td>';
                            tab_text += '<td>' + item.falladas+'</td>';
                            tab_text += '</tr>';
                        });
                        tab_text += '</table>';
                        tab_text += '</html>';

                        window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
                        e.preventDefault();

                    }else{
                        Util.alertaB(resultado.datos);
                    }    
                } 
            };


            new Ajex.Api({
                modelo: "Reporte",
                metodo: "cabecera",
                data_in :{
                    p_codigo_plan_produccion : $id
                }
            }, funcion); 

            
           
        }        
    });

}



function listar() {
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                var html = "";
                if ( resultado.datos.msj.length <= 0 ) {                   
                    Util.alerta('info','No se encontrado resultado',2000);
                    return 0;
                }else{
                    html += '<table id="tabla-listado" class="table table-bordered table-striped display nowrap" cellspacing="0" width="100%">';
                    html += '<thead>';
                    html += '<tr>';
                    html += '<th style="text-align: center">Código</th>';
                    html += '<th style="text-align: center">Inicio Proceso</th>';
                    html += '<th style="text-align: center">Acondicionamiento</th>';
                    html += '<th style="text-align: center">Fundición</th>';
                    html += '<th style="text-align: center">Acabado</th>';
                    html += '<th style="text-align: center">Finalizado</th>';
                    html += '<th style="text-align: center">VER</th>';
                    html += '</tr>';
                    html += '</thead>';
                    html += '<tbody>';  
                    $.each(resultado.datos.msj, function (i, item) {
                        html += '<tr data-id="'+item.cod_plan_produccion+'">';                
                        html += '<td align="center">' + item.nombre + '</td>';
                        html += '<td align="center">' + item.fecha_inicio_proceso + '</td>';
                        html += '<td align="center">' + item.acondicionamiento_fecha + '</td>';
                        html += '<td align="center">' + item.fundicion_fecha + '</td>';
                        html += '<td align="center">' + item.acabado_fecha + '</td>';
                        html += '<td align="center">' + item.finalizacion_fecha + '</td>';
                        html += '<td align="center">';
                        html += '<button type="button" class="btn btn-default btn-xs reporte" title="Reporte '+item.nombre+'"><i class="fa fa-print" aria-hidden="true"></i></button>';
                        html += '&nbsp;&nbsp;';
                        html += '<button id="test" type="button" class="btn btn-default btn-xs excel" title="Exportar excel '+item.nombre+'"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>';
                        html += '&nbsp;&nbsp;';
                        html += '</td>';
                        html += '</tr>';
                    });
                    html += '</tbody>';
                    html += '<tfoot>';            
                    html += '</tfoot>';
                    html += '</table>';
                }
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
        modelo: "Reporte",
        metodo: "reportePlanProduccion",
        data_in :{
            p_fecha_inicio : DOM.p_fecha_inicio.val(),
            p_fecha_fin : DOM.p_fecha_fin.val()
        }
    }, funcion); 
}
