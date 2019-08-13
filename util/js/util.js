'use strict';
var cAPIs = 0, c = 0;


var Ajex = {
   // var self = this;
    $Ld : $(".loader"),
    Loader : {  
        numAPIs: 0,     
        c  : 0, // contador de peticiones activas.        
        timer : 300,
        esMostrado :function(){
            return !(Ajex.$Ld.hasClass("off"));
        },
        quitar : function(){
           Ajex.$Ld.addClass("off"); 
        },
        mostrar : function(){
           Ajex.$Ld.removeClass("off"); 
        }
    },
    Api : function(params, callbackFunction, callbackFunctionError){    
            var self = this;
            this.tracer = false;
            // @  AM : INICIO
            // this.needParseJSON = 1;
            this.needParseJSON = 1; 
            // @ AM : FIN
            this.url = "../../controlador";
            this.ajax = $.ajax({
                url: self.url,
                data: params
            });
            Ajex.Loader.c++;
            this.id =  ++Ajex.Loader.numAPIs;

            if(self.tracer) console.log("INICIO PETICIÓN. ID:",self.id); 
        
            this.ajax
            .done( function(r){
                self.successCallback(r);
            })
            .fail( function(e){
                self.failCallback(e);
            });
               
            setTimeout(function(){              
              if(self.tracer) console.log("Verificador de DELAY: Estado de ID:",self.id); 
                if (self.ajax.readyState != 4){
                  if(self.tracer) console.log("DATA aún no ha llegado.:");
                  Ajex.Loader.mostrar();
                  return;
                }
              if(self.tracer) console.log("DATA ya había llegado. ");
            },Ajex.Loader.timer);
            
            this.back = function(){
                if (self.tracer) console.log("DATA llegó. ID: ", self.id);
                Ajex.Loader.c--;
                if (self.tracer) console.log("PETICIONES ACTIVAS ", Ajex.Loader.c);
                if (Util.loader.esMostrado()){
                    if (self.tracer) console.log("CARGANDO... Está en pantalla.");
                    if (Ajex.Loader.c <= 0){
                        if (self.tracer) console.log("No hay peticiones.");
                        Ajex.Loader.quitar();
                        return;
                    }    
                    return;
                }      
                if (self.tracer)  console.log("CARGANDO... Está fuera de pantalla");  
            };


            this.successCallback = function(success){
                self.back();
                console.log(success);
                if (  self.needParseJSON  == 0){

                    success = JSON.parse(success);    
                }
                callbackFunction(success); 
            }

            this.failCallback = function(error){
                Ajex.Loader.c--;
                Ajex.Loader.quitar();

                if (  self.needParseJSON  == 0){
                    error = JSON.parse(error);            
                }

                if (callbackFunctionError != undefined){
                    callbackFunctionError();
                } else {
                    var datosJSON = JSON.parse(error.responseText);
                    swal("Error", datosJSON.mensaje, "error");    
                }   

            }
        }
};

var Api = function(params, callbackFunction, callbackFunctionError){    
    var self = this;
    this.tracer = true;
    this.needParseJSON = 0; 
    this.url = "../controlador";
    this.ajax = $.ajax({
        url: self.url,
        data: params
    });
    Util.loader.c++;
    this.id =  ++cAPIs;

    if(self.tracer) console.log("INICIO PETICIÓN. ID:",self.id); 
    this.ajax
    .done( function(r){
        self.successCallback(r);
    })
    .fail( function(e){
        self.failCallback(e);
    });
       
    setTimeout(function(){
      if(self.tracer) console.log("Verificador de DELAY: Estado de ID:",self.id); 
        if (self.ajax.readyState != 4){
          if(self.tracer) console.log("DATA aún no ha llegado.:");
          Util.loader.mostrar();
          return;
        }
      if(self.tracer) console.log("DATA ya había llegado. ");
    },Util.loader.timer);

    
    this.back = function(){
        if (self.tracer) console.log("DATA llegó. ID: ", this.id);
        Util.loader.c--;
        if (self.tracer) console.log("PETICIONES ACTIVAS ", Util.loader.c);
        if (Util.loader.esMostrado()){
            if (self.tracer) console.log("CARGANDO... en pantalla.");
            if (Util.loader.c <= 0){
                if (self.tracer) console.log("No hay peticiones.");
                Util.loader.quitar();
                return;
            }    
            return;
        }      
        if (self.tracer)  console.log("CARGANDO... fuera de pantalla");  
    };


    this.successCallback = function(success){
        self.back();
              console.log(success);
        if (  self.needParseJSON  == 1){
            success = JSON.parse(success);    
        }
        callbackFunction(success);        
    }

    this.failCallback = function(error){
        if (  self.needParseJSON  == 1){
            error = JSON.parse(error);            
        }

        if (callbackFunctionError != undefined){
            callbackFunctionError();
        } else {
            var datosJSON = JSON.parse(error.responseText);
            swal("Error", datosJSON.mensaje, "error");    
        }        
    }

};

