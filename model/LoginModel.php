<?php

class LoginModel
{
    private $database;

    public
    function __construct($database)
    {
        $this->database = $database;
    }

    public
    function Loguearse($nombreUsuario, $clave)
    {
        $tipoUsuario = $this->comprobarTipoUsuario($nombreUsuario, $clave);
        $model = array();
        if ($tipoUsuario == 1 || $tipoUsuario == 0) {
            $usuario = $this->obtenerUsuarioPorId($nombreUsuario, $clave);
            $_SESSION["nombreUsuario"] = $usuario["nombreDeUsuario"];
            $_SESSION["idUsuario"] = $usuario["idUsuario"];
            $model = array("tipo" => $tipoUsuario, "nombreSession" => $_SESSION["nombreUsuario"], "idSession" =>  $_SESSION["idUsuario"]);
            return $model;
        }
        if ($tipoUsuario == -1) {
            $mensaje = "Usuario Inexistente";
            $model = array("tipo" => $tipoUsuario, "error" => $mensaje);
            return $model;
        }
        return $model;
    }

    public
    function comprobarTipoUsuario($nombreUsuario, $clave)
    {
        //compruebo si el usuario existe. si me devuelve un 1 es un admin si 0 un usuario comun
        $cero = "0";
        $uno = "1";

        $consultaPorUsuarioComun = "SELECT * FROM Usuario WHERE nombreDeUsuario= '" . $nombreUsuario .
            "'AND contrasenia= '"
            . $clave .
            "'AND tipoDeUsuario = '" . $cero .
            "'";

        $consultaPorAdmin = "SELECT * FROM Usuario WHERE nombreDeUsuario= '" . $nombreUsuario .
            "'AND contrasenia= '"
            . $clave .
            "'AND tipoDeUsuario = '" . $uno .
            "'";

        $usuarioComun = $this->database->query($consultaPorUsuarioComun);
        $usuarioAdmin = $this->database->query($consultaPorAdmin);



        if ($usuarioComun) { //compruebo si devolvio algo.
            return 0; //este me va a dirigir a la pagina de usuarioComun
        } else if ($usuarioAdmin) {
            return 1; //me va a dirigir a la pagina de admin
        } else {
            return -1; //me va a tirar un error de UsuarioInexistente.
        }
    }

    public function obtenerUsuarioPorId($nombreUsuario, $clave)  {
        $consulta =  "SELECT * FROM Usuario WHERE nombreDeUsuario= '" . $nombreUsuario .
        "'AND contrasenia= '"
        . $clave ."'";
        $usuario = $this->database->obtenerArrayRegistro($consulta);
        return $usuario;
    }
}
