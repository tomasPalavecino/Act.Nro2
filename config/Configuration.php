<?php
class Configuration{

    private $config;

    public  function createRegistroController(){ //registroController
        require_once("controller/RegistroController.php");
        return new RegistroController( $this->createRegistroModel() , $this->createPrinter());//le paso un reg model
    }

    private  function createRegistroModel(){//creo un registro model
        require_once("model/RegistroModel.php");
        $database = $this->getDatabase();
        return new RegistroModel($database);//le paso una base de datos
    }

    public  function createLoginController(){//creo un controlador login
        require_once("controller/LoginController.php"); //uso la clase
        return new LoginController( $this->createLoginModel(),  $this->createPrinter());
        //le paso un model con un logger (errores) y printer (ni idea)
    }

    private  function createLoginModel(){//creo un loginmodel
        require_once("model/LoginModel.php");
        $database = $this->getDatabase();
        return new LoginModel($database);//habiendole pasado una BD.
    }  

    public function createCerrarSesionController(){
        require_once("controller/CerrarSesionController.php");
        return new CerrarSesionController( $this->createPrinter());
    }

    public function createSuborbitalController(){
        require_once("controller/SuborbitalController.php");
        return new SuborbitalController($this->createPrinter());
    }
  

    

    private  function getDatabase(){
        require_once("helpers/MyDatabase.php");
        $config = $this->getConfig();
        return new MyDatabase($config["servername"], $config["username"], $config["password"], $config["dbname"]);
    }

    private  function getConfig(){
        if( is_null( $this->config ))
            $this->config = parse_ini_file("config/config.ini");

        return  $this->config;
    }

    private function getLogger(){
        require_once("helpers/Logger.php");
        return new Logger();
    }

    public function createRouter($defaultController, $defaultAction){
        include_once("helpers/Router.php");
        return new Router($this,$defaultController,$defaultAction);
    }

    private function createPrinter(){
        require_once ('public/third-party/mustache/src/Mustache/Autoloader.php');
        require_once("helpers/MustachePrinter.php");
        return new MustachePrinter("view/partials");
    }

}