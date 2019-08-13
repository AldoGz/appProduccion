var DOM = {},
tmpSeleccionado = {};

$(document).ready(function () {
    setDOM();
    setEventos();
    leerDatos();
    //contadorActividad();
    listarPlanProduccion();
    listarRequisitos();
    listarProductos();
    listarPiezas();
    validarEstado();
    //listarAcondicionamiento();
    //listarFundicion();
    cargarPieza();
    cargarFalla();  
    //nombreActividad();
    /*montoTotal();
    montoTotalMP();
    
    contadorActividad();
    //
    llenarTipoFalla();*/
    montoPieza();

});

function setDOM() {
    DOM.form = $("#frm-grabar-requisitos"),
    DOM.listado = $("#listado"),
    DOM.self = $("#myModal"),
    DOM.btnAgregar = $("#btnAgregar"),

    DOM.nombre_plan_produccion = $("#txtnombre_plan_produccion"),
    DOM.fecha_creacion = $("#txtfecha_creacion"),
    DOM.fecha_finalizacion = $("#txtfecha_finalizacion"),
    DOM.estado_proceso = $("#txtestado_proceso"),
    DOM.btnAgregarPedido = $("#btnAgregarPedido"),
    DOM.btnAgregarInterior = $("#btnInterior"),
    DOM.modal2 = $("#myModal2"),
    DOM.modal3 = $("#myModal3"),
    DOM.modal4 = $("#myModal4"),
    DOM.modal5 = $("#myModal5"),

    DOM.listar_pedido_aceptado = $("#listar-pedidos-aceptado"),

    DOM.btnIniciar = $("#btnIniciar"),
    DOM.motivo = $("#txtmotivo"),
    DOM.importeNetoPieza = $("#txtimportenetopieza"),
    DOM.codigo_pieza = $("#txtcodigo_pieza"),
    DOM.pieza = $("#txtpieza"),
    DOM.precio = $("#txtprecio"),
    DOM.cantidad = $("#txtcantidad"),
    DOM.total = $("#txtimporteneto"),
    DOM.detalle_pedido_pieza = $("#detalle-pedido-pieza"),
    DOM.detalle_pedidos_produccion = $("#detalle-pedidos-produccion"),
    DOM.carrito_pieza = $("#btnCarritoPieza"),
    DOM.carrito_falla = $("#btnCarritoFalla"),
    DOM.btnGrabarFalla = $("#btnGrabarFalla"),

    DOM.acondicionamiento_detalle_mp = $("#acondicionamiento-detalle-mp"),
    DOM.acondicionamiento_fundicion_detalle_mp = $("#acondicionamiento-fundicion-detalle-mp"),
    DOM.fundicion_detalle_mp = $("#fundicion-detalle-mp"),
    DOM.filtro = $("#txtbuscar"),
    DOM.btnAcondicionamiento = $("#btnAcondicionamiento"),
    DOM.costo_extra = $("#txtcosto_extra"),
    DOM.costo_materia_prima = $("#txtcosto_materia_prima"),
    DOM.costo_materia_prima_ahorro = $("#txtcosto_materia_prima_ahorro"),
    DOM.motivo_costo = $("#txtmotivo_costo"),
    DOM.fecha_acondicionamiento = $("#txtfecha_acondicionamiento"),

    DOM.btnFundicion = $("#btnFundicion"),
    DOM.minutos_fundicion = $("#txtminutos_fundicion"),
    DOM.costo_fundicion = $("#txtcosto_fundicion"),
    DOM.comentario_fundicion  = $("#txtcomentario_fundicion"),
    DOM.fecha_fundicion =$("#txtfecha_fundicion"),
    DOM.codigo_tipo_falla = $("#txtcodigo_tipo_falla"),
    DOM.cantidad_falla = $("#txtcantidad_falla")

    DOM.fecha_inicio_actividad1 = $("#txtFechaInicioA1"),
    DOM.fecha_fin_actividad1 = $("#txtFechaFinA1"),
    DOM.motivo_actividad1 = $("#txtMotivoA1"),
    DOM.colaborador_actividad1 = $("#txtColaboradorA1");

    DOM.fecha_inicio_actividad2 = $("#txtFechaInicioA2"),
    DOM.fecha_fin_actividad2 = $("#txtFechaFinA2"),
    DOM.motivo_actividad2 = $("#txtMotivoA2"),
    DOM.colaborador_actividad2 = $("#txtColaboradorA2"),

    DOM.verProductosEnsamblado = $("#ver_productos_finales");
    DOM.verPiezasEnsamblado = $("#ver_piezas_finales");
    DOM.btnFinalizar = $("#btnFinalizar");

    DOM.tblVerPiezasAlmacen = $("#ver_piezas_almacen");
    DOM.tblVerProductosAlmacen = $("#ver_productos_almacen");
    DOM.tblVerMPAlmacen = $("#ver_materia_prima_almacen");

    DOM.actividad = [];
    DOM.actividad_subtitulo = [];
    DOM.blkSubFases = []; 
    DOM.motivo_actividad = [];
    DOM.colaborador_actividad = [];
    DOM.fecha_inicio_actividad = [];
    DOM.fecha_fin_actividad = [];
    DOM.mfecha_inicio_actividad = [];
    DOM.mfecha_fin_actividad = [];
    DOM.btnActividad = [];
    DOM.actividad_detalle = [];

    var j = 0;
    for ( var i = 0; i < 9; i++) {
        j = i + 1;
        DOM.actividad.push(new Array());
        DOM.actividad_subtitulo.push($("#actividad-"+j));
        DOM.btnActividad.push($("#btnActividad0"+j));
        DOM.blkSubFases.push($("#blk-sub-fase-"+j));  
        DOM.motivo_actividad.push($("#txtMotivoA"+j));      
        DOM.mfecha_inicio_actividad.push($("#txtMFechaInicioA"+j));
        DOM.mfecha_fin_actividad.push($("#txtMFechaFinA"+j));
        DOM.fecha_inicio_actividad.push($("#txtFechaInicioA"+j));
        DOM.fecha_fin_actividad.push($("#txtFechaFinA"+j));
        DOM.colaborador_actividad.push($("#txtColaboradorA"+j));    
        DOM.actividad_detalle.push ($("#actividad"+j+"-detalle"));  
    };

    DOM.oculto = $("#txtestado_actual");

}

var parametrosArray = retornarParametro();
var p_nombre_plan_produccion =  parametrosArray[0];
var piezas = new Array(); //almacenar las piezas que se desea para el pedido
var actividadAcondicionamiento = new Array(); //almacenar las piezas que se desea para el para actividad inicial
var fallas = new Array();//almacenar las fallas que se desea para el para actividad

var fin_productos = new Array(); //almacenar las piezas que se desea para el para actividad inicial
var fin_piezas = new Array(); //almacenar las piezas que se desea para el para actividad inicial

function validar(){
    $("#txtcosto_extra").keypress(function (e) {
        var valor = $("#txtcosto_extra").val();
        return Util.soloDecimal(e,valor,2);
    });
    $("#txtminutos_fundicion").keypress(function (e) {
        var valor = $("#txtminutos_fundicion").val();
        return Util.soloDecimal(e,valor,1);
    });
    $("#txtcosto_fundicion").keypress(function (e) {
        var valor = $("#txtcosto_fundicion").val();
        return Util.soloDecimal(e,valor,2);
    }); 

    $.each(DOM.colaborador_actividad, function(i,o){
        o.keypress(function(e){
            return Util.soloNumeros(e);
        });
    });
  
    $("#txtcantidad_fallas").keypress(function(e){
        return Util.soloNumeros(e);
    });
    
}

