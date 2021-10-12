<?php

class RegistroModel{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function registrar($nombreUsuarioPOST, $clavePost, $repiteClavePost){
       $mensajeClaveRepetida = "Las claves deben ser iguales";
       $mensajeClaveCorta = "La clave debe tener mas de 8 caracteres";
       $mensajeUsuarioRepetido = "Usuario Existente";
            if ($this->comprobarClavesIguales($clavePost, $repiteClavePost) == true){
                if ($this->comprobarClavesMenoresA8($clavePost, $repiteClavePost == true)){
                    if ($this->comprobarUsuarioExistente($clavePost, $nombreUsuarioPOST)){
                        $query = "insert into Usuario (nombreDeUsuario, contrasenia) values ('".$nombreUsuarioPOST."', '".$clavePost."')";
                        $this->database->agregar($query);
                        return $nombreUsuarioPOST;
                    }else {
                        return $mensajeUsuarioRepetido;
                    } 
                }else {
                    return $mensajeClaveCorta;
                }
            }else {
                return $mensajeClaveRepetida;
            }
            
        } 

    
    public function comprobarClavesIguales($clave, $claveRepetida){
        if (strcmp($clave, $claveRepetida)==0 ){
            return true;
        } else {
           return  false;
        }
    }

    public function comprobarClavesMenoresA8($clave, $claveRepetida){
        if (strlen($clave) > 8 ){
            return true;
        } else {
            return  false;
            }
    }

    public function comprobarUsuarioExistente($clave, $usuario){
        $consulta="SELECT * FROM Usuario WHERE nombreDeUsuario= '" .$usuario."'AND contrasenia= '" . $clave ."'";//guardo en una variable la consulta mysql
        $resultado =  $this->database->query($consulta);
        if ($resultado === []){
            return true;
        }else {
            return false;
        }

    }
}