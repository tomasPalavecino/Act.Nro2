<?php

use Dompdf\Dompdf;

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
        return new SuborbitalController($this->createDomPDF(), $this->createSuborbitalModel(),$this->createPrinter());
    }
  
    public function createSuborbitalModel(){
        require_once("model/SuborbitalModel.php");
        return new SuborbitalModel ($this->getDatabase());
    }

    public function createOrbitalController(){
        require_once("controller/OrbitalController.php");
        return new OrbitalController($this->createOrbitalModel(),$this->createPrinter());
    }
  
    public function createOrbitalModel(){
        require_once("model/OrbitalModel.php");
        return new OrbitalModel ($this->getDatabase());
    }

    public function createDestinosController(){
        require_once("controller/DestinosController.php");
        return new DestinosController($this->createDestinosModel(),$this->createPrinter());
    }
  
    public function createDestinosModel(){
        require_once("model/DestinosModel.php");
        return new DestinosModel ($this->getDatabase());
    }
   
    public function createChequeoController(){
        require_once("controller/ChequeoController.php");
        return new ChequeoController($this->createChequeoModel(),$this->createPrinter());
        }
         
    public function createChequeoModel(){
        require_once("model/ChequeoModel.php");
        return new ChequeoModel($this->getDatabase());
        }

    public function createAdminController(){
        require_once("controller/AdminController.php");
        return new AdminController($this->createAdminModel(), $this->createPrinter());
    }

    public function createAdminModel(){
        require_once("model/AdminModel.php");
        return new AdminModel($this->getDatabase());
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

  

    public function createRouter($defaultController, $defaultAction){
        include_once("helpers/Router.php");
        return new Router($this,$defaultController,$defaultAction);
    }

    private function createPrinter(){
        require_once ('public/third-party/mustache/src/Mustache/Autoloader.php');
        require_once("helpers/MustachePrinter.php");
        return new MustachePrinter("view/partials");
    }

    private function createDomPDF(){
        require_once 'helpers/PdfPrinter.php';
        return new PdfPrinter();

    }

    private function createQRPrinter(){
        require_once 'helpers/QRPrinter.php';
        return new QRPrinter("www.google.com");
    }


}