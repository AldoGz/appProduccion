<?php

require_once '../modelo/MateriaPrima.clase.php';
$obj = new MateriaPrima();

$valor_busqueda = $_GET["term"];

$resultado = $obj->buscar($valor_busqueda);

$datos = array();
for ($i=0; $i<count($resultado); $i++){
    $registro = array
            (
                "label" => $resultado[$i]["nombre"],
                "value" => array
                (
                    "codigo_materia_prima" => $resultado[$i]["cod_materia_prima"],
                    "nombre_materia_prima" => $resultado[$i]["nombre"]
                )
            );
    
    $datos[$i] = $registro;
}


echo json_encode($datos);
