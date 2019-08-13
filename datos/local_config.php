<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//SERVIDOR
 define("MODELO", "../modelo");
 define("MODELO_UTIL", MODELO."/util");
 define("MODELO_WEBSERVICE",MODELO_UTIL."/WebService.php");
 define("MODELO_FUNCIONES",MODELO_UTIL."/Funciones.php");
 define("MODELO_VISTA","../../modelo/Util/Vista.php");
 
 //VISTA
 define("LIB", "../../lib/");
 define("UTIL", "../../util/");
 define("IMG", "../../imagenes/");
 
 //SESION
 define("SESION","_sis_produccion_");
//define("C_REPO_LOCAL","almacen_facturas");

 require_once ("empresa_config.php");