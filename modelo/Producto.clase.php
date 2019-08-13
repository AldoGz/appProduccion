<?php

require_once '../datos/Conexion.clase.php';

class Producto extends Conexion {
    private $cod_producto;
    private $nombre;
    private $precio;
    private $descripcion;
    private $img;
    private $estado_mrcb;

    private $cod_materia_prima;
    private $cantidad;

    private $tb = "producto";

    public function getCod_producto()
    {
        return $this->cod_producto;
    }
     
    public function setCod_producto($cod_producto)
    {
        $this->cod_producto = $cod_producto;
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
    public function getPrecio()
    {
        return $this->precio;
    }
     
    public function setPrecio($precio)
    {
        $this->precio = $precio;
        return $this;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }
     
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    public function getImg()
    {
        return $this->img;
    }
     
    public function setImg($img)
    {
        $this->img = $img;
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

    public function getCod_materia_prima()
    {
        return $this->cod_materia_prima;
    }
    
    public function setCod_materia_prima($cod_materia_prima)
    {
        $this->cod_materia_prima = $cod_materia_prima;
        return $this;
    }

    public function getCantidad()
    {
        return $this->cantidad;
    }
    
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
        return $this;
    }

    public function agregar($p1) {        
        $this->beginTransaction();
        try {
            $foto;
            if ( $this->getImg() == NULL ) { 
                $foto = "defecto.jpg";
            }else{
                $foto = $this->getImg();
            }

            $campos_valores = 
            array(  "nombre"=>strtoupper($this->getNombre()),
                    "precio_fijo"=>$this->getPrecio(),
                    "descripcion"=>strtoupper($this->getDescripcion()),
                    "img"=>$foto);

            $this->insert($this->tb, $campos_valores);

            $sql = "SELECT MAX(cod_producto) FROM $this->tb";
            $codigo_producto = $this->consultarValor($sql);

            $arreglo = json_decode($p1);

            if ( !empty($arreglo) ) {
                for ($i=0; $i < count($arreglo)  ; $i++) { 
                    $item = $arreglo[$i];
                    $campos_valores = 
                    array(  "cod_materia_prima"=>$item->p_codigo_materia_prima,                    
                            "cod_producto"=>$codigo_producto,
                            "cantidad"=>$item->p_cantidad);

                    $this->insert("materia_prima_producto", $campos_valores);
                }
            }
            $this->commit();
            return array("rpt"=>true,"msj"=>"Se actualizado exitosamente");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }

    public function editar() {
        $this->beginTransaction();
        try {
            $foto; 
            if ( $this->getImg() == NULL ) {
                $sql = "SELECT img FROM producto WHERE cod_producto=:0";
                $foto = $this->consultarValor($sql, array($this->getCod_producto()));                             
            }else{
                $foto = $this->getImg();
            }
             

            $campos_valores = 
            array(  "nombre"=>strtoupper($this->getNombre()),
                    "precio_fijo"=>$this->getPrecio(),
                    "descripcion"=>strtoupper($this->getDescripcion()),
                    "img"=>$foto);

            $campos_valores_where = 
            array(  "cod_producto"=>$this->getCod_producto());

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
            array(  "cod_producto"=>$this->getCod_producto());

            $this->update($this->tb, $campos_valores,$campos_valores_where);

            $this->commit();
            return array("rpt"=>true,"msj"=>"Se anulado exitosamente");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }
    

    public function leerDatos(){
        try {
            $sql = "SELECT * FROM $this->tb WHERE cod_producto = :0";
            $producto = $this->consultarFila($sql, array($this->getCod_producto()));

            $sql = "SELECT 
                        mpp.cod_materia_prima,
                        mp.nombre,
                        mpp.cantidad 
                    FROM materia_prima_producto mpp INNER JOIN  materia_prima mp ON mpp.cod_materia_prima = mp.cod_materia_prima 
                    WHERE mpp.cod_producto = :0";
            $materia_prima = $this->consultarFilas($sql, array($this->getCod_producto()));

            $resultado["producto"] = $producto;
            $resultado["materia_prima"] = $materia_prima;

            return array("rpt"=>true,"msj"=>$resultado);


        } catch (Exception $exc) {
            return array("rpt"=>true,"msj"=>$exc);
            throw $exc;
        }
    }

    public function listar(){
        try {
            $sql = "SELECT * FROM $this->tb WHERE estado_mrcb=TRUE";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function correlativo(){
        try {
            $sql = "SELECT 
                        'PRO'||CASE WHEN COUNT(*)=0 THEN 1 ELSE COUNT(*)+1 END||'.jpg'
                    FROM $this->tb WHERE img<>'defecto.jpg'";
            return $this->consultarValor($sql);            
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function agregarMP(){
        $this->beginTransaction();
        try {
            $campos_valores = 
            array(  "cod_materia_prima"=>$this->getCod_materia_prima(),                    
                    "cod_producto"=>$this->getCod_producto(),
                    "cantidad"=>$this->getCantidad());

            $this->insert("materia_prima_producto", $campos_valores);
            $this->commit();
            return array("rpt"=>true,"msj"=>"Se agregado exitosamente");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }

    public function eliminarMP(){
        $this->beginTransaction();
        try {            
            $campos_valores_where = 
            array(  "cod_materia_prima"=>$this->getCod_materia_prima(),
                    "cod_producto"=>$this->getCod_producto());
            $this->delete("materia_prima_producto", $campos_valores_where);
            $this->commit();
            return array("rpt"=>true,"msj"=>"Se quitado exitosamente");
        } catch (Exception $exc) {
            return array("rpt"=>true,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        } 
    }

    public function cbListar(){
        try {
            $sql = "SELECT cod_producto,nombre FROM $this->tb WHERE estado_mrcb=TRUE";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function buscar($parametro){
        try {
            $sql = "SELECT 
                        p.cod_producto, p.nombre, p.precio_fijo, p.img
                    FROM producto p INNER JOIN pieza pi ON p.cod_producto = pi.cod_producto
                    WHERE LOWER(p.nombre) LIKE LOWER('%".$parametro."%') AND p.estado_mrcb=TRUE 
                    GROUP BY p.cod_producto
                    ORDER BY 2 
                    LIMIT 4 OFFSET 0";
            $resultado = $this->consultarFilas($sql);

            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function total(){
        try {  
            $sql = "SELECT COUNT(*) FROM 
                        (SELECT 
                        p.cod_producto, p.nombre, p.precio_fijo, p.img
                        FROM producto p INNER JOIN pieza pi ON p.cod_producto = pi.cod_producto
                        WHERE p.estado_mrcb=TRUE 
                        GROUP BY p.cod_producto
                        ORDER BY 2 LIMIT 4 OFFSET 0
                        ) as tabla";
            $resultado = $this->consultarValor($sql);
            
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function listarProductos($p1,$p2){
        try {
            $sql = "SELECT 
                        p.cod_producto, p.nombre, p.precio_fijo, p.img
                    FROM producto p INNER JOIN pieza pi ON p.cod_producto = pi.cod_producto
                    WHERE p.estado_mrcb=TRUE 
                    GROUP BY p.cod_producto
                    ORDER BY 2 
                    LIMIT $p1 OFFSET $p2";
            $resultado = $this->consultarFilas($sql);

            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

}


