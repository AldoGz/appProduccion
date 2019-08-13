var DOM = {};

$(document).ready(function () {
    setDOM();
    setEventos();
    listar();

});

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
        if ( !( parseFloat($("#txtmonto").val()) <= parseFloat($("#txtmonto_total").val()) ) ) {
            Util.alerta('warning','El monto de pago es mayor que monto total',2000);  
            return 0; //detiene el programa
        }

        if ( parseFloat($("#txtmonto").val()) >= 0.000 && parseFloat($("#txtmonto").val()) <= 0.009) {
            Util.alerta('warning','El monto debe ser mayor de cero',2000);
            return 0;
        };

        var n = new Noty({ 
          layout: 'bottomCenter',        
          text: '¿Esta seguro de grabar los datos ingresados?',
          buttons: [            
            Noty.button('Si', 'btn btn-success', function () {
                var datos_frm = new FormData();
                datos_frm.append("p_array_datos", DOM.form.serialize());
                datos_frm.append("p_foto", DOM.p_foto.prop('files')[0]);

                var funcion = function (resultado) {
                    if (resultado.estado === 200) {
                        if (resultado.datos.rpt === true) {                    
                            Util.alertaA(resultado.datos);
                            listarPago();
                            limpiar();  
                            listar();                          
                            DOM.self2.modal("hide");                    
                        }else{
                            Util.alertaB(resultado.datos);
                            DOM.self2.modal("hide");          
                        }                        
                    } 
                };         
                $.ajax({
                    url: "../../controlador/guardar.pago.php",
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: datos_frm,
                    type: 'post',
                    success: funcion
                });    
                n.close();                  
            }),
            Noty.button('No', 'btn btn-error', function () {
                n.close();
            })
          ]
        }).show();       
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
                html += '<th style="text-align: center">Fecha & hora</th>';
                html += '<th style="text-align: center">Estado proceso</th>';
                html += '<th style="text-align: center">Estado pago</th>';                
                html += '<th style="text-align: center">SubTotal (S/)</th>';
                html += '<th style="text-align: center">IGV (S/)</th>';
                html += '<th style="text-align: center">Monto Total (S/)</th>';
                html += '<th style="text-align: center"># Pedidos</th>';
                html += '<th style="text-align: center">OPCIONES</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';                
                $.each(resultado.datos.msj, function (i, item) {                 
                    html += '<tr>';
                    html += '<td align="center">' + (i + 1) + '</td>';
                    html += '<td align="center">' + item.fecha_hora_registro + '</td>';
                    html += '<td align="center">' + item.proceso + '</td>';
                    html += '<td align="center">' + item.estado_pago + '</td>';
                    html += '<td align="center">' + item.sub_total + '</td>';
                    html += '<td align="center">' + item.igv + '</td>';
                    html += '<td align="center">' + item.monto_total + '</td>';
                    html += '<td align="center">' + item.cantidad_pedido + '</td>';
                    html += '<td align="center">';
                    if ( item.proceso === 'EN PROCESO') {
                        html += '<button type="button" class="btn btn-xs btn-danger" onclick="darBaja(' + item.cod_pedido + ',' +  "'false'" + ')" title="Eliminar"><i class="fa fa-times"></i></button>';                
                        html += '&nbsp;&nbsp;';
                    }
                    html += '<button type="button" class="btn btn-xs btn-default" data-toggle="modal" href="#myModal" onclick="ver(' + item.cod_pedido + ')" title="Ver detalle pedido"><i class="fa fa-eye"></i></button>';                
                    html += '&nbsp;&nbsp;';
                    /*PAGO*/
                    html += '<button type="button" class="btn btn-xs btn-success" data-toggle="modal" href="#myModal2" onclick="pago(' + item.cod_pedido + ')" title="Realizar pago"><i class="fa fa-money"></i></button>';          
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
        metodo: "misPedidos"
    }, funcion);
}

function listarPago() {
    var funcion = function (resultado) {
        console.log(resultado);
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                var html = "";
                $("#txtmonto_total").val(resultado.datos.msj.monto_pago);  

                if ( parseFloat($("#txtmonto_total").val()) === 0 ) {
                    $("#txtfecha_registro").prop("readonly",true);
                    $("#txthora_registro").prop("readonly",true);
                    $("#txtnum_operacion").prop("readonly",true);
                    $("#txtmonto").prop("readonly",true);
                    $("#txtfoto").prop("readonly",true);
                    $("#foto_view").hide();
                    $("#btnVoucher").hide();
                } else {
                    $("#txtfecha_registro").prop("readonly",false);
                    $("#txthora_registro").prop("readonly",false);
                    $("#txtnum_operacion").prop("readonly",false);
                    $("#txtmonto").prop("readonly",false);
                    $("#txtfoto").prop("readonly",false);
                    $("#foto_view").show();
                    $("#btnVoucher").show();
                }

                $.each(resultado.datos.msj.mis_pagos, function (i, item) {                 
                    html += '<tr>';

                    if ( item.estado_mrcb === false ) {
                        html += '<td align="center"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i> ' + item.descripcion + '</td>';
                    }else{
                        html += '<td align="center"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> ' + item.descripcion + '</td>';
                    }


                    
                    html += '<td align="center"> S/. ' + item.monto_pagado + '</td>';
                    html += '</tr>';
                });
                

                html += '<tr>';
                html += '<td align="center">' + resultado.datos.msj.subtotal.descripcion + '</td>';
                html += '<td align="center"> S/. ' + resultado.datos.msj.subtotal.subtotal + '</td>';
                html += '</tr>';

                html += '<tr>';
                html += '<td align="center">' + resultado.datos.msj.total.descripcion + '</td>';
                html += '<td align="center"> S/. ' + resultado.datos.msj.total.total + '</td>';
                html += '</tr>';
                
                $("#tabla-resumen-pago").html(html);;
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
                listar();
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
    DOM.self2.find(".modal-title").text("BOLETA DE RESUMEN");
    DOM.p_codigo_pedido.val(codigo_pedido);
    listarPago();



}