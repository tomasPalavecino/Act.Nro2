<?php

class AdminController
{

    //El admin se va a encargar de subir nuevos vuelos en base a las aeronaves ya existentes.
    //no se va hacer un metodo para crear nuevos aviones aun. Tambien el admin puede habilitar 
    //al usuario con su nombre de usuario para hacer un nuevo chequeo medico.


    private $modelAdmin;
    private $printer;

    public function __construct($modelAdmin, $printer)
    {
        $this->modelAdmin = $modelAdmin;
        $this->printer = $printer;
    }

    public function showFormNuevoVuelo()
    {

        if (isset($_SESSION["idUsuario"])) {
            $model["aviones"] = $this->modelAdmin->obtenerAeronaves();
            $model["destinos"] = $this->modelAdmin->obtenerDestinos();
            $model["salidas"] = $this->modelAdmin->obtenerSalidas();
            $model["nombreSession"] = $_SESSION["nombreUsuario"];

            echo $this->printer->render("view/formNuevoVuelo.html", $model);
        } else {
            header("Location: /TPFINALPW2/Login/show");
        }
    }

    public function registrarVuelo()
    {

        if ($_SESSION["idUsuario"]) {


            $model["nombreSession"] = $_SESSION["nombreUsuario"];
            $idDestino = $_POST["destino"];
            $idSalida = $_POST["salida"];
            $idAeronave = $_POST["aeronave"];
            $fechaSalida = $_POST["FSal"];
            $fechaLlegada = $_POST["FLlegada"];

            $resultado = $this->modelAdmin->validarViaje($idDestino, $idAeronave);

            if ($resultado == true) {

                $vueloRegistrado =  $this->modelAdmin->registrarNuevoVuelo($fechaSalida, $fechaLlegada, $idSalida, $idDestino, $idAeronave);
                $idDelVuelo = $vueloRegistrado["idViaje"];
                $this->modelAdmin->crearReservasParaEseVuelo($idDelVuelo, $idAeronave);

                $model["mensaje"] = "Vuelo registrado con exito";
                echo $this->printer->render("view/resultadoRegistroVuelo.html", $model);
            } else {
                $model["mensaje"] = "No es posible efectuar este tipo de vuelo, revise las opciones ingresadas";
                echo $this->printer->render("view/resultadoRegistroVuelo.html", $model);
            }
        } else {
            header("Location: /TPFINALPW2/Login/show");
        }
    }
}
