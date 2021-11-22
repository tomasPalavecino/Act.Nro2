<?php



class ChequeoModel
{



    private $database;



    public function __construct($database)
    {
        $this->database = $database;
    }



    public function registrarChequeo($idUsuario, $codigo)
    {
        $insert = "insert into ChequeoMedico(tipoDeCliente, idUsuario)
value ('" . $codigo . "','" . $idUsuario . "')";



        $this->database->agregar($insert);
    }

    public function comprobarChequeoExistente($idUsuario){
        $consulta = "SELECT * FROM chequeomedico where idUsuario like '".$idUsuario."'";

        return $this->database->obtenerArrayRegistro($consulta);
       
    }
}
