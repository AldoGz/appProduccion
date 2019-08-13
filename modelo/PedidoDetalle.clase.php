<?php

require_once '../datos/Conexion.clase.php';

class PedidoDetalle extends Conexion {
    
    public function ver($parametro){
        try {
            $sql = "SELECT
          						dp.cod_pedido,
          						p.nombre,
          						dp.cantidad,
          						dp.precio,
          						dp.importe
           					FROM pedido_detalle dp INNER JOIN producto p ON dp.cod_producto = p.cod_producto 
           					WHERE dp.cod_pedido = :0";
            $resultado = $this->consultarFilas($sql, array((int)$parametro));            
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

}