function setEventos() {
    validar();

    DOM.btnAgregarPedido.on("click", function(){
        DOM.self.find(".modal-title").text("Agregar pedidos al plan de producción");
        listarPedidos();         
    });

    DOM.btnAgregarInterior.on("click", function(){
        DOM.modal3.find(".modal-title").text("Agregar requisitos internos al plan de producción");
        limpiarRI();
    });

    DOM.carrito_pieza.on("click", function(){
        carrito();
    });

    DOM.codigo_pieza.on("change",function(){ // CAMBIAR EL COMBO DE PIEZA 
        if ( DOM.codigo_pieza.val() !== '' ) {
            var funcion = function (resultado) {
                if (resultado.estado === 200) {
                    if (resultado.datos.rpt === true) {
                        DOM.precio.val(resultado.datos.msj.precio);
                    }else{
                        Util.alertaB(datos);
                    }
                }
            };
            new Ajex.Api({
                modelo : "Pieza",
                metodo : "leerDatosPieza",
                data_in:{
                    p_cod_pieza : parseInt(DOM.codigo_pieza.val())
                }
            }, funcion);
        }
        DOM.precio.val("");        
    });

    DOM.detalle_pedido_pieza.on("click", "tr td i", function(e){
        var encontro =this.classList.contains('eliminar');

        $tr = this.parentElement.parentElement; // Obtener fila padre

        if ( encontro ) {
            var n = new Noty({ 
              layout: 'bottomCenter',        
              text: '¿Desea eliminar el registro seleccionado?',
              buttons: [            
                Noty.button('Si', 'btn btn-success', function () {
                    $tr.remove();
                    montoPieza();
                    n.close();                  
                }),
                Noty.button('No', 'btn btn-error', function () {
                    n.close();
                })
              ]
            }).show(); 
        }
    });

    DOM.form.submit(function (evento) {
        evento.preventDefault();
        /*Validar si se ha ingresado piezas*/
        if ( DOM.detalle_pedido_pieza.find('tr').length <= 0 ){            
            Util.alerta('warning','Debe tener al menos una pieza para registrar',2000);  
            return 0; //detiene el programa
        }
        /*Validar si se ha ingresado piezas*/

        /*limpiar el array*/
        piezas.splice(0, piezas.length);
        /*limpiar el array*/

        /*RECORREMOS CADA FILA DE LA TABLA PRODUCTO ORDEN*/
        DOM.detalle_pedido_pieza.find("tr").each(function(){
            var codigo_pieza = this.dataset.id, $td = $(this).find("td");
            var cantidad = $td.eq(3).html();
            var precio = $td.eq(2).html(); 
            var importe = $td.eq(4).html();

            var obj = {
                p_codigo_pieza : parseInt(codigo_pieza),
                p_cantidad : parseInt(cantidad),
                p_precio : parseFloat(precio),
                p_importe : parseFloat(importe)
            };

            piezas.push(obj); //agregar el objeto objDetalle al array arrayDetalle
        });
        /*RECORREMOS CADA FILA DE LA TABLA PRODUCTO ORDEN*/

        //Convertimos el array "detalle" a formato de JSON
        var json = JSON.stringify(piezas);
        //Convertimos el array "detalle" a formato de JSON  
              

        var fnAgregar = function (resultado) {
            if (resultado.estado === 200) {
                if (resultado.datos.rpt === true) {                    
                    Util.alertaA(resultado.datos);                     
                    listarRequisitos();
                    listarProductos();
                    listarPiezas();                                      
                }else{
                    Util.alertaB(resultado.datos);
                }      
               DOM.modal3.modal("hide");         
            } 
        };         

        var entradas = {
            modelo: "RequisitoInterno",
            metodo: "agregar",                            
            data_in: {                                                        
                p_motivo :DOM.motivo.val(),
                p_costo_total : parseFloat(DOM.importeNetoPieza.text())
            },
            data_out : [json, p_nombre_plan_produccion]                         
        };
        Util.notificacion(entradas,fnAgregar);
    });

    
    DOM.btnIniciar.on("click", function(){        
        if ( DOM.detalle_pedidos_produccion.find("tr").length <=0) {
            Util.alerta('warning','Debe tener al menos una pedido para registrar',2000);  
            return 0;
        }
        faseInicio();
    });

    DOM.btnAcondicionamiento.on("click", function(){
/*
        if ( parseFloat($("#txtcosto_extra").val()) >= 0.000 && parseFloat($("#txtcosto_extra").val()) <= 0.009) {
            Util.alerta('warning','El costo debe ser mayor de cero',2000);
            return 0;
        };

        if ( $("#txtcosto_extra").val() === '' ) {
            Util.alerta('warning','Debe ingresar costo de acodicionamiento',2000);
            return 0;
        }
        if ( DOM.fecha_acondicionamiento.val() === '' ) {
            Util.alerta('warning','Debe ingresar un fecha de acondicionamiento',2000);
            return 0;
        }
*/
        var fnAgregarAcondicionamiento = function (resultado) {
            if (resultado.estado === 200) {
                if (resultado.datos.rpt === true) {                    
                    Util.alertaA(resultado.datos);
                    leerDatos();
                    validarEstado();                                   
                }else{
                    Util.alertaB(resultado.datos);         
                }                        
            } 
        };         

        /*Obtener los detalles de cada materia_prima*/
        var arrayMP = [], objTmp = {}, $td, $fila;
         var fnLlenarArregloMP = function(i, fila){
                objTmp.cod_materia_prima = fila.dataset.codigo;
                $fila = $(fila);
                $.each($fila.find("td"), function(ix, td){
                    $td = $(td);
                    if ($td.hasClass("x-cant-necesaria")){
                        objTmp.cant_necesaria = $td.html();
                    }

                    if ($td.hasClass("x-cant-comprar")){
                        objTmp.cant_comprar = $td.html();
                    }

                    if ($td.hasClass("x-cant-usada")){
                        /*pos 0: usada, pos 1: sobrante;*/
                        objTmp.cant_usada_almacen = $td.html();
                        objTmp.cant_sobrante = $td.data('cant-sobrante');
                    }

                    if ($td.hasClass("x-precio")){
                        objTmp.costo_unitario_materia_prima = $td.html();
                    }
                });
                arrayMP.push(objTmp);
                objTmp = {};
            };

        $.each( DOM.acondicionamiento_detalle_mp.find("tr"),fnLlenarArregloMP);

        $.each( DOM.acondicionamiento_fundicion_detalle_mp.find("tr"),fnLlenarArregloMP);

        var entradas = {
            modelo: "PlanProduccion",
            metodo: "agregarAcondicionamiento",                            
            data_in: {                                                        
                p_nombre : p_nombre_plan_produccion,
                p_acondicionamiento_fecha : DOM.fecha_acondicionamiento.val(),
                p_acondicionamiento_costo_extra : DOM.costo_extra.val(),
                p_acondicionamiento_comentario : DOM.motivo_costo.val().toUpperCase(),
                p_acondicionamiento_costo_materia_prima : DOM.costo_materia_prima.text(),
                p_acondicionamiento_costo_materia_prima_ahorro : DOM.costo_materia_prima_ahorro.text()
            },
            data_out: [JSON.stringify(arrayMP)]
        };
        Util.notificacion(entradas,fnAgregarAcondicionamiento);
    });

    DOM.btnFundicion.on("click", function(){
        if ( parseFloat($("#txtminutos_fundicion").val()) >= 0.00 && parseFloat($("#txtminutos_fundicion").val()) <= 0.09) {
            Util.alerta('warning','El tiempo debe ser mayor de cero',2000);
            return 0;
        };

        if ( parseFloat($("#txtcosto_fundicion").val()) >= 0.000 && parseFloat($("#txtcosto_fundicion").val()) <= 0.009) {
            Util.alerta('warning','El costo debe ser mayor de cero',2000);
            return 0;
        };

        if ( $("#txtminutos_fundicion").val() === '' ) {
            Util.alerta('warning','Debe ingresar hora de fundición',2000);
            return 0;
        }

        if ( $("#txtcosto_fundicion").val() === '' ) {
            Util.alerta('warning','Debe ingresar costo de fundición',2000);
            return 0;
        }

        /*
        if ( $("#txtcomentario_fundicion").val() === '' ) {
            Util.alerta('warning','Debe ingresar observación de fundición',2000);
            return 0;
        }*/

        if ( $("#txtfecha_fundicion").val() === '' ) {
            Util.alerta('warning','Debe ingresar un fecha de fundicion',2000);
            return 0;
        }

        var fecha_acondicionamiento = $("#fa_formateada").val(),
            dateTimeParts = fecha_acondicionamiento.split(' '),
            turno = dateTimeParts[2],
            timeParts = dateTimeParts[1].split(':'),
            dateParts = dateTimeParts[0].split('/'),
            date1;

        date1 = new Date(dateParts[2], parseInt(dateParts[1], 10) - 1, dateParts[0],
                             (turno ==='p.m.' && timeParts[0] > 12) ? (parseInt(timeParts[0])+12).toString() : timeParts[0] , 
                             timeParts[1]);

        var fecha_fundicion = $("#txtfecha_fundicion").val(),
            dateTimeParts2 = fecha_fundicion.split('T'),
            timeParts2 = dateTimeParts2[1].split(':'),
            dateParts2 = dateTimeParts2[0].split('-'),
            date2;

        date2 = new Date(dateParts2[0], parseInt(dateParts2[1], 10) - 1, dateParts2[2], timeParts2[0], timeParts2[1]);

        if ( date2 <= date1) {
            Util.alerta('warning','Debe ser mayor a la fecha de acondicionamiento',2000);
            return 0;
        }; 


        /*limpiar el array*/
        actividadAcondicionamiento.splice(0, actividadAcondicionamiento.length);
        /*limpiar el array*/

        /*RECORREMOS CADA FILA DE LA TABLA*/
        $("#ver-detalle-piezas tr").each(function(){
            var codigo_pieza = this.dataset.id;
            var piezas_total = $(this).find("td").eq(2).html();
            var piezas_buenas = $(this).find("td").eq(2).html();

            var obj = {
                p_codigo_pieza : parseInt(codigo_pieza),
                p_piezas_total : parseInt(piezas_total),
                p_piezas_buenas : parseInt(piezas_buenas),
                p_piezas_falladas : 0
            };

            actividadAcondicionamiento.push(obj); //agregar el objeto objDetalle al array arrayDetalle
        });

        var arrayMP = [], objTmp = {}, $td, $fila;
        
        var fnLlenarArregloMP = function(i, fila){
                objTmp.cod_materia_prima = fila.dataset.codigo;
                $fila = $(fila);
                $.each($fila.find("td"), function(ix, td){
                    $td = $(td);
                    if ($td.hasClass("x-cant-sobrante")){
                        objTmp.cant_sobrante = $td.html();
                    }

                    if ($td.hasClass("x-cant-usada-almacen")){
                        objTmp.cant_usada_almacen = $td.html();
                    }

                    if ($td.hasClass("x-precio")){
                        objTmp.costo_unitario_materia_prima = $td.html();
                    }
                });
                arrayMP.push(objTmp);
                objTmp = {};
            };

        $.each( DOM.fundicion_detalle_mp.find("tr"),fnLlenarArregloMP);

        /*RECORREMOS CADA FILA DE LA TABLA*/
        //Convertimos el array "detalle" a formato de JSON
        var jsonDetallePiezas = JSON.stringify(actividadAcondicionamiento),
            jsonMateriaPrima = JSON.stringify(arrayMP);
        //Convertimos el array "detalle" a formato de JSON          

        var funcion = function (resultado) {
            if (resultado.estado === 200) {
                if (resultado.datos.rpt === true) {                    
                    Util.alertaA(resultado.datos);
                    leerDatos();
                    validarEstado();                                   
                }else{
                    Util.alertaB(resultado.datos);         
                }                        
            } 
        };         
        var entradas = {
            modelo: "PlanProduccion",
            metodo: "agregarFundicion",                            
            data_in: {                                                        
                p_nombre : p_nombre_plan_produccion,
                p_fundicion_horas : $("#txtminutos_fundicion").val(),
                p_fundicion_costo_extra : $("#txtcosto_fundicion").val(),
                p_fundicion_comentarios : $("#txtcomentario_fundicion").val().toUpperCase(),
                p_fundicion_fecha : $("#txtfecha_fundicion").val()
            },
            data_out :[jsonDetallePiezas,jsonMateriaPrima]                       
        };
        Util.notificacion(entradas,funcion);
    });

    
    DOM.carrito_falla.on("click", function(){
        carritoFalla();
    });

    DOM.btnGrabarFalla.on("click",function (evento) {
        //evento.preventDefault();
        if ( $("#txtoperacion").val() === 'carrito' ) {
            /*Validar si se ha ingresado tipo de falla*/
            if ( $("#detalle-falla-pieza").find('tr').length <= 0 ){            
                Util.alerta('warning','Debe tener al menos un tipo de falla para registrar',2000);  
                return 0; //detiene el programa
            }
            /*Validar si se ha ingresado tipo de falla*/

            /*limpiar el array*/
            fallas.splice(0, fallas.length);
            /*limpiar el array*/

            var falladas = 0;

            /*RECORREMOS CADA FILA DE LA TABLA PRODUCTO ORDEN*/
            $("#detalle-falla-pieza tr").each(function(){
                var codigo_falla = this.dataset.id;
                var motivo = $(this).find("td").eq(1).html();
                var cantidad = $(this).find("td").eq(3).html();

                falladas = falladas + parseInt(cantidad);

                var obj = {
                    /*p_codigo_plan_produccion_pieza : parseInt($("#txtcodigo_plan_produccion_pieza").val()),*/
                    p_codigo_falla : parseInt(codigo_falla),
                    p_motivo : motivo,
                    p_cantidad : parseInt(cantidad)
                };

                fallas.push(obj); //agregar el objeto objDetalle al array arrayDetalle
            });
            /*RECORREMOS CADA FILA DE LA TABLA PRODUCTO ORDEN*/

            //Convertimos el array "detalle" a formato de JSON
            var json = JSON.stringify(fallas);
            //Convertimos el array "detalle" a formato de JSON 

            var buenas = parseInt($("#txttotal_lote").val())-falladas;
                  

            var funcion = function (resultado) {
                if (resultado.estado === 200) {
                    if (resultado.datos.rpt === true) {                    
                        Util.alertaA(resultado.datos);                     
                        $("#myModalActividad").modal("hide"); 
                        limpiar3();                        
                        listarPiezasActividad($("#txtactividad").val());
                    }else{
                        Util.alertaB(resultado.datos);
                        $("#myModalActividad").modal("hide");         
                    }                        
                } 
            };         
            var entradas = {
                modelo: "PlanProduccionPieza",
                metodo: "agregarFalla",                            
                data_in: {                                                        
                    p_cod_plan_produccion_pieza : $("#txtcodigo_plan_produccion_pieza").val(),
                    p_piezas_buenas : buenas,
                    p_piezas_falladas : falladas
                },
                data_out : [json]                         
            };
            Util.notificacion(entradas,funcion);

        }
        
    });

    $("#detalle-falla-pieza").on("click", "tr td i", function(e){
        if ( $("#txtoperacion").val() === 'carrito') {
            var encontro =this.classList.contains('eliminar_falla');

            $tr = this.parentElement.parentElement; // Obtener fila padre

            if ( encontro ) {
                var n = new Noty({ 
                  layout: 'bottomCenter',        
                  text: '¿Desea quitar el registro seleccionado?',
                  buttons: [            
                    Noty.button('Si', 'btn btn-success', function () {
                        $tr.remove();
                        n.close();                  
                    }),
                    Noty.button('No', 'btn btn-error', function () {
                        n.close();
                    })
                  ]
                }).show(); 
            }
        }
        else{            
            var encontro =this.classList.contains('eliminar_falla');

            $tr = this.parentElement.parentElement; // Obtener fila padre
            $cantidad = $tr.children[3].innerHTML; // Obtener cantidad de la fila

            var funcion = function (resultado) {
                if (resultado.estado === 200) {
                    if (resultado.datos.rpt === true) {                    
                        Util.alertaA(resultado.datos); 
                        piezasBuenas($("#txtcodigo_plan_produccion_pieza").val());   
                        listarFalla($("#txtcodigo_plan_produccion_pieza").val());
                        listarPiezasActividad(1);                                                  
                    }else{
                        Util.alertaB(resultado.datos);  
                        $("#myModalActividad").modal("hide");      
                    }                        
                } 
            };         
            var entradas = {
                modelo: "PlanProduccionPieza",
                metodo: "retirarFalla",                            
                data_in: { 
                    p_cod_plan_produccion_pieza : $("#txtcodigo_plan_produccion_pieza").val()
                },
                data_out : [$tr.dataset.id,$cantidad]                            
            };
            Util.notificacion(entradas,funcion);

        }
    });
    
    $("#btnCerrarFalla").on("click",function(){
        limpiar2();        
    });

     function btnActividadEventos(i){

        console.log(i);
        
        if (  DOM.fecha_inicio_actividad[i].val() === '') {
            Util.alerta('warning','Debe ingresar un fecha inicio',2000);
            return 0;
        };

        if (  DOM.fecha_fin_actividad[i].val() === '') {
            Util.alerta('warning','Debe ingresar un fecha fin',2000);
            return 0;
        };

        if (  DOM.colaborador_actividad[i].val() === '') {
            Util.alerta('warning','Debe la cantidad de colaboradores',2000);
            return 0;
        };

        var getDateFromTimeParts = function(fecha, n){
            var booleanN, dateTimeParts, turno, timeParts,dateParts,
                param = new Array(5), retDate;

            booleanN = n == 1;
            if (booleanN){
                dateTimeParts = fecha.val().split(' ');
                turno = dateTimeParts[2],
                timeParts = dateTimeParts[1].split(":"),
                dateParts = dateTimeParts[0].split('/'),
                param[0] = dateParts[2],                
                param[2] = dateParts[0],
                param[3] = (turno ==='p.m.' && timeParts[0] > 12)  ? (parseInt(timeParts[0])+12).toString() : timeParts[0];

            } else {
                dateTimeParts = fecha.val().split('T'),
                timeParts = dateTimeParts[1].split(":"),
                dateParts = dateTimeParts[0].split('-'),
                param[0] = dateParts[0],
                param[2] = dateParts[2],
                param[3] = timeParts[0];

            }

            param[1] = parseInt(dateParts[1], 10) - 1;
            param[4] = timeParts[1];

            return new Date(param[0],param[1],param[2],param[3],param[4]);
        }

        date1 = getDateFromTimeParts(i < 1 ? $("#ff_formateada") : DOM.mfecha_fin_actividad[i-1], 1);
        date2 = getDateFromTimeParts(DOM.fecha_inicio_actividad[i], 2);

        if ( date2 <= date1) {
            Util.alerta('warning','La fecha inicio debe se ser mayor a la fecha de la actividad',2000);
            return 0;
        } 

        if (  DOM.fecha_fin_actividad[i].val() <= DOM.fecha_inicio_actividad[i].val() ) {
            Util.alerta('warning','La fecha fin debe se ser mayor a la fecha de inicio',2000);
            return 0;
        }

        var jsonActividad, 
            l_metodo = "agregarActividad",
            l_data_out =  [p_nombre_plan_produccion],
            l_data_in = {
                p_fecha_inicio : DOM.fecha_inicio_actividad[i].val(),
                p_fecha_fin : DOM.fecha_fin_actividad[i].val(),
                p_observaciones : DOM.motivo_actividad[i].val().toUpperCase(),
                p_cantidad_hombres : DOM.colaborador_actividad[i].val()
            };

        if ( i <= 6 ){
            DOM.actividad[i].splice(0, DOM.actividad[i].length);

            /*RECORREMOS CADA FILA DE LA TABLA*/
            DOM.actividad_detalle[i].find("tr").each(function(){
                var codigo_pieza = this.dataset.id;            
                var piezas_buenas = $(this).find("td").eq(2).html();
                var piezas_malas = 0;
                var piezas_total = $(this).find("td").eq(2).html(); 

                var obj = {
                    p_codigo_pieza : parseInt(codigo_pieza),
                    p_piezas_total : parseInt(piezas_total),
                    p_piezas_buenas : parseInt(piezas_buenas),
                    p_piezas_falladas : parseInt(piezas_malas)
                };

                DOM.actividad[i].push(obj);
            });

            jsonActividad = JSON.stringify(DOM.actividad[i]);
            l_data_out.push(jsonActividad);
        }
        else{
            if (i > 6){
                l_data_in.p_cod_actividad = (i+1);
                l_metodo = "agregarFActividad";
                if (i == 8){
                    l_data_out.push(DOM.fecha_fin_actividad[i].val());
                }
            }
        }

        var fn = function (resultado) {
            if (resultado.estado === 200) {
                if (resultado.datos.rpt === true) {                                             
                    Util.alertaA(resultado.datos); 
                    leerDatos();
                    validarEstado();
                }else{
                    Util.alertaB(resultado.datos);        
                }                        
            } 
        }; 

        var entradas = {
            modelo: "ActividadProduccion",
            metodo: l_metodo,                            
            data_in: l_data_in,
            data_out : l_data_out                         
        };

        Util.notificacion(entradas,fn);   

    };

    $.each(DOM.btnActividad, function(i,o){
        o.on("click", function(){
            btnActividadEventos(i);
        });
    });

    DOM.btnFinalizar.on("click",function(){
        /*limpiar el array*/
        fin_productos.splice(0, fin_productos.length);
        fin_piezas.splice(0, fin_piezas.length);
        /*limpiar el array*/

        $("#ver_productos_almacen tr").each(function(){
            var codigo_producto = this.dataset.id;
            var cantidad = $(this).find("td").eq(2).html();

            if ( cantidad > 0 ) {
                var obj = {
                    p_codigo_producto : parseInt(codigo_producto),
                    p_cantidad : parseInt(cantidad) 

                }
                fin_productos.push(obj);
            }
        });

        $("#ver_piezas_finales tr").each(function(){
            var codigo_pieza = this.dataset.id;
            var cantidad = $(this).find("td").eq(2).html();

            if ( cantidad > 0 ) {
                var obj = {
                    p_codigo_pieza : parseInt(codigo_pieza),
                    p_cantidad : parseInt(cantidad) 
                }
                fin_piezas.push(obj);
            }
        });


        //Convertimos el array "detalle" a formato de JSON
        var json_productos = JSON.stringify(fin_productos);
        var json_piezas = JSON.stringify(fin_piezas);
        //Convertimos el array "detalle" a formato de JSON  
     
        var funcion = function (resultado) {
            if (resultado.estado === 200) {
                if (resultado.datos.rpt === true) {                                             
                    Util.alertaA(resultado.datos); 
                    leerDatos();
                    validarEstado();
                }else{
                    Util.alertaB(resultado.datos);        
                }                        
            } 
        };
        var entradas = {
            modelo: "ActividadProduccion",
            metodo: "finalizarActividad",                            
            data_in: { 
                p_fecha_fin : Util.obtenerTimestamp()
            },
            data_out : [p_nombre_plan_produccion,json_productos,json_piezas]                           
        };
        Util.notificacion(entradas,funcion);
    });
}

