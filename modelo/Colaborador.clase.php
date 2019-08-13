<?php

require_once '../datos/Conexion.clase.php';

class Colaborador extends Conexion {
    private $cod_colaborador;
    private $dni;
    private $nombres;
    private $apellidos;
    private $cod_cargo;
    private $estado_laboral;
    private $fecha_nacimiento;
    private $celular;
    private $correo;
    private $estado_mrcb;
    private $cod_usuario_registro;

    private $tb = "colaborador";
    private $tb_usuario = "usuario";

    private $usuario;

    public function getUsuario()
    {
        return $this->usuario;
    }
     
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
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

    public function getDocumento()
    {
        return $this->dni;
    }
     
    public function setDocumento($dni)
    {
        $this->dni = $dni;
        return $this;
    }

    public function getNombres()
    {
        return $this->nombres;
    }
     
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;
        return $this;
    }

    public function getApellidos()
    {
        return $this->apeliidos;
    }
     
    public function setApellidos($apeliidos)
    {
        $this->apeliidos = $apeliidos;
        return $this;
    }

    public function getCod_cargo()
    {
        return $this->cod_cargo;
    }
     
    public function setCod_cargo($cod_cargo)
    {
        $this->cod_cargo = $cod_cargo;
        return $this;
    }

    public function getEstado_laboral()
    {
        return $this->estado_laboral;
    }
     
    public function setEstado_laboral($estado_laboral)
    {
        $this->estado_laboral = $estado_laboral;
        return $this;
    }

    public function getFecha_nacimiento()
    {
        return $this->fecha_nacimiento;
    }
     
    public function setFecha_nacimiento($fecha_nacimiento)
    {
        $this->fecha_nacimiento = $fecha_nacimiento;
        return $this;
    }

    public function getCelular()
    {
        return $this->celular;
    }
     
    public function setCelular($celular)
    {
        $this->celular = $celular;
        return $this;
    }

    public function getCorreo()
    {
        return $this->correo;
    }
     
    public function setCorreo($correo)
    {
        $this->correo = $correo;
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

    public function getCod_usuario_registro()
    {
        return $this->cod_usuario_registro;
    }
     
    public function setCod_usuario_registro($cod_usuario_registro)
    {
        $this->cod_usuario_registro = $cod_usuario_registro;
        return $this;
    }

    public function agregar(){ 
        session_name("_sis_produccion_");
        session_start();       
        $this->beginTransaction();
        try {
            
            $campos_valores = 
            array(  "dni"=>$this->getDocumento(),
                    "nombres"=>$this->getNombres(),
                    "apellidos"=>$this->getApellidos(),
                    "cod_cargo"=>$this->getCod_cargo(),
                    "fecha_nacimiento"=>$this->getFecha_nacimiento(),
                    "celular"=>$this->getCelular(),
                    "correo"=>$this->getCorreo(),
                    "cod_usuario_registro"=>$_SESSION["cod_usuario"]);

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
        session_name("_sis_produccion_");
        session_start();  
        $this->beginTransaction();
        try {
            
            $campos_valores = 
            array(  "dni"=>$this->getDocumento(),
                    "nombres"=>$this->getNombres(),
                    "apellidos"=>$this->getApellidos(),
                    "cod_cargo"=>$this->getCod_cargo(),
                    "fecha_nacimiento"=>$this->getFecha_nacimiento(),
                    "celular"=>$this->getCelular(),
                    "correo"=>$this->getCorreo(),
                    //"cod_usuario_registro"=>$this->getCod_usuario_registro());
                    "cod_usuario_registro"=>$_SESSION["cod_usuario"]);


            $campos_valores_where = 
            array(  "cod_colaborador"=>$this->getCod_colaborador());

            $this->update($this->tb, $campos_valores,$campos_valores_where);

            $this->commit();
            return array("rpt"=>true,"msj"=>"Se agregado exitosamente");
        } catch (Exception $exc) {            
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }

    public function status() {
        $this->beginTransaction();
        try {

            $texto = $this->getEstado_laboral() != 'A' ? 
                'Se inactivado existosamente' : 'Se activado existosamente';
            
            
            $campos_valores = 
            array(  "estado_laboral"=>$this->getEstado_laboral());

            $campos_valores_where = 
            array(  "cod_colaborador"=>$this->getCod_colaborador());

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
            array(  "cod_colaborador"=>$this->getCod_colaborador());

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
            $sql = "SELECT * FROM $this->tb WHERE cod_colaborador = :0";
            $resultado = $this->consultarFilas($sql, array($this->getCod_colaborador()));
            return array("rpt"=>true,"msj"=>$resultado); 
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function listar(){
        try {
            $sql = "SELECT 
                        co.cod_colaborador,
                        co.dni,
                        co.nombres||' '||co.apellidos as nombres,   
                        EXTRACT(YEAR FROM age(co.fecha_nacimiento)) as edad,    
                        co.celular,
                        co.correo,
                        ca.nombre as cargo,
                        co.estado_laboral,
                        co.estado_mrcb
                    FROM
                        colaborador co INNER JOIN cargo ca ON co.cod_cargo = ca.cod_cargo
                    WHERE 
                        co.estado_mrcb = TRUE AND co.cod_colaborador<>0";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado); 
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function cbListar(){
        try {
            $sql = "SELECT cod_colaborador,nombres||' '||apellidos as nombre FROM $this->tb WHERE estado_mrcb=TRUE";
            $resultado =  $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

}