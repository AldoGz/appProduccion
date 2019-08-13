var DOM = {};

$(document).ready(function () {
    listar();
    setDOM();
    setEventos();
    listarPedidos();    
});


function listar() {
    var funcion = function (resultado) {
        console.log(resultado);
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                var html = "";
                html += '<table id="tabla-listado" class="table table-bordered table-striped display nowrap" cellspacing="0" width="100%">';
                html += '<thead>';
                html += '<tr>';
                html += '<th style="text-align: center">Documento</th>';
                html += '<th style="text-align: center">Nombre del cliente</th>';
                html += '<th style="text-align: center">OPCIONES</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';              
                $.each(resultado.datos.msj, function (i, item) {                 
                    html += '<tr>';
                    html += '<td align="center">' + item.nro_documento + '</td>';                
                    html += '<td align="center">' + item.cliente + '</td>';   

                    html += '<td align="center">';

                    if ( item.estado_mrcb != true ) {
                        html = html + '<h5><span class="label label-danger">INACTIVO</span></h5>'; 
                    }else{                   
                        html = html + '<button type="button" class="btn btn-xs btn-info" title="agregar nuevo pedido" onclick="abrir('+ item.cod_cliente +')"><i class="fa fa-plus"></i> Nuevo pedido</button>'; 
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
            }else{
                Util.alertaB(resultado.datos);
            }    
        } 
    };
    
    new Ajex.Api({
        modelo: "Cliente",
        metodo: "listar"
    }, funcion);
}

function abrir($id){
    var id = window.btoa($id);
    document.location.href = "../carrito/index.php?id=" + id;
}


function setDOM() {
    DOM.form = $("#frm-grabar"),
    DOM.self = $("#myModal"),
    DOM.self2 = $("#myModal2"),

    DOM.p_codigo_pedido = $("#txtcodigo_pedido");
    DOM.p_foto = $("#txtfoto");
}

function limpiar(){
    $("#txtfecha_registro").val("");
    $("#txthora_registro").val("");
    $("#txtnum_operacion").val("");
    $("#txtmonto").val("");
    $("#txtfoto").val("");
}

function validar(){
    $("#txtnum_operacion").keypress(function (e) {
        return Util.soloNumeros(e);
    }); 
    $("#txtmonto").keypress(function (e) {
        var valor = $("#txtmonto").val();
        return Util.soloDecimal(e,valor,2);
    }); 

}

function setEventos() {
    validar();

    DOM.form.submit(function (evento) {
        evento.preventDefault();

        var funcion = function (resultado) {
            if (resultado.estado === 200) {
                if (resultado.datos.rpt === true) {                    
                    Util.alertaA(resultado.datos);
                    listarPedidos();                   
                    DOM.self2.modal("hide");                    
                }else{
                    Util.alertaB(resultado.datos);
                    DOM.self2.modal("hide");          
                }                        
            } 
        };         
        var entradas = {
            modelo: "Pago",
            metodo: "agregar", 
            data_in :{
                p_cod_pedido : DOM.p_codigo_pedido.val()
            }                            
        };
        Util.notificacion(entradas,funcion);
    });
}

function listarPedidos() {
    var funcion = function (resultado) {

        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                var html = "";
                html += '<table id="tabla-listado-pedidos" class="table table-bordered table-striped display nowrap" cellspacing="0" width="100%">';
                html += '<thead>';
                html += '<tr>';
                html += '<th style="text-align: center">N°</th>';
                html += '<th style="text-align: center">Cliente</th>'; 
                html += '<th style="text-align: center">Fecha & hora</th>';           
                html += '<th style="text-align: center">SubTotal (S/)</th>';
                html += '<th style="text-align: center">IGV (S/)</th>';
                html += '<th style="text-align: center">Monto Total (S/)</th>';
                html += '<th style="text-align: center">Estado</th>';
                html += '<th style="text-align: center">OPCIONES</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';                
                $.each(resultado.datos.msj, function (i, item) {                 
                    html += '<tr>';
                    html += '<td align="center">' + (i + 1) + '</td>';
                    html += '<td align="center">' + item.cliente + '</td>';
                    html += '<td align="center">' + item.fecha_hora_registro + '</td>';
                    html += '<td align="center">' + item.sub_total + '</td>';
                    html += '<td align="center">' + item.igv + '</td>';
                    html += '<td align="center">' + item.monto_total + '</td>';
                    
                    html += '<td align="center"><h5><span class="label label-success">'+item.proceso+'</span></h5></td>';
                    html += '<td align="center">';
                    if ( item.proceso === 'EN PROCESO') {
                        html += '<button type="button" class="btn btn-xs btn-danger" onclick="darBaja(' + item.cod_pedido + ',' +  "'false'" + ')" title="Eliminar"><i class="fa fa-times"></i></button>';                
                        html += '&nbsp;&nbsp;';
                    }
                    html += '<button type="button" class="btn btn-xs btn-default" data-toggle="modal" href="#myModal" onclick="ver(' + item.cod_pedido + ')" title="Ver detalle pedido"><i class="fa fa-eye"></i></button>';                
                    html += '&nbsp;&nbsp;';
                    /*PAGO*/

                    if ( item.proceso === 'EN PROCESO' ) {;
                        html += '<button type="button" class="btn btn-xs btn-success" data-toggle="modal" href="#myModal2" onclick="pago(' + item.cod_pedido + ',' + "'" + item.proceso + "'" + ')" title="Realizar pago"><i class="fa fa-money"></i></button>';          
                        html += '&nbsp;&nbsp;';
                    }
                    html += '</td>';
                    html += '</tr>';
                });
                html += '</tbody>';
                html += '<tfoot>';            
                html += '</tfoot>';
                html += '</table>';
                $("#listado-pedidos").html(html);
                $("#tabla-listado-pedidos").dataTable({
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
        metodo: "misPedidos"
    }, funcion);
}

function listarPedidosPago() {
    var funcion = function (resultado) {
        console.log(resultado);
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {

                $("#txtdocumento").text(resultado.datos.msj.cliente.documento);
                $("#txtcliente").text(resultado.datos.msj.cliente.cliente);
                $("#txtdireccion").text(resultado.datos.msj.cliente.direccion+'('+
                        resultado.datos.msj.cliente.departamento+' - '+
                        resultado.datos.msj.cliente.provincia+' - '+
                        resultado.datos.msj.cliente.distrito
                        +')');

                
                var html = "";
                $.each(resultado.datos.msj.pedido, function (i, item) {                 
                    html += '<tr>';
                    html += '<td align="center">' + (i + 1) + '</td>';
                    html += '<td align="center">' + item.nombre + '</td>';
                    html += '<td align="center">' + item.cantidad + '</td>';
                    html += '<td align="center">' + item.precio + '</td>';
                    html += '<td align="center">' + item.importe + '</td>';                
                    html += '</tr>';
                });
                
                $("#tabla-resumen-pago").html(html);

                $("#txtsub_total").text('SUBTOTAL: '+resultado.datos.msj.cliente.sub_total);
                $("#txtivg").text('IGV: '+resultado.datos.msj.cliente.igv);
                $("#txttotal").text('MONTO TOTAL: '+resultado.datos.msj.cliente.monto_total);
            }else{
                Util.alertaB(resultado.datos);
            }    
        } 
    };

    new Ajex.Api({
        modelo: "Pago",
        metodo: "leerDatos",
        data_in :{
            p_cod_pedido : DOM.p_codigo_pedido.val()
        }
    }, funcion);
}


function darBaja(p_codigo, p_estado) {
    var texto = 'anular';
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                Util.alertaA(resultado.datos);
                listarPedidos();
            }else{
                Util.alertaB(resultado.datos);
            }            
        } 
    };

    var entradas = {
        modelo: "Pedido",
        metodo: "habilitar",
        data_in: {
            p_cod_pedido : p_codigo,
            p_estado_mrcb : p_estado
        } 
    }; 
    Util.notificacion(entradas,funcion, texto);
}

