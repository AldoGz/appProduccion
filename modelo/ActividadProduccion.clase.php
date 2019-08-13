<?php

require_once '../datos/Conexion.clase.php';

class ActividadProduccion extends Conexion {
    private $cod_actividad_plan_produccion;
    private $cod_actividad;
    private $cod_plan_produccion;
    private $cantidad_hombres;
    private $fecha_inicio;
    private $fecha_fin;
    private $observaciones;

    private $tb = "actividad_plan_produccion";

    public function getCod_actividad_plan_produccion()
    {
        return $this->cod_actividad_plan_produccion;
    }
     
    public function setCod_actividad_plan_produccion($cod_actividad_plan_produccion)
    {
        $this->cod_actividad_plan_produccion = $cod_actividad_plan_produccion;
        return $this;
    }

    public function getCod_actividad()
    {
        return $this->cod_actividad;
    }
     
    public function setCod_actividad($cod_actividad)
    {
        $this->cod_actividad = $cod_actividad;
        return $this;
    }
    public function getCantidad_hombres()
    {
        return $this->cantidad_hombres;
    }
     
    public function setCantidad_hombres($cantidad_hombres)
    {
        $this->cantidad_hombres = $cantidad_hombres;
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

    public function getObservaciones()
    {
        return $this->observaciones;
    }
     
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;
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


    public function agregarActividad($p1, $p2=null){
        session_name("_sis_produccion_");
        session_start(); 
        $this->beginTransaction();
        try {
            $sql = "SELECT cod_plan_produccion FROM plan_produccion WHERE nombre = :0";
            $codigo_plan_produccion =  $this->consultarValor($sql, array($p1));
            $this->setCod_plan_produccion($codigo_plan_produccion);

            $sql= "SELECT ap.cod_actividad_plan_produccion 
                    FROM plan_produccion_pieza pp 
                    INNER JOIN actividad_plan_produccion ap ON pp.cod_actividad_plan_produccion = ap.cod_actividad_plan_produccion
                    WHERE pp.cod_plan_produccion = :0 
                    GROUP BY ap.cod_actividad_plan_produccion
                    ORDER BY 1 DESC
                    LIMIT 1"; 
            $cod_actividad_plan_produccion =  $this->consultarValor($sql, array($codigo_plan_produccion));  

            $campos_valores = 
            array(  "cantidad_hombres"=>$this->getCantidad_hombres(),
                    "observaciones"=>$this->getObservaciones(),
                    "fecha_inicio"=>$this->getFecha_inicio(),
                    "fecha_fin"=>$this->getFecha_fin());

            $campos_valores_where = 
            array(  "cod_actividad_plan_produccion"=>$cod_actividad_plan_produccion);

            $this->update($this->tb, $campos_valores,$campos_valores_where);

            $sql= "SELECT ap.cod_actividad
                    FROM plan_produccion_pieza pp 
                    INNER JOIN actividad_plan_produccion ap ON pp.cod_actividad_plan_produccion = ap.cod_actividad_plan_produccion
                    WHERE pp.cod_plan_produccion = :0 
                    GROUP BY ap.cod_actividad_plan_produccion
                    ORDER BY 1 DESC
                    LIMIT 1"; 
            $cod_actividad =  $this->consultarValor($sql, array($codigo_plan_produccion));  

            $campos_valores = 
            array(  "estado_actividad"=>$cod_actividad);

            $campos_valores_where = 
            array(  "cod_plan_produccion"=>$codigo_plan_produccion);

            $this->update("plan_produccion", $campos_valores,$campos_valores_where);

            
            if ( $p2 != null) {  
                $cod_estado_acabado = $this->consultarValor("SELECT cod_estado FROM estado_proceso WHERE proceso = 'ACABADO'");

                $nuevo = (int)$cod_actividad+1;
                $campos_valores = 
                array(  "cod_actividad" => $nuevo,
                        "cod_usuario_registro"=>$_SESSION["cod_usuario"]);

                $this->insert("actividad_plan_produccion", $campos_valores);       

                /*JSON*/
                $detalle = json_decode($p2);

                $sql = "SELECT MAX(cod_actividad_plan_produccion) FROM actividad_plan_produccion";
                $maximo =  $this->consultarValor($sql);


                for ($i=0; $i <count($detalle) ; $i++) { 
                    $item = $detalle[$i];
                    $campos_valores1 = 
                    array(  "cod_plan_produccion" => $codigo_plan_produccion,
                            "cod_actividad_plan_produccion"=>$maximo,
                            "cod_pieza"=>$item->p_codigo_pieza,
                            "piezas_total"=>$item->p_piezas_total,
                            "piezas_buenas"=>$item->p_piezas_buenas,
                            "piezas_falladas"=>$item->p_piezas_falladas);

                    $this->insert("plan_produccion_pieza", $campos_valores1);       

                    $sql = "SELECT cod_materia_prima, (cantidad * :2)::numeric(10,2) as cantidad, false as recuperacion FROM materia_prima_pieza
                     WHERE tipo = :0
                     AND cod_pieza = :1 and estado_mrcb";

                    $materiaPrimaPieza = $this->consultarFilas($sql, array($cod_actividad, $item->p_codigo_pieza, $item->p_piezas_buenas));

                     if (count($materiaPrimaPieza) > 0){
                        $this->operacionAlmacen("S",
                            array( "cod_plan_produccion" => $codigo_plan_produccion,                        
                                    "cod_plan_produccion_fase"=>$cod_estado_acabado,
                                    "cod_plan_produccion_actividad"=> $cod_actividad
                                    ),
                            $materiaPrimaPieza
                            ); 
                    }  

                } 

            }

            $this->commit();
            return array("rpt"=>true,"msj"=>"Se ha registrado correctamente");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }        
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

                if (isset($value["cod_materia_prima"])){
                    $cod_materia_prima = $value["cod_materia_prima"];
                    $campos_valores = 
                        array(  "cod_almacen_operacion" => $cod_almacen_operacion,                        
                                "cod_materia_prima"=>$cod_materia_prima,
                                "cantidad"=>$value["cantidad"],
                                "recuperacion"=>$value["recuperacion"] ? '1' : '0'
                                );
                    $this->insert("almacen_operacion_detalle", $campos_valores);

                    $cant_anterior = $this->consultarValor("SELECT cantidad FROM almacen_stock WHERE cod_materia_prima = :0", array($cod_materia_prima));
                    $indxNuevaCantidad = $tipoOperacion == "E" ? 1 : -1;                
                    $campos_valores = array("cantidad"=>($cant_anterior + ($value["cantidad"]) * $indxNuevaCantidad ));
                    $campos_valores_where = array("cod_materia_prima" => $cod_materia_prima);     

                    $this->update("almacen_stock", $campos_valores, $campos_valores_where);

                } else {
                    if (isset($value["cod_pieza"])){
                        $cod_pieza = $value["cod_pieza"];
                        $campos_valores = 
                            array(  "cod_almacen_operacion" => $cod_almacen_operacion,                        
                                    "cod_pieza"=>$cod_pieza,
                                    "cantidad"=>$value["cantidad"],
                                    "recuperacion"=>$value["recuperacion"] ? '1' : '0'
                                    );
                        $this->insert("almacen_operacion_detalle", $campos_valores);

                        $cant_anterior = $this->consultarValor("SELECT cantidad FROM almacen_stock WHERE cod_pieza = :0", array($cod_pieza));
                        $indxNuevaCantidad = $tipoOperacion == "E" ? 1 : -1;                
                        if ($cant_anterior == false ){
                            $campos_valores = array("cod_pieza"=>$cod_pieza,"cantidad"=>($value["cantidad"] * $indxNuevaCantidad ));
                            $this->insert("almacen_stock", $campos_valores);     
                        } else {
                            $campos_valores = array("cantidad"=>($cant_anterior + ($value["cantidad"]) * $indxNuevaCantidad ));
                            $campos_valores_where = array("cod_pieza" => $cod_pieza);     
                            $this->update("almacen_stock", $campos_valores, $campos_valores_where);     
                        }

                    }
                    else {
                     $cod_producto = $value["cod_producto"];
                        $campos_valores = 
                            array(  "cod_almacen_operacion" => $cod_almacen_operacion,                        
                                    "cod_pieza"=>$cod_producto,
                                    "cantidad"=>$value["cantidad"],
                                    "recuperacion"=>$value["recuperacion"] ? '1' : '0'
                                    );
                        $this->insert("almacen_operacion_detalle", $campos_valores);

                        $cant_anterior = $this->consultarValor("SELECT cantidad FROM almacen_stock WHERE cod_producto = :0", array($cod_producto));
                        $indxNuevaCantidad = $tipoOperacion == "E" ? 1 : -1;                
                        if ($cant_anterior == false ){
                            $campos_valores = array("cod_producto"=>$cod_producto,"cantidad"=>($value["cantidad"] * $indxNuevaCantidad ));
                             $this->insert("almacen_stock", $campos_valores);   
                        } else {
                            $campos_valores = array("cantidad"=>($cant_anterior + ($value["cantidad"]) * $indxNuevaCantidad ));
                            $campos_valores_where = array("cod_producto" => $cod_producto);     
                            $this->update("almacen_stock", $campos_valores, $campos_valores_where);     
                        }

                    }   

                }         
              
            }
    }

