<?php
class MyDataBase{

    private $conection;

    public function __construct($servidor, $nombreUsuario, $contrasenia, $nombrebd){//el constructor me establece la conexion
        $this->conection = mysqli_connect($servidor, $nombreUsuario, $contrasenia, $nombrebd);

        if (!$this->conection){
            die("Connection failed: ".msyqli_connect_error());
        }

    }
    public function agregar($insert){
        return  mysqli_query($this->conection, $insert, true);
    }

    public function query($consulta){//este metodo va hacer las consultas y va a devolver el valor.
        $databaseResult = mysqli_query($this->conection, $consulta);

        if (mysqli_num_rows($databaseResult) <= 0)
            return [];

        return mysqli_fetch_ASSOC($databaseResult);
    }
    }

    






?>