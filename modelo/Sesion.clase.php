<?php

require_once '../datos/Conexion.clase.php';

class Sesion extends Conexion {    
    private $usuario;
    private $clave;

    public function getUsuario(){
        return $this->usuario;
    }
    
    public function getClave(){
        return $this->clave;
    }

    public function setUsuario($usuario){
        $this->usuario = $usuario;
    }

    public function setClave($clave){
        $this->clave = $clave;
    }

    public function inicioSesion(){       
        try{
            $sql = "SELECT 
                        u.cod_usuario,
                        COALESCE(COALESCE(c.razon_social,c.nombres||' '||c.apellidos),co.nombres||' '||co.apellidos) as nombre_usuario, 
                        u.clave,
                        u.cod_perfil,
                        p.nombre as perfil,                        
                        u.estado_acceso
                    FROM usuario u 
                        INNER JOIN perfil p ON u.cod_perfil = p.cod_perfil
                        LEFT JOIN cliente c ON u.cod_cliente  = c.cod_cliente
                        LEFT JOIN colaborador co ON u.cod_colaborador = co.cod_colaborador
                    WHERE LOWER(u.usuario)=LOWER(:p_user) AND u.estado_mrcb=TRUE";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(':p_user', $this->getUsuario());
            $sentencia->execute();
            if ($sentencia->rowCount()) {
                $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
                if ($resultado["clave"] == md5($this->getClave())) {
                    if ($resultado["estado_acceso"] == 'I') {
                        return 0; // Este usuario se encuentra inactivo del sistema
                    } else {
                        session_name("_sis_produccion_");
                        session_start();

                        if ( $resultado["cod_perfil"] == 0 ) {
                            $_SESSION["cod_usuario"] = $resultado["cod_usuario"];
                            $_SESSION["usuario"] = $resultado["nombre_usuario"];                        
                            $_SESSION["cod_perfil"] = $resultado["cod_perfil"];                        
                            $_SESSION["perfil"] = $resultado["perfil"];
                            $_SESSION["rpt"] = 0;                        
                            return 1; // OK
                        }else{
                            $_SESSION["cod_usuario"] = $resultado["cod_usuario"];
                            $_SESSION["usuario"] = $resultado["nombre_usuario"];                        
                            $_SESSION["cod_perfil"] = $resultado["cod_perfil"];                        
                            $_SESSION["perfil"] = $resultado["perfil"]; 
                            $_SESSION["rpt"] = 1;                        
                            return 2; // OK
                        }                       
                    }
                } else {                    
                    return 3; // La clave de este usuario no coinciden
                }
            } else {
                return 4; // Este usuario no se encuentra registrado                
            }             
        } catch (Exception $exc){
            throw $exc;            
        }
    }

}