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

require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once ROOT_PATH . 'src' . DIRECTORY_SEPARATOR . 'constants.php';
require_once ROOT_PATH . 'src' . DIRECTORY_SEPARATOR . 'functions.php';
require_once ROOT_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use App\BackEnd\Cookie;
use App\BackEnd\Models\Users\Administrator;
use App\BackEnd\Session;
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

            $loginPutedInform   = $_POST["admin_login"];
            $adminLogin         = mb_strtolower(htmlentities($_POST["admin_login"]));
            $adminPassword      = $_POST["admin_password"];
            $activateCookie     = $_POST["activate_cookie"] ?? null;

            if (empty($adminLogin) || empty($adminPassword)) {
                $error = $notification->inputsEmpty();
            } else {

                $admin = Administrator::getByLogin($adminLogin);

                if ($admin) {

                    if ($admin->isAuthentified($adminLogin, $adminPassword)) {

                        Session::setAdministratorSessionVar($admin);

                        if ($activateCookie) {
                            Cookie::setAdministratorCookieVar($admin);
                        }
                              
                        Utility::header(ADMIN_URL);

                    } else {
                        $error = $notification->errorAuthentification();
                    }

                } else {
                    $error = $notification->errorAuthentification();
                }
            }

            $adminLogin = $loginPutedInform;
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
