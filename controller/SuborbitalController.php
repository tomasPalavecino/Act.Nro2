<?php
class SuborbitalController{

    private $printer;
    
    //ver luego si requiero un model
    public function SuborbitalController($printer){
        $this->printer = $printer;

    }

    public function show(){
        echo $this->printer->render("view/SuborbitalReserva.html");

    }





    
}



?>