function ver(p_codigo_pedido){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                var html = "";
                $.each(resultado.datos.msj, function (i, item) {
                    html += '<tr>';
                    html += '<td align="center">' + (i + 1) + '</td>';
                    html += '<td align="center">' + item.nombre + '</td>';
                    html += '<td align="center">' + item.cantidad + '</td>';
                    html += '<td align="center">' + item.precio + '</td>';
                    html += '<td align="center">' + item.importe + '</td>';                
                    html += '</tr>';
                });
                $("#detalle_productos").html(html);
                montoTotal();
            }else{
                Util.alertaB(resultado.datos);
            }
        } 
    };

    new Ajex.Api({
        modelo: "PedidoDetalle",
        metodo: "ver",
        data_out: [p_codigo_pedido]
    },funcion); 
}


function montoTotal(){
    var neto = 0;
    
    $("#detalle_productos tr").each(function(){
        var importe = $(this).find("td").eq(4).html();
        neto = neto  + parseFloat(importe);
    });
       
    //Mostrar los totales
    //$("#txttotal").val(neto.toFixed(2));
    $("#txtimporteneto").text(neto.toFixed(2));   
}

function pago(codigo_pedido){
    DOM.self2.find(".modal-title").text("Ficha de pago");
    DOM.p_codigo_pedido.val(codigo_pedido);
    listarPedidosPago();

    
}