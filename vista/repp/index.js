var DOM = {};

var parametrosArray = retornarParametro();
var p1 =  parametrosArray[0]; 
var p2 =  parametrosArray[1]; 
var p3 =  parametrosArray[2]; 
var p4 =  parametrosArray[3]; 



$(document).ready(function () {
    listar();
});


function listar() {
    $("#txtnombre").text("PLAN PRODUCCIÓN: "+p2);
    $("#txtfi").text("FECHA INICIO PROCESO: "+p3);
    $("#txtff").text("FECHA FIN PROCESO: "+p4);
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                var html = "";
                html += '<table id="tabla-listado" class="table table-bordered table-striped display nowrap" cellspacing="0" width="100%">';
                html += '<thead>';
                html += '<tr>';
                html += '<th style="text-align: center">#</th>';
                html += '<th style="text-align: center">Actividad</th>';
                html += '<th style="text-align: center">Piezas</th>';
                html += '<th style="text-align: center">Total</th>';
                html += '<th style="text-align: center">Buenas</th>';
                html += '<th style="text-align: center">Falladas</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';  
                             
                $.each(resultado.datos.msj, function (i, item) {                 
                    html += '<tr>';
                    html += '<td align="center">' + item.cod_pieza + '</td>';                
                    html += '<td align="center">' + item.accion + '</td>'; 
                    html += '<td align="center">' + item.pieza + '</td>';
                    html += '<td align="center">' + item.total+'</td>';
                    html += '<td align="center">' + item.buenas+'</td>';
                    html += '<td align="center">' + item.falladas+'</td>';
                    html += '</tr>';
                });
                html += '</tbody>';

                html += '<tfoot>';            
                html += '</tfoot>';
                html += '</table>';
                $("#listado").html(html);
            }else{
                Util.alertaB(resultado.datos);
            }    
        } 
    };
    new Ajex.Api({
        modelo: "Reporte",
        metodo: "cabecera",
        data_in :{
            p_codigo_plan_produccion : p1
        }
    }, funcion); 
}
