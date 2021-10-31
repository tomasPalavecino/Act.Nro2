<?php
  

class OrbitalController{

    private $modelOrbital;
    private $printer;
    
    //ver luego si requiero un model
    public function __construct($modelOrbital ,$printer){
        $this->modelOrbital = $modelOrbital;
        $this->printer = $printer;

    }

    public function show(){
        
        if (isset($_SESSION["idUsuario"])){
            $varSession = $_SESSION["nombreUsuario"];
            $model["nombreSession"] = $varSession;
            $model["orbitales"] =  $this->modelOrbital->listarViajesOrbitalesDisponibles();
            echo $this->printer->render("view/OrbitalReserva.html", $model);
        }else {
            header ("Location:/TPFINALPW2/Login/show");
        }

    }

    public function showForm()
    {
        if (isset($_SESSION["idUsuario"])) {


            $idViaje = $_GET["idViaje"];
            $idUsuario = $_SESSION["idUsuario"];
            $validacionTipoUsuario = $this->modelOrbital->comprobarSiPuedeVolar($idUsuario, $idViaje); //boolean
            $varSession = $_SESSION["nombreUsuario"];

            $validacionTieneChequeoRealizado = $this->modelOrbital->comprobarChequeoExistente($idUsuario);

            if ($validacionTieneChequeoRealizado == true) {

                if ($validacionTipoUsuario == true) {
                    $model["nombreSession"] = $varSession;
                    $model["data"] = array("idViaje" => $_GET["idViaje"], "regreso" => $_GET["freg"], "salida" => $_GET["fsal"]);
                    echo $this->printer->render("view/formularioOrbitalReserva.html", $model);
                } else {
                    $varSession = $_SESSION["nombreUsuario"];
                    $error = "Usted no puede acceder a este tipo de vuelos, debe ser del tipo 3";

                    $model["nombreSession"] = $varSession;
                    $model["error"] = $error;
                    echo $this->printer->render("view/alertaReserva.html", $model);
                }
            } else {
                $model["nombreSession"] =  $varSession;
                $model["error"] = "Para reservar un vuelo usted necesita tener el chequeo medico realizado";
                echo $this->printer->render("view/alertaReserva.html", $model);
            }



            //Si es tipo 1 y 2 (Suborbital y baja aceleracion) y si 3, los 3

        } else {
            header("Location: /TPFINALPW2/Login/show");
        }
    }

    public function enviarEmail(){


        $idViaje =  $_GET["idViaje"];
        $idUsuario =  $_SESSION["idUsuario"];
        $_SESSION["NumRandom"] = rand(5000,6000);
        $nRan= $_SESSION["NumRandom"]; 
        
        $viaje = $this->modelOrbital->obtenerViajePorId($idViaje);

        $emailSubject = "Confirmacion de reserva Gaucho Rocket";
        $email_mensaje = "Detalle del viaje: " . "\n";
        $email_mensaje .= "Nombre: ". $_SESSION["nombreUsuario"]. "\r\n";
        $email_mensaje .= "Direccion: ". $_POST["direccion"]. "\r\n";
        $email_mensaje .= "Con Fecha de Salida: ". $viaje["fechaSalida"]. "\r\n";
        $email_mensaje .= "Con Fecha de Regreso: ". $viaje["fechaRegreso"]. "\r\n";
        $email_mensaje .= "Viaje de tipo Orbital: \r\n";
        $email_mensaje .= "La duracion sera de: ". $viaje["duracion"]. " dias \r\n";
        $email_mensaje .= "Viajara en cabina de tipo ". $viaje["cabina"]. "\r\n";
        $email_mensaje .= "Su equipo sera: ".$viaje["equipo"]. "\r\n";
        $email_mensaje .= "Confirmar turno local http://localhost/TPFINALPW2/Suborbital/reservar?idViaje=$idViaje&idUsuario=$idUsuario&nRan=$nRan"; 

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

        if ($_SESSION["NumRandom"] == $_GET["nRan"]){
            $idViaje = $_GET["idViaje"];
            $idUsuario = $_GET["idUsuario"];
            $varSession = $_SESSION["nombreUsuario"];
            $model["nombreSession"] = $varSession;
            $this->modelSuborbital->reservarViaje($idViaje, $idUsuario);
    
    
            echo $this->printer->render("view/reservaExitosa.html" ,$model);
    
        }else {
            echo"Ups ha ocurrido un error"; 

        }
 

    }

}


?>