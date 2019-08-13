<?php

require_once '../datos/Conexion.clase.php';

class Usuario extends Conexion {
    private $cod_usuario;
    private $usuario;
    private $clave;
    private $estado_acceso;    
    private $tipo_usuario;
    private $cod_perfil;
    private $cod_colaborador;
    private $estado_mrcb;

    private $tb = "usuario";

    public function getCod_usuario()
    {
        return $this->cod_usuario;
    }
     
    public function setCod_usuario($cod_usuario)
    {
        $this->cod_usuario = $cod_usuario;
        return $this;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }
     
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getClave()
    {
        return $this->clave;
    }
     
    public function setClave($clave)
    {
        $this->clave = $clave;
        return $this;
    }

    public function getEstado_acceso()
    {
        return $this->estado_acceso;
    }
     
    public function setEstado_acceso($estado_acceso)
    {
        $this->estado_acceso = $estado_acceso;
        return $this;
    }

    public function getTipo_usuario()
    {
        return $this->tipo_usuario;
    }
     
    public function setTipo_usuario($tipo_usuario)
    {
        $this->tipo_usuario = $tipo_usuario;
        return $this;
    }

    public function getCod_perfil()
    {
        return $this->cod_perfil;
    }
     
    public function setCod_perfil($cod_perfil)
    {
        $this->cod_perfil = $cod_perfil;
        return $this;
    }

    public function getCod_colaborador()
    {
        return $this->cod_colaborador;
    }
     
    public function setCod_colaborador($cod_colaborador)
    {
        $this->cod_colaborador = $cod_colaborador;
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

    public function agregar(){        
        $this->beginTransaction();
        try {
            
            $campos_valores = 
            array(  "usuario" => $this->getUsuario(),
                    "clave" => md5($this->getClave()),
                    "estado_acceso" => $this->getEstado_acceso(),
                    "tipo_usuario" => 'O',
                    "cod_perfil" => $this->getCod_perfil(),
                    "cod_colaborador" =>$this->getCod_colaborador());

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
            array(  "usuario" => $this->getUsuario(),
                    "estado_acceso" => $this->getEstado_acceso(),
                    "tipo_usuario" => 'O',
                    "cod_perfil" => $this->getCod_perfil(),
                    "cod_colaborador" =>$this->getCod_colaborador());


            $campos_valores_where = 
            array(  "cod_usuario"=>$this->getCod_usuario());

            $this->update($this->tb, $campos_valores,$campos_valores_where);

            $this->commit();
            return array("rpt"=>true,"msj"=>"Se actualizado exitosamente");
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }

    public function status() {
        $this->beginTransaction();
        try {
            $texto = $this->getEstado_acceso() != 'A' ? 
                'Se inactivado existosamente' : 'Se activado existosamente';
            
            $campos_valores = 
            array(  "estado_acceso"=>$this->getEstado_acceso());

            $campos_valores_where = 
            array(  "cod_usuario"=>$this->getCod_usuario());

            $this->update($this->tb, $campos_valores,$campos_valores_where);

            $this->commit();
            return array("rpt"=>true,"msj"=>$texto);
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
            array(  "cod_usuario"=>$this->getCod_usuario());

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
            $sql = "SELECT * FROM $this->tb WHERE cod_usuario = :0";
            $resultado = $this->consultarFilas($sql, array($this->getCod_usuario()));
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
                        u.cod_usuario,
                        u.usuario,
                        COALESCE(c.nombres||' '||c.apellidos,COALESCE(cli.razon_social,cli.nombres||' '||cli.apellidos)) as user,
                        p.nombre as perfil,
                        u.estado_acceso
                    FROM usuario u INNER JOIN perfil p ON u.cod_perfil = p.cod_perfil 
                               LEFT JOIN colaborador c ON u.cod_colaborador = c.cod_colaborador
                               LEFT JOIN cliente cli ON u.cod_cliente = cli.cod_cliente
                    WHERE u.tipo_usuario=:0 AND u.estado_mrcb=TRUE";
            $resultado = $this->consultarFilas($sql, array($this->getTipo_usuario()));
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

}