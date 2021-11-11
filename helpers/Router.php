<?php

class Router{
    private $configuration;
    private $defaultController ;
    private $defaultAction;
//este router es el que me arma los create del configuracion.
    public function __construct($configuration, $defaultController , $defaultAction ){
        $this->configuration = $configuration;//recibo la configuracion
        $this->defaultController = $defaultController;//el controlador por defecto
        $this->defaultAction = $defaultAction;
    }

    public function executeActionFromModule($module, $action){//ejecuto la accion de un modulo
        $controller = $this->getControllerFrom($module);
        $this->executeMethodFromController($controller,$action);
    }

    private function getControllerFrom($module){
        $controllerName = "create" . ucfirst($module) . "Controller"; //ACA
        $validController = method_exists($this->configuration, $controllerName) ? $controllerName : $this->defaultController;
    //se fija si realmente ese controlador existex
        return call_user_func(array($this->configuration, $validController));
    }

    private function executeMethodFromController($controller, $method){
        $validMethod = method_exists($controller, $method) ?$method : $this->defaultAction;
        call_user_func(array($controller, $validMethod));
    }
}