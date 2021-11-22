<?php

class LoginModel
{
    private $database;

    public
    function __construct($database)
    {
        $this->database = $database;
    }

    public
    function Loguearse($nombreUsuario, $clave)
    {
        $tipoUsuario = $this->comprobarTipoUsuario($nombreUsuario, $clave);
        
            $usuario = $this->obtenerUsuarioPorId($nombreUsuario, $clave);
            $_SESSION["nombreUsuario"] = $usuario["nombreDeUsuario"];
            $_SESSION["idUsuario"] = $usuario["idUsuario"];
            $_SESSION["tipoDeUsuario"] = $tipoUsuario;
            
       
    }

    public
    function comprobarTipoUsuario($nombreUsuario, $clave)
    {
        //compruebo si el usuario existe. si me devuelve un 1 es un admin si 0 un usuario comun
        $cero = "0";
        $uno = "1";

        $consultaPorUsuarioComun = "SELECT * FROM Usuario WHERE nombreDeUsuario= '" . $nombreUsuario .
            "'AND contrasenia= '"
            . $clave .
            "'AND tipoDeUsuario = '" . $cero .
            "'";

        $consultaPorAdmin = "SELECT * FROM Usuario WHERE nombreDeUsuario= '" . $nombreUsuario .
            "'AND contrasenia= '"
            . $clave .
            "'AND tipoDeUsuario = '" . $uno .
            "'";

        $usuarioComun = $this->database->query($consultaPorUsuarioComun);
        $usuarioAdmin = $this->database->query($consultaPorAdmin);



        if  ($usuarioComun) { //compruebo si devolvio algo.
            return 0; //este me va a dirigir a la pagina de usuarioComun
        } else if ($usuarioAdmin) {
            return 1; //me va a dirigir a la pagina de admin
        } else{
            return 2; //me va a tirar un error de UsuarioInexistente.

        }
        
    }

    public function obtenerUsuarioPorId($nombreUsuario, $clave)  {
        $consulta =  "SELECT * FROM Usuario WHERE nombreDeUsuario= '" . $nombreUsuario .
        "'AND contrasenia= '"
        . $clave ."'";
        $usuario = $this->database->obtenerArrayRegistro($consulta);
        return $usuario;
    }

    public function listarVuelosPendientesDePago($idUsuario){
        $consulta = "SELECT * FROM reserva inner join viaje on viaje.idViaje = reserva.idViaje
        inner join aeronave on viaje.idAeronave = aeronave.idAeronave
        inner join tipodeviaje on aeronave.idTipoDeViaje = tipodeviaje.idTipoDeViaje inner join salida
        on viaje.idSalida = salida.idSalida inner join destino on viaje.idDestino = destino.idDestino
        inner join equipo on equipo.idEquipo = aeronave.idEquipo inner join modelo on modelo.idModelo = aeronave.idModelo 
        inner join cabina on cabina.idCabina = aeronave.idCabina  where reserva.idUsuario = '".$idUsuario."'";   
        return $this->database->query($consulta);

        
    }
}
