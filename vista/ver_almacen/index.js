var DOM = {};



$(document).ready(function () {
    setDOM();
    listar();
});

function setDOM() {
    DOM.tblProductos = $("#tab_productos"),
    DOM.tblPiezas = $("#tab_piezas"),
    DOM.tblMP = $("#tab_mp");
}


function listar() {
    var printTbl = function(tab, data){
        var html = "";
                html += '<table class="table table-bordered">';
                html += '<thead>';
                html += '<tr>';
                html += '<th style="text-align: center">N°</th>';
                html += '<th style="text-align: center">Codigo</th>';
                html += '<th style="text-align: center">Nombre</th>';
                html += '<th style="text-align: center">Cantidad</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';                
                if (data.length > 0){
                  $.each(data, function (i, item) {                 
                        html += '<tr>';
                        html += '<td align="center">' + (i+1) + '</td>';                
                        html += '<td align="center">' + item.codigo + '</td>';                
                        html += '<td align="center">' + item.nombre + '</td>'; 
                        html += '<td align="center">' + item.cantidad + '</td>'; 
                        html += '</tr>';
                    });  
              } else {
                     html += '<tr>';
                     html += '<td colspan="4" align="center"> No hay registros disponibles </td>';  
                     html += '</tr>';
              }
                
                html += '</tbody>';
                html += '<tfoot>';            
                html += '</tfoot>';
                html += '</table>';

                tab.html(html);

                $("#tabla-listado").dataTable({
                    "aaSorting": [[0, "asc"]],
                    "rowReorder": {
                        selector: 'td:nth-child(2)'
                    },
                    "responsive": true
                });
    };

    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                printTbl(DOM.tblProductos,resultado.datos.data.productos);
                printTbl(DOM.tblPiezas,resultado.datos.data.piezas);
                printTbl(DOM.tblMP,resultado.datos.data.mp);
            }else{
                Util.alertaB(resultado.datos);
            }    
        } 
    };
    new Ajex.Api({
        modelo: "AlmacenStock",
        metodo: "listar"
    }, funcion);
}
