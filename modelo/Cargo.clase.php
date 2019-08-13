<?php

require_once '../datos/Conexion.clase.php';

class Cargo extends Conexion {
    private $cod_cargo;
    private $nombre;
    private $estado;
    private $estado_mrcb;

    private $tb = "cargo";

    public function getCod_cargo()
    {
        return $this->cod_cargo;
    }
     
    public function setCod_cargo($cod_cargo)
    {
        $this->cod_cargo = $cod_cargo;
        return $this;
    }

    public function getNombre()
    {
        return $this->nombre;
    }
     
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getEstado()
    {
        return $this->estado;
    }
     
    public function setEstado($estado)
    {
        $this->estado = $estado;
        return $this;
    }

    public function getEstado_mrcb()
    {
        return $this->estado_mrcb;
    }
     
    public function setEstado_mrcb($estado_mrcb)
    {
        $this->estado_mrcb = $estado_mrcb;
        return $this;
    }

    public function agregar() {      
        $this->beginTransaction();
        try {            
            $campos_valores = 
            array(  "nombre"=>$this->getNombre(),
                    "estado"=>$this->getEstado());

            $this->insert($this->tb, $campos_valores);

            $this->commit();
            return array("rpt"=>true,"msj"=>"Se agregado exitosamente");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
        
    }

    public function editar() {
        $this->beginTransaction();
        try {            
            $campos_valores = 
            array(  "nombre"=>$this->getNombre(),
                    "estado"=>$this->getEstado());

            $campos_valores_where = 
            array(  "cod_cargo"=>$this->getCod_cargo());

            $this->update($this->tb, $campos_valores,$campos_valores_where);

            $this->commit();
            return array("rpt"=>true,"msj"=>"Se actualizado exitosamente");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }

    public function status() {
        $this->beginTransaction();
        try {
            $texto = $this->getEstado() != 'A' ? 
                'Se inactivado existosamente' : 'Se activado existosamente';
            
            $campos_valores = 
            array(  "estado"=>$this->getEstado());

            $campos_valores_where = 
            array(  "cod_cargo"=>$this->getCod_cargo());

            $this->update($this->tb, $campos_valores,$campos_valores_where);

            $this->commit();
            return array("rpt"=>true,"msj"=>$texto);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }

    public function habilitar() {
        $this->beginTransaction();
        try {            
            $campos_valores = 
            array(  "estado_mrcb"=>$this->getEstado_mrcb());

            $campos_valores_where = 
            array(  "cod_cargo"=>$this->getCod_cargo());

            $this->update($this->tb, $campos_valores,$campos_valores_where);

            $this->commit();
            return array("rpt"=>true,"msj"=>"Se anulado existosamente");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }
    

    public function leerDatos(){
        try {
            $sql = "SELECT * FROM $this->tb WHERE cod_cargo = :0";
            $resultado = $this->consultarFilas($sql, array($this->getCod_cargo()));
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function listar(){
        try {
            $sql = "SELECT * FROM $this->tb WHERE estado_mrcb=TRUE AND cod_cargo>0";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function cbListar(){
        try {
            $sql = "SELECT cod_cargo,nombre FROM $this->tb WHERE estado_mrcb=TRUE AND estado='A'";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

}