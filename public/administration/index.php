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
use App\BackEnd\Utils\Utils;
use App\FrontEnd\Page;
use App\BackEnd\Utils\Notification;

try {
    
    if (!someoneIsConnected()) { Utils::header(ADMIN_URL . "/connexion"); }
    $url = isset($_GET['url']) ? $_GET['url'] : "";
    $router = new Router($url);
    $route = $router->adminRouter();
    $page = new Page($route["meta_title"], $route["content"]);
    echo $page->adminPage();

} catch(Error|TypeError|Exception|PDOException $e) {
    $exception = 'Erreur : ' . $e->getMessage() . ', Fichier : ' . $e->getFile() . ', Ligne : ' . $e->getLine();
    $notification = new Notification();
    echo $notification->exception($exception);
}
