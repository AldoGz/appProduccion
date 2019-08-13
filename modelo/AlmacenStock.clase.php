<?php

require_once '../datos/Conexion.clase.php';

class AlmacenStock extends Conexion {

    public function listar(){
        try {
            $sql = "SELECT 'PR'||RIGHT('0000'||pro.cod_producto,4) as codigo, pro.nombre as nombre, cantidad::int as cantidad, 
                    'UN'::varchar(3) as um 
                    FROM almacen_stock ast
                    INNER JOIN producto pro ON pro.cod_producto = ast.cod_producto
                    ";

           $productos = $this->consultarFilas($sql);

           $sql = "SELECT 'PI'||RIGHT('0000'||pz.cod_pieza,4) as codigo, pz.nombre as nombre, cantidad::int as cantidad, 
                    'UN'::varchar(3) as um 
                    FROM almacen_stock ast
                    INNER JOIN pieza pz ON pz.cod_pieza = ast.cod_pieza
                    ";

           $piezas = $this->consultarFilas($sql);


            $sql = "SELECT 'MP'||RIGHT('0000'||mp.cod_materia_prima,4) as codigo, mp.nombre as nombre,
                     cantidad,  um.abreviatura as um
                    FROM almacen_stock ast
                    INNER JOIN materia_prima mp ON mp.cod_materia_prima = ast.cod_materia_prima
                    INNER JOIN unidad_medida um ON um.cod_unidad_medida = mp.cod_unidad_medida
                    ";

           $mp = $this->consultarFilas($sql);

            return array("rpt"=>true,"data"=>array("productos"=>$productos,"piezas"=>$piezas,"mp"=>$mp));
        } catch (Exception $exc) {
            throw $exc;
        }
    }

 
}