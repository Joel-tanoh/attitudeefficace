<?php

/**
 * Fichier de classe.
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */

namespace App\BackEnd\APIs;

/**
 * Permet de gérer l'url et toutes les méthodes pour travailler 
 * sur l'url
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */
class Url
{
    /**
     * Retourne l'url de la page courante grâce au fichier .htacces qui
     * permet de ramener toutes les urls vers l'index du dossier où le
     * fichier il se trouve en générant une variable global $_GET["url"] et
     * une variable serveur $_SERVER["REQUESY_URI"].
     * 
     * @return string
     */
    static function getUrl()
    {
        if ($_SERVER["QUERY_STRING"] == "") {
            return "";
        }
        return substr($_SERVER["QUERY_STRING"], 4);
    }

    /**
     * Permet de découper l'url en plusieurs parties.
     * 
     * @return array
     */
    static function slicedUrl()
    {
        return explode("/", substr($_SERVER["REQUEST_URI"], 1));
    }

}