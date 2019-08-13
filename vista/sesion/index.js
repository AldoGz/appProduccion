var DOM = {};

$(document).ready(function () {
    setDOM();
    setEventos();
    vista();


});

function setDOM() {
    DOM.form = $("#frm-sesion"),
    DOM.usuario = $("#txtusuario"),
    DOM.clave = $("#txtclave");
    /**/
    DOM.registrar = $("#frm-grabar"),
    DOM.self = $("#myModal"),
    DOM.btnRegistrar = $("#btnRegistrar"),

    DOM.tipo_documento = $("#txttipo_cliente"),
    DOM.documento = $("#txtdocumento"),
    DOM.razon_social = $("#txtrazon_social"),
    DOM.razon_comercial = $("#txtrazon_comercial"),
    DOM.telefono_movil = $("#txttelefono_movil"),
    DOM.correo = $("#txtemail"),
    DOM.direccion = $("#txtdireccion"),
    DOM.user = $("#txtuser_cliente"),
    DOM.user_clave = $("#txtclave_cliente"),
    DOM.nombres = $("#txtnombres"),
    DOM.apellidos = $("#txtapellidos");

    /**/   
}

function limpiar() {
    DOM.usuario.val("");
    DOM.clave.val("");
}

function limpiar2() {    
    DOM.documento.val("");
    DOM.razon_social.val("");
    DOM.nombres.val("");
    DOM.apellidos.val("");
    DOM.telefono_movil.val("");
    DOM.correo.val("");
    DOM.direccion.val("");  
}

function validar() {
    DOM.documento.keypress(function (e) {
        return Util.soloNumeros(e);
    });
    DOM.nombres.keypress(function (e) {
        return Util.soloLetras(e);
    });

    DOM.apellidos.keypress(function (e) {
        return Util.soloLetras(e);
    });
    DOM.telefono_movil.keypress(function (e) {
        return Util.soloNumeros(e);
    });
}




function setEventos() {
    validar();

    DOM.btnRegistrar.on("click", function () {
        DOM.self.find(".modal-title").text("Registrar nuevo cliente");
        limpiar2();
    });

    DOM.tipo_documento.on("change",function(){
        vista();
    });    
    
    DOM.form.submit(function (evento) {
        evento.preventDefault();

        var data_in = {            
            p_usuario : DOM.usuario.val(),
            p_clave : DOM.clave.val()
        };
           
        var funcion = function (resultado) {
            
            if (resultado.estado === 200) {
                switch(resultado.datos){
                    case 1:
                        document.location.href = "../pedido/";
                        break;
                    case 2:
                        document.location.href = "../plan_produccion/";
                        break;
                    case 3:
                        swal("Mensaje del sistema", "La clave no coince con este usuario", "warning");
                        limpiar();
                        break;
                    case 4:
                        swal("Mensaje del sistema", "Este usuario no se encuentra registrado", "warning");
                        limpiar();
                        break;
                    default:
                        swal("Mensaje del sistema", "Este usuario se encuentra inactivo para ingresar al sistema", "warning");   
                        limpiar();
                        break;
                } 
            } else {
                swal("Mensaje del sistema", resultado, "warning");
            }
        };

        new Ajex.Api({
            modelo: "Sesion",
            metodo: "inicioSesion",                            
            data_in: data_in
        }, funcion);
    });

    
    DOM.registrar.submit(function (evento) {
        evento.preventDefault();

        var funcion = function (resultado) {
            if (resultado.estado === 200) {
                if (resultado.datos.rpt === true) {                    
                    Util.alertaA(resultado.datos);
                    limpiar2(); 
                    DOM.self.modal("hide");                    
                }else{
                    Util.alertaB(resultado.datos);
                    DOM.self.modal("hide");          
                }                        
            } 
        };         
        var entradas = {
            modelo: "Cliente",
            metodo: "agregar",                            
            data_in: {
                p_cod_tipo_documento : DOM.tipo_documento.val(),
                p_nro_documento : DOM.documento.val(),
                p_razon_social : DOM.razon_social.val().toUpperCase(),
                p_nombres : DOM.nombres.val().toUpperCase(),
                p_apellidos : DOM.apellidos.val().toUpperCase(),
                p_celular : DOM.telefono_movil.val(),
                p_correo : DOM.correo.val(),
                p_direccion : DOM.direccion.val()
            },
            data_out : [DOM.user.val(), DOM.user_clave.val()]                            
        };
        Util.notificacion(entradas,funcion);     
    });        
}


function vista(){
    if ( DOM.tipo_documento.val() === '01') {
        DOM.documento.val("");
        DOM.razon_social.val("");
        $("#razon_social").hide();
        $("#nombres_apellidos").show();
        DOM.documento.attr("maxlength","8");
    }else{
        DOM.documento.val("");
        DOM.nombres.val("");
        DOM.apellidos.val("");
        $("#razon_social").show();
        $("#nombres_apellidos").hide();
        DOM.documento.attr("maxlength","11")
    }
}