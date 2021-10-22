<?php
class SuborbitalController{

    private $modelSuborbital;
    private $printer;
    
    //ver luego si requiero un model
    public function __construct($modelSuborbital ,$printer){
        $this->modelSuborbital = $modelSuborbital;
        $this->printer = $printer;

    }

    public function show(){

        $model["suborbitales"] =  $this->modelSuborbital->listarViajesSuborbitalesDisponibles();
        echo $this->printer->render("view/SuborbitalReserva.html", $model);

    }





    
}



?>