function limpiar3(){
    $("#txtcodigo_tipo_falla").val("").select2({
        placeholder: "Seleccionar pieza",
        allowClear: true
    });
    $("#txtdescripcion").val("");
    $("#txtcantidad_fallas").val("");
    $("#detalle-falla-pieza").empty();
}

function moverFinalPagina(){
    $('body').animate({scrollTop: document.body.scrollHeight}, 'slow');
}

function leerDatos(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if ( resultado.datos.rpt === true) {
                $.each(resultado.datos.msj, function (i, item) {
                   DOM.nombre_plan_produccion.text(item.nombre);
                   DOM.fecha_creacion.text(item.fecha_hora_registro);
                   DOM.fecha_finalizacion.text(item.fecha_finalizacion);
                   DOM.estado_proceso.text(item.proceso);
                });
            }else{
                Util.alertaB(resultado.datos);
            }
        } 
    };

    new Ajex.Api({
        modelo: "PlanProduccion",
        metodo: "leerDatos",
        data_in: {
            p_nombre: p_nombre_plan_produccion
        }
    },funcion);
}


function listarPedidos(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if ( resultado.datos.rpt === true) {
                var html = "";
                if (resultado.datos.msj.length > 0) {
                    $.each(resultado.datos.msj, function (i, item) {
                        html += '<tr ondblclick="seleccionarPedido('+item.cod_pedido+',this)" title="Seleccionar pedido de '+item.cliente+'">';
                        html += '<td align="center">' + item.cod_pedido + '</td>';
                        html += '<td align="center">' + item.cliente + '</td>';              
                        html += '<td align="center">' + item.monto_total + '</td>';              
                        html += '</tr>';
                    });
                } else {
                    html += '<tr>';
                            html += '<td align="center" colspan="3"> No hay pedidos en espera.</td>';                            
                            html += '</td>';  
                            html += '</tr>';
                }   

                $("#listar-pedidos-aceptado").html(html);
            }else{
                Util.alertaB(resultado.datos);
            }            
        } 
    };

    new Ajex.Api({
        modelo: "Pedido",
        metodo: "listarPedidosAceptados"                    
    }, funcion);
}

