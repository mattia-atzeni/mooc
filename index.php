<?php

include_once 'php/controller/BaseController.php';
include_once 'php/controller/ProviderController.php';
include_once 'php/controller/LearnerController.php';
include_once 'php/model/User.php';

FrontController::dispatch();

// Punto unico di accesso all'applicazione
class FrontController {
    public static function dispatch() {
        session_start(); // inizializza la sessione
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : "login";
        
        // controlla la pagina richiesta e crea il controller appropriato
        switch ($page) {
            case "login":
                $controller = new BaseController();
                $controller->handleInput();
                break;
            case "info":
                $controller = new BaseController();
                $controller->showInfoPage();
                break;
            case "learner":
                $controller = new LearnerController();
                if (isset($_SESSION[BaseController::Role]) &&
                    $_SESSION[BaseController::Role] != User::Learner) {
                    self::write403();
                }
                $controller->handleInput();
                break;
            case "provider":
                $controller = new ProviderController();
                if (isset($_SESSION[BaseController::Role]) &&
                    $_SESSION[BaseController::Role] != User::Provider) {
                    self::write403();
                }
                $controller->handleInput();
                break;
            default:
                self::write404();
        }
    }
    
    public static function write404() {      
        header('HTTP/1.0 404 Not Found');
        $title = "File non trovato";
        $message = "La pagina richiesta non &egrave; disponibile";
        require 'php/error.php';
        exit();
    }
    
    public static function write403() {
        header('HTTP/1.0 403 Forbidden');
        $title = "Accesso negato";
        $message = "Non disponi dei diritti necessari per visualizzare la pagina";
        require 'php/error.php';
        exit();
    }
}
