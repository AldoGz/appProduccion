<?php

require_once '../datos/Conexion.clase.php';

class PlanProduccion extends Conexion {
    private $cod_plan_produccion;
    private $nombre;
    private $fecha_inicio_proceso;

    private $acondicionamiento_fecha;
    private $acondicionamiento_costo_extra;
    private $acondicionamiento_comentario;
    private $acondicionamiento_costo_materia_prima;
    private $acondicionamiento_costo_materia_prima_ahorro;
    private $estado_fase;

    private $fundicion_horas;
    private $fundicion_costo_extra;
    private $fundicion_comentarios;
    private $fundicion_fecha;

    private $finalizacion_fecha;

    private $tb = "plan_produccion";
    private $tb_p = "pedido";


    public function getCodPlanProduccion()
    {
        return $this->cod_plan_produccion;
    }
    
    
    public function setCodPlanProduccion($cod_plan_produccion)
    {
        $this->cod_plan_produccion = $cod_plan_produccion;
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

    public function getFecha_inicio_proceso()
    {
        return $this->fecha_inicio_proceso;
    }
     
    public function setFecha_inicio_proceso($fecha_inicio_proceso)
    {
        $this->fecha_inicio_proceso = $fecha_inicio_proceso;
        return $this;
    }

    public function getAcondicionamiento_fecha()
    {
        return $this->acondicionamiento_fecha;
    }
     
    public function setAcondicionamiento_fecha($acondicionamiento_fecha)
    {
        $this->acondicionamiento_fecha = $acondicionamiento_fecha;
        return $this;
    }

    public function getAcondicionamiento_costo_extra()
    {
        return $this->acondicionamiento_costo_extra;
    }
     
    public function setAcondicionamiento_costo_extra($acondicionamiento_costo_extra)
    {
        $this->acondicionamiento_costo_extra = $acondicionamiento_costo_extra;
        return $this;
    }

    public function getAcondicionamiento_comentario()
    {
        return $this->acondicionamiento_comentario;
    }
     
    public function setAcondicionamiento_comentario($acondicionamiento_comentario)
    {
        $this->acondicionamiento_comentario = $acondicionamiento_comentario;
        return $this;
    }

    public function getAcondicionamiento_costo_materia_prima()
    {
        return $this->acondicionamiento_costo_materia_prima;
    }
     
    public function setAcondicionamiento_costo_materia_prima($acondicionamiento_costo_materia_prima)
    {
        $this->acondicionamiento_costo_materia_prima = $acondicionamiento_costo_materia_prima;
        return $this;
    }

     public function getAcondicionamiento_costo_materia_prima_ahorro()
    {
        return $this->acondicionamiento_costo_materia_prima_ahorro;
    }
     
    public function setAcondicionamiento_costo_materia_prima_ahorro($acondicionamiento_costo_materia_prima_ahorro)
    {
        $this->acondicionamiento_costo_materia_prima_ahorro = $acondicionamiento_costo_materia_prima_ahorro;
        return $this;
    }

    public function getFundicion_fecha()
    {
        return $this->fundicion_fecha;
    }
     
    public function setFundicion_fecha($fundicion_fecha)
    {
        $this->fundicion_fecha = $fundicion_fecha;
        return $this;
    }

    public function getFundicion_comentarios()
    {
        return $this->fundicion_comentarios;
    }
     
    public function setFundicion_comentarios($fundicion_comentarios)
    {
        $this->fundicion_comentarios = $fundicion_comentarios;
        return $this;
    }

    public function getFundicion_horas()
    {
        return $this->fundicion_horas;
    }
     
    public function setFundicion_horas($fundicion_horas)
    {
        $this->fundicion_horas = $fundicion_horas;
        return $this;
    }

    public function getFundicion_costo_extra()
    {
        return $this->fundicion_costo_extra;
    }
     
    public function setFundicion_costo_extra($fundicion_costo_extra)
    {
        $this->fundicion_costo_extra = $fundicion_costo_extra;
        return $this;
    }

    public function getEstado_fase()
    {
        return $this->estado_fase;
    }
     
    public function setEstado_fase($estado_fase)
    {
        $this->estado_fase = $estado_fase;
        return $this;
    }

    public function correlativo(){
        try {
            $sql = "SELECT fn_generar_plan_produccion()::CHAR(5) as correlativo";
            $resultado = $this->consultarValor($sql);
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

  	public function agregar() {  
  		session_name("_sis_produccion_");
        session_start();      
        $this->beginTransaction();
        try {            
            $campos_valores = 
            array(  "nombre" => $this->getNombre(),
                    "cod_usuario_registro"=>$_SESSION["cod_usuario"]);
            $this->insert($this->tb, $campos_valores);
            $this->commit();
            return array("rpt"=>true,"msj"=>"Se registrado correctamente");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }
    
    public function listar(){
        try {
            $sql = "SELECT 
                        pp.cod_plan_produccion,
                        pp.nombre,
                        to_char(pp.fecha_hora_registro,'dd/MM/yyyy HH12:MI:SS')||CASE WHEN to_char(pp.fecha_hora_registro,'HH12')::int <= 12 THEN ' a.m.' ELSE ' p.m.' END as fecha_hora_registro,                                                
                        CASE WHEN  pp.estado_fase IS NULL THEN 'SIN PROCESO' 
                            ELSE 
                            CASE WHEN pp.estado_fase = 5 AND pp.estado_actividad IS NOT NULL THEN (SELECT proceso FROM estado_proceso WHERE cod_estado=6)||' - '||ac.nombre ELSE ep.proceso END
                            END as proceso                    
                    FROM plan_produccion pp 
                        LEFT JOIN estado_proceso ep ON pp.estado_fase = ep.cod_estado
                        LEFT JOIN actividad ac ON pp.estado_actividad = ac.cod_actividad
                    ORDER BY 1 DESC";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    /*FECHA SEGUIMIENTO DE PLAN DE PRODUCCION*/

    public function leerDatos(){
        try {
            $sql = "SELECT 
                        pp.nombre,
                        to_char(pp.fecha_hora_registro,'dd/MM/yyyy HH12:MI:SS')||CASE WHEN to_char(pp.fecha_hora_registro,'HH12')::int <= 12 THEN ' a.m.' ELSE ' p.m.' END as fecha_hora_registro,                        
                        CASE WHEN pp.finalizacion_fecha IS NULL THEN 'SIN FINALIZAR' ELSE to_char(pp.finalizacion_fecha,'dd/MM/yyyy HH12:MI:SS')||CASE WHEN to_char(pp.finalizacion_fecha,'HH12')::int <= 12 THEN ' a.m.' ELSE ' p.m.' END END as fecha_finalizacion,
                        CASE WHEN  pp.estado_fase IS NULL THEN 'SIN PROCESO' 
                            ELSE 
                            CASE WHEN pp.estado_fase = 5 AND pp.estado_actividad IS NOT NULL THEN (SELECT proceso FROM estado_proceso WHERE cod_estado=6)||' - '||ac.nombre ELSE ep.proceso END
                            END as proceso                    
                    FROM plan_produccion pp 
                        LEFT JOIN estado_proceso ep ON pp.estado_fase = ep.cod_estado
                        LEFT JOIN actividad ac ON pp.estado_actividad = ac.cod_actividad
                    WHERE pp.nombre= :0";
            $resultado = $this->consultarFilas($sql, array($this->getNombre()));
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }


    public function listarPlanPedido(){
        try {
            $sql = "SELECT 
                        'PE'||RIGHT('0000'||p.cod_pedido,4) as cod_pedido,
                        p.cod_pedido as nro,
                        COALESCE(c.razon_social,c.nombres||' '||c.apellidos) as cliente,
                        to_char(p.fecha_atencion::date,'dd/mm/YYYY') as fecha_atencion,
                        p.monto_total,
                        p.cod_plan_produccion,                        
                        CASE WHEN pp.estado_fase IS NULL THEN -1 ELSE pp.estado_fase END
                    FROM plan_produccion pp INNER JOIN pedido p ON pp.cod_plan_produccion = p.cod_plan_produccion
                                INNER JOIN cliente c ON p.cod_usuario_cliente = c.cod_cliente
                    WHERE pp.nombre = :0 AND pp.estado_mrcb=TRUE";
            $resultado = $this->consultarFilas($sql, array($this->getNombre()));
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function listarProductos(){
        try {
            $sql = "SELECT cod_plan_produccion FROM plan_produccion WHERE nombre = :0";
            $codigo_plan_produccion =  $this->consultarValor($sql, array($this->getNombre()));

            $sql = "SELECT 
                        'PR'||RIGHT('0000'||p.cod_producto,4) as cod_producto, 
                        p.cod_producto as codigo,
                        p.nombre, 
                        MIN(cantidad) as cantidad
                    FROM
                    (SELECT 
                        SUM(cantidad)::INT as cantidad , 
                        cod_producto 
                     FROM ( SELECT 
                            pi.cod_pieza, 
                            SUM(pd.cantidad)::INT as cantidad,
                            pi.cod_producto                   
                        FROM pedido p 
                            INNER JOIN pedido_detalle pd ON p.cod_pedido = pd.cod_pedido
                            INNER JOIN producto pro ON pd.cod_producto = pro.cod_producto
                            INNER JOIN pieza pi ON pi.cod_producto = pro.cod_producto
                        WHERE p.cod_plan_produccion = :0
                        GROUP BY pi.cod_pieza
                        UNION
                        SELECT 
                            tb.cod_pieza,
                            tb.cantidad,
                            tb.cod_producto 
                        FROM pieza tbp 
                            LEFT JOIN (SELECT 
                                    p.cod_pieza,
                                    SUM(rip.cantidad)::INT as cantidad,
                                    p.cod_producto
                                   FROM requisito_interno ri 
                                    INNER JOIN requisito_interno_pieza rip ON ri.cod_requisito_interno = rip.cod_requisito_interno
                                    INNER JOIN pieza p ON p.cod_pieza = rip.cod_pieza
                                       WHERE ri.cod_plan_produccion=:0 AND ri.estado_mrcb=true AND rip.estado_mrcb=true
                                   GROUP BY p.cod_pieza ) as tb ON tbp.cod_pieza= tb.cod_pieza
                        WHERE 
                            (SELECT COUNT(*) FROM pieza where cod_producto = tbp.cod_producto ) = 
                            (SELECT 
                                COUNT(distinct pi.cod_pieza)::INT
                             FROM requisito_interno ri 
                                INNER JOIN requisito_interno_pieza rip ON ri.cod_requisito_interno = rip.cod_requisito_interno
                                INNER JOIN pieza pi ON pi.cod_pieza = rip.cod_pieza
                             WHERE ri.cod_plan_produccion=:0 AND pi.cod_producto=tbp.cod_producto)   
                        ) as tabla  
                    GROUP BY cod_pieza, cod_producto) as tabla_2 
                        INNER JOIN producto p ON tabla_2.cod_producto = p.cod_producto
                    GROUP BY p.cod_producto
                    ORDER BY 1";
            $resultado = $this->consultarFilas($sql, array($codigo_plan_produccion));

            for ($i=0; $i < count($resultado) ; $i++) { 
                $pila = $this->listarPiezas($resultado[$i]["codigo"]);                
                $resultado[$i]["piezas"] = $pila;                
            }
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function listarPiezas($p1){
        try {
            $sql = "SELECT cod_plan_produccion FROM plan_produccion WHERE nombre = :0";
            $codigo_plan_produccion =  $this->consultarValor($sql, array($this->getNombre()));

            $sql = "SELECT 
            cod_producto,
                        'PI'||RIGHT('0000'||cod_pieza,4) as cod_pieza, 
                        cod_pieza as codigo, 
                        pieza, SUM(cantidad)::INT as cantidad 
                    FROM (
                        SELECT 
                            pro.cod_producto,
                            pi.cod_pieza, 
                            pi.nombre as pieza,
                            SUM(pd.cantidad)::INT as cantidad                    
                        FROM pedido p 
                            INNER JOIN pedido_detalle pd ON p.cod_pedido = pd.cod_pedido
                            INNER JOIN producto pro ON pd.cod_producto = pro.cod_producto
                            INNER JOIN pieza pi ON pi.cod_producto = pro.cod_producto
                        WHERE p.cod_plan_produccion = :0
                        GROUP BY pi.cod_pieza, pro.cod_producto
                        UNION
                        SELECT
                            pro.cod_producto, 
                            p.cod_pieza,
                            p.nombre as pieza,
                            SUM(rip.cantidad)::INT as cantidad
                        FROM requisito_interno ri 
                            INNER JOIN requisito_interno_pieza rip ON ri.cod_requisito_interno = rip.cod_requisito_interno
                            INNER JOIN pieza p ON p.cod_pieza = rip.cod_pieza
                            INNER JOIN producto pro ON pro.cod_producto = p.cod_producto
                        WHERE ri.cod_plan_produccion=:0 AND ri.estado_mrcb=true AND rip.estado_mrcb=true
                        GROUP BY p.cod_pieza, pro.cod_producto) as tabla 
                    WHERE cod_producto = :1
                    GROUP BY cod_pieza, pieza ,cod_producto
                    
                    ORDER BY 1";
            $resultado = $this->consultarFilas($sql, array($codigo_plan_produccion,$p1));
            return $resultado;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function listarPiezas2(){
        try {
            $sql = "SELECT cod_plan_produccion FROM plan_produccion WHERE nombre = :0";
            $codigo_plan_produccion =  $this->consultarValor($sql, array($this->getNombre()));

            $sql = "SELECT 
                        'PI'||RIGHT('0000'||cod_pieza,4) as cod_pieza, 
                        cod_pieza as codigo, 
                        pieza, SUM(cantidad)::INT as cantidad 
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
                        WHERE ri.cod_plan_produccion=:0 AND ri.estado_mrcb=true AND rip.estado_mrcb=true
                        GROUP BY p.cod_pieza) as tabla 
                    GROUP BY cod_pieza, pieza 
                    ORDER BY 1";
            $resultado = $this->consultarFilas($sql, array($codigo_plan_produccion));
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function validar(){
        try {            
            $sql = "SELECT 
                        CASE WHEN estado_fase IS NULL THEN '0' 
                            ELSE 
                                CASE WHEN estado_fase = 0 AND estado_actividad IS NULL THEN '1' -- INICIAL
                                     WHEN estado_fase = 4 AND estado_actividad IS NULL THEN '2' -- ACONDICIONAMIENTO
                                     WHEN estado_fase = 5 AND estado_actividad IS NULL THEN '3' -- FUNDICION
                                     WHEN estado_fase = 5 AND estado_actividad = 1 THEN 'A' --LLENADO DE MOLDES
                                     WHEN estado_fase = 5 AND estado_actividad = 2 THEN 'B' --ENFRIAMIENTO
                                     WHEN estado_fase = 5 AND estado_actividad = 3 THEN 'C' --DESMOLDAJE
                                     WHEN estado_fase = 5 AND estado_actividad = 4 THEN 'D' --MASILLADO
                                     WHEN estado_fase = 5 AND estado_actividad = 5 THEN 'E' --TORNEADO
                                     WHEN estado_fase = 5 AND estado_actividad = 6 THEN 'F' --PULIDO
                                     WHEN estado_fase = 5 AND estado_actividad = 7 THEN 'G' --PINTADO
                                     WHEN estado_fase = 5 AND estado_actividad = 8 THEN 'H' --ENSAMBLADO
                                     WHEN estado_fase = 5 AND estado_actividad = 9 THEN 'I' --EMPAQUETADO
                                     ELSE '5' END          -- FINAL
                            END
                    FROM plan_produccion WHERE nombre=:0";
            $resultado = $this->consultarValor($sql, array($this->getNombre()));
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }


    public function faseInicio() {
        $this->beginTransaction();
        try {  
            $sql = "SELECT cod_plan_produccion FROM plan_produccion WHERE nombre = :0";  // 19
            $codigo_plan_produccion =  $this->consultarValor($sql, array($this->getNombre()));

            $campos_valores = 
            array(  "estado_fase" =>0,
                    "fecha_inicio_proceso"=>$this->getFecha_inicio_proceso());

            $campos_valores_where = 
            array(  "nombre"=>$this->getNombre());

            $this->update($this->tb, $campos_valores,$campos_valores_where);
            
            $campos_valores = 
            array(  "estado_pedido"=>0);

            $campos_valores_where = 
            array(  "cod_plan_produccion"=>$codigo_plan_produccion);

            $this->update($this->tb_p, $campos_valores,$campos_valores_where);

            $this->commit();
            return array("rpt"=>true, "msj"=>"Se a registrado correctamente");                         
        } catch (Exception $exc) {
            return array("rpt"=>false, "msj"=> $exc); 
            $this->rollBack();
            throw $exc;
        }
    }

    public function listarMateriasPrimasPreAcondicionamiento()
    {
            $sql = "SELECT  
                    mp.cod_materia_prima as x_cod_materia_prima,
                    'MP'||RIGHT('0000'||mp.cod_materia_prima,4) as cod_materia_prima,
                    mp.nombre,   um.abreviatura,                 
                    mp.precio_base as precio,                   
                    COALESCE(SUM(mpp.cantidad * tb_pieza.cantidad), SUM(mpp.cantidad * pddt.cantidad)) as cant_necesaria,
                    COALESCE((SELECT cantidad FROM almacen_stock WHERE estado_mrcb AND cod_materia_prima = mp.cod_materia_prima),'0.00') as cant_almacen
                    FROM 
                    (SELECT cod_materia_prima, cod_pieza, cantidad, NULL as cod_producto
                    FROM materia_prima_pieza WHERE estado_mrcb 
                    UNION
                    SELECT cod_materia_prima, NULL, cantidad, cod_producto
                    FROM materia_prima_producto WHERE estado_mrcb ) mpp                    
                    INNER JOIN materia_prima mp ON mpp.cod_materia_prima = mp.cod_materia_prima
                    INNER JOIN unidad_medida um ON um.cod_unidad_medida = mp.cod_unidad_medida                                          
                    LEFT JOIN 
                    (
                    SELECT
                    cod_pieza,
                    SUM(cantidad)::numeric(10,2) as cantidad
                    FROM
                    (
                    SELECT 
                    pi.cod_pieza, 
                    SUM(pd.cantidad)::numeric(10,2) as cantidad                    
                    FROM pedido p INNER JOIN pedido_detalle pd ON p.cod_pedido = pd.cod_pedido
                          INNER JOIN producto pro ON pd.cod_producto = pro.cod_producto
                          INNER JOIN pieza pi ON pi.cod_producto = pro.cod_producto
                    WHERE p.cod_plan_produccion = :0
                    GROUP BY pi.cod_pieza
                    UNION
                    SELECT 
                    p.cod_pieza,
                    SUM(rip.cantidad)::numeric(10,2) as cantidad
                    FROM requisito_interno ri 
                        INNER JOIN requisito_interno_pieza rip ON ri.cod_requisito_interno = rip.cod_requisito_interno
                        INNER JOIN pieza p ON p.cod_pieza = rip.cod_pieza
                    WHERE ri.cod_plan_produccion= :0 AND ri.estado_mrcb=true AND rip.estado_mrcb=true
                    GROUP BY p.cod_pieza                    
                    ) as tabla
                    GROUP BY cod_pieza
                    ) as tb_pieza ON mpp.cod_pieza = tb_pieza.cod_pieza                               
                    LEFT JOIN 
                    (SELECT pd.cod_producto, cantidad FROM pedido_detalle pd LEFT JOIN pedido pe ON pe.cod_pedido = pd.cod_pedido AND cod_plan_produccion = 32 
                    AND pd.estado_mrcb)  pddt ON mpp.cod_producto = pddt.cod_producto         
                     WHERE (pddt.cod_producto IS NOT NULL AND pddt.cantidad IS NOT NULL) OR (mpp.cantidad IS NOT NULL AND mpp.cod_pieza IS NOT NULL)       
                    GROUP BY mp.cod_materia_prima, mpp.cod_materia_prima, um.cod_unidad_medida                          
                    ORDER BY 1";
            $mps = $this->consultarFilas($sql, array($this->getCodPlanProduccion()));
            $chatarra = array_shift($mps);

            $sql = "SELECT 
                         mp.cod_materia_prima as x_cod_materia_prima,
                        'MP'||RIGHT('0000'||mp.cod_materia_prima,4) as cod_materia_prima,
                         mp.nombre, precio_base as precio, um.abreviatura,
                        ((CASE cod_materia_prima
                        WHEN 2  THEN (select valor FROM variables_constantes where descripcion = 'lenha_x_chatarra')::numeric(3,2) * :0 
                        WHEN 3 THEN (select valor FROM variables_constantes where descripcion = 'carbon_x_chatarra')::numeric(3,2) * :0
                        ELSE (select valor FROM variables_constantes where descripcion = 'piedra_x_chatarra')::numeric(3,2) * :0 END)  ) as cant_necesaria,
                        COALESCE((SELECT cantidad FROM almacen_stock WHERE estado_mrcb AND cod_materia_prima = mp.cod_materia_prima),'0.00') as cant_almacen
                        FROM materia_prima  mp
                        INNER JOIN unidad_medida um ON um.cod_unidad_medida = mp.cod_unidad_medida 
                        WHERE tipo = 2";            

            $mp_fundicion = $this->consultarFilas($sql, array($chatarra["cant_necesaria"]));            
            array_push($mp_fundicion,$chatarra);

            return array("mps"=>$mps, "mp_fundicion"=>$mp_fundicion);
    }

    public function listarMateriasPrimasPostAcondicionamiento($cod_estado_acondicionamiento)
    {
            $sql = "SELECT 
                        mp.cod_materia_prima as x_cod_materia_prima,
                        'MP'||RIGHT('0000'||mp.cod_materia_prima,4) as cod_materia_prima,
                        mp.nombre, precio_base as precio, um.abreviatura,
                        ppmp.cant_necesaria, ppmp.cant_comprar, (cant_usada_almacen + cant_sobrante) as cant_almacen                        
                        FROM plan_produccion_materia_prima ppmp 
                        INNER JOIN materia_prima  mp ON mp.cod_materia_prima = ppmp.cod_materia_prima
                        INNER JOIN unidad_medida um ON um.cod_unidad_medida = mp.cod_unidad_medida 
                        WHERE ppmp.cod_plan_produccion = :0 AND mp.tipo <> 2 AND ppmp.plan_produccion_estado_fase = :1
                        ORDER BY 1";
            $mps = $this->consultarFilas($sql, array($this->getCodPlanProduccion(),$cod_estado_acondicionamiento));
            $chatarra = array_shift($mps);

            $sql = "SELECT 
                        mp.cod_materia_prima as x_cod_materia_prima,
                        'MP'||RIGHT('0000'||mp.cod_materia_prima,4) as cod_materia_prima,
                        mp.nombre, precio_base as precio, um.abreviatura,
                        ppmp.cant_necesaria, ppmp.cant_comprar, (cant_usada_almacen + cant_sobrante) as cant_almacen                        
                        FROM plan_produccion_materia_prima ppmp 
                        INNER JOIN materia_prima  mp ON mp.cod_materia_prima = ppmp.cod_materia_prima
                        INNER JOIN unidad_medida um ON um.cod_unidad_medida = mp.cod_unidad_medida 
                        WHERE ppmp.cod_plan_produccion = :0 AND mp.tipo = 2 AND ppmp.plan_produccion_estado_fase = :1";            

            $mp_fundicion = $this->consultarFilas($sql, array($this->getCodPlanProduccion(),$cod_estado_acondicionamiento));            
            array_push($mp_fundicion,$chatarra);

            return array("mps"=>$mps, "mp_fundicion"=>$mp_fundicion);
    }

    public function getEstadoProceso($nombre_proceso)
    {
        $sql = "SELECT cod_estado FROM estado_proceso WHERE proceso = :0 AND estado_mrcb";
        return $this->consultarValor($sql,array($nombre_proceso));
    }

    public function getEstadoActividad($nombre_proceso)
    {
        $sql = "SELECT cod_actividad FROM actividad WHERE nombre = :0  AND estado_mrcb";
        return $this->consultarValor($sql,array($nombre_proceso));
    }

    public function listarMateriasPrimas(){
        try {
            $sql = "SELECT cod_plan_produccion FROM plan_produccion WHERE nombre = :0";
            $this->setCodPlanProduccion($this->consultarValor($sql, array($this->getNombre())));

            $cod_estado_acondicionamiento = $this->getEstadoProceso('ACONDICIONAMIENTO');
            
            $sql = "SELECT estado_fase >= :0 FROM plan_produccion WHERE cod_plan_produccion = :1";
            $paso_acondicionamiento = $this->consultarValor($sql, array($cod_estado_acondicionamiento, $this->getCodPlanProduccion()));

            if ($paso_acondicionamiento){
                $arreglosMP = $this->listarMateriasPrimasPostAcondicionamiento($cod_estado_acondicionamiento);
            }
            else{
                $arreglosMP = $this->listarMateriasPrimasPreAcondicionamiento();    
            }

            return array("rpt"=>true, "data"=>array("mps"=>$arreglosMP["mps"],"mp_fundicion"=>$arreglosMP["mp_fundicion"], "paso_acondicionamiento"=>$paso_acondicionamiento));
        } catch (Exception $exc) {
            return array("rpt"=>false, "msj"=>$exc);
            throw $exc;
        }
    }

    public function agregarAcondicionamiento($JSONArregloMP){
        $this->beginTransaction();
        try {          
            session_name("_sis_produccion_");
            session_start();                      
      
            $cod_estado_fase = $this->consultarValor("SELECT  cod_estado FROM estado_proceso WHERE descripcion = 'ACONDICIONAMIENTO'");
            $campos_valores = 
            array(  "acondicionamiento_fecha"=>$this->getAcondicionamiento_fecha(),
                    "acondicionamiento_costo_extra"=>$this->getAcondicionamiento_costo_extra(),
                    "acondicionamiento_comentarios"=>$this->getAcondicionamiento_comentario(),
                    "acondicionamiento_costo_materia_prima"=>$this->getAcondicionamiento_costo_materia_prima(),
                    "acondicionamiento_costo_materia_prima_ahorro"=>$this->getAcondicionamiento_costo_materia_prima_ahorro(),
                    "estado_fase"=>$cod_estado_fase);

            $campos_valores_where = 
            array(  "nombre"=>$this->getNombre());

            /*Colocar valores de acondicionamiento*/
            $this->update($this->tb, $campos_valores,$campos_valores_where);

            $sql = "SELECT cod_plan_produccion FROM plan_produccion WHERE nombre=:0";
            $cod_plan_produccion = $this->consultarValor($sql, array($this->getNombre()));   

            $campos_valores = 
            array(  "estado_pedido"=>$cod_estado_fase);

            $campos_valores_where = 
            array(  "cod_plan_produccion"=>$cod_plan_produccion);

            /*Actualizar los pedidos del plan de producción*/
            $this->update($this->tb_p, $campos_valores,$campos_valores_where);

            /*Guardar toda la materia prima utilizada en grupo_ la comprada y la sacada de almace */

            $arregloMP = json_decode($JSONArregloMP);

            for ($i=0; $i <count($arregloMP) ; $i++) { 
                $item = $arregloMP[$i];
                $campos_valores = 
                array(  "cod_plan_produccion" => $cod_plan_produccion,                        
                        "cod_materia_prima"=>$item->cod_materia_prima,
                        "cant_necesaria"=>$item->cant_necesaria,
                        "cant_comprar"=>$item->cant_comprar,
                        "cant_usada_almacen"=>$item->cant_usada_almacen,                    
                        "cant_sobrante"=>$item->cant_sobrante,
                        "costo_unitario_materia_prima"=> $item->costo_unitario_materia_prima,
                        "plan_produccion_estado_fase"=>$cod_estado_fase
                        );

                $this->insert("plan_produccion_materia_prima", $campos_valores);                
            } 

            /*Guardar las ENTRADAS del almacen, si las hay.*/

            /*Insertar operacion*/
            $campos_valores = 
                array(  "cod_plan_produccion" => $cod_plan_produccion,                        
                        "tipo_operacion"=>"E",
                        "cod_plan_produccion_fase"=>$cod_estado_fase,
                        "cod_almacen"=>1,
                        "cod_usuario_registro"=>$_SESSION["cod_usuario"],
                        );
            $this->insert("almacen_operacion", $campos_valores);         
            /*Insertar detalle operacion*/

            $cod_almacen_operacion = $this->consultarValor("SELECT MAX(cod_almacen_operacion) FROM almacen_operacion"); 

            for ($i=0; $i < count($arregloMP) ; $i++) {
                /*solo se deberá insertar cuando la cantidad a salir del almacen sea mayor que 1 redondeado a 2 cifras significativas.*/
                $item = $arregloMP[$i];
                if (round($item->cant_comprar,3) > 0.000){
                    $campos_valores = 
                    array(  "cod_almacen_operacion" => $cod_almacen_operacion,                        
                            "cod_materia_prima"=>$item->cod_materia_prima,
                            "cantidad"=>$item->cant_comprar
                            );
                    $this->insert("almacen_operacion_detalle", $campos_valores);        
                }
            }

            /*Finalmnete insertar en stock si es necesario.*/
             for ($i=0; $i <count($arregloMP) ; $i++) {
                /*solo se deberá insertar cuando la cantidad a 
                entrar al almacen si es mayor que 1 redondeado a cifras significativas.
                Si existe, actualiza, sino inserta.
                */
                $item = $arregloMP[$i];
                if (round($item->cant_comprar,3) > 0.000){
                    $valorMP  = $this->consultarFila("SELECT cod_almacen_stock, cantidad FROM almacen_stock WHERE estado_mrcb AND cod_materia_prima = :0", array($item->cod_materia_prima));
                    if ($valorMP != false){
                        $aumento_stock = $valorMP["cantidad"] + $item->cant_comprar;
                        $campos_valores = 
                        array(  "cod_almacen" => 1,                        
                                "cod_materia_prima"=>$item->cod_materia_prima,
                                "cantidad"=> $aumento_stock
                                );
                        $campos_valores_where = 
                        array( "cod_almacen_stock" => $valorMP["cod_almacen_stock"] );
                        $this->update("almacen_stock", $campos_valores, $campos_valores_where );      
                    } else {
                        /*Lo insertamos.*/
                        $campos_valores = 
                        array(  "cod_almacen" => 1,                        
                                "cod_materia_prima"=>$item->cod_materia_prima,
                                "cantidad"=> $item->cant_comprar
                                );

                        $this->insert("almacen_stock", $campos_valores );      
                    }
                }
            }

            //var_dump($this->consultarFilas("SELECT * FROM plan_produccion"));

            $this->commit();
            return array("rpt"=>true,"msj"=>"Se registrado correctamente");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }

    public function listarAcondicionamiento(){   
        try {
            $sql = "SELECT 
                        acondicionamiento_costo_extra as costo,
                        acondicionamiento_comentarios as comentario,
                        to_char(acondicionamiento_fecha, 'dd/MM/yyyy HH12:MI ')||(CASE WHEN date_part('hour', acondicionamiento_fecha)::int >= 12 THEN 'p.m.' ELSE 'a.m.' END) as fecha
                    FROM plan_produccion WHERE nombre=:0";
            $resultado = $this->consultarFila($sql, array($this->getNombre()));
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
       
    }

    public function agregarFundicion($JSONdetallePiezas, $JSONArregloMP){
        session_name("_sis_produccion_");
        session_start(); 
        $this->beginTransaction();
        try {
            $cod_estado_fase = $this->getEstadoProceso("FUNDICION");
            $campos_valores = 
            array(  "fundicion_horas"=>$this->getFundicion_horas(),
                    "fundicion_costo_extra"=>$this->getFundicion_costo_extra(),
                    "fundicion_comentarios"=>$this->getFundicion_comentarios(),
                    "fundicion_fecha"=>$this->getFundicion_fecha(),
                    "estado_fase"=>5);

            $campos_valores_where = 
            array(  "nombre"=>$this->getNombre());

            $this->update($this->tb, $campos_valores,$campos_valores_where);

            $sql = "SELECT cod_plan_produccion FROM plan_produccion WHERE nombre=:0";
            $cod_plan_produccion = $this->consultarValor($sql, array($this->getNombre()));

            $campos_valores = 
            array(  "estado_pedido"=>$cod_estado_fase);

            $campos_valores_where = 
            array(  "cod_plan_produccion"=>$cod_plan_produccion);

            $this->update($this->tb_p, $campos_valores,$campos_valores_where);             

            $detalle = json_decode($JSONdetallePiezas);

            $campos_valores = 
            array(  "cod_actividad" => 1,
                    "cod_usuario_registro"=>$_SESSION["cod_usuario"]);

            $this->insert("actividad_plan_produccion", $campos_valores);

            $sql = "SELECT MAX(cod_actividad_plan_produccion) FROM actividad_plan_produccion";
            $maximo =  $this->consultarValor($sql);

            for ($i=0; $i <count($detalle) ; $i++) { 
                $item = $detalle[$i];
                $campos_valores1 = 
                array(  "cod_plan_produccion" => $cod_plan_produccion,
                        "cod_actividad_plan_produccion"=>$maximo,
                        "cod_pieza"=>$item->p_codigo_pieza,
                        "piezas_total"=>$item->p_piezas_total,
                        "piezas_buenas"=>$item->p_piezas_buenas,
                        "piezas_falladas"=>$item->p_piezas_falladas);

                $this->insert("plan_produccion_pieza", $campos_valores1);                
            } 

            $arregloMP = json_decode($JSONArregloMP);
            for ($i=0; $i <count($arregloMP) ; $i++) { 
                $item = $arregloMP[$i];
                $campos_valores = 
                array(  "cod_plan_produccion" => $cod_plan_produccion,                        
                        "cod_materia_prima"=>$item->cod_materia_prima,                                            
                        "cant_usada_almacen"=>$item->cant_usada_almacen,                    
                        "cant_sobrante"=>$item->cant_sobrante,
                        "costo_unitario_materia_prima"=> $item->costo_unitario_materia_prima,
                        "plan_produccion_estado_fase"=>$cod_estado_fase
                        );

                $this->insert("plan_produccion_materia_prima", $campos_valores);                
            } 

            /*Guardar las SALIDAS del almacen, si las hay.*/

            /*Insertar operacion*/
            $campos_valores = 
                array(  "cod_plan_produccion" => $cod_plan_produccion,                        
                        "tipo_operacion"=>"S",
                        "cod_plan_produccion_fase"=>$cod_estado_fase,
                        "cod_almacen"=>1,
                        "cod_usuario_registro"=>$_SESSION["cod_usuario"],
                        );
            $this->insert("almacen_operacion", $campos_valores);         
            /*Insertar detalle operacion*/

            $cod_almacen_operacion = $this->consultarValor("SELECT MAX(cod_almacen_operacion) FROM almacen_operacion"); 
            for ($i=0; $i <count($arregloMP) ; $i++) {
                /*solo se deberá insertar cuando la cantidad a salir del almacen sea mayor que 1 redondeado a 2 cifras significativas.
                    Si este valor es 0 o menor por algun motivo debería votar error.*/
                if (round($item->cant_usada_almacen,2) > 0.00){
                    $item = $arregloMP[$i];
                    $campos_valores = 
                    array(  "cod_almacen_operacion" => $cod_almacen_operacion,                        
                            "cod_materia_prima"=>$item->cod_materia_prima,
                            "cantidad"=>$item->cant_usada_almacen
                            );
                    $this->insert("almacen_operacion_detalle", $campos_valores);           
                } else{
                    return array("rpt"=>false,"msj"=>"ERROR. Se está generando salida de materia prima con una cantidad menor igual que 0");
                }
            }

            /*Finalmnete insertar en stock si es necesario. (Aquí hipotéticamente siempre será necesario.*/
             for ($i=0; $i <count($arregloMP) ; $i++) {
                /*solo se deberá insertar cuando la cantidad a 
                entrar al almacen si es mayor que 1 redondeado a cifras significativas.
                Si existe, actualiza, sino error.
                */
                if (round($item->cant_usada_almacen,2) > 0.00){
                    $item = $arregloMP[$i];
                    $valorMP  = $this->consultarFila("SELECT cod_almacen_stock, cantidad FROM almacen_stock WHERE estado_mrcb AND cod_materia_prima = :0", array($item->cod_materia_prima));
                    if ($valorMP != false){
                        $diferencia_stock = $valorMP["cantidad"] - $item->cant_usada_almacen;

                        if ($diferencia_stock < 0.000){
                            return array("rpt"=>false,"msj"=>"ERROR. La salida de stock de esta materia la deja en NEGATIVO, verifique datos.");
                        }
                        $campos_valores = 
                        array(  "cod_almacen" => 1,                        
                                "cod_materia_prima"=>$item->cod_materia_prima,
                                "cantidad"=> $diferencia_stock
                                );
                        $campos_valores_where = 
                        array( "cod_almacen_stock" => $valorMP["cod_almacen_stock"] );
                        $this->update("almacen_stock", $campos_valores, $campos_valores_where );      
                    } else {
                        return array("rpt"=>false,"msj"=>"ERROR. Se está tratando de retirar materia prima que no existe en almacen.");
                    }
                } 
            }

            $this->commit();
            return array("rpt"=>true,"msj"=>"Se ha registrado correctamente","actividad"=>$maximo);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }        
    }

    public function listarMateriasPrimasPreFundicion($cod_estado_acondicionamiento)
    {        
            $sql = "SELECT  
                    ppmp.cod_materia_prima as x_cod_materia_prima,
                    'MP'||RIGHT('0000'||ppmp.cod_materia_prima,4) as cod_materia_prima,
                    mp.nombre,
                    um.abreviatura,
                    costo_unitario_materia_prima as precio,
                    cant_necesaria as cant_usar,
                    (SELECT cantidad FROM almacen_stock WHERE cod_materia_prima = ppmp.cod_materia_prima) as cant_almacen
                    FROM
                    plan_produccion_materia_prima ppmp
                    INNER JOIN materia_prima mp ON mp.cod_materia_prima = ppmp.cod_materia_prima
                    INNER JOIN unidad_medida um ON um.cod_unidad_medida = mp.cod_unidad_medida
                    WHERE cod_plan_produccion = :0 AND 
                    plan_produccion_estado_fase = :1
                    AND ppmp.cod_materia_prima <= 4
                    ORDER BY 1
                    ";
            $mps = $this->consultarFilas($sql, array($this->getCodPlanProduccion(), $cod_estado_acondicionamiento));

            return $mps;
    }

    public function listarMateriasPrimasPostFundicion()
    {
        /*post: consultas las materias primas guardadas en el acutal estadío*/
            $cod_estado_fundicion = $this->getEstadoProceso('FUNDICION');
            $sql = "SELECT  
                    ppmp.cod_materia_prima as x_cod_materia_prima,
                    'MP'||RIGHT('0000'||ppmp.cod_materia_prima,4) as cod_materia_prima,
                    mp.nombre,
                    um.abreviatura,
                    costo_unitario_materia_prima as precio,
                    cant_usada_almacen as cant_usar,
                    cant_usada_almacen + cant_sobrante as cant_almacen
                    FROM
                    plan_produccion_materia_prima ppmp
                    INNER JOIN materia_prima mp ON mp.cod_materia_prima = ppmp.cod_materia_prima
                    INNER JOIN unidad_medida um ON um.cod_unidad_medida = mp.cod_unidad_medida
                    WHERE cod_plan_produccion = :0 AND 
                    plan_produccion_estado_fase = :1
                    AND ppmp.cod_materia_prima <= 4
                    ORDER BY 1
                    ";
            $mps = $this->consultarFilas($sql, array($this->getCodPlanProduccion(), $cod_estado_fundicion));

            return $mps;
    }

    public function listarFundicion(){   
        try {
            $sql = "SELECT cod_plan_produccion FROM plan_produccion WHERE nombre = :0";
            $this->setCodPlanProduccion($this->consultarValor($sql, array($this->getNombre())));

            $sql = "SELECT 
                        estado_fase,
                        fundicion_horas as horas,
                        fundicion_costo_extra as costo,
                        fundicion_comentarios as comentario,
                        to_char(fundicion_fecha, 'dd/MM/yyyy HH12:MI ')||(CASE WHEN date_part('hour', acondicionamiento_fecha)::int >= 12 THEN 'p.m.' ELSE 'a.m.' END) as fecha
                    FROM plan_produccion WHERE cod_plan_produccion=:0";

            $pp = $this->consultarFila($sql, array($this->getCodPlanProduccion()));
            
            $cod_estado_acondicionamiento = $this->getEstadoProceso('ACONDICIONAMIENTO');
            if ($pp["estado_fase"] <= $cod_estado_acondicionamiento ) {
                $mps = $this->listarMateriasPrimasPreFundicion($cod_estado_acondicionamiento);
            } else {
                $mps = $this->listarMateriasPrimasPostFundicion();
            }

            $chatarra = array_shift($mps);
            array_push($mps,$chatarra);

            return array("rpt"=>true,"data"=>array("pp"=>$pp, "mps"=>$mps));
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }       
    }

      

    public function listarActividad($p1){
        try {
            $texto = '';
            switch ( $p1 ) {
                case '1':
                    $texto = ' LIMIT 1';
                    break;                
                case '2':
                    $texto = ' LIMIT 1 OFFSET 1';
                    break;
                case '3':
                    $texto = ' LIMIT 1 OFFSET 2';
                    break;
                case '4':
                    $texto = ' LIMIT 1 OFFSET 3';
                    break;
                case '5':
                    $texto = ' LIMIT 1 OFFSET 4';
                    break;
                case '6':
                    $texto = ' LIMIT 1 OFFSET 5';
                    break;
                case '7':
                    $texto = ' LIMIT 1 OFFSET 6';
                    break;
            }

            $sql = "SELECT cod_plan_produccion FROM plan_produccion WHERE nombre = :0";
            $codigo_plan_produccion =  $this->consultarValor($sql, array($this->getNombre()));

            $sql = "SELECT 
                    cantidad_hombres,
                    to_char(fecha_inicio, 'dd/MM/yyyy HH12:MI ')||(CASE WHEN date_part('hour', fecha_inicio)::int >= 12 THEN 'p.m.' ELSE 'a.m.' END) as fecha_inicio,
                    to_char(fecha_fin, 'dd/MM/yyyy HH12:MI ')||(CASE WHEN date_part('hour', fecha_fin)::int >= 12 THEN 'p.m.' ELSE 'a.m.' END) as fecha_fin,
                    observaciones 
                    FROM actividad_plan_produccion 
                    WHERE cod_actividad_plan_produccion = 
                    (
                        SELECT ap.cod_actividad_plan_produccion 
                        FROM plan_produccion_pieza pp 
                        INNER JOIN actividad_plan_produccion ap ON pp.cod_actividad_plan_produccion = ap.cod_actividad_plan_produccion
                        WHERE pp.cod_plan_produccion = :0
                        GROUP BY ap.cod_actividad_plan_produccion
                        ORDER BY 1 
                        ".$texto."
                    )";
            $resultado = $this->consultarFila($sql, array($codigo_plan_produccion));
            return array("rpt"=>true,"msj"=>$resultado);            
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function listarEnsamblado()
    {
       try {
            $sql = "SELECT cod_plan_produccion, estado_actividad FROM plan_produccion WHERE nombre = :0";
            $pp = $this->consultarFila($sql, array($this->getNombre()));

            $this->setCodPlanProduccion($pp["cod_plan_produccion"]);
            $cod_estado_ensamblado = $this->getEstadoActividad('ENSAMBLADO');

            if ($pp["estado_actividad"] <= $cod_estado_ensamblado ) {
                $data = $this->listarPreEnsamblado();
            } else {
                $data = $this->listarPostEnsamblado();
            }

            return array("rpt"=>true,"msj"=>$data);            
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
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
            $productos = $this->consultarFilas($sql, array($this->getCodPlanProduccion()));

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

            $piezas = $this->consultarFilas($sql, array($this->getCodPlanProduccion()));

            return array("piezas"=>$piezas,"productos"=>$productos);            
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }  


    public function listarPostEnsamblado()
    {
        return [];
    }

    public function listarFActividad($p1){
        try {
            $sql = "SELECT cod_plan_produccion FROM plan_produccion WHERE nombre = :0";
            $codigo_plan_produccion =  $this->consultarValor($sql, array($this->getNombre()));

            $sql = "SELECT 
                        cantidad_hombres,
                        to_char(fecha_inicio, 'dd/MM/yyyy HH12:MI ')||(CASE WHEN date_part('hour', fecha_inicio)::int >= 12THEN 'p.m.' ELSE 'a.m.' END) as fecha_inicio,
                        to_char(fecha_fin, 'dd/MM/yyyy HH12:MI ')||(CASE WHEN date_part('hour', fecha_fin)::int >= 12THEN 'p.m.' ELSE 'a.m.' END) as fecha_fin,
                        observaciones 
                    FROM actividad_plan_produccion 
                    WHERE cod_plan_produccion=:0 AND cod_actividad=:1";
            $resultado = $this->consultarFila($sql, array($codigo_plan_produccion,$p1));
            return array("rpt"=>true,"msj"=>$resultado);            
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

     public function listarEmpaquetado(){
        try {
            $sql = "SELECT cod_plan_produccion FROM plan_produccion WHERE nombre = :0";
            $this->setCodPlanProduccion($this->consultarValor($sql, array($this->getNombre())));

            $cod_actividad_ensamblado = $this->getEstadoActividad("ENSAMBLADO");
            $cod_estado_empaquetado = $this->getEstadoActividad('EMPAQUETADO');

            
            $sql = "SELECT estado_actividad >= :0 FROM plan_produccion WHERE cod_plan_produccion = :1";
            $paso_actividad= $this->consultarValor($sql, array($cod_estado_empaquetado, $this->getCodPlanProduccion()));

            $sql = "SELECT 
                    aod.cod_pieza as codigo, cantidad::int as cantidad,
                    'PI'||RIGHT('0000'||pz.cod_pieza,4) as cod_pieza, pz.nombre, pz.cod_producto as codigo
                    FROM  almacen_operacion ao
                    INNER JOIN almacen_operacion_detalle aod ON aod.cod_almacen_operacion = ao.cod_almacen_operacion
                    INNER JOIN pieza pz ON pz.cod_pieza = aod.cod_pieza
                    WHERE cod_plan_produccion = :0 AND tipo_operacion = 'E' AND cod_plan_produccion_actividad = :1";

            $piezas = $this->consultarFilas($sql, array($this->getCodPlanProduccion(),$cod_actividad_ensamblado));

            $sql = "SELECT 
                'MP'||RIGHT('0000'||mp.cod_materia_prima,4) as cod_materia_prima,
                mp.cod_materia_prima as codigo, 
                SUM(aod.cantidad) as cantidad_recuperada,
                mp.nombre, 
                ppmp.costo_unitario_materia_prima as precio_base
                FROM  almacen_operacion ao
                INNER JOIN almacen_operacion_detalle aod ON aod.cod_almacen_operacion = ao.cod_almacen_operacion
                INNER JOIN materia_prima mp ON mp.cod_materia_prima = aod.cod_materia_prima
                INNER join plan_produccion_materia_prima ppmp ON ppmp.cod_plan_produccion = ao.cod_plan_produccion 
                AND ppmp.cod_materia_prima = mp.cod_materia_prima AND ppmp.plan_produccion_estado_fase = (SELECT cod_estado FROM estado_proceso WHERE proceso = 'ACONDICIONAMIENTO' AND estado_mrcb)
                WHERE ao.tipo_operacion = 'E' AND ao.cod_plan_produccion = :0  AND recuperacion 
                GROUP BY mp.cod_materia_prima, mp.nombre, ppmp.costo_unitario_materia_prima ";

            $mp = $this->consultarFilas($sql, array($this->getCodPlanProduccion()));            

            if ($paso_actividad){
                $productos = $this->listarPreEmpaquetado();
            }
            else{
                $productos = $this->listarPreEmpaquetado(); //post
            }        

            return array("rpt"=>true, "data"=> array("paso"=>$paso_actividad, "productos"=>$productos,"mp"=>$mp, "piezas"=>$piezas));
        } catch (Exception $exc) {
            return array("rpt"=>false, "msj"=>$exc);
            throw $exc;
        }
    }

    public function listarPreEmpaquetado()
    {
        /*post: consultas las materias primas guardadas en el acutal estadío*/
        $cod_actividad_ensamblado = $this->getEstadoActividad("ENSAMBLADO");

            $sql = "SELECT  
                    'PR'||RIGHT('0000'||pro.cod_producto,4) as cod_producto, 
                    pro.cod_producto as codigo,
                    pro.nombre,
                    cantidad, NULL as costo_produccion
                    FROM plan_produccion_producto ppp
                    INNER JOIN actividad_plan_produccion app ON  
                    app.cod_actividad_plan_produccion = ppp.cod_actividad_plan_produccion
                    INNER JOIN producto pro ON ppp.cod_producto = pro.cod_producto
                    WHERE cod_plan_produccion = :0 AND app.cod_actividad = :1 AND ppp.estado_mrcb";

            return $this->consultarFilas($sql, array($this->getCodPlanProduccion(),$cod_actividad_ensamblado));

    }

    public function listarPostEmpaquetado()
    {
        /*post: consultas las materias primas guardadas en el acutal estadío*/
        $cod_actividad_ensamblado = $this->getEstadoActividad("ENSAMBLADO");

            $sql = "SELECT  
                    'PR'||RIGHT('0000'||pro.cod_producto,4) as cod_producto, 
                    pro.cod_producto as codigo,
                    pro.nombre,
                    cantidad, NULL as costo_produccion
                    FROM plan_produccion_producto ppp
                    INNER JOIN actividad_plan_produccion app ON  
                    app.cod_actividad_plan_produccion = ppp.cod_actividad_plan_produccion
                    INNER JOIN producto pro ON ppp.cod_producto = pro.cod_producto
                    WHERE cod_plan_produccion = :0 AND app.cod_actividad = :1 AND ppp.estado_mrcb";

            return $this->consultarFilas($sql, array($this->getCodPlanProduccion(),$cod_actividad_ensamblado));

    }

      public function listarPiezasActividad($p1){
        try {
            $sql = "SELECT cod_plan_produccion FROM plan_produccion WHERE nombre = :0";
            $codigo_plan_produccion =  $this->consultarValor($sql, array($this->getNombre()));

                    $sql = "SELECT 
                                pp.cod_plan_produccion_pieza,
                                'PI'||RIGHT('0000'||pp.cod_pieza,4) as cod_pieza, 
                                pp.cod_pieza as codigo,
                                (SELECT nombre FROM pieza WHERE cod_pieza=pp.cod_pieza) as pieza,
                                pp.piezas_total, 
                                pp.piezas_buenas,
                                pp.piezas_falladas,
                                ap.cod_actividad,
                                pp.cod_plan_produccion_pieza,
                                COALESCE((SELECT 
                                    pp.piezas_falladas *
                                    SUM(cantidad) * 
                                    (SELECT valor::numeric FROM variables_constantes WHERE descripcion = 'porcentaje_recuperacion_chatarra')
                                    FROM materia_prima_pieza mpp
                                    WHERE cod_pieza = pp.cod_pieza AND cod_materia_prima = 1)::numeric(7,2), '0.00') as retorno_chatarra,
                                COALESCE(p.estado_actividad + 1, 1) as estado_actividad
                                /*
                                CASE WHEN p.estado_actividad IS NULL THEN 1 
                                     WHEN p.estado_actividad = 1 THEN 2
                                     WHEN p.estado_actividad = 2 THEN 3
                                     WHEN p.estado_actividad = 3 THEN 4
                                     WHEN p.estado_actividad = 4 THEN 5
                                     WHEN p.estado_actividad = 5 THEN 6
                                     WHEN p.estado_actividad = 6 THEN 7
                                     WHEN p.estado_actividad = 7 THEN 8
                                     WHEN p.estado_actividad = 8 THEN 9
                                     WHEN p.estado_actividad = 9 THEN 10
                                END AS estado_actividad,*/
                            FROM 
                            plan_produccion p 
                                INNER JOIN plan_produccion_pieza pp ON p.cod_plan_produccion = pp.cod_plan_produccion
                                INNER JOIN actividad_plan_produccion ap ON pp.cod_actividad_plan_produccion = ap.cod_actividad_plan_produccion
                            WHERE pp.cod_plan_produccion = :0 AND ap.cod_actividad = :1
                            ORDER BY 1";
            $resultado = $this->consultarFilas($sql, array($codigo_plan_produccion,$p1));
            return array("rpt"=>true,"msj"=>$resultado);            
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }  


   public function listarMateriasPrimasPre(){
        try {
            $sql = "SELECT cod_plan_produccion FROM plan_produccion WHERE nombre = :0";
            $this->setCodPlanProduccion($this->consultarValor($sql, array($this->getNombre())));

            $cod_estado_acondicionamiento = $this->getEstadoProceso('ACONDICIONAMIENTO');
            
            $arreglosMP = $this->listarMateriasPrimasPreAcondicionamiento();    

            return array("rpt"=>true, "data"=>array("mps"=>$arreglosMP["mps"],"mp_fundicion"=>$arreglosMP["mp_fundicion"]));
        } catch (Exception $exc) {
            return array("rpt"=>false, "msj"=>$exc);
            throw $exc;
        }
    }

}

