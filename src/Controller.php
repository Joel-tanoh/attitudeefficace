<?php

/**
 * Fichier de classe
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */

namespace App;

use App\BackEnd\Models\Model;
use App\BackEnd\Utils\Validator;
use App\BackEnd\Utils\Utils;
use App\View\PageBuilder;
use App\View\View;
use App\View\Notification;

/**
 * Gère le controlleur. Une méthode du controlleur peut être appelée en fonction du routage.
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */
class Controller{

    private $url;
    private $categorie;

    /**
     * Permet d'instancier un controlleur.
     * 
     * @param array $url 
     * 
     * @return void
     */
    function __construct($url)
    {
        $this->url = $url;
        $this->categorie = !empty($this->url[0]) ? $this->url[0] : null;
    }

    /**
     * Controlleur appelé pour la page d'accueil de la partie publique.
     * 
     * @return void
     */
    public function publicAccueilPage()
    {
        $meta_title = "Bienvenu sur " . APP_NAME;
        $page = new PageBuilder($meta_title, View::publicAccueilView());
        Utils::appVisitCounter();
        echo $page->publicPage();
    }

    /**
     * Controlleur appelé pour le dashboard de l'administration dashboard (Tableau de bord).
     * 
     * @return void
     */
    public function dashboard()
    {
        $meta_title = "Tableau de bord";
        $page = new PageBuilder($meta_title, View::adminDashboardView());
        echo $page->adminPage();
    }
      
    /**
     * Controlleur appelé lorsque url = categorie
     * 
     * @return string
     */
    public function listCategorieItems()
    {
        $bdd_manager = Model::bddManager();
        $meta_title = "Mes " . Model::getCategorieFormated($this->categorie, "pluriel");
        $items = $bdd_manager->get("code", Model::getTableNameFrom($this->categorie), "categorie", $this->categorie);
        
        if ($this->categorie === "minis-services") {
            $view = View::listMiniservicesView($items);
        } else {
            $view = View::listItemsView($items, $this->categorie);
        }

        $page = new PageBuilder($meta_title, $view);
        echo $page->adminPage();
    }

    /**
     * Controlleur appele lorque url = administrateurs
     * 
     * @return void
     */
    public function listAdminUsersAccounts()
    {
        $bdd_manager = Model::bddManager();
        $meta_title = "Utilisateurs";
        $accounts = $bdd_manager->get("code", Model::getTableNameFrom( $this->categorie ), "categorie", "utilisateur");
        $page = new PageBuilder($meta_title, View::listAccountsView($accounts));
        echo $page->adminPage();
    }

    /**
     * Controller appelé lorsque url = motivation-plus
     * 
     * @return void
     */
    public function listMotivationPlusVideo()
    {
        $meta_title = "Motivation plus";
        $videos = Model::getchildrenOf("-1", "videos");
        $page = new PageBuilder($meta_title, View::listMotivationPlusVideosView($videos));
        echo $page->adminPage();
    }

    /**
     * Controlleur appelé lorsque url = categorie/create.
     * 
     * @return void
     */
    function createItem()
    {
        $errors = null;
        
        if ($this->categorie === "motivation-plus") {
            $meta_title = "Motivation plus &#8250 nouvelle vidéo";
        } else {
            $meta_title = Model::getCreateItemPageTitle($this->categorie);
        }

        if (isset($_POST['enregistrement'])) {

            $validator = new Validator($_POST);
            $errors = $validator->getErrors();

            if (empty($errors)) {
                if ($this->categorie === "motivation-plus") {
                    Model::createItem("videos", $_POST);
                } else {
                    Model::createItem($this->categorie, $_POST);
                }
            }
        }

        if ($this->categorie === "motivation-plus") {
            $page = new PageBuilder($meta_title, View::createMotivationPlusVideoView($errors));
        } else {
            $page = new PageBuilder($meta_title, View::createItemView($this->categorie, $errors));
        }

        echo $page->adminPage();
    }

