<?php

namespace App\BackEnd;

/**
 * Classe gestionnaire des variables relatives aux cookie.
 */
class Cookie extends Authentification
{ 
    /**
     * Initie la variable de session.
     * 
     * @param \App\BackEnd\Models\Users\Administrateur $administrator
     * 
     * @return bool
     */
    public static function setAdministratorCookieVar(\App\BackEnd\Models\Users\Administrateur $administrator)
    {
        setcookie("attitude_efficace_administrator_login", ucfirst($administrator->getLogin()), time()+(30*24*3600));
    }

    /**
     * Retourne la variable cookie pour la partie administration.
     * 
     * @return string
     */
    public static function getAdministratorCookieVar()
    {
        return $_COOKIE["attitude_efficace_administrator_login"];
    }

}