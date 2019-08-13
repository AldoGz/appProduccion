<?php

require_once '../datos/Conexion.clase.php';

class Reporte extends Conexion {
    private $fecha_inicio;
    private $fecha_fin;

    private $codigo_plan_produccion;


    public function getCodigo_plan_produccion()
    {
        return $this->codigo_plan_produccion;
    }
    
    public function setCodigo_plan_produccion($codigo_plan_produccion)
    {
        $this->codigo_plan_produccion = $codigo_plan_produccion;
        return $this;
    }

    public function getFecha_inicio()
    {
        return $this->fecha_inicio;
    }
    
    public function setFecha_inicio($fecha_inicio)
    {
        $this->fecha_inicio = $fecha_inicio;
        return $this;
    }

    public function getFecha_fin()
    {
        return $this->fecha_fin;
    }
    
    public function setFecha_fin($fecha_fin)
    {
        $this->fecha_fin = $fecha_fin;
        return $this;
    }

    public function reportePlanProduccion(){
        try {
            $sql = "SELECT 
                    cod_plan_produccion,
                    nombre, 
                    to_char(fecha_inicio_proceso, 'dd/MM/yyyy HH12:MI ')||(CASE WHEN date_part('hour', fecha_inicio_proceso)::int > 12 THEN 'p.m.' ELSE 'a.m.' END) as fecha_inicio_proceso,
                    to_char(acondicionamiento_fecha, 'dd/MM/yyyy HH12:MI ')||(CASE WHEN date_part('hour', acondicionamiento_fecha)::int > 12 THEN 'p.m.' ELSE 'a.m.' END) as acondicionamiento_fecha,
                    to_char(fundicion_fecha, 'dd/MM/yyyy HH12:MI ')||(CASE WHEN date_part('hour', fundicion_fecha)::int > 12 THEN 'p.m.' ELSE 'a.m.' END) as fundicion_fecha,
                    to_char(acabado_fecha, 'dd/MM/yyyy HH12:MI ')||(CASE WHEN date_part('hour', acabado_fecha)::int > 12 THEN 'p.m.' ELSE 'a.m.' END) as acabado_fecha,
                    to_char(finalizacion_fecha, 'dd/MM/yyyy HH12:MI ')||(CASE WHEN date_part('hour', finalizacion_fecha)::int > 12 THEN 'p.m.' ELSE 'a.m.' END) as finalizacion_fecha
                    FROM plan_produccion 
                    WHERE estado_fase = 1 AND estado_actividad = 9 AND fecha_inicio_proceso::date >=:0 AND finalizacion_fecha::date <= :1
                    ORDER BY 1";
            $resultado = $this->consultarFilas($sql, array($this->getFecha_inicio(),$this->getFecha_fin()));
            return array("rpt"=>true,"msj"=>$resultado);            
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function cabecera(){
        try {
            $sql = "SELECT 
                    accion,
                    cod_pieza,
                    pieza,
                    total,
                    buenas,
                    falladas
                    FROM (
                    SELECT 
                    *
                    FROM
                    (
                    SELECT 
                    'INICIO'::varchar as accion,
                    'PI'||RIGHT('0000'||cod_pieza,4) as cod_pieza, 
                    pieza, 
                    SUM(cantidad)::INT as total,
                    SUM(cantidad)::INT as buenas,
                    0 as falladas,
                    0 as actividad
                    FROM (
                        SELECT 
                            pi.cod_pieza, 
                            pi.nombre as pieza,
                            SUM(pd.cantidad)::INT as cantidad                    
                        FROM pedido p 
                            INNER JOIN pedido_detalle pd ON p.cod_pedido = pd.cod_pedido
                            INNER JOIN producto pro ON pd.cod_producto = pro.cod_producto
                            INNER JOIN pieza pi ON pi.cod_producto = pro.cod_producto
                        WHERE p.cod_plan_produccion = :0
                        GROUP BY pi.cod_pieza
                        UNION
                        SELECT 
                            p.cod_pieza,
                            p.nombre as pieza,
                            SUM(rip.cantidad)::INT as cantidad
                        FROM requisito_interno ri 
                            INNER JOIN requisito_interno_pieza rip ON ri.cod_requisito_interno = rip.cod_requisito_interno
                            INNER JOIN pieza p ON p.cod_pieza = rip.cod_pieza
                        WHERE ri.cod_plan_produccion = :0 AND ri.estado_mrcb=true AND rip.estado_mrcb=true
                        GROUP BY p.cod_pieza) as tabla 
                    GROUP BY cod_pieza, pieza 
                    ORDER BY 2
                    ) as t1
                    UNION ALL
                    SELECT * FROM 
                    (
                    SELECT 
                    ac.nombre,
                    'PI'||RIGHT('0000'||pp.cod_pieza,4) as cod_pieza, 
                    pi.nombre,
                    pp.piezas_total,
                    pp.piezas_buenas,
                    pp.piezas_falladas,
                    ac.cod_actividad
                    FROM plan_produccion_pieza pp 
                        INNER JOIN actividad_plan_produccion ap ON pp.cod_actividad_plan_produccion = ap.cod_actividad_plan_produccion
                        INNER JOIN actividad ac ON ap.cod_actividad = ac.cod_actividad      
                        INNER JOIN pieza pi ON pp.cod_pieza = pi.cod_pieza  
                    WHERE pp.cod_plan_produccion = :0 AND piezas_falladas > 0 
                    ORDER BY ac.cod_actividad

                    ) as t2
                    ) as maestra";
            $resultado = $this->consultarFilas($sql, array($this->getCodigo_plan_produccion()));


            return array("rpt"=>true,"msj"=>$resultado);            
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

}
