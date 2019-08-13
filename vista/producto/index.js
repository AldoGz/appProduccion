var DOM = {};

$(document).ready(function () {
    setDOM();
    setEventos();
    listar();
    cargarMateriaPrima(); 
    
});

function setDOM() {
    DOM.form = $("#frm-grabar"),
    DOM.listado = $("#listado"),
    DOM.self = $("#myModal"),
    DOM.btnAgregar = $("#btnAgregar"),
    DOM.operacion = $("#txtoperacion"),
    DOM.codigo_producto = $("#txtcodigo_producto"),  
    DOM.nombre = $("#txtnombre"),
    DOM.descripcion = $("#txtdescripcion"),
    DOM.precio = $("#txtprecio"),
    DOM.foto = $("#txtfoto"),
    DOM.imagen = $("#imagen");

    DOM.codigo_materia_prima = $("#txtcodigo_materia_prima"),
    DOM.cantidad = $("#txtcantidad");

    DOM.btnAdicionar = $("#btnAdicionar");  
    DOM.btnAumentar = $("#btnAumentar");

}

function limpiar() {
    DOM.codigo_producto.val(""); 
    DOM.nombre.val("");
    DOM.descripcion.val("");
    DOM.precio.val("");
    DOM.foto.val("");
}

function l2(){
    DOM.codigo_materia_prima.val("").select2({
        "placeholder" : "Seleccionar materia prima",
        "allowClear" : true
    });
    DOM.cantidad.val("");
}

function validar() {    
    DOM.precio.keypress(function (e) {
        var valor = DOM.precio.val();
        return Util.soloDecimal(e,valor,2);
    });

    DOM.cantidad.keypress(function (e) {
        return Util.soloNumeros(e);
    });
}

var arreglo = new Array(); // ACA VA ALMACENAR LAS MATERIAS PRIMAS


