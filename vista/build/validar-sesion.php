<?php
//VALIDAR INICIAR SESION
session_name("_sis_produccion_");
session_start();

if ( isset($_SESSION["usuario"]) ) {
    $respuesta = $_SESSION["rpt"];
    if ( $respuesta == 0 ) {
        header("Location:../pedido/");
        exit; //Detiene la ejecución de la página
    }else{
        header("Location:../aceptar_pedidos/");
        exit; //Detiene la ejecución de la página
    }
}