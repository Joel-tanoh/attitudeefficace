<?php

/**
 * Fichier de connexion.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel Tanoh <joel.developpeur@gmail.com>
 * @license  url.com license
 * @link     Link
 */
session_start();

require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'global' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'global' . DIRECTORY_SEPARATOR . 'functions.php';
require_once ROOT_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use App\BackEnd\Models\Personnes\Administrateur;
use App\View\Notification;
use App\BackEnd\Utils\Utils;
use App\View\View;
use App\View\Page;

try {
    $notification = new Notification();
    
    if (!someoneIsConnected()) {
        $meta_title = APP_NAME . ' - Connexion';
        $admin_login = '';
        $admin_password = '';
        $error = null;

        if (isset($_POST['connexion'])) {
            extract($_POST);
            $input_admin_login = $admin_login;
            $admin_login = htmlentities($admin_login);
            $admin_password = htmlentities($admin_password);

            if (empty($admin_login) || empty($admin_password)) {
                $error = $notification->inputsEmpty();
            } else {
                $admin_login = mb_strtolower($admin_login);
                if (Administrateur::loginIsset($admin_login)) {
                    $admin = Administrateur::getByLogin($admin_login);
                    if ($admin->isAuthentified($admin_login, $admin_password)) {
                        $admin->setSession("admin_login");
                        if ($activate_cookie == "oui") {
                            $admin->setCookie("admin_login", $admin->get("login"));
                        }
                        Utils::header(ADMIN_URL);
                    } else {
                        $error = $notification->errorLogin();
                    }
                } else {
                    $error = $notification->errorLogin();
                }
            }

            $admin_login = $input_admin_login;
        }
        
        $view = new View();
        $page = new Page($meta_title, $view->connexionFormView($admin_login, $admin_password, $error));
        echo $page->connexionPage();

    } else {
        Utils::header(ADMIN_URL);
    }

} catch(Error|TypeError|Exception|PDOException $e) {
    $exception = 'Erreur : '    . $e->getMessage() . ', Fichier : ' . $e->getFile() . ' Ligne : '    . $e->getLine();
    echo $notification->exception($exception);
}
