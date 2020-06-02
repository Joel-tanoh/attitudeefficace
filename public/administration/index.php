<?php
/**
 * Grace au fichiers .htaccess,
 * toutes les urls sont transmises comme étant une variable du tableau GET[],
 * En explodant ce tableau on obtient des variables indexées
 * qui permettent de route les urls.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <tanohbassaptrick@gmail.com>
 * @license  url.com License
 * @link     Link
 */

session_start();

require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'global' . DIRECTORY_SEPARATOR . 'config.php';
require_once ROOT_PATH . 'global' . DIRECTORY_SEPARATOR . 'functions.php';
require_once ROOT_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use App\Router;
use App\Controller;
use App\BackEnd\Utils\Utils;
use App\BackEnd\Models\Model;
use App\View\Notification;

try {
    
    if (!someoneIsConnected()) { Utils::header(ADMIN_URL . "/connexion"); }

    $route = new Router(Router::getUrl());
    $controller = new Controller(Router::getUrlAsArray());

    if ($route->match("")) $controller->dashboard();
    elseif ($route->match("administrateurs")) $controller->listAdminUsersAccounts();
    elseif ($route->match("motivation-plus")) $controller->listMotivationPlusVideo();
    elseif ($route->match("motivation-plus/create")) $controller->createMotivationPlusVideo();
    elseif ($route->match("motivation-plus/delete")) $controller->deleteMotivationPlusVideo();
    elseif ($route->match( [Model::getAllCategories()] ) ) $controller->listCategorieItems();
    elseif ($route->match( [Model::getAllCategories(), "create"] ) ) $controller->createItem();
    elseif ($route->match( [Model::getAllCategories(), "delete"] ) ) $controller->deleteManyItems();
    elseif ($route->match( [Model::getAllCategories(), Model::getAllSlugs()] ) ) $controller->readItem();
    elseif ($route->match( [Model::getAllCategories(), Model::getAllSlugs(), "edit"] ) ) $controller->editItem();
    elseif ($route->match( [Model::getAllCategories(), Model::getAllSlugs(), "delete"] ) ) $controller->deleteItem();
    
    else $controller->adminError404();

} catch(Error|TypeError|Exception|PDOException $e) {
    $exception = 'Erreur : ' . $e->getMessage() . ', Fichier : ' . $e->getFile() . ', Ligne : ' . $e->getLine();
    $notification = new Notification();
    echo $notification->exception($exception);
}