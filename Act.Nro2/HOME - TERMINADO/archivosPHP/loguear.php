<?php
    
    $USUARIO=$_POST["email"];
    $PASS=$_POST["password"];
    $CLAVE_ENCRYPT= md5($PASS);
    
    session_start();
    $_SESSION['usuario'] =  $usuario;

    $db= new mysqli("localhost", "root", "2002", "gauchorocket");

    if($db -> connect_error){
        echo "Ocurrio un error" . $db->connect_error;
    }
   
    $consulta="SELECT * FROM usuario
                        WHERE usuarioMail= '" .$USUARIO
                       ."'AND password= '" . $CLAVE_ENCRYPT ."'";

    $query= $db->query($consulta);

    if ($db -> error) {
        echo "la consulta dio un error" . $db->error;
    }

    $resultado = $query-> num_rows;

    echo $resultado;

    if ($resultado == 1) {
       header("Location: ../../Principal/index.html");
    }
    


?>