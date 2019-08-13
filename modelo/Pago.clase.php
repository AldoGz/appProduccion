<?php

require_once '../datos/Conexion.clase.php';

class Pago extends Conexion {
    private $cod_pago;
    private $cod_pedido;
    private $numero_cuenta;
    private $fecha_registro;
    private $hora_registro;
    private $numero_operacion;
    private $monto_pagado;
    private $voucher;
    private $estado_mrcb;

    private $tb = "pago";

    public function getCod_pago()
    {
        return $this->cod_pago;
    }
    
    public function setCod_pago($cod_pago)
    {
        $this->cod_pago = $cod_pago;
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

    public function getNumero_cuenta()
    {
        return $this->numero_cuenta;
    }
    
    public function setNumero_cuenta($numero_cuenta)
    {
        $this->numero_cuenta = $numero_cuenta;
        return $this;
    }

    public function getFecha_registro()
    {
        return $this->fecha_registro;
    }
    
    public function setFecha_registro($fecha_registro)
    {
        $this->fecha_registro = $fecha_registro;
        return $this;
    }

    public function getHora_registro()
    {
        return $this->hora_registro;
    }
    
    public function setHora_registro($hora_registro)
    {
        $this->hora_registro = $hora_registro;
        return $this;
    }

    public function getNumero_operacion()
    {
        return $this->numero_operacion;
    }
    
    public function setNumero_operacion($numero_operacion)
    {
        $this->numero_operacion = $numero_operacion;
        return $this;
    }

    public function getMonto_pagado()
    {
        return $this->monto_pagado;
    }
    
    public function setMonto_pagado($monto_pagado)
    {
        $this->monto_pagado = $monto_pagado;
        return $this;
    }

    public function getVoucher()
    {
        return $this->voucher;
    }
    
    public function setVoucher($voucher)
    {
        $this->voucher = $voucher;
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
        session_name("_sis_produccion_");
        session_start();        
        $this->beginTransaction();
        try {  
            $fecha = new DateTime();
            $campos_valores = 
            array(  "fecha_atencion"=>$fecha->format('Y-m-d H:i:s e'),
                    "estado_pedido" =>2);

            $campos_valores_where = 
            array(  "cod_pedido"=>$this->getCod_pedido());

            $this->update("pedido", $campos_valores,$campos_valores_where);
            // AGREGAR EL PAGO 

            $campos_valores = 
            array(  "cod_pedido"=>$this->getCod_pedido(),
                    "cod_usuario_registro"=>$_SESSION["cod_usuario"]);

            $this->insert("pago", $campos_valores);

            $this->commit();
            return array("rpt"=>true,"msj"=>"Se agregar correctamente");

        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
        
    }

    public function correlativo(){
        try {
            $sql = "SELECT 
                        'VOUCHER-'||CASE WHEN COUNT(*)=0 THEN 1 ELSE COUNT(*)+1 END||'.jpg'
                    FROM $this->tb";
            return $this->consultarValor($sql);            
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function leerDatos(){
        try {
            $sql = "SELECT
                                dp.cod_pedido,
                                p.nombre,
                                dp.cantidad,
                                dp.precio,
                                dp.importe
                            FROM pedido_detalle dp INNER JOIN producto p ON dp.cod_producto = p.cod_producto 
                            WHERE dp.cod_pedido = :0";
            $pedido = $this->consultarFilas($sql, array($this->getCod_pedido()));    

            $sql5 = "SELECT cli.cod_cliente,
                        COALESCE(cli.razon_social,cli.nombres||' '||cli.apellidos) as cliente,
                        CASE WHEN cli.cod_tipo_documento = '01' THEN 'DNI' ELSE 'RUC' END||':'||cli.nro_documento as documento,
                        cli.direccion,
                        cli.celular,
                        d.nombre as departamento,
                        pr.nombre as provincia,
                        ds.nombre as distrito,
                        p.monto_total,p.igv, p.sub_total
                    FROM pedido p INNER JOIN cliente cli ON p.cod_usuario_cliente = cli.cod_cliente
                            INNER JOIN departamento d ON cli.codigo_departamento = d.codigo_departamento
                            INNER JOIN provincia pr ON cli.codigo_provincia = pr.codigo_provincia
                            INNER JOIN distrito ds ON cli.codigo_distrito = ds.codigo_distrito
                    WHERE cod_pedido=:0";
            $cliente = $this->consultarFila($sql5,array($this->getCod_pedido()));

            //ok
            $resultado["pedido"] = $pedido;
            $resultado["cliente"] = $cliente;

            return array("rpt"=>true,"msj"=>$resultado);            
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }


}