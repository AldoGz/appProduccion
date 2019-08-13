var DOM = {};

$(document).ready(function () {
    setDOM();
    setEventos();
    listar();   
    paginacion();
    montoTotal(); 
    validar();
    cargarDepartamento();
    leerDatos();
    validarCheckBox();


});

var parametrosArray = retornarParametro();
var p1 =  parametrosArray[0]; 

function setDOM() {
    DOM.form = $("#frm-grabar"),
    DOM.listado = $("#listado"),
    DOM.self = $("#myModal"),
    DOM.btnAgregar = $("#btnAgregar"),
    DOM.codigo_cargo = $("#txtcodigo_cargo"),
    DOM.descripcion = $("#txtdescripcion"),
    DOM.estado = $("#txtestado"),

    DOM.destinatario = $("#txtdestinatario"),

    DOM.depatarmento = $("#txtdepartamento"),
    DOM.provincia = $("#txtprovincia"),
    DOM.distrito = $("#txtdistrito"),
    DOM.direccion = $("#txtdireccion_destino");

}

function limpiar(){
    var html = "<option></option>";
    DOM.provincia.html(html).select2({
          placeholder: "Seleccionar provincia",
          allowClear: true
    });

    var html = "<option></option>";
    DOM.distrito.html(html).select2({
          placeholder: "Seleccionar distrito",
          allowClear: true
    });
}

var carrito = new Array(); //almacenar los productos que se desea para el pedido

function setEventos() {
    limpiar();
    $("#txtbuscar").on("keyup",function(){
        buscar();
    });

    DOM.btnAgregar.on("click", function () {
        DOM.self.find(".modal-title").text("Carrito de compras");        
    });

    DOM.depatarmento.change(function(){
        cargarProvincia(); 
    });

    DOM.provincia.change(function(){
        cargarDistrito(); 
    });

    $("#txtdestinatario").change(function(){
        validarCheckBox();

    });


    DOM.form.submit(function (evento) {
        evento.preventDefault();
        /*limpiar el array*/
        carrito.splice(0, carrito.length);
        /*limpiar el array*/

        if($("input[type='checkbox']").is(':checked') === true) {        
            if ( DOM.depatarmento.val() === '' ) {
                Util.alerta('warning','Debe seleccionar un departamento para pedido',2000);
                return 0;
            }
            if ( DOM.provincia.val() === '' ) {
                Util.alerta('warning','Debe seleccionar un provincia para pedido',2000);
                return 0;
            }
            if ( DOM.distrito.val() === '' ) {
                Util.alerta('warning','Debe seleccionar un distrito para pedido',2000);
                return 0;
            }

            if ( DOM.direccion.val() === '' ) {
                Util.alerta('warning','Debe ingresar una dirección para el pedido',2000);
                return 0;
            }
        }
        // PRUEBA
        

        /*RECORREMOS CADA FILA DE LA TABLA PRODUCTO ORDEN*/
        $("#detalle_productos tr").each(function(){
            var codigo_producto = this.dataset.id;
            var cantidad = $(this).find("td input").val();
            var precio = $(this).find("td").eq(3).html();
            var importe = $(this).find("td").eq(4).html();

            var obj = {
                p_codigo_producto : parseInt(codigo_producto),
                p_cantidad : parseInt(cantidad),
                p_precio : parseFloat(precio),
                p_importe : parseFloat(importe)
            };

            carrito.push(obj); //agregar el objeto objDetalle al array arrayDetalle
        });
        /*RECORREMOS CADA FILA DE LA TABLA PRODUCTO ORDEN*/

        //Convertimos el array "detalle" a formato de JSON
        var json = JSON.stringify(carrito);
        //Convertimos el array "detalle" a formato de JSON

        var neto = parseFloat($("#txttotal").val());
        var subtotal = neto/1.18;
        var igv = neto - subtotal;

        var funcion = function (resultado) {
            if (resultado.estado === 200) {
                if (resultado.datos.rpt === true) {                    
                    Util.alertaA(resultado.datos); 
                    $("#detalle_productos").empty();
                    montoTotal();
                    validar();
                    $("#txtcarrito").text(' ('+$("#detalle_productos tr").length+') s/. '+$("#txtimporteneto").text()); 
                    DOM.self.modal("hide");                                             
                }else{
                    Util.alertaB(resultado.datos);
                    DOM.self.modal("hide");         
                }                        
            } 
        };         
        var entradas = {
            modelo: "Pedido",
            metodo: "agregar", 
            data_in :{                                                        
                p_monto_total : neto,
                p_sub_total : subtotal,
                p_igv : igv,
                p_codigo_usuario : p1
            },
            data_out : [json,DOM.depatarmento.val(),DOM.provincia.val(),DOM.distrito.val(),DOM.direccion.val().toUpperCase()]                            
        };
        Util.notificacion(entradas,funcion);        
    });

    $("#detalle_productos").on("change", "tr td input", function(e){
        var encontro =this.classList.contains('cantidad');
        
        $tr = this.parentElement.parentElement; // Obtener fila padre
        $precio = $tr.children[3]; // Obtener precio de la fila
        $importe = $tr.children[4]; // Obtener importe de la fila
        

        if ( encontro ) {
            var cantidad =  this.value; // Obtener el valor de input de la fila

            var valor = $precio.innerHTML;
            $importe.innerHTML = parseFloat(cantidad * valor).toFixed(2);
            montoTotal();
            $("#txtcarrito").text(' ('+$("#detalle_productos tr").length+') s/. '+$("#txtimporteneto").text()); 
        }   
    });

    $("#detalle_productos").on("click", "tr td i", function(e){
        var encontro =this.classList.contains('eliminar');

        $tr = this.parentElement.parentElement; // Obtener fila padre

        if ( encontro ) {
            var n = new Noty({ 
              layout: 'bottomCenter',        
              text: '¿Desea eliminar el registro seleccionado?',
              buttons: [            
                Noty.button('Si', 'btn btn-success', function () {
                    $tr.remove();
                    montoTotal();
                    validar();
                    $("#txtcarrito").text(' ('+$("#detalle_productos tr").length+') s/. '+$("#txtimporteneto").text()); 
                    n.close();                  
                }),
                Noty.button('No', 'btn btn-error', function () {
                    n.close();
                })
              ]
            }).show(); 
        }
    });

    
}

