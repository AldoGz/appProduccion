<?php

require_once '../datos/local_config.php';
require_once MODELO_FUNCIONES;
require_once '../modelo/Pago.clase.php';

if (!isset($_POST["p_array_datos"])) {
    Funciones::imprimeJSON(500, "Faltan parametros", "");
    exit();
}
parse_str($_POST["p_array_datos"], $datosFormulario);

try {
    $obj = new Pago(); 

    if (isset($_FILES["p_foto"])) {  
        $archivo = $obj->correlativo();        
        $tmp = str_replace(" ", "_", $_FILES["p_foto"]["tmp_name"]);
        move_uploaded_file($tmp, "../imagenes/voucher/$archivo");    
    }
    $obj->setCod_pedido($datosFormulario["txtcodigo_pedido"]);
    $obj->setNumero_cuenta($datosFormulario["txtnum_cuenta"]);
    $obj->setFecha_registro($datosFormulario["txtfecha_registro"]);
    $obj->setHora_registro($datosFormulario["txthora_registro"]);
    $obj->setNumero_operacion($datosFormulario["txtnum_operacion"]);
    $obj->setMonto_pagado($datosFormulario["txtmonto"]);      
    $obj->setVoucher($archivo);
      
    Funciones::imprimeJSON(200, "OK",$obj->agregar());
    
} catch (Exception $exc) {
    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}