function seleccionarPedido(p_codigo_pedido, item){
    var $item = $(item);
    $item.addClass("tr-seleccionado");
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {                    
                Util.alertaA(resultado.datos);
                listarPlanProduccion();                            
                DOM.self.modal("hide");                              
            }else{
                Util.alertaB(resultado.datos);
                DOM.self.modal("hide");          
            }                        
        } 
    };         
    var entradas = {
        modelo: "Pedido",
        metodo: "asignar",                            
        data_in: { 
            p_cod_pedido : p_codigo_pedido
        },
        data_out : [p_nombre_plan_produccion]                            
    };

    var funcionNo = function(){
        $item.removeClass("tr-seleccionado");
    };
    Util.notificacion(entradas,funcion,null,funcionNo);
}

function listarPlanProduccion(){    
    var funcion = function (resultado) {
        if (resultado.estado === 200) { 
            if ( resultado.datos.rpt === true ) {
                var html = "";  

                if (resultado.datos.msj.length > 0){
                       $.each(resultado.datos.msj, function (i, item) {
                            html += '<tr>';
                            html += '<td align="center">' + item.cod_pedido + '</td>';
                            html += '<td align="center">' + item.cliente + '</td>';              
                            html += '<td align="center">' + item.fecha_atencion + '</td>'; 
                            html += '<td align="center">' + item.monto_total + '</td>';              
                            html += '<td align="center">';
                            html += '<button title="Ver Detalle" class="btn btn-xs btn-default" data-toggle="modal" href="#myModal2" onclick="ver(' + item.nro + ',' + "'" + item.cod_pedido + "'" + ')"><i class="fa fa-eye"></i></button>';
                            if ( item.estado_fase === -1 ) {
                                html += '<button name="btnRetirar" title="Retirar" class="btn btn-xs btn-danger" onclick="retirar('+item.nro+')"><i class="fa fa-close"></i></button>';
                            }
                            html += '</td>';  
                            html += '</tr>';
                        });
                } else {
                    html += '<tr>';
                            html += '<td align="center" colspan="5"> No hay pedidos registrados.</td>';                            
                            html += '</td>';  
                            html += '</tr>';
                }

                DOM.detalle_pedidos_produccion.html(html);                    
                listarProductos();
                listarPiezas();   
               
            }else{
                Util.alertaB(resultado.datos);
            }            
        }
    };

    new Ajex.Api({
        modelo: "PlanProduccion",
        metodo: "listarPlanPedido",
        data_in : {
            p_nombre : p_nombre_plan_produccion
        }                    
    }, funcion);
}

function ver(p_codigo_pedido, p_nombre){
    $("#myModal2").find(".modal-title").text("Ver pedido "+p_nombre);
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                var html = "";
                if ( resultado.datos.msj.length > 0 ){ 
                    $.each(resultado.datos.msj, function (i, item) {
                        html += '<tr>';
                        html += '<td align="center">' + (i + 1) + '</td>';
                        html += '<td align="center">' + item.nombre + '</td>';
                        html += '<td align="center">' + item.cantidad + '</td>';
                        html += '<td align="center">' + item.precio + '</td>';
                        html += '<td align="center">' + item.importe + '</td>';                
                        html += '</tr>';
                    });
                } else {
                    html += '<tr>';
                            html += '<td align="center" colspan="5"> No hay datos para mostrar.</td>';                            
                            html += '</td>';  
                            html += '</tr>';
                }   
                $("#ver-detalle-pedido").html(html);
                montoPedido();
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

function montoPedido(){
    var neto = 0;    
    $("#ver-detalle-pedido tr").each(function(){
        var importe = $(this).find("td").eq(4).html();
        neto = neto  + parseFloat(importe);
    });
    $("#txtimporteneto").text(neto.toFixed(2));   
}

function retirar(p_codigo_pedido){
    var texto = "retirar";
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                listarPlanProduccion();                         
                Util.alertaA(resultado.datos); 

            }else{
                Util.alertaB(resultado.datos);        
            }                        
        } 
    };         
    var entradas = {
        modelo: "Pedido",
        metodo: "retirar",                            
        data_in: { 
            p_cod_pedido : p_codigo_pedido
        }                           
    };
    Util.notificacion(entradas,funcion,texto); 
}

function listarProductos(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if ( resultado.datos.rpt === true) {
                var html = "";

                if (resultado.datos.msj.length > 0){
                    $.each(resultado.datos.msj, function (i, item) {
                        html += '<tr>';
                        html += '<td align="center">' + item.cod_producto + '</td>';
                        html += '<td align="center">' + item.nombre + '</td>';              
                        html += '<td align="center">' + item.cantidad + '</td>'; 
                        html += '</tr>';
                    });
                } else {
                        html += '<tr>';
                        html += '<td align="center" colspan="3"> No hay datos para mostrar.</td>';                            
                        html += '</td>';  
                        html += '</tr>';
                }   
                $("#ver-plan-producto").html(html);
            }else{
                Util.alertaB(resultado.datos);
            }            
        } 
    };

    new Ajex.Api({
        modelo: "PlanProduccion",
        metodo: "listarProductos",
        data_in : {
            p_nombre : p_nombre_plan_produccion
        }                    
    }, funcion);
}

function listarPiezas(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if ( resultado.datos.rpt === true) {
                var html = "";
                if (resultado.datos.msj.length > 0){
                $.each(resultado.datos.msj, function (i, item) {
                    html += '<tr data-id="'+item.codigo+'">';
                    html += '<td align="center">' + item.cod_pieza + '</td>';             
                    html += '<td align="center">' + item.pieza + '</td>'; 
                    html += '<td align="center">' + item.cantidad + '</td>';                                            
                    html += '</tr>';
                });
                 } else {
                        html += '<tr>';
                        html += '<td align="center" colspan="3"> No hay datos para mostrar.</td>';                            
                        html += '</td>';  
                        html += '</tr>';
                }  
                $("#ver-plan-pieza").html(html);
            }else{
                Util.alertaB(resultado.datos);
            }            
        } 
    };

    new Ajex.Api({
        modelo: "PlanProduccion",
        metodo: "listarPiezas",
        data_in : {
            p_nombre : p_nombre_plan_produccion
        }                    
    }, funcion);
}

function cargarPieza(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if (resultado.datos.rpt === true) {
                var html = "<option></option>";
                for (var i = 0; i < datos.length; i++) {
                    html += "<option value=" + datos[i].cod_pieza + ">" + datos[i].nombre + "</option>";
                }
                DOM.codigo_pieza.html(html).select2({
                      placeholder: "Seleccionar pieza",
                      allowClear: true
                });
            }else{
                Util.alertaB(datos);
            }
        }
    };

    new Ajex.Api({
        modelo : "Pieza",
        metodo : "cbListar"
    }, funcion);
}

function carrito(){
    if ( DOM.codigo_pieza.val() === ''  ) {
        Util.alerta('warning','Debe seleccionar una pieza',2000);
        return 0;
    }
    if ( DOM.cantidad.val() === '') {
        Util.alerta('warning','Debe ingresar una cantidad para la pieza',2000);
        return 0;
    };

    if (! validarMismoPieza( DOM.codigo_pieza.val() )) {
        Util.alerta('warning','No es posible agregar el mismo pieza mas de 1 veces',2000);
        limpiar();
        return 0; //detiene el programa
    }   

    //Elaborar una variable con el HTML para agregar al detalle
    var fila = '<tr  data-id="'+DOM.codigo_pieza.val()+'">' +
            '<td class="text-center"><i style="font-size:20px;" class="fa fa-close text-danger eliminar"></i></td>' +            
            '<td class="text-center">'+ DOM.codigo_pieza.select2('data').text +'</td>' +
            '<td class="text-center" >' + parseFloat(DOM.precio.val()).toFixed(2) + '</td>' +
            '<td class="text-center" >' + DOM.cantidad.val() + '</td>' +
            '<td class="text-center" >' + parseFloat(DOM.precio.val()*DOM.cantidad.val()).toFixed(2) + '</td>' +            
            '</tr>';

    //Agregar el registro al detalle de la producto
    DOM.detalle_pedido_pieza.append(fila);

    /*LIMPIAR CAMPOS*/
    limpiar();
    montoPieza(); 
}

function validarMismoPieza(p_codig_pieza){
    var c = 0;    
    $("#detalle-pedido-pieza tr").each(function(){
        var codigo = this.dataset.id;

        if (codigo === p_codig_pieza){
            c++;
        }
    });    
    if (c >= 1){
        return false;
    }    
    return true; 
}

function montoPieza(){
    var neto = 0;    
    $("#detalle-pedido-pieza tr").each(function(){
        var importe = $(this).find("td").eq(4).html();
        neto = neto  + parseFloat(importe);
    });
    DOM.importeNetoPieza.text(neto.toFixed(2));   
}

function limpiar(){
    DOM.codigo_pieza.val("").select2({
        placeholder: "Seleccionar pieza",
        allowClear: true
    });
    DOM.precio.val("");
    DOM.cantidad.val("");
}

function listarRequisitos() {
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if ( resultado.datos.rpt === true) {
                var html = "";    

                if (resultado.datos.msj.length > 0){        
                    $.each(resultado.datos.msj, function (i, item) {
                        html += '<tr>';
                        html += '<td align="center">' + item.cod_requisito_interno + '</td>';
                        html += '<td align="center">' + item.motivo + '</td>';
                        html += '<td align="center">' + item.costo_total + '</td>';
                        html += '<td align="center">';          
                        if ( item.estado_fase === -1 ) {
                            html += '<button type="button" class="btn btn-xs btn-danger" onclick="darBajaRI(' + item.codigo + ',' + "'false'" + ')" title="Eliminar"><i class="fa fa-times"></i></button>';                
                            html += '&nbsp;&nbsp;';
                        }
                        html += '<button type="button" class="btn btn-xs btn-default" data-toggle="modal" href="#myModal5" onclick="verRI(' + item.codigo + ')" title="Ver detalle pedido"><i class="fa fa-eye"></i></button>';                
                        html += '&nbsp;&nbsp;';
                        html += '</td>';                
                        html += '</tr>';
                    });    
                } else {
                    html += '<tr>';
                            html += '<td align="center" colspan="4"> No hay requerimientos internos registrados.</td>';                            
                            html += '</td>';  
                            html += '</tr>';
                }        
                $("#ver-reque-interno").html(html);
            }else{
                Util.alertaB(resultado.datos);
            }
        } 
    };

    new Ajex.Api({
        modelo: "RequisitoInterno",
        metodo: "listar",
        data_out : [p_nombre_plan_produccion]
    }, funcion);
}

function darBajaRI(p_codigo, p_estado){
    var texto = 'anular';
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                Util.alertaA(resultado.datos);
                listarRequisitos();
                listarPiezas();
                listarProductos();
            }else{
                Util.alertaB(resultado.datos);
            }            
        } 
    };

    var entradas = {
        modelo: "RequisitoInterno",
        metodo: "habilitar",
        data_in: {
            p_cod_requisito_interno: p_codigo,
            p_estado_mrcb: p_estado
        }
    }; 
    Util.notificacion(entradas,funcion, texto);
}