function validarCheckBox(){
    if($("input[type='checkbox']").is(':checked') === true) { 
        DOM.depatarmento.prop("disabled",false);
        DOM.provincia.prop("disabled",false);
        DOM.distrito.prop("disabled",false);
        DOM.direccion.prop("disabled",false);


    }else{
        DOM.depatarmento.prop("disabled",true);
        DOM.provincia.prop("disabled",true);
        DOM.distrito.prop("disabled",true);
        DOM.direccion.prop("disabled",true);

    }
}

function buscar(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                var html = "";               
                $.each(resultado.datos.msj, function (i, item) {  
                    html += '<div class="col-md-6 col-sm-6 col-xs-12">';    
                    html += '<div class="x_panel tile fixed_height_325 overflow_hidden">';                         
                    html += '<div class="x_content">'; 
                    html += '<table class="" style="width:100%">'; 
                    html += '<tbody>';   
                    html += '<tr>'; 
                    html += '<td style="text-align: center">';  
                    html += '<br>'; 
                    html += '<div class="panel panel-default">'; 
                    html += '<div class="panel-body">'; 
                    html += '<h4>' + item.nombre + '</h4>'; 
                    html += '<img src="../../imagenes/productos/' + item.img + '" class="img-rounded" alt="' + item.img + '" width="160" height="160">'; 
                    html += '<h2>' + item.precio_fijo +'</h2>'; 
                    html += '<button type="button" class="btn btn-danger" onclick="agregarCarrito('+item.cod_producto+')" title="Agregar carrito">Agregar</button>'; 
                    html += '</div>'; 
                    html += '</div>'; 
                    html += '</td>'; 
                    html += '</tr>'; 
                    html += '</tbody>'; 
                    html += '</table>'; 
                    html += '</div>'; 
                    html += '</div>'; 
                    html += '</div>'; 
                });
                $("#listado-productos").html(html);
            }else{
                Util.alertaB(resultado.datos);
            }            
        } 
    };

    new Ajex.Api({
        modelo: "Producto",
        metodo: "buscar",                            
        data_out: [$("#txtbuscar").val()]
    }, funcion);


}

