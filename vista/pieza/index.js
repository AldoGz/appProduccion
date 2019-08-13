var DOM = {};

$(document).ready(function () {
    setDOM();
    setEventos();
    listar();
    cargarProducto(); 
    cargarMateriaPrima();   
});

function setDOM() {
    DOM.form = $("#frm-grabar"),
    DOM.listado = $("#listado"),
    DOM.self = $("#myModal"),
    DOM.self2 = $("#myModal2"),
    DOM.btnAgregar = $("#btnAgregar"),
    DOM.operacion = $("#txtoperacion"),
    DOM.operacion_mp = $("#txtoperacion_mp"),
    
    //ENTRADAS1
    DOM.codigo_pieza = $("#txtcodigo_pieza"),    
    DOM.nombre_pieza = $("#txtnombre"),
    DOM.codigo_producto = $("#txtcodigo_producto"),
    DOM.cantidad = $("#txtcantidad");

    //ENTRADAS2
    DOM.tipo = $("#txttipo");
    DOM.codigo_materia_prima = $("#txtcodigo_materia_prima"),
    DOM.cantidad_materia_prima = $("#txtcantidad_materia_prima"),

    //NEW BUTONES
    DOM.btnAgregar = $("#btnAgregar"),
    DOM.btnMasillado = $("#btnMasillado"),
    DOM.btnPulido = $("#btnPulido"),
    DOM.btnPintado = $("#btnPintado"),
    DOM.btnCarrito = [];

    var j = 0;
    for ( var i = 0; i < 3; i++) {
        j = i + 1;
        DOM.btnCarrito.push($("#btnC"+j));
    };

    DOM.btnAdicionar = [];

    var j = 0;
    for ( var i = 0; i < 3; i++) {
        j = i + 1;
        DOM.btnAdicionar.push($("#btnA"+j));
    };
}

function l1(a1,a2,a3) {
    DOM.codigo_pieza.val("");    
    DOM.nombre_pieza.val("");
    DOM.codigo_producto.val("").select2({
          placeholder: "Seleccionar producto",
          allowClear: true
    });
    DOM.cantidad.val("");
    a1.splice(0, a1.length);
    a2.splice(0, a2.length);
    a3.splice(0, a3.length);
    $("#detalle_materia_primas tr").remove();
}

function l2() {
    DOM.codigo_materia_prima.val("").select2({
          placeholder: "Seleccionar materia prima",
          allowClear: true
    });
    DOM.cantidad_materia_prima.val("");    
}

function validar() {
    DOM.cantidad_materia_prima.keypress(function (e) {
        return Util.soloDecimal(e, DOM.cantidad_materia_prima.val(), 2);
    });
    DOM.cantidad.keypress(function (e) {
        return Util.soloDecimal(e, DOM.cantidad.val(), 2);
    });
}

var masillado = new Array();  //almacenar arreglo de masillado
var pulido = new Array();  //almacenar arreglo de pulido
var pintado = new Array();  //almacenar arreglo de pintado