function verRI(p_codigo_requisito_interno){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if ( resultado.datos.rpt === true) {
                var html = "";
                if (resultado.datos.msj.length > 0){
                    $.each(resultado.datos.msj, function (i, item) {
                        DOM.modal5.find(".modal-title").text("Ver detalle: Requerimiento Interno");
                        html += '<tr>';
                        html += '<td align="center">' + item.cod_requisito_interno_pieza + '</td>';
                        html += '<td align="center">' + item.nombre + '</td>';
                        html += '<td align="center">' + item.costo_unitario + '</td>';
                        html += '<td align="center">' + item.cantidad + '</td>';
                        html += '<td align="center">' + item.importe + '</td>';                
                        html += '</tr>';
                    });
                }else {
                    html += '<tr>';
                            html += '<td align="center" colspan="5"> No hay detalle de req. interno..</td>';                            
                            html += '</td>';  
                            html += '</tr>';
                }     
                $("#ver-requisito-interno").html(html);
            }else{
                Util.alertaB(resultado.datos);
            }            
        } 
    };

    new Ajex.Api({
        modelo: "RequisitoInterno",
        metodo: "ver",
        data_in: {
            p_cod_requisito_interno : p_codigo_requisito_interno
        }
    },funcion); 
}

function faseInicio(){
    var fnIniciarPlanProduccion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {                    
                Util.alertaA(resultado.datos);  
                leerDatos();
                listarPlanProduccion();
                listarRequisitos();
                validarEstado();                        
            }else{
                Util.alertaB(resultado.datos);       
            }                        
        } 
    };         
    var entradas = {
        modelo: "PlanProduccion",
        metodo: "faseInicio",                            
        data_in: { 
            p_fecha_inicio_proceso : Util.obtenerTimestamp(),
            p_nombre : p_nombre_plan_produccion
        }                         
    };
    Util.notificacion(entradas,fnIniciarPlanProduccion);
}

function listarMateriasPrimas(){
    var acumulativoMonto = 0.00,  acumulativoAhorro = 0.00,
    importeNuevo = 0.00, importeAhorro = 0.00, cant_usada = 0.00, 
    cant_comprar = 0.00, cant_almacen = 0.00, temp_cant;
    var fnListarMP = function (resultado) {
        if (resultado.estado === 200) {
            if ( resultado.datos.rpt === true ) {
                var html = "", data = resultado.datos.data;
                var llenarTabla = function (data, colorBg){
                    html = "";
                    $.each(data, function (i, item) {
                        temp_cant = tFloat(item.cant_almacen - item.cant_necesaria);
                        if (temp_cant >= 0.00){
                            cant_comprar = "0.00";
                            cant_almacen = tFloat(temp_cant);
                            cant_usada = tFloat(item.cant_necesaria);
                        } else{
                            cant_usada = tFloat(item.cant_almacen);
                            cant_comprar = tFloat(item.cant_necesaria - cant_usada);
                            cant_almacen = "0.00";
                        }
                        importeNuevo = parseFloat(item.precio * cant_comprar);
                        importeAhorro = parseFloat(item.precio * cant_usada);

                        if (colorBg){
                            if (i == 3){
                                colorBg = "bg-info";                        
                            }       
                            html += '<tr data-cant-almacen="'+item.cant_almacen+'" data-codigo ="'+item.x_cod_materia_prima+'" class="'+colorBg+'">';
                        } else {
                            html += '<tr data-codigo ="'+item.x_cod_materia_prima+'">';
                        }
                        html += '<td align="center">' + item.cod_materia_prima + '</td>';
                        html += '<td align="center">' + item.nombre + ' ('+item.abreviatura+') </td>';
                        html += '<td align="center" class="x-precio">' + item.precio + '</td>';              
                        html += '<td align="center" class="x-cant-necesaria">' + tFloat(item.cant_necesaria) + '</td>';  
                        html += '<td align="center" class="x-cant-comprar" style="font-weight: bold;">'+cant_comprar+'</td>';
                        html += '<td align="center" data-cant-sobrante="'+cant_almacen+'" class="x-cant-usada bg-success green" style="font-weight: bold;">' + cant_usada + '</td>';  
                        html += '<td align="center" class="x-total-recuperado">' + importeAhorro.toFixed(2) + '</td>'; 
                        html += '<td align="center" class="x-total-comprar">' + importeNuevo.toFixed(2) + '</td>'; 
                        html += '</tr>';
                        acumulativoMonto += importeNuevo;
                        acumulativoAhorro += importeAhorro;
                    });
                };
                llenarTabla(data.mps);
                DOM.acondicionamiento_detalle_mp.html(html);
                llenarTabla(data.mp_fundicion,"bg-warning");
                DOM.acondicionamiento_fundicion_detalle_mp.html(html);
                DOM.costo_materia_prima.text(acumulativoMonto.toFixed(2));   
                DOM.costo_materia_prima_ahorro.text(acumulativoAhorro.toFixed(2));   
            }else{
                Util.alertaB(resultado.datos); 
            }      
        } else {
            swal("Mensaje del sistema", resultado, "warning");
        }
    };

    new Ajex.Api({
        modelo: "PlanProduccion",
        metodo: "listarMateriasPrimas",
        data_in : {
            p_nombre : p_nombre_plan_produccion
        }                    
    }, fnListarMP);
}


function listarAcondicionamiento(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if ( resultado.datos.rpt === true ) {                
                DOM.costo_extra.val(datos.costo);
                DOM.motivo_costo.val(datos.comentario);
                $("#fa_formateada").val(datos.fecha); 
            }else{
                Util.alertaB(resultado.datos);
            }                       
        } 
    };

    new Ajex.Api({
        modelo: "PlanProduccion",
        metodo: "listarAcondicionamiento",
        data_in : {
            p_nombre : p_nombre_plan_produccion
        }                    
    }, funcion);   
}

function listarFundicionPiezas(){ 
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if ( resultado.datos.rpt === true) {
                var html = "";
                $.each(resultado.datos.msj, function (i, item) {
                    html += '<tr data-id="'+item.codigo+'">';
                    html += '<td align="center">' + item.cod_pieza + '</td>';             
                    html += '<td align="center">' + item.pieza + '</td>'; 
                    html += '<td align="center">' + item.cantidad + '</td>';                                            
                    html += '</tr>';
                });
                $("#ver-detalle-piezas").html(html);
            }else{
                Util.alertaB(resultado.datos);
            }            
        } 
    };

    new Ajex.Api({
        modelo: "PlanProduccion",
        metodo: "listarPiezas",
        data_in : {
            p_nombre : p_nombre_plan_produccion
        }                    
    }, funcion);
}


function listarFundicion(){ /*Más Fundición Materia Prima*/
    var acumulativoMonto = 0.00;
    var funcion = function (resultado) {
        if (resultado.estado === 200) {                
            if ( resultado.datos.rpt === true) { 
                var data = resultado.datos.data, html ="", colorBg = "bg-warning"; 
                $.each(data.mps, function (i, item) {
                    if (item === null){
                        return;
                    }
                        importe = parseFloat(item.precio * item.cant_usar);
                        cant_restante = tFloat(item.cant_usar - item.cant_almacen);
                        if (i == 3){ colorBg = "bg-info"; }    
                        html += '<tr class="'+colorBg+'" data-codigo ="'+item.x_cod_materia_prima+'">';
                        html += '<td align="center">' + item.cod_materia_prima + '</td>';
                        html += '<td align="center">' + item.nombre + ' ('+item.abreviatura+') </td>';
                        html += '<td align="center" class="x-precio">' + item.precio + '</td>';              
                        html += '<td align="center" class="x-cant-usada-almacen green" style="font-weight: bold;">' + tFloat(item.cant_usar) + '</td>';  
                        html += '<td align="center" data-cant-restante="'+cant_restante+'" class="x-cant-sobrante red" style="font-weight: bold;">' + cant_restante + '</td>';  
                        html += '<td align="center" class="x-total-import">' + importe.toFixed(2) + '</td>'; 
                        html += '</tr>';
                        acumulativoMonto += importe;
                    });                
                DOM.fundicion_detalle_mp.html(html);               

                if (data.pp){
                    DOM.minutos_fundicion.val(data.pp.horas);
                    DOM.costo_fundicion.val(data.pp.costo);
                    DOM.comentario_fundicion.val(data.pp.comentario);
                    $("#ff_formateada").val(data.pp.fecha);    
                }

            }else{
                Util.alertaB(resultado.datos);
            }            
        } 
    };

    new Ajex.Api({
        modelo: "PlanProduccion",
        metodo: "listarFundicion",
        data_in : {
            p_nombre : p_nombre_plan_produccion
        }                    
    }, funcion);   

    listarFundicionPiezas();
}

function cabecera(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if ( resultado.datos.rpt === true) {   
                $.each(DOM.actividad_subtitulo, function( i, o ){
                    o.text(resultado.datos.msj[i].nombre);
                });
            }else{
                Util.alertaB(resultado.datos);
            }            
        } 
    };

    new Ajex.Api({
        modelo: "Actividad",
        metodo: "cabecera"                  
    }, funcion);   
}

function listarPiezasActividad(numero){    
    /*parametro,etiqueta*/
    var porcTtotal, color, funcion = function (resultado) {

        if (resultado.estado === 200) {
            if ( resultado.datos.rpt === true) {
                var html = "";
                if (resultado.datos.msj.length > 0 ){ 
                    $.each(resultado.datos.msj, function (i, item) {
                        html += '<tr data-id="'+item.codigo+'" data_lote="'+item.piezas_total+'">';
                        html += '<td align="center">' + item.cod_pieza + '</td>';             
                        html += '<td align="center">' + item.pieza + '</td>'; 
                        html += '<td align="center">' + item.piezas_buenas + '</td>';                                            
                        html += '<td align="center">' + item.piezas_falladas + '</td>';
                        html += '<td class="project_progress">';
                        html += '<div class="progress progress_sm">';
                        porcTotal = parseFloat((parseInt(item.piezas_total)-parseInt(item.piezas_falladas))*100/(parseInt(item.piezas_total))).toFixed(2);
                        var numero = porcTotal === 'NaN' ? '0' : porcTotal;

                        
                        color = ((numero <= 35.00) ? "red" : (numero <= 70.00 ? "orange" : "green" ));
                        html += '<div class="progress-bar bg-'+color+'" role="progressbar" style="width: '+numero+'%;"></div>';
                        html += '</div>';
                        html += '<small>'+ numero +'% de las Piezas</small>';                          
                        html += '</div>';
                        html += '</td>';
                        html += '<td align="center">';
                        if ( item.cod_actividad !== item.estado_actividad) {                        
                            if ( item.piezas_total !== item.piezas_buenas ) {
                                html += '<button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#myModalVerActividad" onclick="verFallas('+item.codigo+','+item.cod_actividad+',\''+item.pieza+'\')" title="Ver"><i class="fa fa-eye"></i></button>';
                                html += '&nbsp;&nbsp;';                            
                            }else{
                                html += '<span class="label label-info">Sin fallas</span>';
                                html += '&nbsp;&nbsp;';  
                            }                        
                        }else{
                            if ( item.piezas_buenas === 0 ) { 
                                html += '<button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#myModalVerActividad" onclick="verFallas('+item.codigo+','+item.cod_actividad+',\''+item.pieza+'\')" title="Ver"><i class="fa fa-eye"></i></button>';
                                html += '&nbsp;&nbsp;';
                            }else{
                                if ( item.piezas_total === item.piezas_buenas) {
                                    html += '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModalActividad" onclick="agregarFalla('+item.cod_plan_produccion_pieza + ',' + "'" + item.pieza + "'" + ',' +  item.piezas_total + ',' +  item.cod_actividad +')" title="Agregar Falla"><i class="fa fa-pencil"></i></button>';
                                    html += '&nbsp;&nbsp;';
                                }else{
                                    html += '<button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#myModalVerActividad" onclick="verFallas('+item.codigo+','+item.cod_actividad+',\''+item.pieza+'\')" title="Ver"><i class="fa fa-eye"></i></button>';
                                    html += '&nbsp;&nbsp;';                      
                                    html += '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModalActividad"  onclick="agregarFallaT('+item.cod_plan_produccion_pieza+ ',' + "'" + item.pieza + "'" + ',' +  item.piezas_total + ',' +  item.piezas_buenas + ',' +  item.cod_actividad +')" title="Editar"><i class="fa fa-pencil"></i></button>';
                                    html += '&nbsp;&nbsp;';
                                }
                            }
                        }                    
                        html += '</td>';
                        html += '</tr>';
                    });
                } else {
                    html += '<tr>';
                            html += '<td align="center" colspan="6"> No hay piezas para mostrar.</td>';                            
                            html += '</td>';  
                            html += '</tr>';
                }     
                DOM.actividad_detalle[numero-1].html(html);
            }else{
                Util.alertaB(resultado.datos);
            }            
        } 
    };

    new Ajex.Api({
        modelo: "PlanProduccion",
        metodo: "listarPiezasActividad",
        data_in : {
            p_nombre : p_nombre_plan_produccion
        },
        data_out :[numero]                   
    }, funcion);
}

