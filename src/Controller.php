<?php

/**
 * Fichier de classe
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */

namespace App;

use App\BackEnd\APIs\Bdd;
use App\BackEnd\Models\Model;
use App\BackEnd\Utils\Notification;
use App\FrontEnd\Page;
use App\BackEnd\Utils\Validator;
use App\BackEnd\Utils\Utils;
use App\FrontEnd\View\View;

/**
 * Gère le controlleur. Une méthode du controlleur peut être appelée en fonction du routage.
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */
class Controller
{
    private $url;

    /**
     * Permet d'instancier un controlleur.
     * 
     * @param array $this->url 
     * 
     * @return void
     */
    function __construct($url)
    {
        $this->url = $url;
        $this->view = new View();
    }

    /**
     * Controlleur de la page d'accueil de la partie publique du site.
     * 
     * @return array
     */
    public function publicAccueilPage()
    {
        $meta_title = "Bienvenu sur " . APP_NAME;
        $view = new View();

        return [
            "meta_title" => $meta_title,
            "content" => $view->publicAccueil()
        ];
    }

    /**
     * Controlleur du dashboard (Tableau de bord).
     * 
     * @return array
     */
    function dashboard()
    {
        $meta_title = "Tableau de bord";
        $view = new View();

        return [
            "meta_title" => $meta_title,
            "content" => $view->adminDashboard()
        ];
    }
      
    /**
     * Controlleur pour lister les items d'une catégorie.
     * 
     * @return string
     */
    public function listCategorieItems()
    {
        $meta_title = "Mes " . Model::getCategorieFormated($this->url[0], "pluriel");
        $view = new View();

        if ($this->url[0] == "motivation-plus") { $items = Bdd::getchildrenOf("-1", "videos"); }
        else { $items = Bdd::getAllFrom(Model::getTableNameFrom($this->url[0]), $this->url[0]); }

        return [
            "meta_title" => $meta_title,
            "content" => $view->listItems($items, $this->url[0]),
        ];
    }

    /**
     * Controlleur pour lister les comptes administrateurs.
     * 
     * @return array
     */
    public function listAdminUsersAccounts()
    {
        $meta_title = "Comptes";
        $accounts = Bdd::getAllFrom( Model::getTableNameFrom( $this->url[0] ), "utilisateur" );
        $view = new View();

        return [
            "meta_title" => $meta_title,
            "content" => $view->listAccounts($accounts)
        ];
    }

    /**
     * Controlleur de création d'un nouvel élément.
     * 
     * @return array
     */
    function createItem()
    {
        $errors = null;
        $meta_title = Model::getCreateItemPageTitle($this->url[0]);
        $view = new View();

        if (isset($_POST['enregistrement'])) {
            dump($_POST);
            dump($_FILES);
            die();
            $validator = new Validator($_POST);
            $errors = $validator->getErrors();
            if (empty($errors)) {
              Model::create($this->url[0], $_POST);
            }
        }

        return [
            "meta_title" => $meta_title,
            "content" => $view->createItem($this->url[0], $errors)
        ];
    }

    /**
     * Controlleur pour lire un item.
     */
    public function readItem()
    {
        $item = Model::getObjectBy("slug", $this->url[1], Model::getTableNameFrom($this->url[0]), $this->url[0]);
        $meta_title = ucfirst($item->get("categorie")) . ' &#8250; ' . ucfirst($item->get("meta_title"));
        $view = new View();

        return [
            "meta_title" => $meta_title,
            "content" => $view->readItem($item)
        ];
    }

    /**
     * Controlleur d'édition d'un item.
     * 
     * @return array
     */
    function editItem()
    {
        $item = Model::getObjectBy("slug", $this->url[1], Model::getTableNameFrom($this->url[0]), $this->url[0]);
        $errors = null;
        $meta_title = ucfirst($item->get("categorie")) . " &#8250 " . ucfirst($item->get("meta_title")) . " &#8250 Editer";
        $view = new View();

        if (isset($_POST["enregistrement"])) {
            $validator = new Validator($_POST);
            $errors = $validator->getErrors();
            if (empty($errors)) {
                $item->edit($this->url[0], $_POST);
            }
        }

        return [
            "meta_title" => $meta_title,
            "content" => $view->editItem($item, $this->url[0], $errors)
        ];
    }

    /**
     * Contrilolleur de suppression d'un item.
     * 
     * @return void
     */
    public function deleteItem()
    {
        $item = Model::getObjectBy("slug", $this->url[1], Model::getTableNameFrom($this->url[0]), $this->url[0]);
        if ($item->delete()) {
            Utils::header(ADMIN_URL . "/" . $this->url[0]);
        }
    }

    /**
     * Controlleur de suppression d'un item ou de plusieurs items.
     * 
     * @return array
     */
    public function deleteManyItems()
    {
        $items = Bdd::getAllFrom(Model::getTableNameFrom($this->url[0]), $this->url[0]);
        $meta_title = "Supprimer des " . Model::getCategorieFormated($this->url[0], "pluriel");
        $view = new View();

        if (isset($_POST["suppression"])) {
            if (empty($_POST["codes"])) {
                $notification = new Notification();
                $error = $notification->nothingSelected();
            } else {
                Model::deleteItems($this->url[0]);
                Utils::header(ADMIN_URL . "/" . $this->url[0]);
            }
        }

        return [
            "meta_title" => $meta_title,
            "content" => $view->deleteItems($items, $this->url[0], $error)
        ];
    }

    /**
     * Controlleur de page d'erreur 404 sur la partie administration.
     * 
     * @return array
     */
    function adminError404()
    {
        $meta_title = "Page non trouvée";
        
        $view = new View();
        
        return [
            "meta_title" => $meta_title,
            "content" => $view->adminError404()
        ];
    }

    /**
     * Controlleur de page d'erreur 404 sur la partie publique.
     * 
     * @return array
     */
    function publicError404()
    {
        $meta_title = "Page non trouvée";

        $view = new View();

        return [
            "meta_title" => $meta_title,
            "content" => $view->publicError404()
        ];
    }

}