function validar(){
    if ( parseFloat($("#txttotal").val()) <= 0 ) {
        $("#mensaje").show();
        $("#tabla").hide();
        $("#btnGrabar").hide();
        $("#informacion").hide();
    }else{
        $("#mensaje").hide();
        $("#tabla").show();
        $("#btnGrabar").show();
        $("#informacion").show();
    }
}


function paginacion() {    
    var funcion = function (resultado) {         
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                var totalRows = parseInt(resultado.datos.msj);
                var maxRows = 4;                
                var pagenum = Math.ceil(totalRows/maxRows);

                $('#paginacion').bootpag({
                    total: pagenum
                }).on("page", function(event, num){                   
                    var limite = maxRows*num;
                    var tope = (num-1)*maxRows;

                    /*EMPEZAR*/
                    var funcion = function (resultado) {
                        if (resultado.estado === 200) {
                            if (resultado.datos.rpt === true) {              
                                var html = "";               
                                $.each(resultado.datos.msj, function (i, item) {  
                                    html += '<div class="col-md-6 col-sm-6 col-xs-12">';    
                                    html += '<div class="x_panel tile fixed_height_325 overflow_hidden">';                         
                                    html += '<div class="x_content">'; 
                                    html += '<table class="" style="width:100%">'; 
                                    html += '<tbody>';   
                                    html += '<tr>'; 
                                    html += '<td style="text-align: center">';  
                                    html += '<br>'; 
                                    html += '<div class="panel panel-default">'; 
                                    html += '<div class="panel-body">'; 
                                    html += '<h4>' + item.nombre + '</h4>'; 
                                    html += '<img src="../../imagenes/productos/' + item.img + '" class="img-rounded" alt="' + item.img + '" width="50" height="50">'; 
                                    html += '<h2>' + item.precio_fijo +'</h2>'; 
                                    html += '<button type="button" class="btn btn-danger" onclick="agregarCarrito('+item.cod_producto+')" title="Agregar carrito">Agregar</button>'; 
                                    html += '</div>'; 
                                    html += '</div>'; 
                                    html += '</td>'; 
                                    html += '</tr>'; 
                                    html += '</tbody>'; 
                                    html += '</table>'; 
                                    html += '</div>'; 
                                    html += '</div>'; 
                                    html += '</div>'; 
                                });
                                $("#listado-productos").html(html);                               
                            }else{
                                Util.alertaB(resultado.datos);
                            }    
                        } 
                    };

                    new Ajex.Api({
                        modelo: "Producto",
                        metodo: "listarProductos",
                        data_out : [limite,tope]
                    }, funcion);
                    /*EMPEZAR*/
                });                               
            }else{
                Util.alertaB(resultado.datos);
            }    
        } 
    };

    new Ajex.Api({
        modelo: "Producto",
        metodo: "total"
    }, funcion); 
}

