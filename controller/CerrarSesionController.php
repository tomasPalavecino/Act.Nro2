<?php

class CerrarSesionController{

    private $printer;

    public function __construct($printer){//no entiendo printer
        $this->printer = $printer;
    }   

    function show(){
        session_destroy();
        echo $this->printer->render("view/sesionFinalizada.html");
    }

    
    }

