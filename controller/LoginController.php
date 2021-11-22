<?php

class LoginController{

    private $printer;
    private $loginModel;

    public function __construct($loginModel,$printer){//no entiendo printer
        $this->printer = $printer;
        $this->loginModel = $loginModel;
    }   

    function show(){
        echo $this->printer->render("view/LoginView.html");
    }



    function loguearse(){
        if (isset($_POST["NombreUsuario"]) == true && isset($_POST["clave"]) == true){
            
            $nombreUsuario = $_POST["NombreUsuario"];
            $clave = $_POST["clave"];

            $this->loginModel->Loguearse($nombreUsuario, $clave);
            $this->showPaginaPrincipal();
        
       }else {
        header("Location: /TPFINALPW2/Login/show");

       }

    }

    function showPaginaPrincipal(){

        if(isset($_SESSION["tipoDeUsuario"]) || isset($_SESSION["idUsuario"])){

        
        if ($_SESSION["tipoDeUsuario"] == 0){
            $model = array("nombreSession" => $_SESSION["nombreUsuario"], "idSession" =>  $_SESSION["idUsuario"]);
            echo $this->printer->render("view/Principal.html", $model);
           }
    
           if ($_SESSION["tipoDeUsuario"] == 1){
            $model = array("nombreSession" => $_SESSION["nombreUsuario"], "idSession" =>  $_SESSION["idUsuario"]);
            echo $this->printer->render("view/AdminView.html", $model);
    
           }

           if ($_SESSION["tipoDeUsuario"] == 2){
               
            session_destroy();
            $mensaje = "Usuario Inexistente";
            $model = array("error" => $mensaje);
            echo $this->printer->render("view/LoginView.html", $model);
            exit();
           }
        }else{
            header("Location: /TPFINALPW2/Login/show");

        }
    }

    public function listaDeVuelosPendientesDePago(){

        if(isset($_SESSION["idUsuario"])){

        
            $idUsuario = $_SESSION["idUsuario"];

            $registrosDeVuelosPorConfirmar = $this->loginModel->listarVuelosPendientesDePago($idUsuario);
     
            $model["vuelosPorConfirmar"] = $registrosDeVuelosPorConfirmar;
            $model["nombreSession"] = $_SESSION["nombreUsuario"];
            
            echo $this->printer->render("view/vuelosPorConfirmar.html", $model);  
        }else{
            header("Location: /TPFINALPW2/Login/show");
        }
 
    }

}