function listar() {    
    var funcion = function (resultado) { 
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {              
                var html = "";               
                $.each(resultado.datos.msj, function (i, item) {  
                    html += '<div class="col-md-6 col-sm-6   col-xs-12 elemento">';    
                    html += '<div class="x_panel tile fixed_height_325 overflow_hidden">';                         
                    html += '<div class="x_content">'; 
                    html += '<table class="" style="width:100%">'; 
                    html += '<tbody>';   
                    html += '<tr>'; 
                    html += '<td style="text-align: center">';  
                    html += '<br>'; 
                    html += '<div class="panel panel-default">'; 
                    html += '<div class="panel-body">'; 
                    html += '<h4>' + item.nombre + '</h4>'; 
                    html += '<img src="../../imagenes/productos/' + item.img + '" class="img-rounded" alt="' + item.img + '" width="160" height="160">'; 
                    html += '<h2>' + item.precio_fijo +'</h2>'; 
                    html += '<button type="button" class="btn btn-danger" onclick="agregarCarrito('+item.cod_producto+')" title="Agregar carrito">Agregar</button>'; 
                    html += '</div>'; 
                    html += '</div>'; 
                    html += '</td>'; 
                    html += '</tr>'; 
                    html += '</tbody>'; 
                    html += '</table>'; 
                    html += '</div>'; 
                    html += '</div>'; 
                    html += '</div>'; 
                });
                $("#listado-productos").html(html);                               
            }else{
                Util.alertaB(resultado.datos);
            }    
        } 
    };

    new Ajex.Api({
        modelo: "Producto",
        metodo: "listarProductos",
        data_out : [4,0]
    }, funcion); 
}



function editar(p_codigo) {
    DOM.self.find(".modal-title").text("Editar producto");
    DOM.operacion.val("editar");
    DOM.imagen.show();
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                $.each(resultado.datos.msj, function (i, item) {
                    DOM.codigo_producto.val(item.cod_producto);
                    DOM.nombre.val(item.nombre);
                    DOM.descripcion.val(item.descripcion);
                    DOM.precio.val(item.precio_fijo);
                    $("#nombre_foto").html('<img src="../../imagenes/productos/'+item.img+'" width=100 height=100>');                    
                });
            }else{
                Util.alertaB(resultado.datos);
            }
        }
    };
    new Ajex.Api({
        modelo: "Producto",
        metodo: "leerDatos",
        data_in: {
            p_cod_producto: p_codigo
        }
    },funcion);
}

function agregarCarrito(p_codigo_producto){
    if ( !(parseFloat($("#txttotal").val()) <= 0) ) { 
        if (! validarMismo(p_codigo_producto)) {
            Util.alerta('warning','No es posible agregar al carrito de compras un misma producto 2 veces',2000);            
            return 0; //detiene el programa
        }            
    } 
    var funcion = function (resultado) {
        console.log(resultado);
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                var item =  resultado.datos.msj.producto;         

                //Elaborar una variable con el HTML para agregar al detalle
                var fila = '<tr data-id="'+item.cod_producto+'">' +
                                '<td class="text-center"><i style="font-size:20px;" class="fa fa-close text-danger eliminar"></i></td>' +
                                '<td class="text-center"><img src="../../imagenes/productos/'+item.img+'" width=30 height=30><p>' +item.nombre+ '</p></td>'+                                                                        
                                '<td class="text-center"><input class="text-center cantidad" type="number" value="1" class="form-control input-sm cantidad"  min="1"/></td>' +
                                '<td class="text-center">' + item.precio_fijo + '</td>' +                                                                                    
                                '<td class="text-center">' + parseFloat(parseFloat(item.precio_fijo)*1).toFixed(2)+ '</td>' +                                                            
                            '</tr>';

                //Agregar el registro al detalle de la producto
                $("#detalle_productos").append(fila);

                montoTotal();
                validar();

                $("#txtcarrito").text(' ('+$("#detalle_productos tr").length+') s/. '+$("#txtimporteneto").text()); 

            }else{
                Util.alertaB(resultado.datos);
            }
        }
    };
    new Ajex.Api({
        modelo: "Producto",
        metodo: "leerDatos",
        data_in: {
            p_cod_producto: p_codigo_producto
        }
    },funcion);
}

function montoTotal(){
    var neto = 0;
    
    $("#detalle_productos tr").each(function(){
        var importe = $(this).find("td").eq(4).html();
        neto = neto  + parseFloat(importe);
    });
       
    //Mostrar los totales
    $("#txttotal").val(neto.toFixed(2));
    $("#txtimporteneto").text(neto.toFixed(2));   
}

