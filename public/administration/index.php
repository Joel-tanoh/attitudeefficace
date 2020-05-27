<?php
/**
 * Grace au fichiers .htaccess,
 * toutes les urls sont transmises comme étant une variable du tableau GET[],
 * En explodant ce tableau on obtient des variables indexées
 * qui permettent de router les urls.
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

require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once ROOT_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use App\Router;
use App\Controller;
use App\BackEnd\Utils\Utils;
use App\BackEnd\Models\Model;
use App\FrontEnd\View\Notification;

try {
    
    if (!someoneIsConnected()) { Utils::header(ADMIN_URL . "/connexion"); }
    $router = new Router(Router::getUrl());
    $controller = new Controller(Router::getUrlAsArray());

    if ($router->match("")) $controller->dashboard();
    elseif ($router->match("administrateurs")) $controller->listAdminUsersAccounts();
    elseif ($router->match("motivation-plus")) $controller->listMotivationPlusVideo();
    elseif ($router->match("motivation-plus/create")) $controller->createMotivationPlusVideo();
    elseif ($router->match("motivation-plus/delete")) $controller->deleteMotivationPlusVideo();
    elseif ($router->match( [Model::getAllCategories()] ) ) $controller->listCategorieItems();
    elseif ($router->match( [Model::getAllCategories(), "create"] ) ) $controller->createItem();
    elseif ($router->match( [Model::getAllCategories(), "delete"] ) ) $controller->deleteManyItems();
    elseif ($router->match( [Model::getAllCategories(), Model::getAllSlugs()] ) ) $controller->readItem();
    elseif ($router->match( [Model::getAllCategories(), Model::getAllSlugs(), "edit"] ) ) $controller->editItem();
    elseif ($router->match( [Model::getAllCategories(), Model::getAllSlugs(), "delete"] ) ) $controller->deleteItem();
    
    else $controller->adminError404();

} catch(Error|TypeError|Exception|PDOException $e) {
    $exception = 'Erreur : ' . $e->getMessage() . ', Fichier : ' . $e->getFile() . ', Ligne : ' . $e->getLine();
    $notification = new Notification();
    echo $notification->exception($exception);
}