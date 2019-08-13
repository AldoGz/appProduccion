<?php

require_once '../datos/Conexion.clase.php';


class RequisitoInterno extends Conexion {
    private $cod_requisito_interno;
    private $cod_plan_produccion;
    private $costo_total;
    private $motivo;
    private $estado_mrcb;

    private $tb = "requisito_interno";
    private $tb_rip = "requisito_interno_pieza";

    public function getCod_requisito_interno()
    {
        return $this->cod_requisito_interno;
    }
     
    public function setCod_requisito_interno($cod_requisito_interno)
    {
        $this->cod_requisito_interno = $cod_requisito_interno;
        return $this;
    }

    public function getCod_plan_produccion()
    {
        return $this->cod_plan_produccion;
    }
     
    public function setCod_plan_produccion($cod_plan_produccion)
    {
        $this->cod_plan_produccion = $cod_plan_produccion;
        return $this;
    }

    public function getCosto_total()
    {
        return $this->costo_total;
    }
     
    public function setCosto_total($costo_total)
    {
        $this->costo_total = $costo_total;
        return $this;
    }

    public function getMotivo()
    {
        return $this->motivo;
    }
     
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
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
 
    public function agregar($p1, $p2) {
        /*p1 = json, p2 = nombre_PP*/
        session_name("_sis_produccion_");
        session_start();       
        $this->beginTransaction();
        try {
            $sql = "SELECT cod_plan_produccion FROM plan_produccion WHERE nombre = :0";
            $codigo_plan_produccion =  $this->consultarValor($sql, array($p2));
            
            $campos_valores = 
            array(  "cod_plan_produccion"=>$codigo_plan_produccion,
                    "costo_total" =>$this->getCosto_total(),
                    "motivo"=>$this->getMotivo(),
                    "cod_usuario_registro"=>$_SESSION["cod_usuario"]);

            $this->insert($this->tb, $campos_valores);

            $arreglo = json_decode($p1);

            $sql2 = "SELECT MAX(cod_requisito_interno) FROM $this->tb";
            $cod_requisito_interno = $this->consultarValor($sql2);            

            for ($i=0; $i <count($arreglo) ; $i++) { 
                $item = $arreglo[$i];

                $campos_valores1 = 
                array(  "cod_requisito_interno"=>$cod_requisito_interno,
                        "cod_pieza"=>$item->p_codigo_pieza,           
                        "costo_unitario"=>$item->p_precio,
                        "cantidad"=>$item->p_cantidad,
                        "importe"=>$item->p_importe);

                $this->insert($this->tb_rip, $campos_valores1);
            }

            $this->commit();
            return array("rpt"=>true,"msj"=>"Se registrado correctamente");

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
            array(  "cod_requisito_interno"=>$this->getCod_requisito_interno());

            $this->update($this->tb, $campos_valores,$campos_valores_where); 

            $campos_valores2 = 
            array(  "estado_mrcb"=>$this->getEstado_mrcb());

            $campos_valores_where2 = 
            array(  "cod_requisito_interno"=>$this->getCod_requisito_interno());

            $this->update($this->tb_rip, $campos_valores2,$campos_valores_where2);
            
            $this->commit();
            return array("rpt"=>true,"msj"=>"Se anulado existosamente");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }        
    }
    
    public function listar($p_plan_produccion){
        try {
            $sql = "SELECT 
                        'RI'||RIGHT('0000'||ri.cod_requisito_interno, 4) as cod_requisito_interno,
                        ri.cod_requisito_interno as codigo,
                        ri.motivo,
                        ri.costo_total,
                         CASE WHEN pp.estado_fase IS NULL THEN -1 ELSE pp.estado_fase END
                    FROM requisito_interno ri 
                            INNER JOIN plan_produccion pp ON ri.cod_plan_produccion = pp.cod_plan_produccion
                    WHERE ri.estado_mrcb=TRUE AND pp.nombre=:0";
            $resultado = $this->consultarFilas($sql, array($p_plan_produccion));
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function ver(){
        try {
            $sql = "SELECT 
                        'RP'||RIGHT('0000'||rp.cod_requisito_interno_pieza, 4) as cod_requisito_interno_pieza,
                        p.nombre,
                        rp.costo_unitario,
                        rp.cantidad,
                        rp.importe
                    FROM requisito_interno_pieza rp INNER JOIN pieza p ON rp.cod_pieza = p.cod_pieza
                    WHERE cod_requisito_interno=:0
                    ORDER BY 1";
            $resultado = $this->consultarFilas($sql, array($this->getCod_requisito_interno()));
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

}