<?php


class PresentacionesController
{
    private $presentacionesModel;
    private $printer;

    public function __construct($presentacionesModel, $printer){
        $this->presentacionesModel = $presentacionesModel;
        $this->printer = $printer;
    }

    public function show(){
        $presentaciones = $this->presentacionesModel->getPresentaciones();

        $data["presentaciones"] = $presentaciones;
        echo $this->printer->render( "view/presentacionesView.html" , $data);
    }
}