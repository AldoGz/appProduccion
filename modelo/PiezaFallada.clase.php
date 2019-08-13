<?php

require_once '../datos/Conexion.clase.php';

class PiezaFallada extends Conexion {
    private $cod_pieza_fallada_actividad;
    private $cod_plan_produccion_pieza;
    private $descripcion_falla;
    private $cod_tipo_falla;
    private $estado_mrcb;
    private $total_piezas;

    private $tb = "pieza_fallada_actividad";
    private $tbp = "plan_produccion_pieza";

    public function getCod_pieza_fallada_actividad()
    {
        return $this->cod_pieza_fallada_actividad;
    }
    
    public function setCod_pieza_fallada_actividad($cod_pieza_fallada_actividad)
    {
        $this->cod_pieza_fallada_actividad = $cod_pieza_fallada_actividad;
        return $this;
    }

    public function getCod_plan_produccion_pieza()
    {
        return $this->cod_plan_produccion_pieza;
    }
    
    public function setCod_plan_produccion_pieza($cod_plan_produccion_pieza)
    {
        $this->cod_plan_produccion_pieza = $cod_plan_produccion_pieza;
        return $this;
    }

    public function getDescripcion_falla()
    {
        return $this->descripcion_falla;
    }
    
    public function setDescripcion_falla($descripcion_falla)
    {
        $this->descripcion_falla = $descripcion_falla;
        return $this;
    }

    public function getCod_tipo_falla()
    {
        return $this->cod_tipo_falla;
    }
    
    public function setCod_tipo_falla($cod_tipo_falla)
    {
        $this->cod_tipo_falla = $cod_tipo_falla;
        return $this;
    }

    public function getTotal_piezas()
    {
        return $this->total_piezas;
    }
    
    public function setTotal_piezas($total_piezas)
    {
        $this->total_piezas = $total_piezas;
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

    public function agregar(){
    	session_name("_sis_produccion_");
        session_start();   
    	$this->beginTransaction();
        try {
            $campos_valores = 
                array(  "cod_plan_produccion_pieza"=>$this->getCod_plan_produccion_pieza(),
                        "descripcion_falla"=>$this->getDescripcion_falla(),
                        "cod_tipo_falla"=>$this->getCod_tipo_falla(),
                        "total_piezas"=>$this->getTotal_piezas(),
                        "cod_usuario_registro"=>$_SESSION["cod_usuario"]);

            $this->insert($this->tb, $campos_valores);

            $sql1 = "SELECT piezas_buenas as buenas, piezas_falladas as malas FROM plan_produccion_pieza WHERE cod_plan_produccion_pieza=:0";
            $piezas = $this->consultarFila($sql1, array($this->getCod_plan_produccion_pieza()));
  
            $pb = (int)$piezas["buenas"]-(int)$this->getTotal_piezas();
            $pm = (int)$piezas["malas"]+(int)$this->getTotal_piezas();

            $campos_valores1 = 
            array(  "piezas_buenas"=>$pb,
                    "piezas_falladas"=>$pm);

            $campos_valores_where1 = 
            array(  "cod_plan_produccion_pieza"=>$this->getCod_plan_produccion_pieza());

            $this->update($this->tbp, $campos_valores1,$campos_valores_where1);

            $this->commit();
            return array("rpt"=>true,"msj"=>"Se retirado exitosamente");

        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }



}