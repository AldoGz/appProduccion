<?php

require_once '../datos/Conexion.clase.php';

class Pedido extends Conexion {
    private $cod_pedido;
    private $fecha_entrega;
    private $monto_total;
    private $cod_plan_produccion;
    private $fecha_atencion;
    private $estado_pedido;
    private $pagado;
    private $estado_mrcb;
    private $fecha_hora_registro;
    private $foto;
    private $igv;
    private $sub_total;
    private $codigo_usuario;

    private $tb = "pedido";
    private $tb_dp = "pedido_detalle";
    private $tb_cp = "comprobante_pago";

    public function getCodigo_usuario()
    {
        return $this->codigo_usuario;
    }
    
    public function setCodigo_usuario($codigo_usuario)
    {
        $this->codigo_usuario = $codigo_usuario;
        return $this;
    }
 
  	public function getCod_pedido()
  	{
  	    return $this->cod_pedido;
  	}
  	 
  	public function setCod_pedido($cod_pedido)
  	{
  	    $this->cod_pedido = $cod_pedido;
  	    return $this;
  	}

  	public function getFecha_entrega()
  	{
  	    return $this->fecha_entrega;
  	}
  	 
  	public function setFecha_entrega($fecha_entrega)
  	{
  	    $this->fecha_entrega = $fecha_entrega;
  	    return $this;
  	}

  	public function getMonto_total()
  	{
  	    return $this->monto_total;
  	}
  	 
