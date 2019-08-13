<?php

class Acceso {
    private $codigo;
    private $token;
    private $cargo;
    private $menu;

    public function getCodigo() {
        return $this->codigo;
    }

    public function getToken() {
        return $this->token;
    }

    public function getCargo(){
        return $this->cargo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }


    public function setToken($token) {
        $this->token = $token;
    }

    public function setCargo($cargo) {
        $this->cargo = $cargo;
    }

    public function getMenu()
    {
        return $this->menu;
    }
     
    public function setMenu($menu)
    {
        $this->menu = $menu;
        return $this;
    }

    public function __construct($arreglo) {
        foreach ($arreglo as $valor) {
            if ( $_SESSION["cod_perfil"] == $valor ) { 
                $this->setCodigo($_SESSION["cod_usuario"]);
                $this->setToken($_SESSION["usuario"]);
                $this->setCargo($_SESSION["perfil"]);
                $this->setMenu($this->menuPrincipal($_SESSION["cod_perfil"]));
            }else{
                $respuesta = $_SESSION["rpt"];
                if ( $respuesta == 0 ) {
                    header("Location:../pedido/");
                    exit; //Detiene la ejecución de la página
                }else{
                    header("Location:../plan_produccion/");
                    exit; //Detiene la ejecución de la página
                }
            }
        }
    }

    public function menuPrincipal($parametro) {
        $menu01 = 
                        ' 
                                <li><a><i class="fa fa-book" aria-hidden="true"></i> Mantenimientos <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu"> 
                                        <li><a href="../cargo/">Cargo</a></li>
                                        <li><a href="../unidad_medida/">Unidad medida</a></li>
                                        <li><a href="../perfil/"> Perfil</a></li>
                                        <li><a href="../variable_constante/"> Variable constante</a></li>
                                        <li><a href="../tipo_falla/"> Tipo de falla</a></li>
                                        <li><a href="../estado_proceso/"> Estado de proceso</a></li>
                                        <li><a href="../almacen/"> Almacen</a></li>
                                        <li><a href="../materia_prima/"> Materia prima</a></li>
                                        <li><a href="../maquina/"> Maquina</a></li>
                                        <li><a href="../colaborador/"> Colaborador</a></li>
                                        <li><a href="../producto/"> Producto</a></li>
                                        <li><a href="../pieza/"> Pieza</a></li>
                                        <li><a href="../actividad/"> Actividad</a></li>                             
                                        <li><a>Usuario<span class="fa fa-chevron-down"></span></a>
                                          <ul class="nav child_menu">
                                            <li><a href="../usuario_empleado/"> Usuario empleado</a></li>
                                            <li><a href="../usuario_cliente/"> Usuario cliente</a></li>
                                          </ul>
                                        </li>                               
                                        <li><a href="../cliente/"> Cliente</a></li>                                
                                    </ul>
                                </li>
                                
                                <li><a href="../comercio/"><i class="fa fa-cubes" aria-hidden="true"></i> Pedidos </a></li>
                                <li><a href="../plan_produccion/"><i class="fa fa-cubes" aria-hidden="true"></i> Plan producción </a></li>
                                <li><a href="../ver_almacen/"><i class="fa fa-cubes" aria-hidden="true"></i> Ver almacen </a></li>
                                <li><a><i class="fa fa-book" aria-hidden="true"></i> Reportes <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu"> 
                                        <li><a href="../reporte/"> Reporte de plan producción</a></li>
                                    </ul>
                                </li>
                        ';
                
        $menu02 = 
                        '
                                <li><a href="../carrito/"><i class="fa fa-shopping-cart"></i> Carrito de compras</a></li>                                                         
                                <li><a href="../pedido/"><i class="fa fa-list-alt"></i> Mis pedidos</a></li>                                                         
                        ';
                
        
        return $parametro != 0 ? $menu01 : $menu02;   
    }
}