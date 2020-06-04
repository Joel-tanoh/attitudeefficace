<?php

/**
 * Description
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  GIT: Joel_tanoh
 * @link     Link
 */

namespace App;

use App\BackEnd\Models\Model;
use App\Controller;

/**
 * Routeur de l'application.
 *  
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class Router
{
    private $url;
    private $url_as_array;

    /**
     * Constructeur du routeur, prend en paramètre l'url.
     * 
     * @param $url 
     * 
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
        $this->url_as_array = explode('/', $url);
    }

    /**
     * Retourne l'url de la page courante grâce au fichier .htacces qui
     * permet de ramener toutes les urls vers l'index du dossier où le
     * fichier il se trouve en générant une variable global $_GET["url"].
     * 
     * @return string
     */
    public static function getUrl()
    {
        return isset($_GET["url"]) ? $_GET["url"] : "";
    }

    /**
     * Permet de modifier l'url passé en paramètre.
     * 
     * @param string $url
     * 
     * @return void
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Permet de découper l'url en plusieurs parties. Index 0 : catégorie
     * 
     * @return array
     */
    public static function getUrlAsArray()
    {
        $url_as_array = explode("/", self::getUrl());
        $last_url_as_array_key = array_key_last($url_as_array);
        if (empty($url_as_array[$last_url_as_array_key])) {
            array_pop($url_as_array);
        }

        return $url_as_array;
    }

    /**
     * Retourne les variables passées par url.
     * 
     * @return array
     */
    public static function getUrlVars()
    {
        
    }

    /**
     * Vérifie la concordance de l'url et la variable passée en paramètre.
     * 
     * @param mixed $route
     * 
     * @return bool
     */
    public function match($route)
    {
        if (is_string($route)) {
            return self::getUrl() === $route;
        } elseif (is_array($route)) {

            $url_offsets = count(self::getUrlAsArray());
            $route_offsets = count($route);

            if ($url_offsets === $route_offsets) {
                $counter = 0;
                for ($i = 0; $i <= $route_offsets - 1; $i++) {
                    if (is_string($route[$i])) {
                        if (self::getUrlAsArray()[$i] === $route[$i]) $counter++;
                    } elseif (is_array($route[$i])) {
                        if (in_array(self::getUrlAsArray()[$i], $route[$i])) $counter++;
                    }
                }

                if ($counter === $route_offsets) return true;
                else return false;

            } else {
                return false;
            }
        }
    }

}