<?php

class RegistroController
{
    private $clavesdistintas = "Las claves deben ser iguales";
    private $clavesCortas = "La clave debe tener mas de 8 caracteres";
    private $mensajeUsuarioRepetido = "Usuario Existente";

    private $printer;
    private $registroModel;

    public function __construct($registroModel, $printer)
    { //no entiendo printer
        $this->printer = $printer;
        $this->registroModel = $registroModel;
    }

    function show()
    {
        echo $this->printer->render("view/RegistroView.html");
    }

    function enviarMailDeConfirmacion()
    {

        $EmailParaEnviarConfirmacionDelRegistro = $_POST["email"];
        $data["dato"] = $this->registroModel->comprobarDatos($_POST["NombreUsuario"], $_POST["clave"], $_POST["claveRepetida"]);
        $nombreUsuario = $_POST["NombreUsuario"];
        $resultado = $data["dato"];
        $_SESSION["clave"] = $_POST["clave"];

        if ((strcmp($resultado, $this->clavesdistintas) == 0) ||
            (strcmp($resultado, $this->clavesCortas) == 0) ||
            (strcmp($resultado, $this->mensajeUsuarioRepetido) == 0)
        ) {
            echo $this->printer->render("view/RegistroView.html", $data);
            //este es el caso de error
        } else {

            $emailSubject = "Confirmacion de registro de Usuario";
            $email_mensaje = "Confirmar Registro de Usuario http://localhost/TPFINALPW2/Registro/registrarse?usuario=$nombreUsuario";

            // destinatario //
            $emailTo = $EmailParaEnviarConfirmacionDelRegistro;
            $headers = 'From: c.fernandez.melian@hotmail.com' . "\r\n" .
                'Reply-To: c.fernandez.melian@hotmail.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            if (mail($emailTo, $emailSubject, $email_mensaje, $headers)) {
                echo $this->printer->render("view/ConfirmacionRegistroEmail.html");
            } else {
                $model["mensaje"] = "Disculpe, ha ocurrido un error. Intente reenviar su solicitud";
                echo $this->printer->render("view/ConfirmacionRegistroEmail.html", $model);
            }
        }
    }

    function registrarse()
    {
        $clave = $_SESSION["clave"];
        $usuario = $_GET["usuario"];
        $data["dato"] = $this->registroModel->registrar($usuario, $clave);
        session_destroy();
        echo $this->printer->render("view/registroDeUsuarioExitoso.html", $data);



    }
}
