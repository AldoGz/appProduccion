<?php

require_once '../datos/Conexion.clase.php';

class Ubigeo extends Conexion {
    

    public function llenarDepartamento(){
        try {
            $sql = "SELECT codigo_departamento, nombre FROM departamento";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function llenarProvincia($p1){
        try {
            $sql = "SELECT codigo_provincia, nombre FROM provincia WHERE codigo_departamento = :0";
            $resultado = $this->consultarFilas($sql,array($p1));
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function llenarDistrito($p1){
        try {
            $sql = "SELECT codigo_distrito,nombre FROM distrito WHERE codigo_provincia = :0";
            $resultado = $this->consultarFilas($sql,array($p1));
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

}