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
     * @param \App\BackEnd\Models\Users\Administrator $administrator
     * 
     * @return bool
     */
    public static function setAdministratorCookieVar($administrator)
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

    /**
     * Initialise la variable cookie pour le visiteur et la depose sur son équipement.
     * 
     * @param string $sessionId
     */
    public static function setVisitorSessionIdInCookie(string $sessionId)
    {
        setcookie("attitude_efficace_visitor", $sessionId, time()+(30*24*3600));
    }

    /**
     * Retourne le contenu de la variable cookie de l'id de session du visiteur.
     * 
     * @return string
     */
    public static function getVisitorSessionIdFromCookie()
    {
        if (isset($_COOKIE["attitude_efficace_visitor"])) {
            return $_COOKIE["attitude_efficace_visitor"];
        }
    }

    /**
     * Vérifie si l'id de session du visiteur existe.
     * 
     * @return bool
     */
    public static function visitorCookieIsset()
    {
        return null !== self::getVisitorSessionIdFromCookie();
    }

    /**
     * Permet de mettre le visiteur en session afin de pouvoir y accéder
     * sur toutes les pages, nom de la variable : attitude_efficace_visitor.
     * 
     * @param \App\BackEnd\Models\Users\Visitor $visitor
     * 
     * @return void
     */
    public static function setVisitorInCookie($visitor)
    {
        setcookie("attitude_efficace_visitor", $visitor, time()+(30*24*3600));
    }

}