    /**
     * Controlleur appelé lorsque url = motivation-plus/create.
     * 
     * @return void
     */
    function createMotivationPlusVideo()
    {
        $errors = null;
        $meta_title = "Motivation plus &#8250 nouvelle vidéo";
        if (isset($_POST["enregistrement"])) {
            $validator = new Validator($_POST);
            $errors = $validator->getErrors();
            if (empty($errors)) {
                Model::createItem("videos", $_POST);
            }
        }
        $page = new PageBuilder($meta_title, View::createMotivationPlusVideoView($errors));
        echo $page->adminPage();
    }

    /**
     * Controlleur appelé lorque url = categorie/slug.
     * 
     * @return void
     */
    public function readItem()
    {
        $item = Model::getObjectBy("slug", $this->url[1], Model::getTableNameFrom($this->categorie), $this->categorie);
        $meta_title = ucfirst($item->get("categorie")) . ' &#8250; ' . ucfirst($item->get("title"));
        $page = new PageBuilder($meta_title, View::readItemView($item));
        echo $page->adminPage();
    }

    /**
     * Controlleur appelé lorque url = categorie/slug/edit.
     * 
     * @return void
     */
    function editItem()
    {
        $item = Model::getObjectBy("slug", $this->url[1], Model::getTableNameFrom($this->categorie), $this->categorie);
        $errors = null;
        $meta_title = ucfirst($item->get("categorie")) . " &#8250 " . ucfirst($item->get("title")) . " &#8250 Editer";

        if (isset($_POST["enregistrement"])) {
            $validator = new Validator($_POST);
            $errors = $validator->getErrors();
            if (empty($errors)) {
                $item->editItem($this->categorie, $_POST);
            }
        }

        $page = new PageBuilder($meta_title, View::editItemView($item, $this->categorie, $errors));
        echo $page->adminPage();
    }

    /**
     * Controlleur appelé lorque url = categorie/delete.
     * 
     * @return void
     */
    public function deleteItems()
    {
        $error = null;
        $bdd_manager = Model::bddManager();
        if ($this->categorie === "motivation-plus") {
            $meta_title = "Motivation plus &#8250 supprimer";
            $to_delete = Model::getchildrenOf("-1", "videos");
        } else {
            $meta_title = "Supprimer des " . Model::getCategorieFormated($this->categorie, "pluriel");
            $to_delete = $bdd_manager->get("code", Model::getTableNameFrom($this->categorie), "categorie", $this->categorie);
        }

        if (isset($_POST["suppression"])) {
            if (empty($_POST["codes"])) {
                $notification = new Notification();
                $error = $notification->nothingSelected();
            } else {
                Model::deleteItems($this->categorie);
                Utils::header(ADMIN_URL . "/" . $this->categorie);
            }
        }

        $page = new PageBuilder($meta_title, View::deleteItemsView($to_delete, $this->categorie, $error));
        echo $page->adminPage();
    }

    /**
     * Contrilolleur appelé lorque url = categorie/slug/delete.
     * 
     * @return void
     */
    public function deleteItem()
    {
        $item = Model::getObjectBy("slug", $this->url[1], Model::getTableNameFrom($this->categorie), $this->categorie);
        if ($item->delete()) {
            Utils::header(ADMIN_URL . "/" . $this->categorie);
        }
    }

    /**
     * Controlleur appelé sur la partie admin lorsque l'url n'est pas encore géré par 
     * le système.
     * 
     * @return void
     */
    function adminError404()
    {
        $meta_title = "Page non trouvée";
        $page = new PageBuilder($meta_title, View::adminError404View());
        echo $page->adminPage();
    }

    /**
     * Controlleur appelé sur la partie publique lorsque l'url n'est pas encore géré par 
     * le système.
     * 
     * @return void
     */
    function publicError404()
    {
        $meta_title = "Page non trouvée";
        $page = new PageBuilder($meta_title, View::publicError404View());
        echo $page->publicPage();
    }

}