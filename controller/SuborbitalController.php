<?php



class SuborbitalController
{

    private $modelSuborbital;
    private $printer;
    private $dompdf;
    private $qr;

    //ver luego si requiero un model
    public function __construct($dompdf, $modelSuborbital, $printer, $qr)
    {
        $this->modelSuborbital = $modelSuborbital;
        $this->printer = $printer;
        $this->dompdf = $dompdf;
        $this->qr = $qr;
    }

    public function show()
    {

        if (isset($_SESSION["idUsuario"])) {
            $varSession = $_SESSION["nombreUsuario"];
            $model["nombreSession"] = $varSession;
            $model["suborbitales"] =  $this->modelSuborbital->listarViajesSuborbitalesDisponibles();
            echo $this->printer->render("view/SuborbitalReserva.html", $model);
        } else {
            header("Location: /TPFINALPW2/Login/show");
        }
    }

    public function showForm()
    {
        if (isset($_SESSION["idUsuario"])) {


            $idViaje = $_GET["idViaje"];
            $idUsuario = $_SESSION["idUsuario"];
            $validacionTipoUsuario = $this->modelSuborbital->comprobarSiPuedeVolar($idUsuario, $idViaje); //boolean
            $varSession = $_SESSION["nombreUsuario"];
            $validacionTieneChequeoRealizado = $this->modelSuborbital->comprobarChequeoExistente($idUsuario);

            if ($validacionTieneChequeoRealizado == true) {

                if ($validacionTipoUsuario == true) {
                    $model["nombreSession"] = $varSession;
                    $model["data"] = array("idViaje" => $_GET["idViaje"], "regreso" => $_GET["freg"], "salida" => $_GET["fsal"]);
                    $idViaje = $_GET["idViaje"];
                    $model["disponibilidad"] = $this->modelSuborbital->obtenerReservasDisponibles($idViaje);
                    echo $this->printer->render("view/formularioSuborbitalReserva.html", $model);
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
        $reserva = $this->modelSuborbital->obtenerAsientoPorReserva($idReserva);
        $asiento = $reserva["asiento"];
        $viaje = $this->modelSuborbital->obtenerViajePorId($idViaje);


        $emailSubject = "Confirmacion de reserva Gaucho Rocket";
        $email_mensaje = "Detalle del viaje: " . "\n";
        $email_mensaje .= "Nombre: " . $_SESSION["nombreUsuario"] . "\r\n";
        $email_mensaje .= "Direccion: " . $_POST["direccion"] . "\r\n";
        $email_mensaje .= "Con Fecha de Salida: " . $viaje["fechaSalida"] . "\r\n";
        $email_mensaje .= "Con Fecha de Regreso: " . $viaje["fechaRegreso"] . "\r\n";
        $email_mensaje .= "Viaje de tipo: Suborbital \r\n";
        $email_mensaje .= "Viajara en cabina de tipo: " . $viaje["cabina"] . "\r\n";
        $email_mensaje .= "Su asiento sera el N- : " . $asiento . "\r\n";
        $email_mensaje .= "Su equipo sera: " . $viaje["equipo"] . "\r\n";
        $email_mensaje .= "Confirmar turno local http://localhost/TPFINALPW2/Suborbital/reservar?idViaje=$idViaje&idUsuario=$idUsuario&nRan=$nRan&idReserva=$idReserva \r\n";
        $email_mensaje .= "Se guardara su lugar, pero recuerde que no podra viajar hasta no tener el pago realizado";

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
            $model["mensaje"] = "Disculpe, ha ocurrido un error. Intente reenviar su solicitud";
            $model["nombreSession"] = $_SESSION["nombreUsuario"];

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
            $viaje = $this->modelSuborbital->reservarViaje($idUsuario, $idReserva);
            $tipo = "Suborbital";
            $this->dompdf->render($suborbital,$viaje, $varSession, $this->qr);
        } else {
            echo "Ups ha ocurrido un error";
        }
    }

    public function showFormPago(){   
        $idReserva = $_GET["idReserva"];
        $comprobarSiYaPago = $this->modelSuborbital->obtenerAsientoPorReserva($idReserva);
        if(isset($_SESSION["idUsuario"])){

            if($comprobarSiYaPago["confirmado"] == true){
                $model["nombreSession"] = $_SESSION["nombreUsuario"];
                echo $this->printer->render("view/pagoRealizado.html", $model);
                exit();
            }

        $reserva = $this->modelSuborbital->obtenerTodaLaInformacionParaElPDF($idReserva);
        //aca arranca lo de Mercado Pago, el codigo es tal cual esta en la pagina
        // SDK de Mercado Pago
        require __DIR__ .  '/../vendor/autoload.php';
        // Agrega tus credenciales
        MercadoPago\SDK::setAccessToken('TEST-262736215767777-111621-1ac4ab10864719f8f5f2c61e9e77bd5e-183335380');
        // Crea un objeto de preferencia
        $preference = new MercadoPago\Preference();

        // Crea un ítem en la preferencia (la info del pago)
        $item = new MercadoPago\Item();
        $item->title = "Vuelo suborbital"; //titulo
        $item->description = $reserva["idReserva"];

        $item->quantity = 1;
        $item->unit_price = $reserva["precio"];
        $preference->items = array($item);//guardas en la preferencia el item
        $preference->save(); //guardas

        $preference->back_urls = array(//cuando pones "volver al sitio" te manda a esta url
                                        // y podes obtener por get la ID del pago para consultar en la api
            "success" => "http://localhost/TPFINALPW2/Suborbital/confirmar"
        );
        $preference->auto_return = "approved";
        $preference->payment_methods = array(
          "excluded_payment_types" => array(
            array("id" => "ticket")
          )
        );
        $preference->save();
      
        $model["nombreSession"] = $_SESSION["nombreUsuario"];
        $model["preference"] = $preference;//guardas la preferencia en un model
        echo $this->printer->render("view/paginaDePago.html", $model);//le mandas a la vista el objeto

    }else{
        header("Location: /TPFINALPW2/Login/show");
 
    }

}

    public function confirmar(){

       $idPago = $_GET["collection_id"];
       $HOLA = json_decode(file_get_contents("https://api.mercadopago.com/v1/payments/$idPago?access_token=TEST-262736215767777-111621-1ac4ab10864719f8f5f2c61e9e77bd5e-183335380")); 
       $idReserva = intval($HOLA->additional_info->items[0]->description); //recupero del Json la info de la reserva
        

        $this->modelSuborbital->confirmarReservaPorPagoRealizado($idReserva);
        $model["nombreSession"] = $_SESSION["nombreUsuario"];
        echo $this->printer->render("view/confirmacionReservaExito.html", $model);

        }

}
  ?>
    

