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
                   $model = $this->loginModel->Loguearse($_POST["NombreUsuario"], $_POST["clave"]);

       if ($model["tipo"] == 0){
        echo $this->printer->render("view/Principal.html", $model);
       }

       if ($model["tipo"] == 1){
        echo $this->printer->render("view/AdminView.html", $model);

       } 
       if ($model["tipo"] == -1){

        echo $this->printer->render("view/LoginView.html", $model);
       }
        

       }else {
        echo $this->printer->render("view/LoginView.html");

       }

    }

}