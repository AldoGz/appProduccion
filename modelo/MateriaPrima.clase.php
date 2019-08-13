<?php

require_once '../datos/Conexion.clase.php';

class MateriaPrima extends Conexion {
    private $cod_materia_prima;
    private $descripcion;
    private $nombre;
    private $precio_base;
    private $estado_mrcb;
    private $cod_unidad_medida;

    private $tb = "materia_prima";

    public function getCod_materia_prima()
    {
        return $this->cod_materia_prima;
    }
     
    public function setCod_materia_prima($cod_materia_prima)
    {
        $this->cod_materia_prima = $cod_materia_prima;
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

    public function getNombre()
    {
        return $this->nombre;
    }
     
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getPrecio_base()
    {
        return $this->precio_base;
    }
     
    public function setPrecio_base($precio_base)
    {
        $this->precio_base = $precio_base;
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

    public function getCod_unidad_medida()
    {
        return $this->cod_unidad_medida;
    }
     
    public function setCod_unidad_medida($cod_unidad_medida)
    {
        $this->cod_unidad_medida = $cod_unidad_medida;
        return $this;
    }


    public function agregar() {        
        $this->beginTransaction();
        try {            
            $campos_valores = 
            array(  "descripcion"=>$this->getDescripcion(),
                    "nombre"=>$this->getNombre(),
                    "precio_base"=>$this->getPrecio_base(),
                    "cod_unidad_medida"=>$this->getCod_unidad_medida());

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
            array(  "descripcion"=>$this->getDescripcion(),
                    "nombre"=>$this->getNombre(),
                    "precio_base"=>$this->getPrecio_base(),
                    "cod_unidad_medida"=>$this->getCod_unidad_medida());

            $campos_valores_where = 
            array(  "cod_materia_prima"=>$this->getCod_materia_prima());

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
            array(  "cod_materia_prima"=>$this->getCod_materia_prima());

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
            $sql = "SELECT * FROM $this->tb WHERE cod_materia_prima = :0";
            $resultado = $this->consultarFilas($sql, array($this->getCod_materia_prima()));
            return array("rpt"=>true,"msj"=>$resultado); 
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function listar(){
        try {
            $sql = "SELECT mp.*,um.abreviatura 
                    FROM materia_prima mp 
                        INNER JOIN unidad_medida um ON mp.cod_unidad_medida = um.cod_unidad_medida 
                    WHERE mp.estado_mrcb=TRUE";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado); 
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function cbListar(){
        try {
            $sql = "SELECT cod_materia_prima,nombre FROM $this->tb WHERE estado_mrcb=TRUE AND cod_materia_prima > 4";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado); 
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function buscar($p_nombre){
        try {
            $sql = "SELECT cod_materia_prima,nombre FROM $this->tb WHERE nombre LIKE '%'||:0||'%' AND estado_mrcb=TRUE";
            $resultado = $this->consultarFilas($sql, array($p_nombre));
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

}