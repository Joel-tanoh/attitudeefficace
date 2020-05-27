<?php

/**
 * Index de la partie publique du site web
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */

session_start();

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once ROOT_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use App\Router;
use App\Controller;
use App\FrontEnd\View\Notification;

try {
    $router = new Router(Router::getUrl());
    $controller = new Controller($router->getUrl());

    // Accueil
    if ($router->match("")) $controller->publicAccueilPage();

    // Error 404
    else $controller->publicError404();
    
} catch(Error|TypeError|Exception|PDOException $e) {
    $exception = 'Erreur : ' . $e->getMessage() . ', Fichier : ' . $e->getFile() . ', Ligne : ' . $e->getLine();
    $notification = new Notification;
    echo $notification->exception($exception);
}