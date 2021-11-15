<?php
require 'public/phpqrcode/qrlib.php';

class QRPrinter{
    private $filename; //donde lo vas a guardar
    private $tamanio; // recomiendo 5 o 10
    private $calidad; //CALIDAD de la imagen - M ya esta bien (mejores son Q y H)
    private $frameSize; //contorno, algo asi como un margin
    private $contenido;

    function __construct($content) {
        $this->contenido = $content;
        $this->tamanio=5;
        $this->calidad= "M";
    }

    function set_Filename($filename){
        $this->filename = $filename;
    }

    function set_frameSize($frameSize){
        $this->frameSize = $frameSize;
    }

    function set_tamanio($tamanio){
        $this->tamanio = $tamanio;
    }

    function set_calidad($calidad){
        $this->calidad = $calidad;
    }

    function set_Contenido($contenido){
        $this->contenido = $contenido;
    }

    function get_Contenido(){
        return $this->contenido;
    }

    function imprimirQr(){
        return QRcode::png($this->contenido);
    }

    function guardarQr(){
        return QRcode::png($this->contenido, $this->filename);
    }







}