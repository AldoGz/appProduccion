var DOM = {};

$(document).ready(function () {
    setDOM();
    setEventos();
    listar();
    
});

function setDOM() {
    DOM.form = $("#frm-grabar"),
    DOM.listado = $("#listado"),
    DOM.self = $("#myModal"),
    DOM.btnAgregar = $("#btnAgregar"),
    DOM.comboEstado = $("#cboEstado"),
    DOM.operacion = $("#txtoperacion"),
    DOM.codigo_maquina = $("#txtcodigo_maquina"),    
    DOM.nombre = $("#txtnombre"),
    DOM.descripcion = $("#txtdescripcion"),
    DOM.costo = $("#txtcosto");
}

function limpiar() {
    DOM.codigo_maquina.val("");    
    DOM.nombre.val("");
    DOM.descripcion.val("");
    DOM.costo.val("");
}

function validar() {
    DOM.nombre.keypress(function (e) {
        return Util.soloLetras(e);
    });
    DOM.costo.keypress(function(e){
        return Util.soloNumeros(e);
    });
}


function setEventos() {
    validar();

    DOM.btnAgregar.on("click", function () {
        DOM.self.find(".modal-title").text("Agregar nuevo maquina");
        DOM.operacion.val("agregar");
        limpiar();
    });

    DOM.comboEstado.change("click", function () {
        listar();
    });


    DOM.form.submit(function (evento) {
        evento.preventDefault();

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
            modelo: "Maquina",
            metodo: DOM.operacion.val(),                            
            data_in: {
                p_cod_maquina : DOM.codigo_maquina.val(),    
                p_nombre : DOM.nombre.val().toUpperCase(),
                p_descripcion : DOM.descripcion.val().toUpperCase(),
                p_costo_hora_promedio : DOM.costo.val()
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
                html += '<th style="text-align: center">Costo uso</th>';            
                html += '<th style="text-align: center">OPCIONES</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';                
                $.each(resultado.datos.msj, function (i, item) {                 
                    html += '<tr>';
                    html += '<td align="center">' + (i+1) + '</td>';                
                    html += '<td align="center">' + item.nombre + '</td>';
                    html += '<td align="center">' + item.costo_hora_promedio_uso + '</td>';              
                    html += '<td align="center">';
                    html += '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal" onclick="editar(' + item.cod_maquina + ')" title="Editar"><i class="fa fa-pencil"></i></button>';
                    html += '&nbsp;&nbsp;';

                    var tmpEstado = item.estado_operatividad != "A"?
                            {icon: "up", title: "Habilitar", bol: "A", boton: "btn-warning"} :
                            {icon: "down", title: "Deshabilitar", bol: "I", boton: "btn-dark    "};

                    html += '<button type="button" class="btn btn-xs ' + tmpEstado.boton + '" onclick="operatividad(' + item.cod_maquina + ',' + "'" + tmpEstado.bol + "'" + ')" title="' + tmpEstado.title + '"><i class="fa fa-thumbs-o-' + tmpEstado.icon + '"></i></button>';                
                    html += '&nbsp;&nbsp;';

                    html += '<button type="button" class="btn btn-xs btn-danger" onclick="darBaja(' + item.cod_maquina + ','  + "'false'" + ')" title="Eliminar"><i class="fa fa-times"></i></button>';                
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
        modelo: "Maquina",
        metodo: "listar"
    }, funcion);
}

function editar(p_codigo) {
    DOM.self.find(".modal-title").text("Editar maquina");
    DOM.operacion.val("editar");

    var funcion = function (resultado) {
        console.log("editar");
        console.log(resultado);
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                $.each(resultado.datos.msj, function (i, item) {
                    DOM.codigo_maquina.val(item.cod_maquina);
                    DOM.nombre.val(item.nombre);
                    DOM.descripcion.val(item.descripcion);
                    DOM.costo.val(item.costo_hora_promedio_uso);
                });
            }else{
                Util.alertaB(resultado.datos);
            }
        } 
    };
    
    new Ajex.Api({
        modelo: "Maquina",
        metodo: "leerDatos",
        data_in: {
            p_cod_maquina: p_codigo
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
        modelo: "Maquina",
        metodo: "habilitar",
        data_in: {
            p_cod_maquina: p_codigo,
            p_estado_mrcb: p_estado
        } 
    }; 
    Util.notificacion(entradas,funcion, texto);    
}

function operatividad(p_codigo, p_estado) {
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
        modelo: "Maquina",
        metodo: "status",
        data_in: {
            p_cod_maquina: p_codigo,
            p_estado_operatividad: p_estado
        } 
    }; 
    Util.notificacion(entradas,funcion, texto);    
}