<?php

require_once '../datos/local_config.php';
require_once MODELO_FUNCIONES;
require_once '../modelo/Producto.clase.php';

if (!isset($_POST["p_array_datos"])) {
    Funciones::imprimeJSON(500, "Falta parametros de formulario", "");
    exit();
}
parse_str($_POST["p_array_datos"], $datosFormulario);

try {
    $obj = new Producto(); 

    if (isset($_FILES["p_foto"])) {  
        $archivo = $obj->correlativo();        
        $tmp = str_replace(" ", "_", $_FILES["p_foto"]["tmp_name"]);
        move_uploaded_file($tmp, "../imagenes/productos/$archivo");    
    }
     
    $obj->setCod_producto($datosFormulario["txtcodigo_producto"]);
    $obj->setNombre($datosFormulario["txtnombre"]);
    $obj->setDescripcion($datosFormulario["txtdescripcion"]);
    $obj->setPrecio($datosFormulario["txtprecio"]);
    $img = empty( $archivo ) ? NULL :   $archivo;   
    $obj->setImg($img);

    $accion = $datosFormulario["txtoperacion"] == 'agregar' ? $obj->agregar($_POST["p_arreglo"]) : $obj->editar();
      
    Funciones::imprimeJSON(200, "OK",$accion);
    
} catch (Exception $exc) {
    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}