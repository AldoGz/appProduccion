<?php

require_once '../datos/local_config.php';
require_once MODELO_FUNCIONES;
require_once '../modelo/Pedido.clase.php';

if (!isset($_POST["p_array_datos"])) {
    Funciones::imprimeJSON(500, "Faltan parametros", "");
    exit();
}
parse_str($_POST["p_array_datos"], $datosFormulario);

<pre>
 $datosFormulario;
</pre>
/*
try {
    if (isset($_FILES["p_foto"])) {  
        $archivo = str_replace(" ", "_", $_FILES["p_foto"]["name"]);        
        $tmp = str_replace(" ", "_", $_FILES["p_foto"]["tmp_name"]);
        move_uploaded_file($tmp, "../imagenes/productos/$archivo");    
    }
    $obj = new Producto();  
    $obj->setCod_producto($datosFormulario["txtcodigo_producto"]);
    $obj->setNombre($datosFormulario["txtnombre"]);
    $obj->setDescripcion($datosFormulario["txtdescripcion"]);
    $img = empty( $archivo ) ? NULL :   $archivo;   
    $obj->setImg($img);


    $accion = $datosFormulario["txtoperacion"] == 'agregar' ? $obj->agregar() : $obj->editar();
  
    
    
    Funciones::imprimeJSON(200, "OK",$accion);
    
} catch (Exception $exc) {
    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}*/