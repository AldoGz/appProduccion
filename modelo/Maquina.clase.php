<?php

require_once '../datos/Conexion.clase.php';

class Maquina extends Conexion {
    private $cod_maquina;
    private $costo_hora_promedio;
    private $nombre;
    private $descripcion;
    private $estado_operatividad;
    private $estado_mrcb;

    private $tb = "maquina";

    public function getCod_maquina()
    {
        return $this->cod_maquina;
    }
     
    public function setCod_maquina($cod_maquina)
    {
        $this->cod_maquina = $cod_maquina;
        return $this;
    }

    public function getCosto_hora_promedio()
    {
        return $this->costo_hora_promedio;
    }
     
    public function setCosto_hora_promedio($costo_hora_promedio)
    {
        $this->costo_hora_promedio = $costo_hora_promedio;
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

    public function getDescripcion()
    {
        return $this->descripcion;
    }
     
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    public function getEstado_operatividad()
    {
        return $this->estado_operatividad;
    }
     
    public function setEstado_operatividad($estado_operatividad)
    {
        $this->estado_operatividad = $estado_operatividad;
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
            array(  "costo_hora_promedio_uso" =>$this->getCosto_hora_promedio(),
            		"nombre"=>$this->getNombre(),
                    "descripcion" =>$this->getDescripcion());

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
            array(  "costo_hora_promedio_uso" =>$this->getCosto_hora_promedio(),
                    "nombre"=>$this->getNombre(),
                    "descripcion" =>$this->getDescripcion());

            $campos_valores_where = 
            array(  "cod_maquina"=>$this->getCod_maquina());

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

            $texto = $this->getEstado_operatividad() != 'A' ? 
                'Se inactivado existosamente' : 'Se activado existosamente';
            
            
            $campos_valores = 
            array(  "estado_operatividad"=>$this->getEstado_operatividad());

            $campos_valores_where = 
            array(  "cod_maquina"=>$this->getCod_maquina());

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
            array(  "cod_maquina"=>$this->getCod_maquina());

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
            $sql = "SELECT * FROM $this->tb WHERE cod_maquina = :0";
            $resultado = $this->consultarFilas($sql, array($this->getCod_maquina()));
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function listar(){
        try {
            $sql = "SELECT * FROM $this->tb WHERE estado_mrcb=TRUE";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {            
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function cbListar(){
        try {
            $sql = "SELECT cod_maquina,nombre FROM $this->tb WHERE estado_mrcb=TRUE";
            $resultado =    $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {            
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }
}