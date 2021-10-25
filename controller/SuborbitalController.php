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
        
        $varSession = $_SESSION["nombreUsuario"];
        $model["nombreSession"] = $varSession;
        $model["suborbitales"] =  $this->modelSuborbital->listarViajesSuborbitalesDisponibles();

        echo $this->printer->render("view/SuborbitalReserva.html", $model);

    }

    public function showForm(){

        $varSession = $_SESSION["nombreUsuario"];
        $model["nombreSession"] = $varSession;
        $model["data"] = array ("idViaje" => $_GET["idViaje"], "regreso" => $_GET["freg"], "salida" => $_GET["fsal"]);
        
        echo $this->printer->render("view/formularioReservaTurno.html", $model);

    }

    public function enviarEmail(){
        $idViaje =  $_GET["idViaje"];
        $idUsuario =  $_SESSION["idUsuario"];
        
        $viaje = $this->modelSuborbital->obtenerViajePorId($idViaje);

        $emailSubject = "Confirmacion de reserva Gaucho Rocket";
        $email_mensaje = "Detalle del viaje: " . "\n";
        $email_mensaje .= "Nombre: ". $_SESSION["nombreUsuario"]. "\r\n";
        $email_mensaje .= "Direccion: ". $_POST["direccion"]. "\r\n";
        $email_mensaje .= "Con Fecha de Salida: ". $viaje["fechaSalida"]. "\r\n";
        $email_mensaje .= "Con Fecha de Regreso: ". $viaje["fechaRegreso"]. "\r\n";
        $email_mensaje .= "Confirmar turno local http://localhost/TPFINALPW2/Suborbital/reservar?idViaje=$idViaje&idUsuario=$idUsuario"; 

    
        // destinatario//
        $emailTo = $_POST["email"];
        $headers = 'From: c.fernandez.melian@hotmail.com' . "\r\n". 
        'Reply-To: c.fernandez.melian@hotmail.com' . "\r\n".
        'X-Mailer: PHP/' . phpversion();

        if (mail($emailTo, $emailSubject,$email_mensaje,$headers)){
            echo 'enviado';
        }else{
            echo 'no se envio';
            echo $email_mensaje;
        }

    }

    public function reservar(){
        $idViaje = $_GET["idViaje"];
        $idUsuario = $_GET["idUsuario"];
        $varSession = $_SESSION["nombreUsuario"];
        $model["nombreSession"] = $varSession;
        $this->modelSuborbital->reservarViaje($idViaje, $idUsuario);


        echo $this->printer->render("view/reservaExitosa.html" ,$model);


    }








    
}



?>