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
        if ($url == "") {
            $this->url = "";
        } else {
            $this->url = explode('/', $url);
        }
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

        // Home
        if ($this->url == "")
            $route = $controller->dashboard();

        // administrateurs
        elseif ($this->url[0] == 'administrateurs' && empty($this->url[1])) 
            $route = $controller->listAdminUsersAccounts();

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
        if ($this->url == "")
            $route = $controller->publicAccueilPage();

        // Error 404
        else $route = $controller->publicError404();

        return $route;
    }

    /**
     * Permet de vérifier la concordance en une chaine de caractère passé en
     * paramètre et l'url.
     * 
     * @param string $string
     * 
     * @return bool
     */
    private function match(string $string)
    {

    }

}