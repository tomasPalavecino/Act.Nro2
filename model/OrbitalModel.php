<?php

class OrbitalModel{

    private $database;

    public function __construct($database) {
        $this->database = $database;

    }


    public function listarViajesOrbitalesDisponibles(){
        $consulta = "SELECT * FROM viaje inner join equipo on viaje.idEquipo = equipo.idEquipo
        inner join TipoDeViaje on viaje.idTipoDeViaje = TipoDeViaje.idTipoDeViaje inner join salida
        on viaje.idSalida = salida.idSalida inner join destino on viaje.idDestino = destino.idDestino
        inner join cabina on viaje.idCabina = cabina.idCabina  where TipoDeViaje.tipoDeViaje like 'Orbital'" ;
        $resultado = $this->database->query($consulta); //me devuelve un array
        return $resultado;
    }

    public function obtenerViajePorId($idViaje){
        $consulta = "SELECT * FROM viaje inner join equipo on viaje.idEquipo = equipo.idEquipo
        inner join TipoDeViaje on viaje.idTipoDeViaje = TipoDeViaje.idTipoDeViaje inner join salida
        on viaje.idSalida = salida.idSalida inner join destino on viaje.idDestino = destino.idDestino
        inner join cabina on viaje.idCabina = cabina.idCabina where viaje.idViaje = '".$idViaje."'";
        $viaje = $this->database->obtenerArrayRegistro($consulta);
        return $viaje;
    }



    public function reservarViaje($idViaje, $idUsuario){
        $insert = "INSERT into reserva (idViaje, idUsuario) values ('" . $idViaje . "', '" . $idUsuario . "')"; 
        $this->database->agregar($insert);
    }


}


?>