var DOM = {};

$(document).ready(function () {
    setDOM();
    setEventos();
    listar();
    cargarUnidad();
    
});

function setDOM() {
    DOM.form = $("#frm-grabar"),
    DOM.listado = $("#listado"),
    DOM.self = $("#myModal"),
    DOM.btnAgregar = $("#btnAgregar"),
    DOM.comboEstado = $("#cboEstado"),
    DOM.operacion = $("#txtoperacion"),
    DOM.codigo_materia_prima = $("#txtcodigo_materia_prima"),
    DOM.nombre = $("#txtnombre"),    
    DOM.descripcion = $("#txtdescripcion"),
    DOM.precio = $("#txtprecio"),
    DOM.unidad_medida = $("#txtunidad_medida");
}

function limpiar() {
    DOM.codigo_materia_prima.val("");    
    DOM.nombre.val("");
    DOM.descripcion.val("");
    DOM.precio.val("");
}

function validar() {
    DOM.nombre.keypress(function (e) {
        return Util.soloLetras(e);
    });
    
    DOM.precio.keypress(function (e) {
        var valor = DOM.precio.val();
        return Util.soloDecimal(e,valor,2);
    });
}

function setEventos() {
    validar();

    DOM.btnAgregar.on("click", function () {
        DOM.self.find(".modal-title").text("Agregar nuevo materia prima");
        DOM.operacion.val("agregar");
        limpiar();
    });

    DOM.comboEstado.change("click", function () {
        listar();
    });


    DOM.form.submit(function (evento) {
        evento.preventDefault();

        if ( parseFloat(DOM.precio.val()) >= 0.000 && parseFloat(DOM.precio.val()) <= 0.009) {
            Util.alerta('warning','El precio debe ser mayor de cero',2000);
            return 0;
        };

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
        var entradas = {
            modelo: "MateriaPrima",
            metodo: DOM.operacion.val(),                            
            data_in: {
                p_cod_materia_prima : DOM.codigo_materia_prima.val(),    
                p_nombre : DOM.nombre.val().toUpperCase(),
                p_descripcion : DOM.descripcion.val().toUpperCase(),
                p_precio_base : parseFloat(DOM.precio.val()).toFixed(2),
                p_cod_unidad_medida : DOM.unidad_medida.val()                            
            }                            
        };
        Util.notificacion(entradas,funcion);
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
                html += '<th style="text-align: center">Nombre</th>';
                html += '<th style="text-align: center">Descripción</th>';
                html += '<th style="text-align: center">Precio</th>';            
                html += '<th style="text-align: center">Unidad medida</th>'; 
                html += '<th style="text-align: center">OPCIONES</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';                
                $.each(resultado.datos.msj, function (i, item) {                 
                    html += '<tr>';
                    html += '<td align="center">' + item.cod_materia_prima + '</td>';                
                    html += '<td align="center">' + item.nombre + '</td>';
                    html += '<td align="center">' + item.descripcion + '</td>';
                    html += '<td align="center">' + item.precio_base + '</td>'; 
                    html += '<td align="center">' + item.abreviatura + '</td>'; 
                    html += '<td align="center">';
                    html += '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal" onclick="editar(' + item.cod_materia_prima + ')" title="Editar"><i class="fa fa-pencil"></i></button>';
                    html += '&nbsp;&nbsp;';

                    if ( item.cod_materia_prima > 4 ) {
                        html += '<button type="button" class="btn btn-xs btn-danger" onclick="darBaja(' + item.cod_materia_prima + ',' + "'false'" + ')" title="Eliminar"><i class="fa fa-times"></i></button>';                
                        html += '&nbsp;&nbsp;';
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
        modelo: "MateriaPrima",
        metodo: "listar"
    }, funcion);
}

function editar(p_codigo) {
    DOM.self.find(".modal-title").text("Editar materia prima");
    DOM.operacion.val("editar");
    
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                $.each(resultado.datos.msj, function (i, item) {
                    DOM.codigo_materia_prima.val(item.cod_materia_prima);
                    DOM.nombre.val(item.nombre);
                    DOM.descripcion.val(item.descripcion);
                    DOM.precio.val(item.precio_base);
                    DOM.unidad_medida.val(item.cod_unidad_medida);
                });
            }else{
                Util.alertaB(resultado.datos);
            }
        } 
    };
    new Ajex.Api({
        modelo: "MateriaPrima",
        metodo: "leerDatos",
        data_in: {
            p_cod_materia_prima: p_codigo
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
        modelo: "MateriaPrima",
        metodo: "habilitar",
        data_in: {
            p_cod_materia_prima: p_codigo,
            p_estado_mrcb: p_estado
        } 
    }; 
    Util.notificacion(entradas,funcion, texto);
}

function cargarUnidad(){
    var funcion = function (resultado) {
        console.log("cargar");
        console.log(resultado);
        if (resultado.estado === 200) {
            if ( resultado.datos.rpt === true ) {
                var datos = resultado.datos.msj;
                var html = "";
                for (var i = 0; i < datos.length; i++) {
                    html = html + "<option value=" + datos[i].cod_unidad_medida + ">" + datos[i].nombre+  "</option>";
                }
                DOM.unidad_medida.html(html);
            }else{
                Util.alertaB(resultado.datos);
            }
        }
    };

    new Ajex.Api({
        modelo : "UnidadMedida",
        metodo : "cbListar"
    }, funcion);
}