    public function getEstadoProceso($nombre_proceso)
    {
        $sql = "SELECT cod_estado FROM estado_proceso WHERE proceso = :0 AND estado_mrcb";
        return $this->consultarValor($sql,array($nombre_proceso));
    }


    public function agregarFActividad($p1, $p2=null){
        session_name("_sis_produccion_");
        session_start(); 
        $this->beginTransaction();
        try {
            $sql = "SELECT cod_plan_produccion FROM plan_produccion WHERE nombre = :0";
            $codigo_plan_produccion =  $this->consultarValor($sql, array($p1));
            $this->setCod_plan_produccion($codigo_plan_produccion);

            $campos_valores = 
            array(  "cantidad_hombres"=>$this->getCantidad_hombres(),
                    "observaciones"=>$this->getObservaciones(),
                    "fecha_inicio"=>$this->getFecha_inicio(),
                    "fecha_fin"=>$this->getFecha_fin(),
                    "cod_actividad"=>$this->getCod_actividad(),
                    "cod_plan_produccion"=>$codigo_plan_produccion,
                    "cod_usuario_registro"=>$_SESSION["cod_usuario"]);


 
            $this->insert("actividad_plan_produccion", $campos_valores);

            $cod_actividad_plan_produccion = $this->consultarValor("SELECT MAX(cod_actividad_plan_produccion) FROM actividad_plan_produccion");


            $cod_estado_acabado = $this->getEstadoProceso("ACABADO");


            if ( $p2 != null ) {


                $cod_actividad = $this->getEstadoActividad("EMPAQUETADO");

                /*9*/
                $campos_valores = 
                array(  "estado_actividad"=>$this->getCod_actividad(),
                        "acabado_fecha"=>$this->getFecha_fin());

                $campos_valores_where = 
                array(  "cod_plan_produccion"=>$codigo_plan_produccion);

                $this->update("plan_produccion", $campos_valores,$campos_valores_where);

                $campos_valores = 
                array(  "estado_pedido"=>6);

                $campos_valores_where = 
                array(  "cod_plan_produccion"=>$codigo_plan_produccion);

                $this->update("pedido", $campos_valores,$campos_valores_where);

                /*Aparte de actualizar el producto aquí se debería guardar el costo de empaqueteado de producto y/o ensamblaje siempre
                apuntando a la tabla plan_produccion_producto.*/


                /*Lo que sí se debe guardar ahora es pasar el producto de plan_p_producto a almacen ocmo una Entrada.*/

                $operacion_cabecera = 
                        array( "cod_plan_produccion" => $codigo_plan_produccion,                        
                                    "cod_plan_produccion_fase"=>$cod_estado_acabado,
                                    "cod_plan_produccion_actividad"=> $cod_actividad
                                    );

                $sql = "SELECT ppp.cod_producto, cantidad, false as recuperacion  FROM plan_produccion_producto ppp 
                        INNER JOIN actividad_plan_produccion app ON ppp.cod_actividad_plan_produccion = app.cod_actividad_plan_produccion 
                        WHERE app.cod_plan_produccion = :0 AND ppp.estado_mrcb";
                $listadoProductos = $this->consultarFilas($sql, array($codigo_plan_produccion));
                $this->operacionAlmacen("E",$operacion_cabecera,$listadoProductos);

            }else{
                /*8*/   

                /*Esto al ser ensamblaje, debe registrar piezas en esta PP y/o  a esta actividad.
                    Además se debe consumir los recursos asignados
                */
                $cod_actividad = $this->getEstadoActividad("ENSAMBLADO");

                $materiales = $this->listarPreEnsamblado();                         

                /*Generar una operacion salida.*/      
                $operacion_cabecera = 
                        array( "cod_plan_produccion" => $codigo_plan_produccion,                        
                                    "cod_plan_produccion_fase"=>$cod_estado_acabado,
                                    "cod_plan_produccion_actividad"=> $cod_actividad
                                    );

                $materiasPrimaAcumulado = array();
                $piezasUsadas = array();

                foreach ($materiales["productos"] as $key => $value) {

                    $campos_valores = array(
                            "cod_actividad_plan_produccion"=>$cod_actividad_plan_produccion,
                            "cod_producto"=> $value["codigo"],
                            "cantidad"=> $value["cantidad"]
                        );


                    $this->insert("plan_produccion_producto", $campos_valores);  

                    $sql = "SELECT cod_pieza FROM pieza WHERE cod_producto = :0 AND estado_mrcb";
                    $piezas = $this->consultarFilas($sql, array($value["codigo"]));

                    foreach($piezas as $clave => $valor){
                        if (array_key_exists($valor["cod_pieza"], $piezasUsadas)) {
                            $piezasUsadas[$valor["cod_pieza"]] += $value["cantidad"];
                        } else {
                            $piezasUsadas[$valor["cod_pieza"]] = $value["cantidad"]; 
                        }
                    }

                    /*Recursos consumidos por producto*/
                    $sql = "SELECT cod_materia_prima, (cantidad * :1)::numeric(10,2) as cantidad, false as recuperacion 
                                 FROM materia_prima_producto WHERE cod_producto = :0";
                    $materiaPrimaProducto = $this->consultarFilas($sql, array($value["codigo"],$value["cantidad"]));


                    foreach ($materiaPrimaProducto as $k => $v) {
                        array_push($materiasPrimaAcumulado, $v);        
                    }

                }    

                /*Salida de la materia prima de los productos */
                $this->operacionAlmacen("S",$operacion_cabecera,$materiasPrimaAcumulado);

                $piezasSobrantes = array();
                foreach ($materiales["piezas"] as $key => $value) { 
                    $campos_valores = array(
                            "cod_plan_produccion"=>$this->getCod_plan_produccion(),
                            "cod_actividad_plan_produccion"=>$cod_actividad_plan_produccion,
                            "cod_pieza"=> $value["codigo"],
                            "piezas_total"=> $value["cantidad"],
                            "piezas_buenas"=> $value["cantidad"],
                            "piezas_falladas"=> 0                            
                        );
                    $this->insert("plan_produccion_pieza", $campos_valores); 

                    $value["cantidad"] -= $piezasUsadas[$value["codigo"]];
                    if ($value["cantidad"]  > 0){
                        //guardar esta pieza en almacen.
                        array_push($piezasSobrantes,
                                    array(
                                        "cantidad"=>$value["cantidad"] ,
                                        "cod_pieza"=>$value["codigo"],
                                        "recuperacion"=>'0'
                                        ));
                    }
                }   

                /*Guardar sobrantes en el almacen*/
                $this->operacionAlmacen("E",$operacion_cabecera,$piezasSobrantes);

                $campos_valores = 
                array(  "estado_actividad"=>$this->getCod_actividad());

                $campos_valores_where = 
                array(  "cod_plan_produccion"=>$codigo_plan_produccion);

                $this->update("plan_produccion", $campos_valores,$campos_valores_where);
            }

            $this->commit();            
            return array("rpt"=>true,"msj"=>"Se ha registrado correctamente");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }        
    } 

