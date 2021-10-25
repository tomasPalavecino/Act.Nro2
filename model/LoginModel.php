<?php

class LoginModel
{
    private $database;

    public
    function __construct($database)
    {
        $this->database = $database;
    }

    public function Loguearse($nombreUsuario, $clave)
    {
        $usuario = $this->comprobarTipoUsuario($nombreUsuario, $clave);
        $model = array();

        if ($usuario["tipoDeUsuario"] == 0 || $usuario["tipoDeUsuario"] == 1) {
            $tipoDeUsuario = $usuario["tipoDeUsuario"];
            $_SESSION["nombreDeUsuario"] = $usuario["nombreDeUsuario"];
            $_SESSION["idUsuario"] = $usuario["idUsuario"];
            $model = array("tipo" => $tipoDeUsuario, "usuario" => $usuario, "sessionId" => $_SESSION["idUsuario"], "sessionNombre" => $_SESSION["nombreDeUsuario"]);

            return $model;
        }
        $mensaje = "Usuario Inexistente";
        $model = array("tipo" => $usuario, "error" => $mensaje);
        return $model;
    }

    public
    function comprobarTipoUsuario($nombreUsuario, $clave)
    {
        //compruebo si el usuario existe. si me devuelve un 1 es un admin si 0 un usuario comun

        $consultaPorUsuarioComun = "SELECT * FROM Usuario WHERE nombreDeUsuario= '" . $nombreUsuario .
            "'AND contrasenia= '"
            . $clave . "'";


        $usuarioComun = $this->database->query($consultaPorUsuarioComun);


        if ($usuarioComun) { //compruebo si devolvio algo.
            return $usuarioComun; //este me va a dirigir a la pagina de usuarioComun

        } else {
            return -1;
        }
    }
}