function cargarFalla(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if (resultado.datos.rpt === true) {
                var html = "<option></option>";
                for (var i = 0; i < datos.length; i++) {
                    html += "<option value=" + datos[i].cod_falla + ">" + datos[i].nombre + "</option>";
                }
                $("#txtcodigo_tipo_falla").html(html).select2({
                      placeholder: "Seleccionar tipo de falla",
                      allowClear: true
                });
            }else{
                Util.alertaB(datos);
            }
        }
    };

    new Ajex.Api({
        modelo : "TipoFalla",
        metodo : "cbListar"
    }, funcion);
}

function agregarFalla(p_codigo, p_pieza, p_total, p_actividad){
    $("#myModalActividad").find(".modal-title").html("AGREGAR FALLA: <b>"+p_pieza+"<b>");
    $("#txtcodigo_plan_produccion_pieza").val(p_codigo);
    $("#txtoperacion").val("carrito");
    $("#txttotal_lote").val(p_total);
    $("#txttotal_piezas").val("");
    $("#txtactividad").val(p_actividad);
    
    //$("#detalle-falla-pieza").html("<tr><td colspan='4' class='text-center'>Sin registros.</td></tr>");
    limpiar3();
    DOM.btnGrabarFalla.show();

}

function agregarFallaT(p_codigo, p_pieza, p_total, p_buenas, p_actividad){
    $("#myModalActividad").find(".modal-title").html("AGREGAR FALLA: <b>"+p_pieza+"<b>");
    $("#txtcodigo_plan_produccion_pieza").val(p_codigo);    
    $("#txtoperacion").val("agregar");
    $("#txttotal_lote").val(p_buenas);
    $("#txttotal_piezas").val(p_total);
    DOM.btnGrabarFalla.hide();
    $("#txtactividad").val(p_actividad);


    listarFalla(p_codigo);
    piezasBuenas(p_codigo);

}

function listarFalla(p_codigo){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if (resultado.datos.rpt === true) {
                var html = "";
                if ( resultado.datos.msj.length > 0 ){ 

                    $.each(resultado.datos.msj, function (i, item) {
                        html += '<tr data-id="'+item.cod_tipo_falla+'">';
                        html += '<td align="center"><i style="font-size:20px;" class="fa fa-close text-danger eliminar_falla"></i></td>';   
                        html += '<td align="center">' + item.descripcion_falla + '</td>';             
                        html += '<td align="center">' + item.nombre + '</td>'; 
                        html += '<td align="center">' + item.total_piezas + '</td>';            
                        html += '</tr>';
                    });
                }
                else {
                    html += '<tr>';
                            html += '<td align="center" colspan="5"> No hay piezas falladas.</td>';                            
                            html += '</td>';  
                            html += '</tr>';
                }     
                $("#detalle-falla-pieza").html(html);
            }else{
                Util.alertaB(datos);
            }
        }
    };

    new Ajex.Api({
        modelo : "PlanProduccionPieza",
        metodo : "leerDatos",
        data_in : {
            p_cod_plan_produccion_pieza : p_codigo
        }
    }, funcion);
}

function piezasBuenas(p_codigo){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {                
                $("#txttotal_lote").val(resultado.datos.msj);                
            }else{
                Util.alertaB(datos);
            }
        }
    };

    new Ajex.Api({
        modelo : "PlanProduccionPieza",
        metodo : "piezasBuenas",
        data_in : {
            p_cod_plan_produccion_pieza : p_codigo
        }
    }, funcion);
}

function carritoFalla(){
    if ( $("#txtoperacion").val() === 'carrito' ) {
        if ( $("#txtdescripcion").val() === ''  ) {
            Util.alerta('warning','Debe ingresar un motivo para la falla',2000);
            return 0;
        }
        if ( $("#txtcodigo_tipo_falla").val() === ''  ) {
            Util.alerta('warning','Debe seleccionar una falla',2000);
            return 0;
        }
        if ( $("#txtcantidad_fallas").val() === ''  ) {
            Util.alerta('warning','Debe ingresar una cantidad para la falla',2000);
            return 0;
        }

        if ( parseInt($("#txttotal_lote").val())  < parseInt($("#txtcantidad_fallas").val())) {
            Util.alerta('warning','La cantidad no debe ser mayor que el total',2000);
            return 0;
        }

        if (! validarMismoFalla( $("#txtcodigo_tipo_falla").val() )) {
            Util.alerta('warning','No es posible agregar el mismo falla mas de 1 veces',2000);
            limpiar2();
            return 0; //detiene el programa
        } 

        if ( !($("#detalle-falla-pieza tr").length <= 0) ) {
            if( !(sumaFallas(parseInt($("#txttotal_lote").val()),parseInt($("#txtcantidad_fallas").val()) ) ) ){
                Util.alerta('warning','La cantidad no debe ser mayor que el total',2000);
                limpiar2();
                return 0; //detiene el programa 
            }
        }

        //Elaborar una variable con el HTML para agregar al detalle
        var fila = '<tr  data-id="'+$("#txtcodigo_tipo_falla").val()+'">' +
                '<td class="text-center"><i style="font-size:20px;" class="fa fa-close text-danger eliminar_falla"></i></td>' +            
                '<td class="text-center">'+ $("#txtdescripcion").val() +'</td>' +
                '<td class="text-center" >' + $("#txtcodigo_tipo_falla").select2('data').text + '</td>' +
                '<td class="text-center" >' + $("#txtcantidad_fallas").val() + '</td>' +                
                '</tr>';

        //Agregar el registro al detalle de la producto
        $("#detalle-falla-pieza").append(fila);
        limpiar2();        
    }else{
        if ( $("#txtdescripcion").val() === ''  ) {
            Util.alerta('warning','Debe ingresar un motivo para la falla',2000);
            return 0;
        }
        if ( $("#txtcodigo_tipo_falla").val() === ''  ) {
            Util.alerta('warning','Debe seleccionar una falla',2000);
            return 0;
        }
        if ( $("#txtcantidad_fallas").val() === ''  ) {
            Util.alerta('warning','Debe ingresar una cantidad para la falla',2000);
            return 0;
        }

        if ( parseInt($("#txttotal_lote").val())  < parseInt($("#txtcantidad_fallas").val())) {
            Util.alerta('warning','La cantidad no debe ser mayor que el total',2000);
            return 0;
        }

        if (! validarMismoFalla( $("#txtcodigo_tipo_falla").val() )) {
            Util.alerta('warning','No es posible agregar el mismo falla mas de 1 veces',2000);
            limpiar2();
            return 0; //detiene el programa
        }  

        var funcion = function (resultado) {
            if (resultado.estado === 200) {
                if (resultado.datos.rpt === true) {                    
                    Util.alertaA(resultado.datos);                    
                    limpiar2();
                    listarPiezasActividad($("#txtactividad").val());
                    $("#myModalActividad").modal("hide");
                }else{
                    Util.alertaB(resultado.datos); 
                    $("#myModalActividad").modal("hide");      
                }                        
            } 
        };         
        var entradas = {
            modelo: "PiezaFallada",
            metodo: "agregar",                            
            data_in: { 
                p_cod_plan_produccion_pieza :$("#txtcodigo_plan_produccion_pieza").val(),
                p_descripcion_falla : $("#txtdescripcion").val(),
                p_cod_tipo_falla : $("#txtcodigo_tipo_falla").val(),
                p_total_piezas : $("#txtcantidad_fallas").val()
            }                         
        };
        Util.notificacion(entradas,funcion);
    }    
}

function validarMismoFalla(p_codigo_falla){
    var c = 0;    
    $("#detalle-falla-pieza tr").each(function(){
        var codigo = this.dataset.id;

        if (codigo === p_codigo_falla){
            c++;
        }
    });    
    if (c >= 1){
        return false;
    }    
    return true; 
}

function limpiar2(){
    $("#txtcodigo_tipo_falla").val("").select2({
        placeholder: "Seleccionar pieza",
        allowClear: true
    });
    $("#txtdescripcion").val("");
    $("#txtcantidad_fallas").val("");
}

function sumaFallas(p_total,p_cantidad){
    var suma = 0;     
    $("#detalle-falla-pieza tr").each(function(){
        var cantidad = $(this).find("td").eq(3).html();
        suma = suma  + parseInt(cantidad);
    }); 
    var contar = suma+p_cantidad;

    if ( !(contar <= p_total) ) {
        return false;
    }
    return true;
}

function verFallas(p_codigo, p_limite, subtitulo){

    $("#myModalVerActividad").find(".modal-title").html("Mostrando Fallas: <b>"+subtitulo+"</b>");
   var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if (resultado.datos.rpt === true) {
                var html = "";
                $.each(resultado.datos.msj, function (i, item) {
                    html += '<tr>';
                    html += '<td align="center">' + (i+1) +'</td>';   
                    html += '<td align="center">' + item.actividad + '</td>';             
                    html += '<td align="center">' + item.falla + '</td>'; 
                    html += '<td align="center">' + item.motivo + '</td>';            
                    html += '<td align="center">' + item.piezas_total + '</td>'; 
                    html += '<td align="center">' + item.piezas_buenas + '</td>'; 
                    html += '<td align="center">' + item.piezas_falladas + '</td>'; 

                    html += '</tr>';
                });
                $("#ver_detalle-falla-pieza").html(html);
            }else{
                Util.alertaB(datos);
            }
        }
    };

    new Ajex.Api({
        modelo : "PlanProduccionPieza",
        metodo : "leerDatos",
        data_in : {
            p_cod_pieza : p_codigo
        },
        data_out : [p_nombre_plan_produccion, p_limite]
    }, funcion); 
}