var Util = {
    $loader : $(".loader"),
    loader: {       
        c  : 0, // contador de peticiones activas.        
        timer : 1350,
        esMostrado :function(){
            return !(Util.$loader.hasClass("off"));
        },
        quitar : function(){
            Util.$loader.addClass("off"); 
        },
        mostrar : function(){
            Util.$loader.removeClass("off"); 
        }
    },
    apiBack: function(api){    
        console.log("api back", api.tempo);
         Util.loader.c--;
         console.log("PETICIONES ACTIVAS ", Util.loader.c);
        if (Util.loader.esMostrado()){
            console.log("esta mostrado");
            if (Util.loader.c <= 0){
                console.log("no hay peticiones");
                Util.loader.quitar();
                return;
            }    
            return;
        }      
        console.log("no estuvo mostrado");  
    },
    api: function (params) {      
        var ajax = $.ajax({
            url: '../controlador',
            data: params
        });
        Util.loader.c++;
        ajax.tempo = ++c;
        console.log("preparo api ",ajax.tempo); 

        setTimeout(function(){
            console.log("pregunta estado api",ajax.tempo); 
            if (ajax.readyState != 4){
                console.log("api sin hacer",ajax.tempo);
                Util.loader.mostrar();
                return;
            }
            console.log("api ya hecha",ajax.tempo);
        },1350);
        return ajax;
    },
    global: {
        IGV: 0.18,
        ISC: 0.20
    },
    soloNumeros: function (evento) {
        var tecla = (evento.which) ? evento.which : evento.keyCode;
        if ((tecla >= 48 && tecla <= 57)) {
            return true;
        }
        return false;
    },  
    soloDecimal: function(evento, cadena,mostrar){
        var tecla = (evento.which) ? evento.which : evento.keyCode;
        var key = cadena.length;
        var posicion = cadena.indexOf('.');
        var contador = 0;
        var numero = cadena.split(".");
        var resultado1 = numero[0];
        var suma = resultado1.length+mostrar; 

        while (posicion != -1) { 
            contador++;             
            posicion = cadena.indexOf('.', posicion + 1);

        }
        

        if ( (tecla>=48 && tecla<=57) || (tecla==46) ) {    
            if ( key == 0 &&  tecla == 46 ) { // SOLO PERMITE ENTRE 0 AL 9
                return false;
            }
            
            if (contador != 0 && tecla == 46) { //NO SE REPITA EL PUNTO                
                return false;
            }

            if ( cadena == '0') { // EL SIGUIENTE ES PUNTO   
                if ( tecla>=48 && tecla<=57 ) {
                    return false;
                }
                return true;                
            }      
            
            if (!(key <= suma)) {
                return false;
            }
            return true;            
        }
        return false;
    },
    soloLetras: function (evento, espacio=null) {
        var tecla = (evento.which) ? evento.which : evento.keyCode;
        if ( espacio != null ) {
            if ((tecla >= 65 && tecla <= 90) || (tecla >= 97 && tecla <= 122) || (tecla==241) || (tecla==209) ) {
                return true;
            }    
        }else{
            if ((tecla >= 65 && tecla <= 90) || (tecla >= 97 && tecla <= 122) || (tecla==241) || (tecla==209) || (tecla==32) ) {
                return true;
            } 
        }
        return false;
    },
    mostrarFecha: function (fecha) {
        var r = fecha.split("-");
        return r[2] + "/" + r[1] + "/" + r[0];
    },
    obtenerFecha : function(fecha){
        var r = fecha.split("/");
        return (r[2]+"-"+r[1]+"-"+r[0]);
    },
    obtenerTimestamp : function(){
        var now = new Date();

        var anio = now.getFullYear();

        var mes  = ((now.getMonth() + 1) < 10) ? ("0" + (now.getMonth() + 1)) : (now.getMonth() + 1);

        var dia  = ( now.getDate() < 10) ? ("0" + now.getDate()) : (now.getDate());

        var hora = ( now.getHours() < 10) ? ("0" + now.getHours()) : (now.getHours());

        var minutos = ((now.getMinutes() < 10) ? ("0" + now.getMinutes()) : (now.getMinutes()));

        var segundos  = ((now.getSeconds() < 10) ? ("0" + now.getSeconds()) : (now.getSeconds()));

        var time = (now.getTime());

        return ( dia + '/' + mes + '/' + anio + " " + hora + ':' + minutos + ':' + segundos +'.'+ time);
    },
    ordenarArreglo: function (a , codigo){
        var swapped;
        do {
            swapped = false;
            for (var i=0; i < a.length-1; i++) {
                if (a[i][codigo] < a[i+1][codigo]) {
                    var temp = a[i];
                    a[i] = a[i+1];
                    a[i+1] = temp;
                    swapped = true;
                }
            }
        } while (swapped);
        return a;
    },
    notificacion : function(entradas,funcion,texto=null,funcionNo){         
        var accion = texto != null ? texto+' el registro seleccionado?' : 'grabar los datos ingresados?';
        var n = new Noty({ 
          layout: 'bottomCenter',        
          text: '¿Esta seguro de '+accion,
          buttons: [            
            Noty.button('Si', 'btn btn-success', function () {
                new Ajex.Api(entradas,funcion);
                n.close();                  
            }),
            Noty.button('No', 'btn btn-error', function () {
                if (typeof funcionNo == "function"){
                    funcionNo();
                }
                n.close();
            })
          ]
        }).show();
    },
    alertaA : function (resultado){        
        new Noty({
            layout: 'top',
            type: 'success',
            text: resultado.msj,
            timeout: 5000,
            progressBar: true
        }).show();                     
    },
    alertaB : function(resultado){
        new Noty({
            layout: 'top',
            type: 'warning',
            text: resultado.msj.errorInfo[2],
            timeout: 5000,
            progressBar: true
        }).show(); 
    },
    alerta : function(p_type,p_text,p_time){
        new Noty({
            layout: 'top',
            type: p_type,
            text: p_text,
            timeout: p_time,
            progressBar: true
        }).show(); 
    },
};