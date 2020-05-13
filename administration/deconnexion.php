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

session_start();
session_unset();
session_destroy();
setcookie('admin_login', '', 0);
header("location: " . ADMIN_URL);
exit();