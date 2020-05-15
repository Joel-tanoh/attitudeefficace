<?php

/**
 * Index de la partie publique du site web
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */

session_start();

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once ROOT_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use App\BackEnd\Utils\Notification;
use App\Router;
use App\FrontEnd\Page;

try {
    $url = isset($_GET['url']) ? explode('/', $_GET['url']) : "";
    $router = new Router($url);
    $page = $router->publicRouter();
    $view = new Page($page["title"], $page["content"]);
    echo $view->publicPage();

} catch(Error|TypeError|Exception|PDOException $e) {
    $exception = 'Erreur : ' . $e->getMessage()
        . ', Fichier : ' . $e->getFile()
        . ', Ligne : ' . $e->getLine();
    
    $notification = new Notification;
    echo $notification->exception($exception);
}