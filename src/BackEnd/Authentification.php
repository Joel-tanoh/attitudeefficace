<?php

namespace App\BackEnd;

use App\BackEnd\Models\Users\Administrateur;
use App\BackEnd\Models\Users\ServiceProvider;
use App\BackEnd\Models\Users\Suscriber;
use App\BackEnd\Utilities\Validator;

/**
 * Fichier de classe gestionnaire de l'authentification des utilisateurs.
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */
class Authentification
{
    /**
     * Retourne le tableau contenant les valeurs de session.
     * 
     * @param string $sessionKey
     * 
     * @return array
     */
    public static function getSession(string $sessionKey = null)
    {
        if (null !== $sessionKey) {
            return $_SESSION[$sessionKey];
        }

        return $_SESSION;
    }

    /**
     * Retourne le tableau des valeurs de coockie.
     * 
     * @param string $cookieKey 
     * 
     * @return array
     */
    public function getCookies(string $cookieKey = null)
    {
        if (null !== $cookieKey) {
            return $_SESSION[$cookieKey];
        }

        return $_COOKIE;
    }
    
    /**
     * Initalise les variables de sessions en mettant le login de l'administrateur.
     * 
     * @param string $sessionKey   La clé de la session.
     * @param mixed  $sessionValue La valeur de la session
     * 
     * @return void
     */
    public static function setSession($sessionKey, $sessionValue)
    {
        $_SESSION[$sessionKey] = ucfirst($sessionValue);
    }

    /**
     * Initialise les variables de cookie.
     * 
     * @param mixed  $cookieKey La clé identifiant le cookie.
     * @param mixed  $value     La valeur
     * @param string $domain 
     * 
     * @return void
     */
    public static function setCookie($cookieKey, $value, $domain = null)
    {
        setcookie(
            $cookieKey,
            ucfirst($value),
            time()+(30*24*3600),
            null,
            $domain,
            false,
            true
        );
    }

    /**
     * Gère l'authentification des suscribers.
     * 
     * @param string $emailAddress
     * @param string $password
     * @param string $userCategorie
     * 
     * @return bool
     */
    public static function authentificateUser($emailAddress, $password, $userCategorie)
    {
        if (null === $emailAddress) {
            return false;
        } else {

            $validator = new Validator();

            if ($validator->validateEmail($emailAddress)) {

                if ($userCategorie === "suscribers")
                    $user = Suscriber::getByEmail($emailAddress);

                elseif ($userCategorie === "administrateurs")
                    $user = Administrateur::getByEmail($emailAddress);

                if ($user) {

                    if (password_verify($password, $user->getPassword())) {

                        if ($user->getCategorie() === "administrateurs") {

                        } elseif ($user->getCategorie() === "suscribers") {

                        }
                        
                        return true;
                        
                    } else {
                        return false;
                    }

                } else {
                    return false;
                }

            } else {
                return false;
            }
        }
    }

}