function validarMismo(p_codigo_producto){
    var c = 0;    
    $("#detalle_productos tr").each(function(){
        var codigo = this.dataset.id;

        if (parseInt(codigo) === p_codigo_producto){
            c++;
        }
    });    
    if (c >= 1){
        return false;
    }    
    return true; 
}


function cargarDepartamento(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if (resultado.datos.rpt === true) {
                var html = "<option></option>";
                for (var i = 0; i < datos.length; i++) {
                    html += "<option value=" + datos[i].codigo_departamento + ">" + datos[i].nombre + "</option>";
                }
                DOM.depatarmento.html(html).select2({
                      placeholder: "Seleccionar departamento",
                      allowClear: true
                });
            }else{
                Util.alertaB(datos);
            }
        }
    };
    new Ajex.Api({
        modelo : "Ubigeo",
        metodo : "llenarDepartamento"
    }, funcion);
}

function cargarProvincia(){
    if ( DOM.depatarmento.val() === '') {
        var html = "<option></option>";
        DOM.provincia.html(html).select2({
              placeholder: "Seleccionar provincia",
              allowClear: true
        });

        var html = "<option></option>";
        DOM.distrito.html(html).select2({
              placeholder: "Seleccionar distrito",
              allowClear: true
        });
        return 0;
    }
    var funcion = function (resultado) {
        console.log(resultado);
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if (resultado.datos.rpt === true) {
                var html = "<option></option>";
                for (var i = 0; i < datos.length; i++) {
                    html += "<option value=" + datos[i].codigo_provincia + ">" + datos[i].nombre + "</option>";
                }
                DOM.provincia.html(html).select2({
                      placeholder: "Seleccionar provincia",
                      allowClear: true
                });
            }else{
                Util.alertaB(datos);
            }
        }
    };
    new Ajex.Api({
        modelo : "Ubigeo",
        metodo : "llenarProvincia",
        data_out :  [DOM.depatarmento.val()]
    }, funcion);
}

function cargarDistrito(){
    if ( DOM.provincia.val() === '') {
        var html = "<option></option>";
        DOM.distrito.html(html).select2({
              placeholder: "Seleccionar distrito",
              allowClear: true
        });
        return 0;
    }
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if (resultado.datos.rpt === true) {
                var html = "<option></option>";
                for (var i = 0; i < datos.length; i++) {
                    html += "<option value=" + datos[i].codigo_distrito + ">" + datos[i].nombre + "</option>";
                }
                DOM.distrito.html(html).select2({
                      placeholder: "Seleccionar distrito",
                      allowClear: true
                });
            }else{
                Util.alertaB(datos);
            }
        }
    };
    new Ajex.Api({
        modelo : "Ubigeo",
        metodo : "llenarDistrito",
        data_out :  [DOM.provincia.val()]
    }, funcion);
}

function leerDatos(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if (resultado.datos.rpt === true) {
                $("#txtcliente").text("NOMBRE DEL CLIENTE : "+resultado.datos.msj.cliente);
                $("#txtcliente2").text("NOMBRE DEL CLIENTE : "+resultado.datos.msj.cliente);
                $("#txttelefono_movil").text("CELULAR : "+resultado.datos.msj.celular);
                $("#txtdireccion").text("DIRECCIÓN : "+resultado.datos.msj.direccion + "("+resultado.datos.msj.depatarmento+" - "+resultado.datos.msj.provincia+" - "+resultado.datos.msj.distrito);
                $("#txtdocumento").text("RUC/DNI : "+resultado.datos.msj.nro_documento);
            }else{
                Util.alertaB(datos);
            }
        }
    };
    new Ajex.Api({
        modelo : "Cliente",
        metodo : "leerPerfil",
        data_in : {
            p_cod_cliente : p1
        }
    }, funcion);
}