  	public function setMonto_total($monto_total)
  	{
  	    $this->monto_total = $monto_total;
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

    public function getFecha_atencion()
    {
        return $this->fecha_atencion;
    }
     
    public function setFecha_atencion($fecha_atencion)
    {
        $this->fecha_atencion = $fecha_atencion;
        return $this;
    }

  	public function getEstado_pedido()
  	{
  	    return $this->estado_pedido;
  	}
  	 
  	public function setEstado_pedido($estado_pedido)
  	{
  	    $this->estado_pedido = $estado_pedido;
  	    return $this;
  	}

  	public function getPagado()
  	{
  	    return $this->pagado;
  	}
  	 
  	public function setPagado($pagado)
  	{
  	    $this->pagado = $pagado;
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

  	public function getFoto()
  	{
  	    return $this->foto;
  	}
  	 
  	public function setFoto($foto)
  	{
  	    $this->foto = $foto;
  	    return $this;
  	}

  	public function getIgv()
  	{
  	    return $this->igv;
  	}
  	 
  	public function setIgv($igv)
  	{
  	    $this->igv = $igv;
  	    return $this;
  	}

  	public function getSub_total()
  	{
  	    return $this->sub_total;
  	}
  	 
  	public function setSub_total($sub_total)
  	{
  	    $this->sub_total = $sub_total;
  	    return $this;
  	}

  	public function agregar($p_detalle,$p2,$p3,$p4,$p5) {  
  		  session_name("_sis_produccion_");
        session_start();      
        $this->beginTransaction();
        try {
            
            $campos_valores = 
            array(  "monto_total"=>$this->getMonto_total(),                    
                    "cod_usuario_cliente"=>$this->getCodigo_usuario(),
                    "igv"=>$this->getIgv(),
                    "sub_total"=>$this->getSub_total());

            $this->insert($this->tb, $campos_valores);

            $sql = "SELECT MAX(cod_pedido) FROM $this->tb";
            $codigo_pedido = $this->consultarValor($sql);

            $arreglo = json_decode($p_detalle); 

            for ($i=0; $i <count($arreglo) ; $i++) { 
                $item = $arreglo[$i];

                $campos_valores1 = 
                array(  "cod_pedido"=>$codigo_pedido,
                		    "cod_producto"=>$item->p_codigo_producto,                    
                        "cantidad"=>$item->p_cantidad,
                        "precio"=>$item->p_precio,
                        "importe"=>$item->p_importe);

                $this->insert($this->tb_dp, $campos_valores1);
            }
            
            if ( $p2 != '' && $p3 != '' && $p4 != '' && $p5 != '') {
                $campos_valores1 = 
                array(  "codigo_departamento"=>$p2,
                        "codigo_provincia"=>$p3,                    
                        "codigo_distrito"=>$p4,
                        "codigo_pedido"=>$codigo_pedido,
                        "direccion"=>$p5);

                $this->insert("ubigeo_pedido", $campos_valores1);
            }

            


            $this->commit();
            return array("rpt"=>true,"msj"=>"Se agregado exitosamente");

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
            array(  "fecha_atencion"=>$this->getFecha_atencion(),
                    "estado_mrcb"=>$this->getEstado_mrcb(),
                    "estado_pedido" =>3);

            $campos_valores_where = 
            array(  "cod_pedido"=>$this->getCod_pedido());

            $this->update($this->tb, $campos_valores,$campos_valores_where);

            $this->commit();
            return array("rpt"=>true,"msj"=>"Se anulado existosamente");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
        
    }   

    public function misPedidos(){
        session_name("_sis_produccion_");
        session_start(); 
        try {
            $sql = "SELECT  
                        p.cod_pedido,
                        (SELECT COUNT(*) FROM pedido_detalle WHERE cod_pedido=p.cod_pedido) as cantidad_pedido,
                        to_char(p.fecha_hora_registro,'dd/MM/yyyy HH12:MI:SS')||CASE WHEN to_char(p.fecha_hora_registro,'HH12')::int <= 12 THEN ' a.m.' ELSE ' p.m.' END as fecha_hora_registro,
                        p.igv,
                        p.sub_total,
                        p.monto_total,
                        CASE WHEN p.pagado = FALSE THEN 'PENDIENTE' ELSE 'CANCELADO' END as estado_pago,
                        CASE WHEN p.estado_pedido IS NULL THEN 'EN PROCESO' ELSE ep.proceso END,
                        COALESCE(cli.razon_social,cli.nombres||' '||cli.apellidos) as cliente                       
                    FROM pedido p LEFT JOIN estado_proceso ep ON p.estado_pedido = ep.cod_estado
                        INNER JOIN cliente cli ON p.cod_usuario_cliente = cli.cod_cliente 
                    WHERE p.estado_mrcb=TRUE
                    ORDER BY 1 DESC";
            $resultado = $this->consultarFilas($sql);            
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

     public function listarNuevo(){
        try {
            $sql = "SELECT  
                        p.cod_pedido,
                        COALESCE(c.razon_social,c.nombres||' '||c.apellidos) as cliente,
                        (SELECT COUNT(*) FROM pedido_detalle WHERE cod_pedido=p.cod_pedido) as cantidad_pedido,
                        to_char(p.fecha_hora_registro,'dd/MM/yyyy HH12:MI:SS')||CASE WHEN to_char(p.fecha_hora_registro,'HH12')::int <= 12 THEN ' a.m.' ELSE ' p.m.' END as fecha_hora_registro,
                        p.igv,
                        p.sub_total,
                        p.monto_total,
                        CASE WHEN p.pagado = FALSE THEN 'PENDIENTE' ELSE 'CANCELADO' END as estado_pago,
                        CASE WHEN p.estado_pedido IS NULL THEN 'EN PROCESO' ELSE ep.proceso END                      
                    FROM pedido p LEFT JOIN estado_proceso ep ON p.estado_pedido = ep.cod_estado 
                    INNER JOIN usuario u ON p.cod_usuario_cliente = u.cod_usuario
                    INNER JOIN cliente c ON u.cod_cliente = c.cod_cliente
                    WHERE p.estado_mrcb";
            $resultado = $this->consultarFilas($sql);            
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function listar(){
        try {
            $sql = "SELECT 
                        p.cod_pedido,
                        COALESCE(c.razon_social,c.nombres||' '||c.apellidos) as cliente,
                        to_char(p.fecha_hora_registro,'dd/MM/yyyy HH12:MI:SS')||CASE WHEN to_char(p.fecha_hora_registro,'HH12')::int <= 12 THEN ' a.m.' ELSE ' p.m.' END as fecha_hora_registro,
                        CASE WHEN p.pagado = FALSE THEN 'PENDIENTE' ELSE 'CANCELADO' END as estado_pago,
                        p.igv,
                        p.sub_total,
                        p.monto_total
                    FROM pedido p INNER JOIN usuario u ON p.cod_usuario_cliente = u.cod_usuario
                                  INNER JOIN cliente c ON u.cod_cliente = c.cod_cliente
                    WHERE p.estado_pedido IS NULL AND p.estado_mrcb=TRUE";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }


    public function aceptado() {
        $this->beginTransaction();
        try {            
            $campos_valores = 
            array(  "fecha_atencion"=>$this->getFecha_atencion(),
                    "estado_pedido" =>2);

            $campos_valores_where = 
            array(  "cod_pedido"=>$this->getCod_pedido());

            $this->update($this->tb, $campos_valores,$campos_valores_where);

            $this->commit();
            return array("rpt"=>true,"msj"=>"Se aceptado correctamente");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }

    public function rechazar(){      
        $this->beginTransaction();
        try {
            
            $campos_valores = 
            array(  "fecha_atencion"=>$this->getFecha_atencion(),
                    "estado_pedido" =>3);

            $campos_valores_where = 
            array(  "cod_pedido"=>$this->getCod_pedido());

            $this->update($this->tb, $campos_valores,$campos_valores_where);

            $this->commit();
            return array("rpt"=>true,"msj"=>"Se rechazado correctamente");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }

    public function listarPedidosAceptados(){
        try {
            $sql = "SELECT 
                      p.cod_pedido, 
                      COALESCE(c.razon_social, c.nombres||' '||c.apellidos) as cliente, 
                      p.monto_total 
                    FROM pedido p INNER JOIN cliente c ON p.cod_usuario_cliente = c.cod_cliente
                    WHERE p.estado_pedido=2 AND p.estado_mrcb=TRUE AND p.cod_plan_produccion IS NULL";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function asignar($parametro){      
        $this->beginTransaction();
        try {

            $sql = "SELECT cod_plan_produccion FROM plan_produccion WHERE nombre = :0";
            $codigo_plan_produccion =  $this->consultarValor($sql, array($parametro));
            
            $campos_valores = 
            array(  "cod_plan_produccion"=>$codigo_plan_produccion);

            $campos_valores_where = 
            array(  "cod_pedido"=>$this->getCod_pedido());

            $this->update($this->tb, $campos_valores,$campos_valores_where);

            $this->commit();
            return array("rpt"=>true,"msj"=>"Se agregado el pedido correctamente");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }
    public function retirar(){      
        $this->beginTransaction();
        try {            
            $campos_valores = 
            array(  "cod_plan_produccion"=>null);

            $campos_valores_where = 
            array(  "cod_pedido"=>$this->getCod_pedido());

            $this->update($this->tb, $campos_valores,$campos_valores_where);

            $this->commit();
            return array("rpt"=>true,"msj"=>"Se retirado el pedido correctamente");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }
}