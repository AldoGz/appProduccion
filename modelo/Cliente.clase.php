<?php

require_once '../datos/Conexion.clase.php';

class Cliente extends Conexion {
    private $cod_cliente;
    private $razon_social;
    private $nombres;
    private $apellidos;
    private $cod_tipo_documento;
    private $nro_documento;
    private $correo;
    private $celular;
    private $direccion;
    private $departamento;
    private $provincia;
    private $distrito;
    private $estado_mrcb;

    private $tb = "cliente";
    private $tb_usuario = "usuario";

    public function getCod_cliente()
    {
        return $this->cod_cliente;
    }
     
    public function setCod_cliente($cod_cliente)
    {
        $this->cod_cliente = $cod_cliente;
        return $this;
    }

    public function getRazon_social()
    {
        return $this->razon_social;
    }
     
    public function setRazon_social($razon_social)
    {
        $this->razon_social = $razon_social;
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
        return $this->apellidos;
    }
     
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
        return $this;
    }

    public function getCod_tipo_documento()
    {
        return $this->cod_tipo_documento;
    }
     
    public function setCod_tipo_documento($cod_tipo_documento)
    {
        $this->cod_tipo_documento = $cod_tipo_documento;
        return $this;
    }

    public function getNro_documento()
    {
        return $this->nro_documento;
    }
     
    public function setNro_documento($nro_documento)
    {
        $this->nro_documento = $nro_documento;
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

    public function getCelular()
    {
        return $this->celular;
    }
     
    public function setCelular($celular)
    {
        $this->celular = $celular;
        return $this;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }
     
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
        return $this;
    }
    public function getDepartamento(){
        return $this->departamento;
    }
    
    public function setDepartamento($departamento){
        $this->departamento = $departamento;
        return $this;
    }

    public function getProvincia(){
        return $this->provincia;
    }
    
    public function setProvincia($provincia){
        $this->provincia = $provincia;
        return $this;
    }

    public function getDistrito(){
        return $this->distrito;
    }
    
    public function setDistrito($distrito){
        $this->distrito = $distrito;
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
        $this->beginTransaction();
        try {
            
            $campos_valores = 
            array(  "razon_social"=>$this->getRazon_social() != null ? $this->getRazon_social() : null,
                    "nombres"=>$this->getNombres() != null ? $this->getNombres() : null,
                    "apellidos"=>$this->getApellidos() != null ? $this->getApellidos() : null,
                    "cod_tipo_documento"=>$this->getCod_tipo_documento(),
                    "nro_documento"=>$this->getNro_documento(),
                    "correo"=>$this->getCorreo(),
                    "celular"=>$this->getCelular(),
                    "direccion"=>$this->getDireccion(),
                    "codigo_departamento"=>$this->getDepartamento(),
                    "codigo_provincia"=>$this->getProvincia(),
                    "codigo_distrito"=>$this->getDistrito());

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
            array(  "razon_social"=>$this->getRazon_social() != null ? $this->getRazon_social() : null,
                    "nombres"=>$this->getNombres() != null ? $this->getNombres() : null,
                    "apellidos"=>$this->getApellidos() != null ? $this->getApellidos() : null,
                    "cod_tipo_documento"=>$this->getCod_tipo_documento(),
                    "nro_documento"=>$this->getNro_documento(),
                    "correo"=>$this->getCorreo(),
                    "celular"=>$this->getCelular(),
                    "direccion"=>$this->getDireccion(),
                    "codigo_departamento"=>$this->getDepartamento(),
                    "codigo_provincia"=>$this->getProvincia(),
                    "codigo_distrito"=>$this->getDistrito());

            $campos_valores_where = 
            array(  "cod_cliente"=>$this->getCod_cliente());

            $this->update($this->tb, $campos_valores,$campos_valores_where);


            $this->commit();
            return array("rpt"=>true);
        } catch (Exception $exc) {
            $this->rollBack();
            throw $exc;
        }
        return array("rpt"=>false,"msj"=>"ERROR AL INGRESAR BASE");
    }


    public function habilitar() {
        $this->beginTransaction();
        try {
            
            $texto = $this->getEstado_mrcb() != 'true' ? 
                'Se inactivado existosamente' : 'Se activado existosamente';
            
            $campos_valores = 
            array(  "estado_mrcb"=>$this->getEstado_mrcb());

            $campos_valores_where = 
            array(  "cod_cliente"=>$this->getCod_cliente());

            $this->update($this->tb, $campos_valores,$campos_valores_where);

            $this->commit();
            return array("rpt"=>true,"msj"=>$texto);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            $this->rollBack();
            throw $exc;
        }
    }
    

    public function leerDatos(){
        try {
            $sql = "SELECT * FROM cliente WHERE cod_cliente = :0";
            $resultado = $this->consultarFila($sql, array($this->getCod_cliente()));
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function leerPerfil(){
        try {
            $sql = "SELECT 
                        cli.cod_cliente,
                        cli.nro_documento,
                        coalesce(cli.razon_social, cli.nombres||' '||cli.apellidos) as cliente,
                        cli.direccion,
                        cli.celular,
                        cli.correo,
                        cli.estado_mrcb,
                        d.nombre as depatarmento,
                        p.nombre as provincia,
                        di.nombre as distrito
                    FROM cliente cli 
                        INNER JOIN departamento d ON cli.codigo_departamento = d.codigo_departamento
                        INNER JOIN provincia p ON cli.codigo_provincia = p.codigo_provincia
                        INNER JOIN distrito di ON cli.codigo_distrito = di.codigo_distrito
                    WHERE cli.cod_cliente = :0";
            $resultado = $this->consultarFila($sql, array($this->getCod_cliente()));
            
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

    public function listar(){
        try {
            $sql = "SELECT 
                        cod_cliente,
                        nro_documento,
                        coalesce(razon_social, nombres||' '||apellidos) as cliente,
                        direccion,
                        celular,
                        correo,
                        estado_mrcb
                    FROM $this->tb WHERE estado_mrcb = TRUE";
            $resultado = $this->consultarFilas($sql);
            return array("rpt"=>true,"msj"=>$resultado);
        } catch (Exception $exc) {
            return array("rpt"=>false,"msj"=>$exc);
            throw $exc;
        }
    }

}