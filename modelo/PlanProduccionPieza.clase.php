<?php

require_once '../datos/Conexion.clase.php';

class PlanProduccionPieza extends Conexion {
    private $cod_plan_produccion_pieza;
    private $cod_plan_produccion;
    private $cod_pieza;
    private $piezas_total;
    private $piezas_buenas;
    private $piezas_falladas;

    private $tb = "plan_produccion_pieza";
    private $tbl_pieza_fallada_actividad = "pieza_fallada_actividad";

    public function getCod_plan_produccion_pieza()
    {
        return $this->cod_plan_produccion_pieza;
    }
     
    public function setCod_plan_produccion_pieza($cod_plan_produccion_pieza)
    {
        $this->cod_plan_produccion_pieza = $cod_plan_produccion_pieza;
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

    public function getCod_pieza()
    {
        return $this->cod_pieza;
    }
     
    public function setCod_pieza($cod_pieza)
    {
        $this->cod_pieza = $cod_pieza;
        return $this;
    }

    public function getPiezas_total()
    {
        return $this->piezas_total;
    }
     
    public function setPiezas_total($piezas_total)
    {
        $this->piezas_total = $piezas_total;
        return $this;
    }

    public function getPiezas_buenas()
    {
        return $this->piezas_buenas;
    }
     
    public function setPiezas_buenas($piezas_buenas)
    {
        $this->piezas_buenas = $piezas_buenas;
        return $this;
    }

    public function getPiezas_falladas()
    {
        return $this->piezas_falladas;
    }
     
    public function setPiezas_falladas($piezas_falladas)
    {
        $this->piezas_falladas = $piezas_falladas;
        return $this;
    }

    public function agregarFalla($JSONFallas){
        session_name("_sis_produccion_");
        session_start();   
        $this->beginTransaction();
        try { 
            $arreglo = json_decode($JSONFallas);
            $cantidadPiezasFalladas = 0;
            for ($i=0; $i <count($arreglo) ; $i++) { 
                $item = $arreglo[$i];

                $campos_valores = 
                array(  "cod_plan_produccion_pieza"=> $this->getCod_plan_produccion_pieza(),## $item->p_codigo_plan_produccion_pieza,
                        "descripcion_falla"=>$item->p_motivo,
                        "cod_tipo_falla"=>$item->p_codigo_falla,
                        "total_piezas"=>$item->p_cantidad,
                        "cod_usuario_registro"=>$_SESSION["cod_usuario"]);

                $this->insert($this->tbl_pieza_fallada_actividad, $campos_valores);
                $cantidadPiezasFalladas += $item->p_cantidad;
              
            }

            $campos_valores = 
            array(  "piezas_buenas"=>$this->getPiezas_buenas(),
                    "piezas_falladas"=>$this->getPiezas_falladas());

            $campos_valores_where = 
            array(  "cod_plan_produccion_pieza"=>$this->getCod_plan_produccion_pieza());

            $this->update($this->tb, $campos_valores,$campos_valores_where);   

                 /*Muchas fallas (con cantidad) para cada Pieza
                La idea es aquí calcular (de qué pieza estamos hablando)
                Y por cada falla vER CUANTAS piezas (total_pieza)
                La idea es sumar las piezas.
                Luego que temnemos una cantidad de material en esa pieza.
                */
            
            $sql = "SELECT cod_pieza, cod_actividad, ppp.cod_plan_produccion
                FROM plan_produccion_pieza ppp
                INNER JOIN actividad_plan_produccion app ON ppp.cod_actividad_plan_produccion = app.cod_actividad_plan_produccion
                WHERE cod_plan_produccion_pieza = :0";

            $datos_pieza = $this->consultarFila($sql, array($this->getCod_plan_produccion_pieza()));

            $this->setCod_plan_produccion($datos_pieza["cod_plan_produccion"]);

            $this->ingresarChatarraRecuperacion($datos_pieza,$cantidadPiezasFalladas);
           
           
            /*Sacar de almacen toda la materia prima asociada a esa pieza */
          
            $cod_estado_acabado = $this->consultarValor("SELECT cod_estado FROM estado_proceso WHERE proceso = 'ACABADO'");

            $sql = "SELECT (cantidad * (:2)::numeric(12,3) ) as cantidad, cod_materia_prima, false as recuperacion
                    FROM materia_prima_pieza WHERE cod_pieza = :0 AND tipo  = :1";

            $materiaPrimaPieza = $this->consultarFilas($sql, array($datos_pieza["cod_pieza"], $datos_pieza["cod_actividad"],$cantidadPiezasFalladas));

            if (count($materiaPrimaPieza) > 0){
                $this->operacionAlmacen("S",
                    array( "cod_plan_produccion" => $datos_pieza["cod_plan_produccion"],                        
                            "cod_plan_produccion_fase"=>$cod_estado_acabado,
                            "cod_plan_produccion_actividad"=> $datos_pieza["cod_actividad"]
                            ),
                    $materiaPrimaPieza
                    ); 
            }  

            $this->commit();

            return array("rpt"=>true,"msj"=>"Se ha agregado exitosamente");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }
    

    public function ingresarChatarraRecuperacion($datos_pieza,$cantidadPiezasFalladas)
    {
        $cod_materia_prima_chatarra = $this->consultarValor("SELECT cod_materia_prima FROM materia_prima WHERE nombre = 'CHATARRA'"); 

        $cantidad_chatarra_recuperada = 
        $this->consultarValor("SELECT (SELECT valor::numeric(4,3) FROM variables_constantes WHERE descripcion = 'porcentaje_recuperacion_chatarra') * cantidad * :2
                                    FROM materia_prima_pieza
                                    WHERE cod_pieza = :0 AND 
                                    cod_materia_prima = :1;",
                                    array($datos_pieza["cod_pieza"],$cod_materia_prima_chatarra,$cantidadPiezasFalladas));

        $cod_estado_acabado = $this->consultarValor("SELECT cod_estado FROM estado_proceso WHERE proceso = 'ACABADO'");

        $this->operacionAlmacen("E",
                array( "cod_plan_produccion" => $this->getCod_plan_produccion(),                        
                        "cod_plan_produccion_fase"=>$cod_estado_acabado,
                        "cod_plan_produccion_actividad"=> $datos_pieza["cod_actividad"]
                        ),
                array(
                    array(  "cantidad" => $cantidad_chatarra_recuperada,                        
                            "cod_materia_prima"=>$cod_materia_prima_chatarra,
                            "recuperacion"=> true
                        )
                    )
                );     
    }

    public function operacionAlmacen($tipoOperacion,$campos_valores_cabecera,$campos_valores_detalle)
    {
        /*
            operacion,
            operacion_detalle,
            stock
        */
            $campos_valores = 
                array(  "cod_plan_produccion" => $this->getCod_plan_produccion(),                        
                        "tipo_operacion"=>$tipoOperacion,
                        "cod_plan_produccion_fase"=>$campos_valores_cabecera["cod_plan_produccion_fase"],
                        "cod_almacen"=> 1,
                        "cod_plan_produccion_actividad"=> $campos_valores_cabecera["cod_plan_produccion_actividad"],
                        "cod_usuario_registro"=>$_SESSION["cod_usuario"],
                        );

            $this->insert("almacen_operacion", $campos_valores);       

            /*Insertar detalle operacion es decir la chatarrra de todo lo fallado*/
            $cod_almacen_operacion = $this->consultarValor("SELECT MAX(cod_almacen_operacion) FROM almacen_operacion"); 

            foreach ($campos_valores_detalle as $key => $value) {
                $cod_materia_prima = $value["cod_materia_prima"];
                $campos_valores = 
                    array(  "cod_almacen_operacion" => $cod_almacen_operacion,                        
                            "cod_materia_prima"=>$cod_materia_prima,
                            "cantidad"=>$value["cantidad"],
                            "recuperacion"=>  $value["recuperacion"] ? '1' : '0'
                            );



                $this->insert("almacen_operacion_detalle", $campos_valores);
                $cant_anterior = $this->consultarValor("SELECT cantidad FROM almacen_stock WHERE cod_materia_prima = :0", array($cod_materia_prima));
                $indxNuevaCantidad = $tipoOperacion == "E" ? 1 : -1;
                $campos_valores = array("cantidad"=>($cant_anterior + ($value["cantidad"]) * $indxNuevaCantidad ));
                $campos_valores_where = array("cod_materia_prima" => $cod_materia_prima);
                $this->update("almacen_stock", $campos_valores, $campos_valores_where);
            }
    }


    public function leerDatos($p1, $p2){
        try {
            $sql = "SELECT cod_plan_produccion FROM plan_produccion WHERE nombre = :0";
            $codigo_plan_produccion =  $this->consultarValor($sql, array($p1));

            $sql = "SELECT
                        pp.cod_plan_produccion_pieza,
                        a.nombre as actividad,
                        t.nombre as falla,
                        pa.descripcion_falla as motivo,
                        pp.piezas_total,
                        pp.piezas_buenas,
                        pp.piezas_falladas
                    FROM plan_produccion_pieza pp 
                        INNER JOIN pieza_fallada_actividad pa ON pp.cod_plan_produccion_pieza = pa.cod_plan_produccion_pieza
                        INNER JOIN tipo_falla t ON pa.cod_tipo_falla = t.cod_falla 
                        INNER JOIN actividad_plan_produccion ap ON pp.cod_actividad_plan_produccion = ap.cod_actividad_plan_produccion
                        INNER JOIN actividad a ON ap.cod_actividad = a.cod_actividad
                    WHERE pp.cod_plan_produccion = :0 AND cod_pieza = :1
                    ORDER BY 1
                    LIMIT :2";
            $resultado = $this->consultarFilas($sql, array($codigo_plan_produccion, $this->getCod_pieza(), $p2 ));
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }

    public function piezasBuenas(){
        try {
            $sql = "SELECT piezas_buenas FROM plan_produccion_pieza WHERE cod_plan_produccion_pieza=:0";
            $resultado = $this->consultarValor($sql, array($this->getCod_plan_produccion_pieza()));
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }

    public function retirarFalla($p1,$p2){
        $this->beginTransaction();
        try {             
            $campos_valores = 
            array(  "estado_mrcb"=>"false");

            $campos_valores_where = 
            array(  "cod_plan_produccion_pieza"=>$this->getCod_plan_produccion_pieza(),
                    "cod_tipo_falla"=>$p1);

            $this->update("pieza_fallada_actividad", $campos_valores,$campos_valores_where);   

            $sql1 = "SELECT piezas_buenas FROM plan_produccion_pieza WHERE cod_plan_produccion_pieza=:0";
            $buenas = $this->consultarValor($sql1, array($this->getCod_plan_produccion_pieza()));

            $sql2 = "SELECT piezas_falladas FROM plan_produccion_pieza WHERE cod_plan_produccion_pieza=:0";
            $malas = $this->consultarValor($sql2, array($this->getCod_plan_produccion_pieza()));

            $pb = (int)$buenas+(int)$p2;
            $pm = (int)$malas-(int)$p2;

            $campos_valores = 
            array(  "piezas_buenas"=>$pb,
                    "piezas_falladas"=>$pm);

            $campos_valores_where = 
            array(  "cod_plan_produccion_pieza"=>$this->getCod_plan_produccion_pieza());

            $this->update("plan_produccion_pieza", $campos_valores,$campos_valores_where);


            $this->commit();
            return array("rpt"=>true,"msj"=>"Se retirado exitosamente");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }




}