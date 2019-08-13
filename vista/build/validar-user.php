<?php
//VALIDAR USER
session_name("_sis_produccion_");
session_start();

if ( !isset($_SESSION["usuario"]) ) {
    header("Location:../sesion/");
    exit; //Detiene la ejecución de la página    
}