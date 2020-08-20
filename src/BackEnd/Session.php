<?php

namespace App\BackEnd;

/**
 * Fichier de classe gestionnaire des variables de session.
 */
class Session extends Authentification
{
    /**
     * Initie la variable de session.
     * 
     * @param \App\BackEnd\Models\Users\Administrateur $administrator
     * 
     * @return bool
     */
    public static function setAdministratorSessionVar(\App\BackEnd\Models\Users\Administrateur $administrator)
    {
        $_SESSION["attitude_efficace_administrator_login"] = ucfirst($administrator->getLogin());
    }

    /**
     * Initie la variable de session.
     * 
     * @return string
     */
    public static function getAdministratorSessionVar()
    {
        if (!empty($_SESSION["attitude_efficace_administrator_login"])) {
            return $_SESSION["attitude_efficace_administrator_login"];
        }
    }

    /**
     * Permet de vérifier que la session du visiteur dest active.
     * 
     * @return bool
     */
    public static function visitorSessionIsActive()
    {
        return isset($_SESSION["attitude_efficace_visitor_session_id"]);
    }

    /**
     * Permet d'activer la session du visiteur.
     * 
     * @param string $sessionId Id de session du visiteur
     * 
     * @return void
     */
    public static function setVisitorSessionId($sessionId)
    {
        $_SESSION["attitude_efficace_visitor_session_id"] = $sessionId;
    }

    /**
     * Retourne l'id de session du visiteur.
     * 
     * @return string
     */
    public static function getVisitorSessionId()
    {
        return self::visitorSessionIsActive() ? $_SESSION["attitude_efficace_visitor_session_id"] : null;
    }
}