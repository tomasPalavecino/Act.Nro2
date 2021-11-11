<?php


class DestinosController
{

    private $modelEntreDestinos;
    private $printer;

    //ver luego si requiero un model
    public function __construct($modelEntreDestinos, $printer)
    {
        $this->modelEntreDestinos = $modelEntreDestinos;
        $this->printer = $printer;
    }

    public function show()
    {

        if (isset($_SESSION["idUsuario"])) {
            $varSession = $_SESSION["nombreUsuario"];
            $model["nombreSession"] = $varSession;
            $model["EntreDestinos"] =  $this->modelEntreDestinos->listarViajesEntreDestinosDisponibles();
            echo $this->printer->render("view/DestinosReserva.html", $model);
        } else {
            header("Location: /TPFINALPW2/Login/show");
        }
    }

    public function showForm()
    {
        if (isset($_SESSION["idUsuario"])) {


            $idViaje = $_GET["idViaje"];
            $idUsuario = $_SESSION["idUsuario"];
            $validacionTipoUsuario = $this->modelEntreDestinos->comprobarSiPuedeVolar($idUsuario, $idViaje); //boolean
            $varSession = $_SESSION["nombreUsuario"];

            $validacionTieneChequeoRealizado = $this->modelEntreDestinos->comprobarChequeoExistente($idUsuario);

            if ($validacionTieneChequeoRealizado == true) {

                if ($validacionTipoUsuario == true) {
                    $model["nombreSession"] = $varSession;
                    $model["data"] = array("idViaje" => $_GET["idViaje"], "regreso" => $_GET["freg"], "salida" => $_GET["fsal"]);
                    $model["disponibilidad"] = $this->modelEntreDestinos->obtenerReservasDisponibles($idViaje);

                    echo $this->printer->render("view/formularioEntreDestinosReserva.html", $model);
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

    public function enviarEmail()
    {


        $idViaje =  $_GET["idViaje"];
        $idUsuario =  $_SESSION["idUsuario"];
        $_SESSION["NumRandom"] = rand(5000, 6000);
        $nRan = $_SESSION["NumRandom"];
        $idReserva = $_POST["idReserva"];
        $reserva = $this->modelEntreDestinos->obtenerAsientoPorReserva($idReserva);
        $asiento = $reserva["asiento"];
        $viaje = $this->modelEntreDestinos->obtenerViajePorId($idViaje);

        $emailSubject = "Confirmacion de reserva Gaucho Rocket";
        $email_mensaje = "Detalle del viaje: " . "\n";
        $email_mensaje .= "Nombre: " . $_SESSION["nombreUsuario"] . "\r\n";
        $email_mensaje .= "Direccion: " . $_POST["direccion"] . "\r\n";
        $email_mensaje .= "Con Fecha de Salida: " . $viaje["fechaSalida"] . "\r\n";
        $email_mensaje .= "Con Fecha de Regreso: " . $viaje["fechaRegreso"] . "\r\n";
        $email_mensaje .= "Viaje de tipo: Entre Destinos \r\n";
        $email_mensaje .= "Viajara en cabina de tipo: " . $viaje["cabina"] . "\r\n";
        $email_mensaje .= "Su asiento sera el N- : " . $asiento . "\r\n";
        $email_mensaje .= "Su equipo sera: " . $viaje["equipo"] . "\r\n";
        $email_mensaje .= "Confirmar turno local http://localhost/TPFINALPW2/Suborbital/reservar?idViaje=$idViaje&idUsuario=$idUsuario&nRan=$nRan&idReserva=$idReserva";
        //numero randomico en el metodo para agregar seguridad

        // destinatario//
        $emailTo = $_POST["email"];
        $headers = 'From: c.fernandez.melian@hotmail.com' . "\r\n" .
            'Reply-To: c.fernandez.melian@hotmail.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        if (mail($emailTo, $emailSubject, $email_mensaje, $headers)) {
            $model["mensaje"] = "Por favor, revise su casilla de correo para confirmar el viaje";
            $model["nombreSession"] = $_SESSION["nombreUsuario"];

            echo $this->printer->render("view/envioDeMailConfirmacion.html", $model);
        } else {
            $model["nombreSession"] = $_SESSION["nombreUsuario"];
            $model["mensaje"] = "Disculpe, ha ocurrido un error. Intente reenviar su solicitud";
            echo $this->printer->render("view/envioDeMailConfirmacion.html", $model);
        }
    }

    public function reservar()
    {

        if ($_SESSION["NumRandom"] == $_GET["nRan"]) {
            $idReserva = $_GET["idReserva"];
            $idUsuario = $_GET["idUsuario"];
            $varSession = $_SESSION["nombreUsuario"];
            $model["nombreSession"] = $varSession;
            $this->modelEntreDestinos->reservarViaje($idUsuario, $idReserva);

            $viaje = $this->modelSuborbital->reservarViaje($idUsuario, $idReserva);
            $this->dompdf->render($viaje, $varSession);
        } else {
            echo "Ups ha ocurrido un error";
        }
    }
}
