<?php
    $db= new mysqli("localhost", "root", "2002", "pw2prueba");

    if($db -> connect_error){
        echo "Ocurrio un error" . $db->connect_error;
    }

?>