function setEventos() {
    validar();

    DOM.btnAdicionar.on("click",function(){
        if ( DOM.codigo_materia_prima.val() === '' ) {
            Util.alerta('warning','Debe seleccionar una materia prima',2000);
            return 0;
        }

        if ( DOM.cantidad.val() === '' ) {
            Util.alerta('warning','Debe ingresar una cantidad para la materia prima',2000);
            return 0;
        }

        if (! validarMismo(DOM.codigo_materia_prima.val())) {
            Util.alerta('warning','No es posible agregar la misma materia prima mas de 1 veces',2000);            
            return 0; //detiene el programa
        }
        //Elaborar una variable con el HTML para agregar al detalle
        var fila = '<tr data-id="'+DOM.codigo_materia_prima.val()+'">' +
                        '<td class="text-center"><i style="font-size:20px;" class="fa fa-close text-danger"></i></td>' +
                        '<td class="text-center">' + DOM.codigo_materia_prima.select2('data').text + '</td>' +
                        '<td class="text-center">' + DOM.cantidad.val() + '</td>' +                        
                    '</tr>';

        //AGREGAR TABLA Y ARREGLO
        $("#detalle_materia_primas").append(fila);
        l2();
    });

    DOM.btnAumentar.on("click",function(){
        if ( DOM.codigo_materia_prima.val() === '' ) {
            Util.alerta('warning','Debe seleccionar una materia prima',2000);
            return 0;
        }

        if ( DOM.cantidad.val() === '' ) {
            Util.alerta('warning','Debe ingresar una cantidad para la materia prima',2000);
            return 0;
        }

        if (! validarMismo(DOM.codigo_materia_prima.val())) {
            Util.alerta('warning','No es posible agregar la misma materia prima mas de 1 veces',2000);            
            return 0; //detiene el programa
        }
        //Elaborar una variable con el HTML para agregar al detalle
        var fila = '<tr data-id="'+DOM.codigo_materia_prima.val()+'">' +
                        '<td class="text-center"><i style="font-size:20px;" class="fa fa-close text-danger"></i></td>' +
                        '<td class="text-center">' + DOM.codigo_materia_prima.select2('data').text + '</td>' +
                        '<td class="text-center">' + DOM.cantidad.val() + '</td>' +                        
                    '</tr>';

        //AGREGAR TABLA Y ARREGLO
        $("#detalle_materia_primas").append(fila);

        var funcion = function (resultado) {
            console.log(resultado);
            if (resultado.estado === 200) {
                if (resultado.datos.rpt === true) {
                    Util.alertaA(resultado.datos);
                }else{
                    Util.alertaB(resultado.datos);
                }
            }
        };
        new Ajex.Api({
            modelo: "Producto",
            metodo: "agregarMP",
            data_in: {
                p_cod_producto : DOM.codigo_producto.val(),
                p_cod_materia_prima : DOM.codigo_materia_prima.val(),
                p_cantidad :  DOM.cantidad.val()
            }
        },funcion);
        l2();
    });



    DOM.btnAgregar.on("click", function () {
        DOM.self.find(".modal-title").text("Agregar nuevo producto");
        DOM.operacion.val("agregar");
        DOM.imagen.hide();
        $("#detalle_materia_primas tr").remove();
        $("#b1").show();
        $("#b2").hide();
        limpiar();
    });

    DOM.foto.on("change", function(){
        DOM.imagen.hide();
    }); 

    DOM.form.submit(function (evento) {
        evento.preventDefault();

        if ( parseFloat(DOM.precio.val()) >= 0.000 && parseFloat(DOM.precio.val()) <= 0.009) {
            Util.alerta('warning','El precio debe ser mayor de cero',2000);
            return 0;
        };

        arreglo.splice(0,arreglo.length);

         $("#detalle_materia_primas tr").each(function(){
            var codigo_materia_prima = this.dataset.id;
            var cantidad = $(this).find("td").eq(2).html();

            var obj = {
                p_codigo_materia_prima : parseInt(codigo_materia_prima),
                p_cantidad : parseInt(cantidad)
            };

            arreglo.push(obj); //agregar el objeto objDetalle al array arrayDetalle
        });

        var json = JSON.stringify(arreglo);
        
        
        var n = new Noty({ 
          layout: 'bottomCenter',        
          text: '¿Esta seguro de grabar los datos ingresados?',
          buttons: [            
            Noty.button('Si', 'btn btn-success', function () {
                var datos_frm = new FormData();
                datos_frm.append("p_array_datos", DOM.form.serialize());
                datos_frm.append("p_foto", DOM.foto.prop('files')[0]);
                datos_frm.append("p_arreglo",json);

                var funcion = function (resultado) {
                    if (resultado.estado === 200) {
                        if (resultado.datos.rpt === true) {                    
                            Util.alertaA(resultado.datos);
                            listar();
                            limpiar();                            
                            DOM.self.modal("hide");                    
                        }else{
                            Util.alertaB(resultado.datos);
                            DOM.self.modal("hide");          
                        }                        
                    } 
                };         
                $.ajax({
                    url: "../../controlador/guardar.producto.php",
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

    $("#detalle_materia_primas").on("click","tr td i",function(){
        $tr = this.parentElement.parentElement;        
        var n = new Noty({ 
          layout: 'bottomCenter',        
          text: '¿Desea eliminar el registro seleccionado?',
          buttons: [            
            Noty.button('Si', 'btn btn-success', function () {
                $tr.remove();
                var funcion = function (resultado) {
                    if (resultado.estado === 200) {
                        if (resultado.datos.rpt === true) {
                            Util.alertaA(resultado.datos);
                        }else{
                            Util.alertaB(resultado.datos);
                        }
                    }
                };
                new Ajex.Api({
                    modelo: "Producto",
                    metodo: "eliminarMP",
                    data_in: {
                        p_cod_producto : DOM.codigo_producto.val(),
                        p_cod_materia_prima : $tr.dataset.id
                    }
                },funcion);                             
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
                html += '<th style="text-align: center">Foto</th>';
                html += '<th style="text-align: center">Producto</th>';  
                html += '<th style="text-align: center">Precio</th>';                          
                html += '<th style="text-align: center">OPCIONES</th>'; 
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';                
                $.each(resultado.datos.msj, function (i, item) {                 
                    html += '<tr>';
                    html += '<td align="center"><img src="../../imagenes/productos/' + item.img + '" width=40 height=40></td>';                
                    html += '<td align="center">' + item.nombre + '</td>';               
                    html += '<td align="center">' + item.precio_fijo + '</td>'; 
                    html += '<td align="center">';
                    html += '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal" onclick="editar(' + item.cod_producto + ')" title="editar"><i class="fa fa-pencil"></i></button>';
                    html += '&nbsp;&nbsp;';
                    html += '<button type="button" class="btn btn-xs btn-danger" onclick="darBaja(' + item.cod_producto + ',' + "'false'" + ')" title="Eliminar"><i class="fa fa-times"></i></button>';                
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
        modelo: "Producto",
        metodo: "listar"
    }, funcion);
}

function editar(p_codigo) {
    DOM.self.find(".modal-title").text("Editar producto");
    DOM.operacion.val("editar");
    DOM.imagen.show();
    $("#b1").hide();
    $("#b2").show();
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                var resultado = resultado.datos.msj;
                var html = "";
                //PRODUCTO
                DOM.codigo_producto.val(resultado.producto.cod_producto);
                DOM.nombre.val(resultado.producto.nombre);
                DOM.descripcion.val(resultado.producto.descripcion);
                DOM.precio.val(resultado.producto.precio_fijo);
                $("#nombre_foto").html('<img src="../../imagenes/productos/'+resultado.producto.img+'" width=100 height=100>');                    
                //MATERIA PRIMA
                $.each(resultado.materia_prima, function (i, item) {
                    html += '<tr data-id="'+ item.cod_materia_prima +'">';
                    html += '<td class="text-center"><i style="font-size:20px;" class="fa fa-close text-danger"></i></td>';
                    html += '<td class="text-center">' + item.nombre + '</td>';
                    html += '<td class="text-center">' + item.cantidad + '</td>';
                    html += '</tr>';
                });
                $("#detalle_materia_primas").html(html);
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
        modelo: "Producto",
        metodo: "habilitar",
        data_in: {
            p_cod_producto: p_codigo,
            p_estado_mrcb : p_estado
        } 
    }; 
    Util.notificacion(entradas,funcion, texto); 
}


function cargarMateriaPrima(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if (resultado.datos.rpt === true) {
                var html = "<option></option>";
                for (var i = 0; i < datos.length; i++) {
                    html += "<option value=" + datos[i].cod_materia_prima + ">" + datos[i].nombre + "</option>";
                }
                DOM.codigo_materia_prima.html(html).select2({
                      placeholder: "Seleccionar materia prima",
                      allowClear: true
                });
            }else{
                Util.alertaB(datos);
            }
        }
    };

    new Ajex.Api({
        modelo : "MateriaPrima",
        metodo : "cbListar"
    }, funcion);
}

function validarMismo(p_codigo_materia_prima){
    var c = 0;    
    $("#detalle_materia_primas tr").each(function(){
        var codigo = this.dataset.id;

        if (codigo === p_codigo_materia_prima){
            c++;
        }
    });    
    if (c >= 1){
        return false;
    }    
    return true; 
}