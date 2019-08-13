<?php

require_once '../modelo/Cliente.clase.php';
$obj = new Cliente();

$valor_busqueda = $_GET["term"];

$resultado = $obj->buscar($valor_busqueda);

$datos = array();
for ($i=0; $i<count($resultado); $i++){
    $registro = array
            (
                "label" => $resultado[$i]["documento"],
                "value" => array
                (
                    "codigo" => $resultado[$i]["codigo"],
                    "documento" => $resultado[$i]["documento"],
                    "cliente" => $resultado[$i]["cliente"]
                )
            );
    
    $datos[$i] = $registro;
}


echo json_encode($datos);
