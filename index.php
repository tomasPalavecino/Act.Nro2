<?php
session_start();
include_once("config/Configuration.php");

$module = isset($_GET["module"]) ? $_GET["module"] : "Login" ;//la pagina por defecto es 
$action = isset($_GET["action"]) ? $_GET["action"] : "show" ;

$configuration = new Configuration(); //crea el objeto configuracion
$router = $configuration->createRouter( "createLoginController", "show");

$router->executeActionFromModule($module,$action);