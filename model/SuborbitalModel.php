<?php

class SuborbitalModel{

    private $database;

    public function __construct($database) {
        $this->database = $database;

    }


    public function listarViajesSuborbitalesDisponibles(){
        $consulta = "SELECT * FROM viaje inner join equipo on viaje.idEquipo = equipo.idEquipo
        inner join TipoDeViaje on viaje.idTipoDeViaje = TipoDeViaje.idTipoDeViaje 
        where TipoDeViaje.tipoDeViaje like 'Suborbital'" ;
        $resultado = $this->database->query($consulta); //me devuelve un array
        return $resultado;
    }

    public function obtenerViajePorId($idViaje){
        $consulta = "SELECT * from viaje where idViaje like ".$idViaje."";
        $viaje = $this->database->obtenerArrayRegistro($consulta);
        return $viaje;
    }



    public function reservarViaje($idViaje, $idUsuario){
        $insert = "INSERT into reserva (idViaje, idUsuario) values ('" . $idViaje . "', '" . $idUsuario . "')"; 
        $this->database->agregar($insert);
    }


}


?>