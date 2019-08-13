<?php

require_once '../datos/Conexion.clase.php';

class Almacen extends Conexion {
    private $cod_almacen;
    private $nombre;
    private $direccion;
    private $estado_mrcb;

    private $tb = "almacen";

    public function getCod_almacen()
    {
        return $this->cod_almacen;
    }
     
    public function setCod_almacen($cod_almacen)
    {
        $this->cod_almacen = $cod_almacen;
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

    public function getDireccion()
    {
        return $this->direccion;
    }
     
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
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
                    "direccion" =>$this->getDireccion());

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
                    "direccion" =>$this->getDireccion());

            $campos_valores_where = 
            array(  "cod_almacen"=>$this->getCod_almacen());

            $this->update($this->tb, $campos_valores,$campos_valores_where);

            $this->commit();
            return array("rpt"=>true,"msj"=>"Se actualizado exitosamente");
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
            array(  "cod_almacen"=>$this->getCod_almacen());

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
            $sql = "SELECT * FROM $this->tb WHERE cod_almacen = :0";
            $resultado = $this->consultarFilas($sql, array($this->getCod_almacen()));
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            $this->rollBack();
            throw $exc;
        }
    }

    public function listar(){
        try {
            $sql = "SELECT * FROM $this->tb  WHERE estado_mrcb=TRUE";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function cbListar(){
        try {
            $sql = "SELECT cod_almacen,nombre FROM $this->tb  WHERE estado_mrcb=TRUE";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            throw $exc;
        }
    }
}