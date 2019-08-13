<?php

require_once '../datos/Conexion.clase.php';


class Actividad extends Conexion {
    private $cod_actividad;
    private $nombre;
    private $cantidad_hombres_base;
    private $costos_indirectos;
    private $numero_orden;
    private $costo_hora_hombre_base;
    private $cantidad_horas_maquina_base;
    private $cod_maquina_usada;
    private $descripcion_costos_indirectos;
    private $estado_mrcb;

    private $tb = "actividad";

    public function getCod_actividad()
    {
        return $this->cod_actividad;
    }
     
    public function setCod_actividad($cod_actividad)
    {
        $this->cod_actividad = $cod_actividad;
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

    public function getCantidad_hombres_base()
    {
        return $this->cantidad_hombres_base;
    }
     
    public function setCantidad_hombres_base($cantidad_hombres_base)
    {
        $this->cantidad_hombres_base = $cantidad_hombres_base;
        return $this;
    }

    public function getCostos_indirectos()
    {
        return $this->costos_indirectos;
    }
     
    public function setCostos_indirectos($costos_indirectos)
    {
        $this->costos_indirectos = $costos_indirectos;
        return $this;
    }

    public function getNumero_orden()
    {
        return $this->numero_orden;
    }
     
    public function setNumero_orden($numero_orden)
    {
        $this->numero_orden = $numero_orden;
        return $this;
    }

    public function getCosto_hora_hombre_base()
    {
        return $this->costo_hora_hombre_base;
    }
     
    public function setCosto_hora_hombre_base($costo_hora_hombre_base)
    {
        $this->costo_hora_hombre_base = $costo_hora_hombre_base;
        return $this;
    }

    public function getCantidad_horas_maquina_base()
    {
        return $this->cantidad_horas_maquina_base;
    }
     
    public function setCantidad_horas_maquina_base($cantidad_horas_maquina_base)
    {
        $this->cantidad_horas_maquina_base = $cantidad_horas_maquina_base;
        return $this;
    }

    public function getCod_maquina_usada()
    {
        return $this->cod_maquina_usada;
    }
     
    public function setCod_maquina_usada($cod_maquina_usada)
    {
        $this->cod_maquina_usada = $cod_maquina_usada;
        return $this;
    }

    public function getDescripcion_costos_indirectos()
    {
        return $this->descripcion_costos_indirectos;
    }
     
    public function setDescripcion_costos_indirectos($descripcion_costos_indirectos)
    {
        $this->descripcion_costos_indirectos = $descripcion_costos_indirectos;
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
                    "cantidad_hombres_base" =>$this->getCantidad_hombres_base(),
                    "costo_hora_hombre_base"=>$this->getCosto_hora_hombre_base(),
                    "cantidad_horas_maquina_base"=>$this->getCantidad_horas_maquina_base(),                    
                    "cod_maquina_usada"=>$this->getCod_maquina_usada(),
                    "descripcion_costos_indirectos"=>$this->getDescripcion_costos_indirectos());

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
                    "cantidad_hombres_base" =>$this->getCantidad_hombres_base(),
                    "costo_hora_hombre_base"=>$this->getCosto_hora_hombre_base(),
                    "cantidad_horas_maquina_base"=>$this->getCantidad_horas_maquina_base(),
                    "cod_maquina_usada" =>$this->getCod_maquina_usada(),                    
                    "descripcion_costos_indirectos"=>$this->getDescripcion_costos_indirectos());

            $campos_valores_where = 
            array(  "cod_actividad"=>$this->getCod_actividad());

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
            array(  "cod_actividad"=>$this->getCod_actividad());

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
            $sql = "SELECT * FROM $this->tb WHERE cod_actividad = :0";
            $resultado = $this->consultarFilas($sql, array($this->getCod_actividad()));
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }

    public function listar(){
        try {
            $sql = "SELECT 
                        a.cod_actividad,
                        a.nombre,
                        a.cantidad_hombres_base,
                        a.costo_hora_hombre_base,
                        a.cantidad_horas_maquina_base,
                        a.descripcion_costos_indirectos,
                        m.nombre as maquina
                    FROM actividad a INNER JOIN maquina m ON a.cod_maquina_usada = m.cod_maquina

                    WHERE a.estado_mrcb=TRUE";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function cabecera(){
        try{
            $sql = "SELECT cod_actividad,nombre FROM $this->tb ORDER BY 1";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado);
        }catch(Exception $exc){
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

/*
    public function cbListar(){
        try {
            $sql = "SELECT cod_actividad,nombre FROM $this->tb WHERE estado_mrcb=TRUE";
            return $this->consultarFilas($sql);
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function cabecera(){
        try{
            $sql = " SELECT 
                    (SELECT nombre from actividad WHERE cod_actividad=1) as act1,
                    (SELECT nombre from actividad WHERE cod_actividad=2) as act2,
                    (SELECT nombre from actividad WHERE cod_actividad=3) as act3,
                    (SELECT nombre from actividad WHERE cod_actividad=4) as act4,
                    (SELECT nombre from actividad WHERE cod_actividad=5) as act5,
                    (SELECT nombre from actividad WHERE cod_actividad=6) as act6,
                    (SELECT nombre from actividad WHERE cod_actividad=7) as act7,
                    (SELECT nombre from actividad WHERE cod_actividad=8) as act8,
                    (SELECT nombre from actividad WHERE cod_actividad=9) as act9";
            return $this->consultarFilas($sql);
        }catch(Exception $exc){
            throw $exc;
        }
    }

    public function titulo($p_codigo_pieza){
        try{
            $sql = "SELECT 
                        (SELECT nombre FROM actividad WHERE cod_actividad=ap.cod_actividad)||':'||(SELECT nombre FROM pieza WHERE cod_pieza=pp.cod_pieza ) as titulo,
                        pp.piezas_total
                    FROM actividad_plan_produccion ap INNER JOIN plan_produccion_pieza pp ON ap.cod_actividad_plan_produccion = pp.cod_actividad_plan_produccion
                    WHERE pp.cod_plan_produccion_pieza = :0";
            return $this->consultarFila($sql, array($p_codigo_pieza));
        }catch(Exception $exc){
            throw $exc;
        }
    }*/
}