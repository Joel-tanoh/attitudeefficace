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

    /**
     * Constructeur du routeur, prend en paramètre l'url.
     * 
     * @param $url 
     * 
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url === "" ? "" : explode('/', $url);
    }

    /**
     * Routeur de l'administration du blog, un tableau contenant le titre et le contenu de
     * la page.
     * 
     * @return array
     **/
    public function adminRouter()
    {
        $controller = new Controller($this->url);

        if ($this->match(""))
            $route = $controller->dashboard();

        elseif ($this->match("administrateurs"))
            $route = $controller->listAdminUsersAccounts();

        elseif ($this->match("motivation-plus"))
            $route = $controller->listMotivationPlusVideo();

        elseif ($this->match("motivation-plus/create"))
            $route = $controller->createMotivationPlusVideo();

        elseif ($this->match("motivation-plus/delete"))
            $route = $controller->deleteMotivationPlusVideo();

        // categorie
        elseif (Model::isCategorie($this->url[0]) && empty($this->url[1]))
            $route = $controller->listCategorieItems();

        // categorie/create
        elseif (Model::isCategorie($this->url[0]) && $this->url[1] == "create" && empty($this->url[2]))
            $route = $controller->createItem();
        
        // categorie/delete
        elseif (Model::isCategorie($this->url[0]) && $this->url[1] == "delete" && empty($this->url[2]))
            $route = $controller->deleteManyItems();
        
        // categorie/slug
        elseif (Model::isCategorie($this->url[0]) && Model::isSlug($this->url[1]) && empty($this->url[2]))
            $route = $controller->readItem();

        // categorie/slug/edit
        elseif (Model::isCategorie($this->url[0]) && Model::isSlug($this->url[1]) && $this->url[2] == "edit")
            $route = $controller->editItem();
        
        // categorie/slug/delete
        elseif (Model::isCategorie($this->url[0]) && Model::isSlug($this->url[1]) && $this->url[2] == "delete")
            $route = $controller->deleteItem();

        // Page 404
        else $route = $controller->adminError404();

        return $route;
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
            $route = $controller->publicAccueilPage();

        // Error 404
        else $route = $controller->publicError404();

        return $route;
    }

    /**
     * Permet de vérifier la concordance en une chaine de caractère passé en
     * paramètre et l'url.
     * 
     * @param string $route
     * 
     * @return bool
     */
    public function match(string $route)
    {
        return self::getUri() == $route;
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
        return explode("/", self::getUri());
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