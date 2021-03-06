<?php

class AdminModel
{

    //El admin se va a encargar de subir nuevos vuelos en base a las aeronaves ya existentes.
    //no se va hacer un metodo para crear nuevos aviones aun. Tambien el admin puede habilitar 
    //al usuario con su nombre de usuario para hacer un nuevo chequeo medico.


    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerAeronaves()
    {
        $consulta = "SELECT * from aeronave inner join equipo 
        on equipo.idEquipo = aeronave.idEquipo 
        inner join modelo on  modelo.idModelo = aeronave.idModelo
        inner join tipodeviaje on tipodeviaje.idTipoDeViaje = aeronave.idTipoDeViaje";

        return $this->database->query($consulta);
    }

    public function obtenerDestinos()
    {
        $consulta = "SELECT * FROM destino";
        return $this->database->query($consulta);
    }

    public function obtenerSalidas()
    {
        $consulta = "SELECT * FROM salida";
        return $this->database->query($consulta);
    }

    public function validarViaje($idDestino, $idAeronave)
    {
        $avion = $this->obtenerAeronavePorId($idAeronave);
        $destino = $this->obtenerDestinoPorId($idDestino);

        if ($avion["idTipoDeViaje"] != 3 && $destino["LugarDes"] == "Europa") {
            return true;
        }
            if ($avion["idTipoDeViaje"] == 3 && $destino["LugarDes"] != "Europa") {
                return true;
            } else {
                return false;
            }
        
    }

    public function obtenerAeronavePorId($idAeronave)
    {
        $consulta = "SELECT * FROM aeronave where idAeronave = '" . $idAeronave . "'";
        return $this->database->obtenerArrayRegistro($consulta);
    }

    public function obtenerSalidaPorId($idSalida)
    {
        $consulta = "SELECT * FROM salida where idSalida = '" . $idSalida . "'";
        return $this->database->obtenerArrayRegistro($consulta);
    }

    public function obtenerDestinoPorId($idDestino)
    {
        $consulta = "SELECT * FROM destino where idDestino = '" . $idDestino . "'";
        return $this->database->obtenerArrayRegistro($consulta);
    }

    public function registrarNuevoVuelo($fechaSalida,$fechaLlegada,$idSalida,$idDestino,$idAeronave, $valor){
        // me tiene que devolver la id del vuelo para despues crear las reservas vacias.
        $insert = "INSERT into viaje (fechaSalida,fechaRegreso,idSalida, idDestino, idAeronave, precio) 
        values ('" . $fechaSalida . "', '" . $fechaLlegada . "', '".$idSalida."', '".$idDestino."', '".$idAeronave."', '".$valor."')";
        $this->database->agregar($insert);
        
        $ultimoVueloRegistrado = "SELECT * FROM viaje ORDER BY idViaje DESC LIMIT 1"; //del insert anterior
        return $this->database->obtenerArrayRegistro($ultimoVueloRegistrado);
        
    }

    public function crearReservasParaEseVuelo($idDelVuelo,$idAeronave, $precio){
        $consulta = "SELECT capacidad from aeronave where idAeronave = '".$idAeronave."'";
        $capacidadDeLaAeronave =  $this->database->obtenerArrayRegistro($consulta);
        $caracteresPermitidos = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        for ($i = 1 ; $i <= $capacidadDeLaAeronave["capacidad"]; $i++){
            $numeroAlfanumerico = substr(str_shuffle($caracteresPermitidos),0,8);

            $reserva = "INSERT INTO reserva (idViaje, asiento, codigoAlfanumerico, precio)
            values ('".$idDelVuelo."' , '".$i."', '".$numeroAlfanumerico."', '".$precio."')";

            $this->database->agregar($reserva);
        }

    }
}