function listarActividad(nro){
    /*p5 = id.*/
    var ind = nro - 1,     
    p1 = DOM.mfecha_inicio_actividad[ind],
    p2 = DOM.mfecha_fin_actividad[ind],
    p3 = DOM.motivo_actividad[ind],
    p4 = DOM.colaborador_actividad[ind],
    funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if ( resultado.datos.rpt === true) {                
                p1.val(datos.fecha_inicio);
                p2.val(datos.fecha_fin);
                p3.val(datos.observaciones);
                p4.val(datos.cantidad_hombres);                    
            }else{
                Util.alertaB(resultado.datos);
            }            
        } 
    };

    DOM.btnActividad[ind].hide();
    var nombreFuncionListar = "listarActividad";
    if (nro == 8 || nro == 9){
        nombreFuncionListar = "listarFActividad";
    }

    new Ajex.Api({
        modelo: "PlanProduccion",
        metodo: nombreFuncionListar,
        data_in : {
            p_nombre : p_nombre_plan_produccion
        },
        data_out : [nro]                    
    }, funcion);   
}

var renderProductosEnsamblado = function(productos){
        var html = "";
         $.each(productos, function (i, item) {
                    html += '<tr data-id="'+item.codigo+'">';                    
                    html += '<td align="center">' + item.cod_producto + '</td>';             
                    html += '<td align="center">' + item.nombre + '</td>'; 
                    html += '<td align="center">' + item.cantidad + '</td>';            
                    html += '</tr>';
                });
                DOM.verProductosEnsamblado.html(html); 
    },
    renderPiezasEnsamblado = function (piezas){
        var html = "";
         $.each(piezas, function (i, item) {
                    html += '<tr data-id="'+item.codigo+'">';                    
                    html += '<td align="center">' + item.cod_pieza + '</td>';             
                    html += '<td align="center">' + item.nombre + '</td>'; 
                    html += '<td align="center">' + item.cantidad + '</td>';            
                    html += '</tr>';
                });
                DOM.verPiezasEnsamblado.html(html);  
    };

function listarEnsamblado(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if (resultado.datos.rpt === true) {
                log(datos);
                renderProductosEnsamblado(datos.productos);
                renderPiezasEnsamblado(datos.piezas);
                                 
            }else{
                Util.alertaB(resultado.datos);
            }            
        } 
    };

    new Ajex.Api({
        modelo: "PlanProduccion",
        metodo: "listarEnsamblado",
        data_in : {
            p_nombre : p_nombre_plan_produccion
        }                    
    }, funcion); 

}

/*
function listarProductosAlmacen(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos.msj;
            if (resultado.datos.rpt === true) {
                var html = "";
                $.each(resultado.datos.msj, function (i, item) {
                    html += '<tr data-id="'+item.codigo+'">';                    
                    html += '<td align="center">' + item.cod_producto + '</td>';             
                    html += '<td align="center">' + item.nombre + '</td>'; 
                    html += '<td align="center">' + item.cantidad + '</td>';            
                    html += '</tr>';
                });
                $("#ver_productos_almacen").html(html);                   
            }else{
                Util.alertaB(resultado.datos);
            }            
        } 
    };

    new Ajex.Api({
        modelo: "PlanProduccion",
        metodo: "listarProductosFinales",
        data_in : {
            p_nombre : p_nombre_plan_produccion
        }                    
    }, funcion); 
}
*/

function listarEmpaquetado(){

    console.log("list");
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            var datos = resultado.datos.data;
            if (resultado.datos.rpt === true) {                            
                var html = "";
                console.log(datos);
                /*resultado.datos.data.productos_almacen, piezas_almacen, mp_almacen*/
            
                $.each(datos.productos, function (i, item) {
                    html += '<tr data-id="'+item.codigo+'">';                    
                    html += '<td align="center">' + item.cod_producto + '</td>';             
                    html += '<td align="center">' + item.nombre + '</td>'; 
                    html += '<td align="center"> - </td>';            
                    html += '<td align="center">' + item.cantidad + '</td>';            
                    html += '<td align="center"> - </td>';            
                    html += '</tr>';
                });
                DOM.tblVerProductosAlmacen.html(html);                   

                html = "";

                $.each(datos.piezas, function (i, item) {
                    html += '<tr data-id="'+item.codigo+'">';                    
                    html += '<td align="center">' + item.cod_pieza + '</td>';             
                    html += '<td align="center">' + item.nombre + '</td>'; 
                    html += '<td align="center">' + item.cantidad + '</td>';            
                    html += '</tr>';
                });
                DOM.tblVerPiezasAlmacen.html(html);                   

                html = "";


                $.each(datos.mp, function (i, item) {
                    html += '<tr data-id="'+item.codigo+'">';                    
                    html += '<td align="center">' + item.cod_materia_prima + '</td>';             
                    html += '<td align="center">' + item.nombre + '</td>'; 
                    html += '<td align="center">' + item.precio_base + '</td>';            
                    html += '<td align="center">' + item.cantidad_recuperada + '</td>';            
                    html += '<td align="center">' + tFloat(item.cantidad_recuperada * item.precio_base) + '</td>';   
                    html += '</tr>';
                });
                
                DOM.tblVerMPAlmacen.html(html);    
                
            }else{
                Util.alertaB(resultado.datos);
            }            
        } 
    };

    new Ajex.Api({
        modelo: "PlanProduccion",
        metodo: "listarEmpaquetado",
        data_in : {
            p_nombre : p_nombre_plan_produccion
        }                    
    }, funcion); 
}

