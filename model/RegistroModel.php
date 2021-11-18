<?php

class RegistroModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function comprobarDatos($nombreUsuarioPOST, $clavePost, $repiteClavePost)
    {
        $mensajeClaveRepetida = "Las claves deben ser iguales";
        $mensajeClaveCorta = "La clave debe tener mas de 8 caracteres";
        $mensajeUsuarioRepetido = "Usuario Existente";
        if ($this->comprobarClavesIguales($clavePost, $repiteClavePost) == false) {
            return $mensajeClaveRepetida;
        }


        if ($this->comprobarClavesMenoresA8($clavePost, $repiteClavePost) == false) {
            return $mensajeClaveCorta;
        }


        if ($this->comprobarUsuarioExistente($clavePost, $nombreUsuarioPOST) == false) {
            return $mensajeUsuarioRepetido;
        }

        
    }

    public function registrar($nombreUsuario, $clave){
        $query = "insert into Usuario (nombreDeUsuario, contrasenia) values ('" . $nombreUsuario . "', '" . $clave . "')";
        $this->database->agregar($query);
        return $nombreUsuario;
    }


    public function comprobarClavesIguales($clave, $claveRepetida)
    {
        if (strcmp($clave, $claveRepetida) == 0) {
            return true;
        } else {
            return  false;
        }
    }

    public function comprobarClavesMenoresA8($clave, $claveRepetida)
    {
        if (strlen($clave) > 8) {
            return true;
        } else {
            return  false;
        }
    }

    public function comprobarUsuarioExistente($clave, $usuario)
    {
        $consulta = "SELECT * FROM Usuario WHERE nombreDeUsuario= '" . $usuario . "'AND contrasenia= '" . $clave . "'"; //guardo en una variable la consulta mysql
        $resultado =  $this->database->query($consulta);
        if ($resultado === []) {
            return true;
        } else {
            return false;
        }
    }
}
