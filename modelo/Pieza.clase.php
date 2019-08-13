<?php

require_once '../datos/Conexion.clase.php';

class Pieza extends Conexion {
    private $cod_pieza;
    private $cod_producto;
    private $codigo_produccion;
    private $nombre;
    private $costo_indirecto_promedio;
    private $estado_mrcb;

    private $cod_materia_prima;
    private $cantidad;
    private $tipo;

    private $tb = "pieza";
    private $tb_mpp = "materia_prima_pieza";

    public function getCod_pieza()
    {
        return $this->cod_pieza;
    }
     
    public function setCod_pieza($cod_pieza)
    {
        $this->cod_pieza = $cod_pieza;
        return $this;
    }

    public function getCod_producto()
    {
        return $this->cod_producto;
    }
     
    public function setCod_producto($cod_producto)
    {
        $this->cod_producto = $cod_producto;
        return $this;
    }

    public function getCodigo_produccion()
    {
        return $this->codigo_produccion;
    }
     
    public function setCodigo_produccion($codigo_produccion)
    {
        $this->codigo_produccion = $codigo_produccion;
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

    public function getCosto_indirecto_promedio()
    {
        return $this->costo_indirecto_promedio;
    }
     
    public function setCosto_indirecto_promedio($costo_indirecto_promedio)
    {
        $this->costo_indirecto_promedio = $costo_indirecto_promedio;
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

    public function getCantidad()
    {
        return $this->cantidad;
    }
    
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
        return $this;
    }

    public function getTipo()
    {
        return $this->tipo;
    }
    
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
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

    public function agregar($p1,$p2,$p3) {        
        $this->beginTransaction();
        try {
            
            $campos_valores = 
            array(  "cod_producto"=>$this->getCod_producto(),                    
                    "nombre"=>$this->getNombre());

            $this->insert($this->tb, $campos_valores);

            $sql = "SELECT MAX(cod_pieza) FROM $this->tb";
            $codigo_pieza = $this->consultarValor($sql);

            $campos_valores = 
            array(  "cod_materia_prima"=>1,                    
                    "cod_pieza"=>$codigo_pieza,
                    "cantidad"=>$this->getCantidad(),
                    "tipo"=>0);

            $this->insert($this->tb_mpp, $campos_valores);

            /*masillado, pulido, pintado deben existir sino generarán problemas.*/
            $arr1 = json_decode($p1);
            $cod_actividad_masillado = $this->getCodigoXNombreActividad("MASILLADO");
            $arr2 = json_decode($p2);
            $cod_actividad_pulido = $this->getCodigoXNombreActividad("PULIDO");
            $arr3 = json_decode($p3);
            $cod_actividad_pintado = $this->getCodigoXNombreActividad("PINTADO");

            if ( !empty($arr1) ) {
                for ($i=0; $i < count($arr1)  ; $i++) { 
                    $item = $arr1[$i];
                    $campos_valores = 
                    array(  "cod_materia_prima"=>$item->p_codigo_materia_prima,                    
                            "cod_pieza"=>$codigo_pieza,
                            "cantidad"=>$item->p_cantidad,
                            "tipo"=>$cod_actividad_masillado);

                    $this->insert($this->tb_mpp, $campos_valores);
                }
            }

            if ( !empty($arr2) ) {
                for ($i=0; $i < count($arr1)  ; $i++) { 
                    $item = $arr1[$i];
                    $campos_valores = 
                    array(  "cod_materia_prima"=>$item->p_codigo_materia_prima,                    
                            "cod_pieza"=>$codigo_pieza,
                            "cantidad"=>$item->p_cantidad,
                            "tipo"=>$cod_actividad_pulido);

                    $this->insert($this->tb_mpp, $campos_valores);
                }
            }

            if ( !empty($arr3) ) {
                for ($i=0; $i < count($arr1)  ; $i++) { 
                    $item = $arr1[$i];
                    $campos_valores = 
                    array(  "cod_materia_prima"=>$item->p_codigo_materia_prima,                    
                            "cod_pieza"=>$codigo_pieza,
                            "cantidad"=>$item->p_cantidad,
                            "tipo"=>$cod_actividad_pintado);

                    $this->insert($this->tb_mpp, $campos_valores);
                }
            }

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
            array(  "cod_producto"=>$this->getCod_producto(),                    
                    "nombre"=>$this->getNombre());

            $campos_valores_where = 
            array(  "cod_pieza"=>$this->getCod_pieza());

            $this->update($this->tb, $campos_valores,$campos_valores_where);

            $campos_valores = 
            array(  "cantidad"=>$this->getCantidad());

            $campos_valores_where = 
            array(  "cod_pieza"=>$this->getCod_pieza(),
                    "cod_materia_prima"=>1);

            $this->update($this->tb_mpp, $campos_valores,$campos_valores_where);

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
            array(  "cod_pieza"=>$this->getCod_pieza());

            $this->update($this->tb, $campos_valores,$campos_valores_where);

            $this->commit();
            return array("rpt"=>true);
        } catch (Exception $exc) {
            $this->rollBack();
            throw $exc;
        }
    }

    public function leerDatos(){
        try {
            $sql = "SELECT * FROM $this->tb WHERE cod_pieza = :0 AND estado_mrcb = true";
            $pieza = $this->consultarFila($sql, array($this->getCod_pieza()));

            $sql = "SELECT cantidad FROM materia_prima_pieza WHERE cod_pieza = :0 AND tipo=0";
            $chatarra = $this->consultarValor($sql, array($this->getCod_pieza()));
            
            $masillado = $this->mpActividad($this->getCod_pieza(),$this->getCodigoXNombreActividad("MASILLADO"));
            $pulido = $this->mpActividad($this->getCod_pieza(),$this->getCodigoXNombreActividad("PULIDO"));
            $pintado = $this->mpActividad($this->getCod_pieza(),$this->getCodigoXNombreActividad("PINTADO"));

            $resultado["pieza"] = $pieza;
            $resultado["chatarra"] = $chatarra;
            $resultado["masillado"] = $masillado;
            $resultado["pulido"] = $pulido;
            $resultado["pintado"] = $pintado;

            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }

    public function mpActividad($p1,$p2){
        $sql = "SELECT 
                    mpp.cod_materia_prima, 
                    mp.nombre,
                    mpp.cantidad
                FROM materia_prima_pieza mpp INNER JOIN materia_prima mp ON mpp.cod_materia_prima = mp.cod_materia_prima
                WHERE mpp.cod_pieza = :0 AND mpp.tipo= :1";  
        return $this->consultarFilas($sql, array($p1,$p2));
    }

    public function listar(){
        try {
            $sql = "SELECT 
                        pi.cod_pieza,
                        pi.nombre as pieza,
                        pi.estado_mrcb,
                        p.nombre as producto
                    FROM pieza pi INNER JOIN producto p ON pi.cod_producto = p.cod_producto
                    WHERE pi.estado_mrcb=TRUE";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado); 
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

 
    public function eliminarMP(){
        $this->beginTransaction();
        try {            

            if ($this->getTipo() < 1 && $this->getTipo() > 3){
                return  array("rpt"=>true,"msj"=>"Este tipo no está en un rango existente.");
            }

            switch($this->getTipo()){
                case "1":
                $nuevoTipo = "MASILLADO";
                break;
                case "2":
                $nuevoTipo = "PULIDO";
                break;
                case "3":
                $nuevoTipo = "PINTADO";
                break;
            }

            $campos_valores_where = 
            array(  "cod_materia_prima"=>$this->getCod_materia_prima(),
                    "cod_pieza"=>$this->getCod_pieza(),
                    "tipo"=>$this->getCodigoXNombreActividad($nuevoTipo));
            $this->delete($this->tb_mpp, $campos_valores_where);
            $this->commit();
            return array("rpt"=>true,"msj"=>"Se quitado exitosamente");
        } catch (Exception $exc) {
            return array("rpt"=>true,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        } 
    }

    public function agregarMP(){
        $this->beginTransaction();
        try {
            if ($this->getTipo() < 1 && $this->getTipo() > 3){
                return  array("rpt"=>true,"msj"=>"Este tipo no está en un rango existente.");
            }

            switch($this->getTipo()){
                case "1":
                $nuevoTipo = "MASILLADO";
                break;
                case "2":
                $nuevoTipo = "PULIDO";
                break;
                case "3":
                $nuevoTipo = "PINTADO";
                break;
            }

            $campos_valores = 
            array(  "cod_materia_prima"=>$this->getCod_materia_prima(),                    
                    "cod_pieza"=>$this->getCod_pieza(),
                    "cantidad"=>$this->getCantidad(),
                    "tipo"=>$this->getCodigoXNombreActividad($nuevoTipo));

            $this->insert($this->tb_mpp, $campos_valores);
            $this->commit();
            return array("rpt"=>true,"msj"=>"Se agregado exitosamente");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }

    public function cbListar(){
        try {
            $sql = "SELECT  
                        p.cod_pieza,
                        p.nombre,
                        SUM(mpp.cantidad*mp.precio_base) as precio
                    FROM pieza p  INNER JOIN materia_prima_pieza mpp ON p.cod_pieza = mpp.cod_pieza
                                INNER JOIN materia_prima mp ON mp.cod_materia_prima = mpp.cod_materia_prima
                    WHERE p.estado_mrcb=true
                    GROUP BY p.cod_pieza
                    ORDER BY 1";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado); 
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }


    public function leerDatosPieza(){
        try {
            $sql = "SELECT  
                        p.cod_pieza,
                        p.nombre,
                        SUM(mpp.cantidad*mp.precio_base) as precio
                    FROM pieza p  INNER JOIN materia_prima_pieza mpp ON p.cod_pieza = mpp.cod_pieza
                                INNER JOIN materia_prima mp ON mp.cod_materia_prima = mpp.cod_materia_prima
                    WHERE p.estado_mrcb=true AND p.cod_pieza=:0
                    GROUP BY p.cod_pieza
                    ORDER BY 1";
            $resultado = $this->consultarFila($sql,array($this->getCod_pieza()));
            return array("rpt"=>true,"msj"=>$resultado); 
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function getCodigoXNombreActividad($nombre)
    {
        $sql = "SELECT cod_actividad FROM actividad WHERE nombre = :0";
        return $this->consultarValor($sql,array($nombre));
    }

}