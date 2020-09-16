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
     * @param \App\BackEnd\Models\Users\Administrator $administrator
     * 
     * @return bool
     */
    public static function setAdministratorSessionVar(\App\BackEnd\Models\Users\Administrator $administrator)
    {
        $_SESSION["attitude_efficace_administrator_login"] = ucfirst($administrator->getLogin());
    }

    /**
     * Permet de vérifier si la session de l'administrateur est activée.
     * 
     * @return bool
     */
    public static function administratorSessionIsActive()
    {
        return !empty($_SESSION["attitude_efficace_administrator_login"]);
    }

    /**
     * Initie la variable de session.
     * 
     * @return string
     */
    public static function getAdministratorSessionVar()
    {
        return self::administratorSessionIsActive() ? $_SESSION["attitude_efficace_administrator_login"] : null;
    }

    /**
     * Permet de vérifier que la session du visiteur dest active.
     * 
     * @return bool
     */
    public static function visitorSessionIsActive()
    {
        return !empty($_SESSION["attitude_efficace_visitor_session_id"]);
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