/*/VALIDAR/*/
function validarEstado(){
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if ( resultado.datos.rpt === true) {
                console.log(resultado.datos.msj);
                switch(resultado.datos.msj){
                    case '0':
                        $("#btnAgregarPedido").show();
                        $("#btnInterior").show();
                        DOM.btnIniciar.show();
                        $("#blk-fase-acondicionamiento").hide();
                        $("#blk-fase-fundicion").hide();
                        $("#blk-fase-acabado").hide();
                        $("#fa_formateada").hide();
                        $("#ff_formateada").hide();
                        $("#btnAcondicionamiento").hide();
                        break;
                    case '1':
                        $("#btnAgregarPedido").hide();
                        $("#btnInterior").hide();
                        DOM.btnIniciar.hide();
                        $("#blk-fase-acondicionamiento").fadeIn("slow");
                        //.show();
                        $("#blk-fase-fundicion").hide();
                        $("#blk-fase-acabado").hide();
                        $("#fa_formateada").hide();
                        $("#ff_formateada").hide();
                        DOM.btnAcondicionamiento.show();
                        listarMateriasPrimas();   
                        break;
                    case '2':
                        $("#btnAgregarPedido").hide();
                        $("#btnInterior").hide();
                        DOM.btnIniciar.hide();
                        $("#blk-fase-acondicionamiento").show();
                        $("#blk-fase-fundicion").fadeIn("slow");
                        $("#blk-fase-acabado").hide();
                        $("#fa_formateada").show();
                        $("#ff_formateada").hide();                        
                        DOM.btnAcondicionamiento.hide();
                        $("#txtcosto_extra").prop("readonly", true);
                        $("#txtmotivo_costo").prop("readonly", true);
                        $("#txtfecha_acondicionamiento").hide();
                        listarMateriasPrimas();
                        listarAcondicionamiento();
                        listarFundicion();
                        $("#btnFundicion").show();   
                        break;
                    case '3':
                        $("#btnAgregarPedido").hide();
                        $("#btnInterior").hide();
                        DOM.btnIniciar.hide();
                        $("#blk-fase-acondicionamiento").show();
                        $("#blk-fase-fundicion").show();
                        $("#blk-fase-acabado").show();
                        $("#fa_formateada").show();
                        $("#ff_formateada").show();                        
                        DOM.btnAcondicionamiento.hide();
                        $("#txtcosto_extra").prop("readonly", true);
                        $("#txtmotivo_costo").prop("readonly", true);
                        $("#txtfecha_acondicionamiento").hide();
                        $("#txtfecha_fundicion").hide();
                        listarMateriasPrimas();
                        listarAcondicionamiento();
                        listarFundicion();
                        $("#txtminutos_fundicion").prop("readonly", true);
                        $("#txtcosto_fundicion").prop("readonly", true);
                        $("#txtcomentario_fundicion").prop("readonly", true); 
                        $("#btnFundicion").hide();  
                        cabecera();
                        listarPiezasActividadBloque(1);
                        mostrarBlkSubFase(1);
                        mostrarElementos(0);                        
                        break;
                    case 'A':
                        $("#btnAgregarPedido").hide();
                        $("#btnInterior").hide();
                        DOM.btnIniciar.hide();
                        $("#blk-fase-acondicionamiento").show();
                        $("#blk-fase-fundicion").show();
                        $("#blk-fase-acabado").show();
                        $("#fa_formateada").show();
                        $("#ff_formateada").show();                        
                        DOM.btnAcondicionamiento.hide();
                        $("#txtcosto_extra").prop("readonly", true);
                        $("#txtmotivo_costo").prop("readonly", true);
                        $("#txtfecha_acondicionamiento").hide();
                        $("#txtfecha_fundicion").hide();
                        listarMateriasPrimas();
                        listarAcondicionamiento();
                        listarFundicion();
                        $("#txtminutos_fundicion").prop("readonly", true);
                        $("#txtcosto_fundicion").prop("readonly", true);
                        $("#txtcomentario_fundicion").prop("readonly", true); 
                        cabecera();
                        listarPiezasActividadBloque(2);
                        mostrarBlkSubFase(2);
                        listarActividadBloque(1);
                        mostrarElementos(1); 
                        break;
                    case 'B':
                        $("#btnAgregarPedido").hide();
                        $("#btnInterior").hide();
                        DOM.btnIniciar.hide();
                        $("#blk-fase-acondicionamiento").show();
                        $("#blk-fase-fundicion").show();
                        $("#blk-fase-acabado").show();
                        $("#fa_formateada").show();
                        $("#ff_formateada").show();                        
                        DOM.btnAcondicionamiento.hide();
                        $("#txtcosto_extra").prop("readonly", true);
                        $("#txtmotivo_costo").prop("readonly", true);
                        $("#txtfecha_acondicionamiento").hide();
                        $("#txtfecha_fundicion").hide();
                        listarMateriasPrimas();
                        listarAcondicionamiento();
                        listarFundicion();
                        $("#txtminutos_fundicion").prop("readonly", true);
                        $("#txtcosto_fundicion").prop("readonly", true);
                        $("#txtcomentario_fundicion").prop("readonly", true); 
                        cabecera();
                        listarPiezasActividadBloque(3);
                        mostrarBlkSubFase(3);
                        listarActividadBloque(2);                        
                        mostrarElementos(2); 
                        break;
                    case 'C':
                        $("#btnAgregarPedido").hide();
                        $("#btnInterior").hide();
                        DOM.btnIniciar.hide();
                        $("#blk-fase-acondicionamiento").show();
                        $("#blk-fase-fundicion").show();
                        $("#blk-fase-acabado").show();
                        $("#fa_formateada").show();
                        $("#ff_formateada").show();                        
                        DOM.btnAcondicionamiento.hide();
                        $("#txtcosto_extra").prop("readonly", true);
                        $("#txtmotivo_costo").prop("readonly", true);
                        $("#txtfecha_acondicionamiento").hide();
                        $("#txtfecha_fundicion").hide();
                        listarMateriasPrimas();
                        listarAcondicionamiento();
                        listarFundicion();
                        $("#txtminutos_fundicion").prop("readonly", true);
                        $("#txtcosto_fundicion").prop("readonly", true);
                        $("#txtcomentario_fundicion").prop("readonly", true); 
                        cabecera();
                        listarPiezasActividadBloque(4);
                        mostrarBlkSubFase(4);
                        listarActividadBloque(3);
                        mostrarElementos(3); 
                        break;
                    case 'D':
                        $("#btnAgregarPedido").hide();
                        $("#btnInterior").hide();
                        DOM.btnIniciar.hide();
                        $("#blk-fase-acondicionamiento").show();
                        $("#blk-fase-fundicion").show();
                        $("#blk-fase-acabado").show();
                        $("#fa_formateada").show();
                        $("#ff_formateada").show();                        
                        DOM.btnAcondicionamiento.hide();
                        $("#txtcosto_extra").prop("readonly", true);
                        $("#txtmotivo_costo").prop("readonly", true);
                        $("#txtfecha_acondicionamiento").hide();
                        $("#txtfecha_fundicion").hide();
                        listarMateriasPrimas();
                        listarAcondicionamiento();
                        listarFundicion();
                        $("#txtminutos_fundicion").prop("readonly", true);
                        $("#txtcosto_fundicion").prop("readonly", true);
                        $("#txtcomentario_fundicion").prop("readonly", true); 
                        cabecera();
                        listarPiezasActividadBloque(5);
                        mostrarBlkSubFase(5);
                        listarActividadBloque(4);
                        mostrarElementos(4); 
                        break;
                    case 'E':
                        $("#btnAgregarPedido").hide();
                        $("#btnInterior").hide();
                        DOM.btnIniciar.hide();
                        $("#blk-fase-acondicionamiento").show();
                        $("#blk-fase-fundicion").show();
                        $("#blk-fase-acabado").show();
                        $("#fa_formateada").show();
                        $("#ff_formateada").show();                        
                        DOM.btnAcondicionamiento.hide();
                        $("#txtcosto_extra").prop("readonly", true);
                        $("#txtmotivo_costo").prop("readonly", true);
                        $("#txtfecha_acondicionamiento").hide();
                        $("#txtfecha_fundicion").hide();
                        listarMateriasPrimas();
                        listarAcondicionamiento();
                        listarFundicion();
                        $("#txtminutos_fundicion").prop("readonly", true);
                        $("#txtcosto_fundicion").prop("readonly", true);
                        $("#txtcomentario_fundicion").prop("readonly", true); 
                        cabecera();
                        listarPiezasActividadBloque(6);
                        mostrarBlkSubFase(6);  
                        mostrarElementos(5); 
                        listarActividadBloque(5);
                        break;
                    case 'F':
                        $("#btnAgregarPedido").hide();
                        $("#btnInterior").hide();
                        DOM.btnIniciar.hide();
                        $("#blk-fase-acondicionamiento").show();
                        $("#blk-fase-fundicion").show();
                        $("#blk-fase-acabado").show();
                        $("#fa_formateada").show();
                        $("#ff_formateada").show();                        
                        DOM.btnAcondicionamiento.hide();
                        $("#txtcosto_extra").prop("readonly", true);
                        $("#txtmotivo_costo").prop("readonly", true);
                        $("#txtfecha_acondicionamiento").hide();
                        $("#txtfecha_fundicion").hide();
                        listarMateriasPrimas();
                        listarAcondicionamiento();
                        listarFundicion();
                        $("#txtminutos_fundicion").prop("readonly", true);
                        $("#txtcosto_fundicion").prop("readonly", true);
                        $("#txtcomentario_fundicion").prop("readonly", true); 
                        cabecera();
                        listarPiezasActividadBloque(7);
                        mostrarBlkSubFase(7);  
                        mostrarElementos(6);
                        listarActividadBloque(6);
                        break;
                    case 'G':
                        $("#btnAgregarPedido").hide();
                        $("#btnInterior").hide();
                        DOM.btnIniciar.hide();
                        $("#blk-fase-acondicionamiento").show();
                        $("#blk-fase-fundicion").show();
                        $("#blk-fase-acabado").show();
                        $("#fa_formateada").show();
                        $("#ff_formateada").show();                        
                        DOM.btnAcondicionamiento.hide();
                        $("#txtcosto_extra").prop("readonly", true);
                        $("#txtmotivo_costo").prop("readonly", true);
                        $("#txtfecha_acondicionamiento").hide();
                        $("#txtfecha_fundicion").hide();
                        listarMateriasPrimas();
                        listarAcondicionamiento();
                        listarFundicion();
                        $("#txtminutos_fundicion").prop("readonly", true);
                        $("#txtcosto_fundicion").prop("readonly", true);
                        $("#txtcomentario_fundicion").prop("readonly", true); 
                        cabecera();
                        listarPiezasActividadBloque(7);
                        mostrarBlkSubFase(8);  
                        mostrarElementos(7);
                        listarActividadBloque(7);
                        listarEnsamblado();
                        break;
                    case 'H':
                        $("#btnAgregarPedido").hide();
                        $("#btnInterior").hide();
                        DOM.btnIniciar.hide();
                        $("#blk-fase-acondicionamiento").show();
                        $("#blk-fase-fundicion").show();
                        $("#blk-fase-acabado").show();
                        $("#fa_formateada").show();
                        $("#ff_formateada").show();                        
                        DOM.btnAcondicionamiento.hide();
                        $("#txtcosto_extra").prop("readonly", true);
                        $("#txtmotivo_costo").prop("readonly", true);
                        $("#txtfecha_acondicionamiento").hide();
                        $("#txtfecha_fundicion").hide();
                        listarMateriasPrimas();
                        listarAcondicionamiento();
                        listarFundicion();
                        $("#txtminutos_fundicion").prop("readonly", true);
                        $("#txtcosto_fundicion").prop("readonly", true);
                        $("#txtcomentario_fundicion").prop("readonly", true); 
                        cabecera();
                        listarPiezasActividadBloque(7);
                        mostrarBlkSubFase(9);  
                        mostrarElementos(8);
                        listarActividadBloque(8);
                        listarEnsamblado();
                        listarEmpaquetado();   
                        break;
                    case 'I':
                        $("#btnAgregarPedido").hide();
                        $("#btnInterior").hide();
                        DOM.btnIniciar.hide();
                        $("#blk-fase-acondicionamiento").show();
                        $("#blk-fase-fundicion").show();
                        $("#blk-fase-acabado").show();
                        $("#fa_formateada").show();
                        $("#ff_formateada").show();                        
                        DOM.btnAcondicionamiento.hide();
                        $("#txtcosto_extra").prop("readonly", true);
                        $("#txtmotivo_costo").prop("readonly", true);
                        $("#txtfecha_acondicionamiento").hide();
                        $("#txtfecha_fundicion").hide();
                        listarMateriasPrimas();
                        listarAcondicionamiento();
                        listarFundicion();
                        $("#txtminutos_fundicion").prop("readonly", true);
                        $("#txtcosto_fundicion").prop("readonly", true);
                        $("#txtcomentario_fundicion").prop("readonly", true); 
                        cabecera();
                        listarPiezasActividadBloque(7);
                        mostrarBlkSubFase(10);  
                        mostrarElementos(9);
                        listarActividadBloque(9);
                        listarEnsamblado();  
                        listarEmpaquetado();     
                        DOM.btnFinalizar.show();                                          
                        break;
                    case '5':
                        $("#btnAgregarPedido").hide();
                        $("#btnInterior").hide();
                        DOM.btnIniciar.hide();
                        $("#blk-fase-acondicionamiento").show();
                        $("#blk-fase-fundicion").show();
                        $("#blk-fase-acabado").show();
                        $("#fa_formateada").show();
                        $("#ff_formateada").show();                        
                        DOM.btnAcondicionamiento.hide();
                        $("#txtcosto_extra").prop("readonly", true);
                        $("#txtmotivo_costo").prop("readonly", true);
                        $("#txtfecha_acondicionamiento").hide();
                        $("#txtfecha_fundicion").hide();
                        listarMateriasPrimas();
                        listarAcondicionamiento();
                        listarFundicion();
                        $("#txtminutos_fundicion").prop("readonly", true);
                        $("#txtcosto_fundicion").prop("readonly", true);
                        $("#txtcomentario_fundicion").prop("readonly", true); 
                        cabecera();
                        listarPiezasActividadBloque(7);
                        mostrarBlkSubFase(10);  
                        mostrarElementos(9);
                        listarActividadBloque(9);   
                        listarEnsamblado();                     
                        listarEmpaquetado(); 
                        DOM.btnFinalizar.hide();                                               
                        break;
                }          

                if (resultado.datos.msj != '0'){
                    moverFinalPagina();
                }      
            }else{
                Util.alertaB(resultado.datos);
            }            
        } 
    };

    new Ajex.Api({
        modelo: "PlanProduccion",
        metodo: "validar",
        data_in: {
            p_nombre : p_nombre_plan_produccion
        }
    },funcion); 
}


function mostrarBlkSubFase (actual) {
    $.each(DOM.blkSubFases, function(i,o){
        if (i < actual){  
          if (i == (actual - 1)){
            o.fadeIn("slow");
          } else{
            o.show();
          }
        } else{
          o.hide();
        }
    });
}

function listarPiezasActividadBloque (actual) {
    for (var i = 1; i <= actual; i++) {
        listarPiezasActividad(i);
    };
};

function listarActividadBloque (actual) {
    for (var i = 1; i <= actual; i++) {
        listarActividad(i);
    };
};

function mostrarElementos (actual) {
    for (var i = 0; i < actual; i++) {
        DOM.mfecha_inicio_actividad[i].show();
        DOM.mfecha_fin_actividad[i].show();
        DOM.fecha_inicio_actividad[i].hide();
        DOM.fecha_fin_actividad[i].hide();
        DOM.motivo_actividad[i].prop("readonly", true);
        DOM.colaborador_actividad[i].prop("readonly", true);
    };

    if (actual < 9){
        DOM.mfecha_inicio_actividad[actual].hide();
        DOM.mfecha_fin_actividad[actual].hide();    
    }
    
};

function limpiarRI(){
    DOM.precio.val("");
    DOM.importeNetoPieza.text("0.00");
    DOM.cantidad.val("");
    DOM.motivo.val("");
    DOM.detalle_pedido_pieza.html("");
    DOM.codigo_pieza.val("").select2({
           placeholder: "Seleccionar pieza",
    });
};

var log = function(r){
    console.log(r);
};

var tFloat= function(val,n){
    return parseFloat(val).toFixed(n ? n : 2);
}