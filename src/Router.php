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
    private $urlAsArray;
    private $categorie;

    /**
     * Constructeur du routeur, prend en paramètre l'url.
     * 
     * @param string $url 
     * 
     * @return void
     */
    public function __construct(string $url)
    {
        $this->url = $url;
        $this->urlAsArray = explode('/', $this->url);
        $this->categorie = !empty($this->urlAsArray[0]) ? $this->urlAsArray[0] : null;
    }

    /**
     * Permet de modifier l'url passé en paramètre.
     * 
     * @param string $url
     * 
     * @return void
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * Vérifie la concordance de l'url et la variable passée en paramètre. Deux formats
     * d'url paramètres sont passables. Le premier format est une chaîne de caractères
     * et le second format est un tableau de variable si l'url doit varier.
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

            $urlOffsets = count(self::getUrlAsArray());
            $routeOffsets = count($route);

            if ($urlOffsets === $routeOffsets) {
                $counter = 0;
                for ($i = 0; $i <= $routeOffsets - 1; $i++) {
                    if (is_string($route[$i])) {
                        if (self::getUrlAsArray()[$i] === $route[$i]) $counter++;
                    } elseif (is_array($route[$i])) {
                        if (in_array(self::getUrlAsArray()[$i], $route[$i])) $counter++;
                    }
                }

                if ($counter === $routeOffsets) return true;
                else return false;

            } else {
                return false;
            }
        }
    }

    /**
     * Retourne l'url de la page courante grâce au fichier .htacces qui
     * permet de ramener toutes les urls vers l'index du dossier où le
     * fichier il se trouve en générant une variable globale $_GET["url"].
     * 
     * @return string
     */
    public static function getUrl()
    {
        $url = isset($_GET["url"]) ? $_GET["url"] : "";

        if ($url !== "") {
            $urlLength = strlen($url);
            $urlOtherChars = substr($url, 0, $urlLength - 1);
            $urlLastChar = substr($url, $urlLength - 1, 1);
            
            if ($urlLastChar === "/") {
                $url = $urlOtherChars;
            }    
        }

        return $url;
    }

    /**
     * Permet de découper l'url en plusieurs parties. Index 0 : catégorie
     * 
     * @return array
     */
    public static function getUrlAsArray()
    {
        $urlAsArray = explode("/", self::getUrl());
        $lastUrlAsArrayKey = array_key_last($urlAsArray);

        if (empty($urlAsArray[$lastUrlAsArrayKey])) {
            array_pop($urlAsArray);
        }

        return $urlAsArray;
    }

    /**
     * Retourne les variables passées par url.
     * 
     * @return array
     */
    public static function getUrlVars()
    {
        
    }

}