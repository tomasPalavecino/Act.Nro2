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


}


?>