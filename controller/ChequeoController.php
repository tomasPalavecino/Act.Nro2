<?php



class ChequeoController
{



    private $modelo;
    private $printer;



    public function __construct($modelo, $printer)
    {
        $this->modelo = $modelo;
        $this->printer = $printer;
    }



    function show()
    {
        if (isset($_SESSION["idUsuario"])) {
            $varSession = $_SESSION["nombreUsuario"];
            $model["nombreSession"] = $varSession;

            echo $this->printer->render("view/formMedic.html", $model);
        } else {
            header("Location: /TPFINALPW2/Login/show");
        }
    }



    function chequeo()
    {

        if (isset($_SESSION["idUsuario"])) {
            $codigo = $this->codUsuario();
            $idUsuario = $_SESSION['idUsuario'];

            $varSession = $_SESSION["nombreUsuario"];
            $model["nombreSession"] = $varSession;

            $registroChequeo = $this->modelo->comprobarChequeoExistente($idUsuario);


            if ($registroChequeo != null) {
                $model["error"] = "Usted ya cuenta con un chequeo medico realizado anteriormente 
                Contactese con su proveedor de servicios para que se le permita registrar 
                otra visita medica. Su chequeo es del tipo: ".$registroChequeo['tipoDeCliente'];
                echo $this->printer->render("view/formMedic.html", $model);

            } else {
                $this->modelo->registrarChequeo($idUsuario, $codigo);
                $registroChequeo = $this->modelo->comprobarChequeoExistente($idUsuario);
                $model["tipoDeCliente"] = $registroChequeo["tipoDeCliente"];
                echo $this->printer->render("view/chequeoExitoso.html", $model);
            }
        } else {
            header("Location: /TPFINALPW2/Login/show");
        }
    }





    private function codUsuario()
    {
        $ran = rand(10, 100);



        switch ($ran) {
            case ($ran >= 60 && $ran <= 100):
                return 3;
                break;
            case ($ran >= 30 && $ran < 60):
                return 2;
                break;
            case ($ran >= 10 && $ran < 30):
                return 1;
                break;
            default:
                "Error";
                break;
        }
    }
}
