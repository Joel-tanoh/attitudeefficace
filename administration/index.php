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

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once ROOT_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use App\Router;
use App\BackEnd\Utils\Utils;
use App\FrontEnd\Page;

try {
    if (!someoneIsConnected()) { Utils::header(ADMIN_URL . "/connexion"); }
    $url = isset($_GET['url']) ? explode('/', $_GET['url']) : "";
    $router = new Router($url);
    $page = $router->adminRouter();
    $page = new Page($page["title"], $page["content"]);
    echo $page->adminPage();

} catch(Error|TypeError|Exception|PDOException $e) {
    $exception = 'Erreur : ' . $e->getMessage()
        . ', Fichier : ' . $e->getFile()
        . ', Ligne : ' . $e->getLine();
    require ROOT_PATH . 'notifier-exception.php';
}
