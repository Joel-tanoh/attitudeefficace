<?php

/**
 * Index de la partie publique du site web
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */

session_start();

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'global' . DIRECTORY_SEPARATOR . 'config.php';
require_once ROOT_PATH . 'src' . DIRECTORY_SEPARATOR . 'constants.php';
require_once ROOT_PATH . 'src' . DIRECTORY_SEPARATOR . 'functions.php';
require_once ROOT_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use App\Router;
use App\Controller;
use App\View\Notification;
use App\BackEnd\Models\Users\Visitor;

try {
    $route = new Router(Router::getUrl());
    $controller = new Controller($route->getUrl());

    Visitor::manageVisitorPresence();

    if ($route->match("")) $controller->publicAccueilPage();
    else $controller->publicError404();
    
} catch(Error|TypeError|Exception|PDOException $e) {
    $exception = 'Erreur : ' . $e->getMessage() . ', Fichier : ' . $e->getFile() . ', Ligne : ' . $e->getLine();
    echo Notification::exception($exception);
}