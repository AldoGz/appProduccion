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
    DOM.codigo_perfil = $("#txtcodigo_perfil"),
    DOM.descripcion = $("#txtdescripcion"),
    DOM.estado = $("#txtestado");    
}

function limpiar() {
    DOM.codigo_perfil.val("");
    DOM.descripcion.val("");
}

function validar() {
    DOM.descripcion.keypress(function (e) {
        return Util.soloLetras(e);
    });

}


function setEventos() {
    validar();

    DOM.btnAgregar.on("click", function () {
        DOM.self.find(".modal-title").text("Agregar nuevo perfil");
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
                    limpiar(); 
                    listar();                           
                    DOM.self.modal("hide");                    
                }else{
                    Util.alertaB(resultado.datos);
                    DOM.self.modal("hide")          
                }                        
            } 
        };         
        var entradas = {
            modelo: "Perfil",
            metodo: DOM.operacion.val(), 
            data_in :{
                p_cod_perfil : DOM.codigo_perfil.val(),
                p_nombre : DOM.descripcion.val().toUpperCase(),
                p_estado : DOM.estado.val()
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
                html += '<th style="text-align: center">Descripción</th>';
                html += '<th style="text-align: center">OPCIONES</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
                
                $.each(resultado.datos.msj, function (i, item) {                 
                    html += '<tr>';
                    html += '<td align="center">' + (i+1) + '</td>';                
                    html += '<td align="center">' + item.nombre + '</td>';                

                    html += '<td align="center">';
                    html += '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal" onclick="editar(' + item.cod_perfil + ')" title="Editar"><i class="fa fa-pencil"></i></button>';
                    html += '&nbsp;&nbsp;';

                    var tmpEstado = item.estado != "A"?
                            {icon: "up", title: "Habilitar", bol: "A", boton: "btn-warning"} :
                            {icon: "down", title: "Deshabilitar", bol: "I", boton: "btn-dark"};

                    html += '<button type="button" class="btn btn-xs ' + tmpEstado.boton + '" onclick="estado(' + item.cod_perfil + ',' + "'" + tmpEstado.bol + "'" + ')" title="' + tmpEstado.title + '"><i class="fa fa-thumbs-o-' + tmpEstado.icon + '"></i></button>';                
                    html += '&nbsp;&nbsp;';

                    html += '<button type="button" class="btn btn-xs btn-danger" onclick="darBaja(' + item.cod_perfil + ',' +  "'false'" +')" title="Eliminar"><i class="fa fa-times"></i></button>';                
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
        modelo: "Perfil",
        metodo: "listar"
    }, funcion);
}

function editar(p_codigo) {    
    DOM.self.find(".modal-title").text("Editar perfil");
    DOM.operacion.val("editar");
    
    var funcion = function (resultado) {
        if (resultado.estado === 200) {
            if (resultado.datos.rpt === true) {
                $.each(resultado.datos.msj, function (i, item) {
                    DOM.codigo_perfil.val(item.cod_perfil);
                    DOM.descripcion.val(item.nombre);
                });
            }else{
                Util.alertaB(resultado.datos);
            }
        } 
    };
    new Ajex.Api({
        modelo: "Perfil",
        metodo: "leerDatos",
        data_in: {
            p_cod_perfil : p_codigo
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
        modelo: "Perfil",
        metodo: "habilitar",
        data_in: {
            p_cod_perfil : p_codigo,
            p_estado_mrcb : p_estado
        } 
    }; 
    Util.notificacion(entradas,funcion, texto); 
}


function estado(p_codigo, p_estado) {
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
        modelo: "Perfil",
        metodo: "status",
        data_in: {
            p_cod_perfil : p_codigo,
            p_estado : p_estado
        }  
    };
    Util.notificacion(entradas,funcion,texto); 
}