<?php

require_once '../datos/Conexion.clase.php';
class TipoFalla extends Conexion {
    private $cod_falla;
    private $nombre;
    private $descripcion;
    private $estado_mrcb;

    private $tb = "tipo_falla";

    public function getCod_falla()
    {
        return $this->cod_falla;
    }
     
    public function setCod_falla($cod_falla)
    {
        $this->cod_falla = $cod_falla;
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
            array(  "nombre"=>$this->getNombre(),
                    "descripcion" =>$this->getDescripcion());

            $campos_valores_where = 
            array(  "cod_falla"=>$this->getCod_falla());

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
            array(  "cod_falla"=>$this->getCod_falla());

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
            $sql = "SELECT * FROM $this->tb WHERE cod_falla = :0";
            $resultado = $this->consultarFilas($sql, array($this->getCod_falla()));
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
            $sql = "SELECT cod_falla,nombre FROM $this->tb  WHERE estado_mrcb=TRUE";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

}