<?php

/**
 * Fichier contenant toutes les fonctions globales du système.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <tanohbassapatrick@gmail.com>
 * @license  url.com license_name
 * @version  GIT: Joel_tanoh
 * @link     Link
 */

/**
 * Vérifie si l'utilisateur est connecté ou s'est déjà connecté.
 * 
 * @return bool True si le cookie['admin_login'] n'est pas vide ou si la
 *              session['admin_login'] n'est pas vide.
 */
function someoneIsConnected()
{
    return !empty($_COOKIE['attitude_efficace_administrator_login']) || !empty($_SESSION['attitude_efficace_administrator_login']) ? true : false;
}

/**
 * Permet de dumper une variable.
 * 
 * @param mixed $var 
 * 
 * @return string
 */
function dump($var)
{
    echo '<pre class="dumper">';
    var_dump($var);
    echo '</pre>';
}