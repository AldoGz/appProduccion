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
                html += '<th style="text-align: center">Usuario</th>';
                html += '<th style="text-align: center">Cliente</th>';
                html += '<th style="text-align: center">OPCIONES</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';                
                $.each(resultado.datos.msj, function (i, item) {                 
                    html += '<tr>';
                    html += '<td align="center">' + (i+1) + '</td>';                
                    html += '<td align="center">' + item.usuario + '</td>';
                    html += '<td align="center">' + item.user + '</td>';
                    html += '<td align="center">';
                    
                    var tmpEstado = item.estado_acceso != "A"?
                            {icon: "up", title: "Habilitar", bol: "A", boton: "btn-warning"} :
                            {icon: "down", title: "Deshabilitar", bol: "I", boton: "btn-dark"};
                    html += '<button type="button" class="btn btn-xs ' + tmpEstado.boton + '" onclick="acceso(' + item.cod_usuario + ',' + "'" + tmpEstado.bol + "'" + ')" title="' + tmpEstado.title + '"><i class="fa fa-thumbs-o-' + tmpEstado.icon + '"></i></button>';                
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
        modelo: "Usuario",
        metodo: "listar",
        data_in:{
            p_tipo_usuario : 'C'
        }
    }, funcion);
}

function acceso(p_codigo, p_estado) { 
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
        modelo: "Usuario",
        metodo: "status",
        data_in: {
            p_cod_usuario : p_codigo,
            p_estado_acceso : p_estado
        }  
    }; 
    Util.notificacion(entradas,funcion, texto); 
}

