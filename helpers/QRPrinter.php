<?php
require 'public/phpqrcode/qrlib.php';

class QRPrinter{
    private $tamanio; // recomiendo 5 o 10
    private $calidad; //CALIDAD de la imagen - M ya esta bien (mejores son Q y H)
    private $contenido;

    function __construct($content) {
        $this->contenido = $content;
        $this->tamanio=3;
        $this->calidad= "H";
    }

   

  

    function set_tamanio($tamanio){
        $this->tamanio = $tamanio;
    }

    function set_calidad($calidad){
        $this->calidad = $calidad;
    }



    function imprimirQr(){
        return QRcode::png($this->contenido);
    }

    function guardarQr($contenido , $filename){
        return QRcode::png($contenido,$filename, $this->calidad, $this->tamanio);
    }







}