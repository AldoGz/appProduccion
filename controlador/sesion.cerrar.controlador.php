<?php

session_name("_sis_produccion_");
session_start();

unset($_SESSION["cod_usuario"]);
unset($_SESSION["usuario"]);
unset($_SESSION["cod_perfil"]);
unset($_SESSION["perfil"]);
session_destroy();

header("location:../vista/sesion/");