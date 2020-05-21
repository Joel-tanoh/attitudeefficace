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
     * Controlleur appelé pour la page d'accueil de la partie publique.
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
     * Controlleur appelé pour le dashboard de l'administration dashboard (Tableau de bord).
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
     * Controlleur appelé lorsque url = categorie
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
     * Controlleur appele lorque url = administrateurs
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
     * Controller appelé lorsque url = motivation-plus
     * 
     * @return array
     */
    public function listMotivationPlusVideo()
    {
        return [
            "meta_title" => "Motivation plus",
            "content" => "Vous êtes sur la page qu s'affiche lorsque vous demandez motivation plus"
        ];
    }

    /**
     * Controlleur appelé lorsque url = categorie/create.
     * 
     * @return array
     */
    function createItem()
    {
        $errors = null;
        $meta_title = Model::getCreateItemPageTitle($this->url[0]);
        $view = new View();

        if (isset($_POST['enregistrement'])) {
            $validator = new Validator($_POST);
            $errors = $validator->getErrors();
            if (empty($errors)) {
                Model::createItem($this->url[0], $_POST);
            }
        }

        return [
            "meta_title" => $meta_title,
            "content" => $view->createItem($this->url[0], $errors)
        ];
    }

    /**
     * Controlleur appelé lorsque url = motivation-plus/create.
     * 
     * @return array
     */
    function createMotivationPlusVideo()
    {
        $errors = null;
        $view = new View();

        return [
            "meta_title" => "Motivation plus - ajouter une vidéo",
            "content" => $view->createMotivationPlusVideo($errors)
        ];
    }

    /**
     * Controlleur appelé lorque url = categorie/slug.
     * 
     * @return array
     */
    public function readItem()
    {
        $item = Model::getObjectBy("slug", $this->url[1], Model::getTableNameFrom($this->url[0]), $this->url[0]);
        $meta_title = ucfirst($item->get("categorie")) . ' &#8250; ' . ucfirst($item->get("title"));
        $view = new View();

        return [
            "meta_title" => $meta_title,
            "content" => $view->readItem($item)
        ];
    }

    /**
     * Controlleur appelé lorque url = categorie/slug/edit.
     * 
     * @return array
     */
    function editItem()
    {
        $item = Model::getObjectBy("slug", $this->url[1], Model::getTableNameFrom($this->url[0]), $this->url[0]);
        $errors = null;
        $meta_title = ucfirst($item->get("categorie")) . " &#8250 " . ucfirst($item->get("title")) . " &#8250 Editer";
        $view = new View();

        if (isset($_POST["enregistrement"])) {
            $validator = new Validator($_POST);
            $errors = $validator->getErrors();
            if (empty($errors)) {
                $item->editItem($this->url[0], $_POST);
            }
        }

        return [
            "meta_title" => $meta_title,
            "content" => $view->editItem($item, $this->url[0], $errors)
        ];
    }

    /**
     * Controlleur appelé lorsque url = motivation-plus/delete
     * 
     * @return array
     */
    public function deleteMotivationPlusVideo()
    {
        return [
            "meta_title" => "Motivation plus",
            "content" => "Vous ête sur la page qui s'affiche lorsque vous voulez supprimer des vidéos de motivation plus"
        ];
    }

    /**
     * Contrilolleur appelé lorque url = categorie/slug/delete.
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
     * Controlleur appelé lorque url = categorie/delete.
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
     * Controlleur appelé sur la partie admin lorsque l'url n'est pas encore géré par 
     * le système.
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
     * Controlleur appelé sur la partie publique lorsque l'url n'est pas encore géré par 
     * le système.
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