<?php

require_once '../datos/Conexion.clase.php';

class VariablesConstantes extends Conexion {
    private $cod_constante;
    private $descripcion;
    private $informacion;
    private $valor;
    private $estado_mrcb;

    private $tb = "variables_constantes";

    public function getCod_constante()
    {
        return $this->cod_constante;
    }
     
    public function setCod_constante($cod_constante)
    {
        $this->cod_constante = $cod_constante;
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

    public function getInformacion()
    {
        return $this->informacion;
    }
    
    
    public function setInformacion($informacion)
    {
        $this->informacion = $informacion;
        return $this;
    }

    public function getValor()
    {
        return $this->valor;
    }
     
    public function setValor($valor)
    {
        $this->valor = $valor;
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
            array(  "descripcion"=>$this->getDescripcion(),
                    "informacion"=>$this->getInformacion(),
                    "valor"=>$this->getValor());

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
                    "informacion"=>$this->getInformacion(),
                    "valor"=>$this->getValor());

            $campos_valores_where = 
            array(  "cod_constante"=>$this->getCod_constante());

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
           array(  "cod_constante"=>$this->getCod_constante());

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
            $sql = "SELECT * FROM $this->tb WHERE cod_constante = :0";
            $resultado = $this->consultarFilas($sql, array($this->getCod_constante()));
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function listar(){
        try {
            $sql = "SELECT * FROM variables_constantes WHERE estado_mrcb=TRUE";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function mostrarVariables(){
        try {
            $sql = "SELECT * FROM variables_constantes WHERE cod_constante = 15";
            $piedra = $this->consultarFila($sql);
            $sql = "SELECT * FROM variables_constantes WHERE cod_constante = 14";
            $carbon = $this->consultarFila($sql);
            $sql = "SELECT * FROM variables_constantes WHERE cod_constante = 13";
            $lenio = $this->consultarFila($sql);

            $resultado["v1"] = $piedra;
            $resultado["v2"] = $carbon;
            $resultado["v3"] = $lenio;

            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

}