    public function finalizarActividad($p1,$p2,$p3){ 
        session_name("_sis_produccion_");
        session_start();        
        $this->beginTransaction();
        try {
            $sql = "SELECT cod_plan_produccion FROM plan_produccion WHERE nombre = :0";
            $codigo_plan_produccion =  $this->consultarValor($sql, array($p1));

            $campos_valores = 
            array(  "estado_fase"=>1,
                    "finalizacion_fecha"=>$this->getFecha_fin());

            $campos_valores_where = 
            array(  "cod_plan_produccion"=>$codigo_plan_produccion);

            $this->update("plan_produccion", $campos_valores,$campos_valores_where);

            $campos_valores = 
            array(  "estado_pedido"=>1);

            $campos_valores_where = 
            array(  "cod_plan_produccion"=>$codigo_plan_produccion);

            $this->update("pedido", $campos_valores,$campos_valores_where);

            /// REGISTRAR CABECERA
            $campos_valores = 
            array(  "cod_plan_produccion"=>$codigo_plan_produccion,
                    "tipo_operacion"=>'E',
                    "cod_almacen"=>1,
                    "cod_usuario_registro"=>$_SESSION["cod_usuario"]);

            $this->insert("almacen_operacion", $campos_valores);

            $sql = "SELECT CASE WHEN MAX(cod_almacen_operacion) IS NULL THEN 1 ELSE MAX(cod_almacen_operacion) END FROM almacen_operacion";
            $maximo =  $this->consultarValor($sql);
        

            /*JSON1*//// REGISTRAR INTERMEDIO
            $productos = json_decode($p2);

            for ($i=0; $i <count($productos) ; $i++) { 
                $item = $productos[$i];
                $campos_valores = 
                array(  "cod_almacen_operacion" => $maximo,
                        "cod_producto"=>$item->p_codigo_producto,
                        "cantidad"=>$item->p_cantidad);

                $this->insert("almacen_operacion_detalle", $campos_valores); 

                $sql = "SELECT COUNT(*) FROM almacen_operacion_detalle WHERE cod_producto = :0";
                $encontro =  $this->consultarValor($sql,array($item->p_codigo_producto));

                if ( (int)$encontro > 1 ) {
                    $sql = "SELECT SUM(cantidad) FROM almacen_operacion_detalle WHERE cod_producto = :0
                            GROUP BY cod_producto";
                    $stock_actual =  $this->consultarValor($sql,array($item->p_codigo_producto));

                    $campos_valores = 
                    array(  "cantidad"=>$stock_actual );

                    $campos_valores_where = 
                    array(  "cod_producto"=>$item->p_codigo_producto);

                    $this->update("almacen_stock", $campos_valores,$campos_valores_where);
                }else{
                    $campos_valores = 
                    array(  "cod_producto"=>$item->p_codigo_producto,
                            "cantidad"=>$item->p_cantidad,
                            "cod_almacen"=>1);

                    $this->insert("almacen_stock", $campos_valores); 
                }             
            } 

            /*JSON2*//// REGISTRAR INTERMEDIO
            $piezas = json_decode($p3);


            for ($i=0; $i <count($piezas) ; $i++) { 
                $item = $piezas[$i];
                $campos_valores = 
                array(  "cod_almacen_operacion" => $maximo,
                        "cod_pieza"=>$item->p_codigo_pieza,                        
                        "cantidad"=>$item->p_cantidad);

                $this->insert("almacen_operacion_detalle", $campos_valores); 


                $sql = "SELECT COUNT(*) FROM almacen_operacion_detalle WHERE cod_pieza = :0";
                $encontro =  $this->consultarValor($sql,array($item->p_codigo_pieza));

                if ( (int)$encontro > 1 ) {
                    $sql = "SELECT SUM(cantidad) FROM almacen_operacion_detalle WHERE cod_pieza = :0
                            GROUP BY cod_pieza";
                    $stock_actual =  $this->consultarValor($sql,array($item->p_codigo_pieza));

                    $campos_valores = 
                    array(  "cantidad"=>$stock_actual );

                    $campos_valores_where = 
                    array(  "cod_pieza"=>$item->p_codigo_pieza);

                    $this->update("almacen_stock", $campos_valores,$campos_valores_where);
                }else{
                    $campos_valores = 
                    array(  "cod_pieza"=>$item->p_codigo_pieza,
                            "cantidad"=>$item->p_cantidad,
                            "cod_almacen"=>1);

                    $this->insert("almacen_stock", $campos_valores);  
                }    
             
            } 

            $this->commit();
            return array("rpt"=>true,"msj"=>"Se ha registrado correctamente en el almacen");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }        
    } 


