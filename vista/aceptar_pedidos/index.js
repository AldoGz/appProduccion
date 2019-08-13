var DOM = {};

$(document).ready(function () {
    listar();
});

function listar() {
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                var html = "";
                html += '<table id="tabla-listado" class="table table-bordered table-striped display nowrap" cellspacing="0" width="100%">';
                html += '<thead>';
                html += '<tr>';
                html += '<th style="text-align: center">N°</th>';
                html += '<th style="text-align: center">Cliente</th>';
                html += '<th style="text-align: center">Fecha & hora</th>';
                html += '<th style="text-align: center">Estado pago</th>';
                html += '<th style="text-align: center">SubTotal</th>';
                html += '<th style="text-align: center">IGV</th>';
                html += '<th style="text-align: center">Monto Total</th>';
                html += '<th style="text-align: center">OPCIONES</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
                
                $.each(resultado.datos.msj, function (i, item) {
                    html += '<tr>';
                    html += '<td align="center">' + (i + 1) + '</td>';
                    html += '<td align="center">' + item.cliente + '</td>';
                    html += '<td align="center">' + item.fecha_hora_registro + '</td>';
                    html += '<td align="center">' + item.estado_pago + '</td>';
                    html += '<td align="center">' + item.sub_total + '</td>';
                    html += '<td align="center">' + item.igv + '</td>';
                    html += '<td align="center">' + item.monto_total + '</td>';
                    html += '<td align="center">';
                    html += '<button type="button" class="btn btn-xs btn-success" onclick="aceptado(' + item.cod_pedido + ')" title="Aceptar"><i class="fa fa-check"></i></button>';                
                    html += '&nbsp;&nbsp;';   
                    html += '<button type="button" class="btn btn-xs btn-danger" onclick="rechazado(' + item.cod_pedido + ')" title="Rechazar"><i class="fa fa-times"></i></button>';                
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
        modelo: "Pedido",
        metodo: "listar"
    }, funcion);
}

function aceptado(p_codigo) { 
    var texto = 'aceptar';
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
        modelo: "Pedido",
        metodo: "aceptado",
        data_in: {
            p_cod_pedido : p_codigo,
            p_fecha_atencion : Util.obtenerTimestamp()
        } 
    }; 
    Util.notificacion(entradas,funcion, texto);         
}

function rechazado(p_codigo) { 
    var texto = 'rechazar';
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
        modelo: "Pedido",
        metodo: "rechazar",
        data_in: {
            p_cod_pedido : p_codigo,
            p_fecha_atencion : Util.obtenerTimestamp()
        } 
    }; 
    Util.notificacion(entradas,funcion, texto);  
 }