<?php

/**
 * Fichier de connexion.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel Tanoh <tanohbassapatrick@gmail.com>
 * @license  url.com license
 * @link     Link
 */
session_start();
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once ROOT_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use App\BackEnd\Data\Personnes\Administrateur;
use App\BackEnd\Utils\Notification;
use App\BackEnd\Utils\Utils;
use App\FrontEnd\Page;

try {
    ob_start();
    if (!someoneIsConnected()) {
        $notification = new Notification();
        $page_title = ' Connexion';
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
                        if ($_POST["activate_cookie"] == "oui") {
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
        $page_content = ob_get_clean();
        $page = new Page($page_title, $page_content);
        echo $page->connexionPage($admin_login, $admin_password, $error);
    } else {
        Utils::header();
    }
} catch(Error|TypeError|Exception|PDOException $e) {
    $exception = 'Erreur : '    . $e->getMessage()
        . ', Fichier : ' . $e->getFile()
        . ' Ligne : '    . $e->getLine();
}

require ROOT_PATH . 'notifier-exception.php';