    public function listarPreEnsamblado(){
        try {
            $sql = "SELECT 
                        'PR'||RIGHT('0000'||pro.cod_producto,4) as cod_producto, 
                        pro.cod_producto as codigo,
                        pro.nombre,
                        MIN(piezas.cantidad) as cantidad
                    FROM 
                    (
                        SELECT 
                        p.cod_pieza,
                        tb.piezas_buenas as cantidad,
                        p.cod_producto
                        FROM 
                        (
                            SELECT 
                            cod_pieza,
                            piezas_total,
                            piezas_buenas,
                            piezas_falladas
                            FROM plan_produccion_pieza 
                            WHERE cod_actividad_plan_produccion = 
                            (
                            SELECT ap.cod_actividad_plan_produccion 
                            FROM plan_produccion_pieza pp 
                            INNER JOIN actividad_plan_produccion ap ON pp.cod_actividad_plan_produccion = ap.cod_actividad_plan_produccion
                            WHERE pp.cod_plan_produccion = :0
                            GROUP BY ap.cod_actividad_plan_produccion
                            ORDER BY 1 DESC 
                            LIMIT 1
                            )
                        ) as tb INNER JOIN pieza p ON tb.cod_pieza = p.cod_pieza
                    ) as piezas INNER JOIN producto pro ON piezas.cod_producto = pro.cod_producto
                    GROUP BY pro.cod_producto
                    ORDER BY 1";
            $productos = $this->consultarFilas($sql, array($this->getCod_plan_produccion()));

            $sql = " SELECT  
                         'PI'||RIGHT('0000'||p.cod_pieza,4) as cod_pieza,
                         p.cod_pieza as codigo,
                         p.nombre,
                         tb.piezas_buenas as cantidad
                     FROM 
                     (
                        SELECT 
                        cod_pieza,
                        piezas_total,
                        piezas_buenas,
                        piezas_falladas
                        FROM plan_produccion_pieza 
                        WHERE cod_actividad_plan_produccion = 
                        (
                        SELECT ap.cod_actividad_plan_produccion 
                        FROM plan_produccion_pieza pp 
                        INNER JOIN actividad_plan_produccion ap ON pp.cod_actividad_plan_produccion = ap.cod_actividad_plan_produccion
                        WHERE pp.cod_plan_produccion = :0
                        GROUP BY ap.cod_actividad_plan_produccion
                        ORDER BY 1 DESC 
                        LIMIT 1
                        )
                    ) as tb INNER JOIN pieza p ON tb.cod_pieza = p.cod_pieza
                    ORDER BY 1";

            $piezas = $this->consultarFilas($sql, array($this->getCod_plan_produccion()));

            return array("piezas"=>$piezas,"productos"=>$productos);            
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }  


    public function getEstadoActividad($nombre_proceso)
    {
        $sql = "SELECT cod_actividad FROM actividad WHERE nombre = :0  AND estado_mrcb";
        return $this->consultarValor($sql,array($nombre_proceso));
    }


}
      
