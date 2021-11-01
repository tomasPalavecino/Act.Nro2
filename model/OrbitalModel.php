<?php

class OrbitalModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }


    public function listarViajesOrbitalesDisponibles()
    {
        $consulta = "SELECT * FROM viaje inner join aeronave on viaje.idAeronave = aeronave.idAeronave
        inner join tipodeviaje on aeronave.idTipoDeViaje = tipodeviaje.idTipoDeViaje inner join salida
        on viaje.idSalida = salida.idSalida inner join destino on viaje.idDestino = destino.idDestino
        inner join equipo on equipo.idEquipo = aeronave.idEquipo inner join modelo on modelo.idModelo = aeronave.idModelo
        where tipodeviaje.tipoDeViaje like 'Orbital'";
        $resultado = $this->database->query($consulta); //me devuelve un array
        return $resultado;
    }

    public function obtenerViajePorId($idViaje)
    {
        $consulta = "SELECT * FROM viaje inner join aeronave on viaje.idAeronave = aeronave.idAeronave
        inner join tipodeviaje on aeronave.idTipoDeViaje = tipodeviaje.idTipoDeViaje inner join salida
        on viaje.idSalida = salida.idSalida inner join destino on viaje.idDestino = destino.idDestino
        inner join equipo on equipo.idEquipo = aeronave.idEquipo inner join modelo on modelo.idModelo = aeronave.idModelo 
        inner join cabina on cabina.idCabina = aeronave.idCabina 
        where viaje.idViaje = '" . $idViaje . "'";
        $viaje = $this->database->obtenerArrayRegistro($consulta);
        return $viaje;
    }



    public function reservarViaje($idUsuario, $idReserva)
    {
        $insert = "UPDATE reserva set idUsuario = '".$idUsuario."', estado = '".true."' where idReserva = '".$idReserva."'";
        $this->database->agregar($insert);
    }

    public function comprobarSiPuedeVolar($idUsuario, $idViaje)
    {
        // si no tiene un chequeo, no puede seguir. y validar su tipo con el viaje que quiere hacer
        // el viaje lo necesito para acceder al equipo.
        $consultarTipoDeUsuario = "SELECT * FROM usuario inner join chequeoMedico 
        on usuario.idUsuario = chequeoMedico.idUsuario where usuario.idUsuario = '" . $idUsuario . "'";

        $arrayUsuario = $this->database->obtenerArrayRegistro($consultarTipoDeUsuario);
        $tipoDeUsuario = $arrayUsuario["tipoDeCliente"];

        $consultarEquipo = "SELECT * FROM viaje inner join aeronave 
        on viaje.idAeronave = aeronave.idAeronave inner join equipo on equipo.idEquipo
        = aeronave.idEquipo where viaje.idViaje = '" . $idViaje . "'";

        $arrayUsuario = $this->database->obtenerArrayRegistro($consultarEquipo);
        $equipo = $arrayUsuario["equipo"];

        if ($tipoDeUsuario == 1 || $tipoDeUsuario == 2) {
            if ($equipo != "Alta aceleracion") {
                return true;
            }
        } else if ($tipoDeUsuario == 3) {
            return true;
        } else {
            return false;
        }
    }

    public function comprobarChequeoExistente($idUsuario){

        $consulta = "SELECT * FROM chequeomedico where idUsuario like '".$idUsuario."'";

        $existe =  $this->database->consultarSiExisteRegistro($consulta);
        return $existe;   
     }

     public function obtenerReservasDisponibles($idViaje){
        $consulta = "SELECT * FROM reserva where idViaje = '".$idViaje."'";
        return $this->database->query($consulta);

     }
     

     public function obtenerAsientoPorReserva($idReserva){
         $consulta = "SELECT * FROM reserva where idReserva = '".$idReserva."'";
         return $this->database->obtenerArrayRegistro($consulta);
     }
}
