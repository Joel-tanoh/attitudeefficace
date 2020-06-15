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
require_once ROOT_PATH . 'global' . DIRECTORY_SEPARATOR . 'constants.php';
require_once ROOT_PATH . 'global' . DIRECTORY_SEPARATOR . 'functions.php';
require_once ROOT_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use App\BackEnd\Models\Users\Administrateur;
use App\View\Notification;
use App\BackEnd\Utilities\Utility;
use App\View\View;
use App\View\PageBuilder;

try {
    $notification = new Notification();
    
    if (!someoneIsConnected()) {
        $metaTitle = APP_NAME . ' - Connexion';

        $adminLogin = '';
        $adminPassword = '';
        $error = null;

        if (isset($_POST['connexion'])) {

            $loginputedInform   = $_POST["admin_login"];
            $adminLogin         = htmlentities($_POST["admin_login"]);
            $adminPassword      = $_POST["admin_password"];

            if (empty($adminLogin) || empty($adminPassword)) {
                $error = $notification->inputsEmpty();
            } else {
                $adminLogin = mb_strtolower($adminLogin);

                if (Administrateur::loginIsset($adminLogin)) {

                    $admin = Administrateur::getByLogin($adminLogin);
                    
                    if ($admin->isAuthentified($adminLogin, $adminPassword)) {

                        $admin->setSession("admin_login");

                        if ($activate_cookie == "oui") {
                            $admin->setCookie("admin_login", $admin->getLogin());
                        }

                        Utility::header(ADMIN_URL);
                    } else {
                        $error = $notification->errorLogin();
                    }

                } else {
                    $error = $notification->errorLogin();
                }
            }

            $adminLogin = $loginputedInform;
        }
        
        $page = new PageBuilder($metaTitle, View::connexionFormView($adminLogin, $adminPassword, $error));
        echo $page->connexionPage();

    } else {
        Utility::header(ADMIN_URL);
    }

} catch(Error|TypeError|Exception|PDOException $e) {
    $exception = 'Erreur : '    . $e->getMessage() . ', Fichier : ' . $e->getFile() . ' Ligne : '    . $e->getLine();
    echo $notification->exception($exception);
}
