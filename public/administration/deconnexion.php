<?php
/**
 * Fichier de déconnexion, détruit les variables de session, vide les cookies.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <tanohbasspatrick@gmail.com>
 * @license  url.com license
 * @link     Link
 */

require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'global' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'global' . DIRECTORY_SEPARATOR . 'functions.php';
require_once ROOT_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

session_start();
session_unset();
session_destroy();
setcookie('admin_login', '', 0);
header("location: " . ADMIN_URL);
exit();