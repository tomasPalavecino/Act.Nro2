<?php

class RegistroController{
    private $clavesdistintas = "Las claves deben ser iguales";
    private $clavesCortas = "La clave debe tener mas de 8 caracteres";
    private $mensajeUsuarioRepetido = "Usuario Existente";

    private $printer;
    private $registroModel;

    public function __construct($registroModel,$printer){//no entiendo printer
        $this->printer = $printer;
        $this->registroModel = $registroModel;
    }   

    function show(){
        echo $this->printer->render("view/RegistroView.html");
    }

    function registrar(){
        $data["dato"] = $this->registroModel->registrar($_POST["NombreUsuario"], $_POST["clave"], $_POST["claveRepetida"]);
        $resultado = $data["dato"];
        if ((strcmp($resultado, $this->clavesdistintas) == 0) || 
            (strcmp($resultado, $this->clavesCortas) == 0) || 
            (strcmp($resultado, $this->mensajeUsuarioRepetido) == 0 )){
            echo $this->printer->render("view/RegistroView.html", $data);
        }else {
            echo $this->printer->render("view/RegistroExitosoView.html", $data);
  
        }
       
    }

}