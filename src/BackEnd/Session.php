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

}