function setEventos() {
    validar();

    DOM.btnAgregar.on("click", function(){
        DOM.self.find(".modal-title").text("Agregar nueva pieza");
        DOM.operacion.val("agregar");
        l1(masillado,pulido,pintado);
    });

    DOM.btnMasillado.on("click", function(){
        DOM.self2.find(".modal-title").text("Materia prima para masillado");  
        mostrar(1,masillado); 
    });

    DOM.btnPulido.on("click", function(){
        DOM.self2.find(".modal-title").text("Materia prima para pulido");
        mostrar(2,pulido);
    });

    DOM.btnPintado.on("click", function(){
        DOM.self2.find(".modal-title").text("Materia prima para pintado");
        mostrar(3,pintado);
    });

    DOM.btnCarrito[0].on("click", function(){
        agregarFila(masillado);
    });

    DOM.btnCarrito[1].on("click", function(){
        agregarFila(pulido);
    });

    DOM.btnCarrito[2].on("click", function(){
        agregarFila(pintado);
    });

    DOM.btnAdicionar[0].on("click", function(){
        adicionarFila();
    });
    DOM.btnAdicionar[1].on("click", function(){
        adicionarFila();
    });
    DOM.btnAdicionar[2].on("click", function(){
        adicionarFila();
    });

    DOM.form.submit(function (evento) {
        evento.preventDefault();
        if ( DOM.nombre_pieza.val() === '' ) {
            Util.alerta('warning','Debe ingresar el nombre para la pieza',2000);
            return 0;
        }

        if ( DOM.codigo_producto.val() === '' ) {
            Util.alerta('warning','Debe seleccionar un producto para la pieza',2000);
            return 0;
        }

        if ( DOM.cantidad.val() === '' ) {
            Util.alerta('warning','Debe ingresar la cantidad de chatarra para la pieza',2000);
            return 0;
        }
        
        var data_in = {
            p_cod_pieza : DOM.codigo_pieza.val(),
            p_cod_producto : DOM.codigo_producto.val(),
            p_nombre : DOM.nombre_pieza.val().toUpperCase(),
            p_cantidad : DOM.cantidad.val()
        };

        var j1 = JSON.stringify(masillado);
        var j2 = JSON.stringify(pulido);
        var j3 = JSON.stringify(pintado);

        var funcion = function (resultado) {
            if (resultado.estado === 200) {
                if (resultado.datos.rpt === true) {                    
                    Util.alertaA(resultado.datos);
                    listar();
                    l1(masillado,pulido,pintado);                         
                    DOM.self.modal("hide");                    
                }else{
                    Util.alertaB(resultado.datos);
                    DOM.self.modal("hide");          
                }                        
            } 
        }; 

        var e1 = {
            modelo: "Pieza",
            metodo: "agregar",                            
            data_in: data_in,
            data_out : [j1,j2,j3] 
        }
        var e2 = {
            modelo: "Pieza",
            metodo: "editar",                            
            data_in: data_in
        }

        var entradas = DOM.operacion.val() === 'agregar' ? e1 : e2;

        Util.notificacion(entradas,funcion);
    });

    $("#detalle_materia_primas").on("click", "tr td i", function(e){     
        $tr = this.parentElement.parentElement;        
        var n = new Noty({ 
          layout: 'bottomCenter',        
          text: '¿Desea eliminar el registro seleccionado?',
          buttons: [            
            Noty.button('Si', 'btn btn-success', function () {
                $tr.remove();
                eliminar(DOM.codigo_pieza.val(),$tr.dataset.id,DOM.tipo.val());                
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
                html += '<th style="text-align: center">Pieza</th>';
                html += '<th style="text-align: center">Producto</th>';            
                html += '<th style="text-align: center">OPCIONES</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';                
                $.each(resultado.datos.msj, function (i, item) {                 
                    html += '<tr>';
                    html += '<td align="center">' + (i+1) + '</td>';                
                    html += '<td align="center">' + item.pieza + '</td>';
                    html += '<td align="center">' + item.producto + '</td>';  
                    html += '<td align="center">';
                    html += '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal" onclick="editar(' + item.cod_pieza + ')" title="Editar pieza"><i class="fa fa-pencil"></i></button>';
                    html += '&nbsp;&nbsp;';
                    html += '<button type="button" class="btn btn-xs btn-danger" onclick="darBaja(' + item.cod_pieza + ',' + "'false'" + ')" title="Eliminar pieza"><i class="fa fa-times"></i></button>';                
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
        modelo: "Pieza",
        metodo: "listar"
    }, funcion);
}

function editar(p_codigo) {
    DOM.self.find(".modal-title").text("Editar pieza");
    DOM.operacion.val("editar");
   

    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                var resultado = resultado.datos.msj;
                //LLENAR PIEZA
                DOM.codigo_pieza.val(resultado.pieza.cod_pieza);
                DOM.nombre_pieza.val(resultado.pieza.nombre);
                DOM.codigo_producto.val(resultado.pieza.cod_producto).select2();
                //LLENAR CANTIDAD CHATARRA
                DOM.cantidad.val(resultado.chatarra);
            }else{
                Util.alertaB(resultado.datos);
            }
        }
    };
    new Ajex.Api({
        modelo: "Pieza",
        metodo: "leerDatos",
        data_in: {
            p_cod_pieza : p_codigo
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
        modelo: "Pieza",
        metodo: "habilitar",
        data_in: {
            p_cod_pieza: p_codigo,
            p_estado_mrcb: p_estado
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

function cargarProducto(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if (resultado.datos.rpt === true) {
                var html = "<option></option>";
                for (var i = 0; i < datos.length; i++) {
                    html += "<option value=" + datos[i].cod_producto + ">" + datos[i].nombre + "</option>";
                }
                DOM.codigo_producto.html(html).select2({
                      placeholder: "Seleccionar producto",
                      allowClear: true
                });
            }else{
                Util.alertaB(datos);
            }
        }
    };
    new Ajex.Api({
        modelo : "Producto",
        metodo : "cbListar"
    }, funcion);
}

function llenarArreglo(arreglo){
    if ( DOM.operacion.val() === 'editar' ) {
        var funcion = function (resultado) {
            if (resultado.estado === 200) {
                if (resultado.datos.rpt === true) {
                    var resultado = resultado.datos.msj;
                    switch(DOM.tipo.val()){
                        case '1':
                            masillado.splice(0,masillado.length);
                            llenarArregloEditar(masillado,resultado.masillado);                            
                            agregarTabla(masillado);
                            break;
                        case '2':
                            pulido.splice(0,pulido.length);                           
                            llenarArregloEditar(pulido,resultado.pulido);
                            agregarTabla(pulido);
                            break;
                        default:
                            pintado.splice(0,pintado.length);
                            llenarArregloEditar(pintado,resultado.pintado);
                            agregarTabla(pintado);
                            break;
                    }
                }else{
                    Util.alertaB(resultado.datos);
                }
            }
        };
        new Ajex.Api({
            modelo: "Pieza",
            metodo: "leerDatos",
            data_in: {
                p_cod_pieza : DOM.codigo_pieza.val()
            }
        },funcion);
    }
    agregarTabla(arreglo);
}

function agregarTabla(arreglo){  
    var fila = "";
    $.each(arreglo, function (i, item) {                 
        fila += '<tr data-id="'+item.p_codigo_materia_prima+'">';
        fila +=     '<td class="text-center"><i style="font-size:20px;" class="fa fa-close text-danger"></i></td>';
        fila +=     '<td class="text-center">' + item.p_materia_prima + '</td>';
        fila +=     '<td class="text-center">' + item.p_cantidad + '</td>';                
        fila += '</tr>';        
    });
    
    $("#detalle_materia_primas").html(fila);
}

function agregarFila(arreglo){
    if ( DOM.codigo_materia_prima.val() === '' ) {
        Util.alerta('warning','Debe seleccionar un materia prima',2000);
        return 0;
    }
    if ( DOM.cantidad_materia_prima.val() === '') {
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
                    '<td class="text-center">' + DOM.cantidad_materia_prima.val() + '</td>' +                        
                '</tr>';

    //AGREGAR TABLA Y ARREGLO
    $("#detalle_materia_primas").append(fila);
    var obj = {
        p_codigo_materia_prima : parseInt(DOM.codigo_materia_prima.val()),
        p_materia_prima : DOM.codigo_materia_prima.select2('data').text,
        p_cantidad : parseInt(DOM.cantidad_materia_prima.val())
    }

    arreglo.push(obj);    
    l2();
}

function adicionarFila(){
    if ( DOM.codigo_materia_prima.val() === '' ) {
        Util.alerta('warning','Debe seleccionar un materia prima',2000);
        return 0;
    }
    if ( DOM.cantidad_materia_prima.val() === '') {
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
                    '<td class="text-center">' + DOM.cantidad_materia_prima.val() + '</td>' +                        
                '</tr>';

    //AGREGAR TABLA Y ARREGLO
    $("#detalle_materia_primas").append(fila);

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
        modelo: "Pieza",
        metodo: "agregarMP",
        data_in: {
            p_cod_pieza : DOM.codigo_pieza.val(),
            p_cod_materia_prima : DOM.codigo_materia_prima.val(),
            p_cantidad :  DOM.cantidad_materia_prima.val(),
            p_tipo : DOM.tipo.val()
        }
    },funcion);
    l2();
}

function mostrar(opcion,arreglo){
    if ( DOM.operacion.val() === 'agregar') {
        switch(opcion){
            case 1:        
                $("#btnC1").show();
                $("#btnC2").hide();
                $("#btnC3").hide();

                $("#btnA1").hide();
                $("#btnA2").hide();
                $("#btnA3").hide();
                break;
            case 2:
                $("#btnC1").hide();
                $("#btnC2").show();
                $("#btnC3").hide(); 

                $("#btnA1").hide();
                $("#btnA2").hide();
                $("#btnA3").hide();       
                break;
            default:
                $("#btnC1").hide();
                $("#btnC2").hide();
                $("#btnC3").show();

                $("#btnA1").hide();
                $("#btnA2").hide();
                $("#btnA3").hide();
                break;
        }
    }else{
        switch(opcion){
            case 1:        
                $("#btnC1").hide();
                $("#btnC2").hide();
                $("#btnC3").hide();

                $("#btnA1").show();
                $("#btnA2").hide();
                $("#btnA3").hide();
                break;
            case 2:
                $("#btnC1").hide();
                $("#btnC2").hide();
                $("#btnC3").hide(); 

                $("#btnA1").hide();
                $("#btnA2").show();
                $("#btnA3").hide();       
                break;
            default:
                $("#btnC1").hide();
                $("#btnC2").hide();
                $("#btnC3").hide();

                $("#btnA1").hide();
                $("#btnA2").hide();
                $("#btnA3").show();
                break;
        }
    }
    
    $("#detalle_materia_primas tr").remove();
    l2(); 
    llenarArreglo(arreglo);
    DOM.tipo.val(opcion);
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

function llenarArregloEditar(p_arreglo,p_resultado){
    p_arreglo.splice(0, p_arreglo.length);
    $.each(p_resultado, function (i, item) {                    
        var obj = {
            p_codigo_materia_prima : item.cod_materia_prima,
            p_materia_prima : item.nombre,
            p_cantidad : item.cantidad
        }
        p_arreglo.push(obj);    
    });
}

function eliminar(p1,p2,tipo){
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
        modelo: "Pieza",
        metodo: "eliminarMP",
        data_in: {
            p_cod_pieza : p1,
            p_cod_materia_prima : p2,
            p_tipo : tipo
        }
    },funcion);
}