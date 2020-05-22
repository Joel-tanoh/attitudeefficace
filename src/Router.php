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
    private $url_array;

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
        $this->url_array = explode('/', $url);
    }

    /**
     * Routeur de l'administration du blog, un tableau contenant le titre et le contenu de
     * la page.
     * 
     * @return array
     **/
    public function adminRouter()
    {
        $controller = new Controller($this->url_array);

        if ($this->match("")) return $controller->dashboard();

        elseif ($this->match("administrateurs")) return $controller->listAdminUsersAccounts();

        elseif ($this->match("motivation-plus")) return $controller->listMotivationPlusVideo();

        elseif ($this->match("motivation-plus/create")) return $controller->createMotivationPlusVideo();

        elseif ($this->match("motivation-plus/delete")) return $controller->deleteMotivationPlusVideo();

        // categorie
        elseif ( $this->match( [Model::getAllCategories()] ) ) return $controller->listCategorieItems();

        // categorie/create
        elseif ( $this->match( [Model::getAllCategories(), "create"] ) ) return $controller->createItem();
        
        // categorie/delete
        elseif ( $this->match( [Model::getAllCategories(), "delete"] ) ) return $controller->deleteManyItems();
        
        // categorie/slug
        elseif ( $this->match( [Model::getAllCategories(), Model::getAllSlugs()] ) ) return $controller->readItem();

        // categorie/slug/edit
        elseif ( $this->match( [Model::getAllCategories(), Model::getAllSlugs(), "edit"] ) )
            return $controller->editItem();
        
        // categorie/slug/delete
        elseif ( $this->match( [Model::getAllCategories(), Model::getAllSlugs(), "delete"] ) )
            return $controller->deleteItem();

        // Page 404
        else return $controller->adminError404();
    }

    /**
     * Routeur de la partie publique.
     * 
     * @return array
     **/
    public function publicRouter()
    {
        $controller = new Controller($this->url);

        // Accueil
        if ($this->match(""))
            return $controller->publicAccueilPage();

        // Error 404
        else return $controller->publicError404();
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
            return self::getUri() == $route;
        } elseif (is_array($route)) {
            $url_offsets = count(self::urlAsArray());
            $route_offsets = count($route);

            if ($url_offsets === $route_offsets) {
                $counter = 0;
                for ($i = 0; $i <= $route_offsets - 1; $i++) {
                    if (is_string($route[$i])) {
                        if (self::urlAsArray()[$i] === $route[$i]) $counter++;
                    } elseif (is_array($route[$i])) {
                        if (in_array(self::urlAsArray()[$i], $route[$i])) $counter++;
                    }
                }

                if ($counter === $route_offsets) return true;
                else return false;

            } else {
                return false;
            }
        }
    }

    /**
     * Retourne l'url de la page courante grâce au fichier .htacces qui
     * permet de ramener toutes les urls vers l'index du dossier où le
     * fichier il se trouve en générant une variable global $_GET["url"].
     * 
     * @return string
     */
    public static function getUri()
    {
        return isset($_GET["url"]) ? $_GET["url"] : "";
    }

    /**
     * Permet de découper l'url en plusieurs parties.
     * 
     * @return array
     */
    public static function urlAsArray()
    {
        $url_as_array = explode("/", self::getUri());
        if ( empty( $url_as_array[ array_key_last($url_as_array) ]) ) {
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

}