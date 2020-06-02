<?php

/**
 * Fichier de classe
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */

namespace App;

use App\BackEnd\BddManager;
use App\BackEnd\Models\Model;
use App\BackEnd\Utils\Validator;
use App\BackEnd\Utils\Utils;
use App\View\Page;
use App\View\View;
use App\View\Notification;

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
     * @return void
     */
    public function publicAccueilPage()
    {
        $meta_title = "Bienvenu sur " . APP_NAME;
        $view = new View();
        $page = new Page($meta_title, $view->publicAccueil());
        Utils::setAppComputerVisite();
        echo $page->publicPage();
    }

    /**
     * Controlleur appelé pour le dashboard de l'administration dashboard (Tableau de bord).
     * 
     * @return void
     */
    function dashboard()
    {
        $meta_title = "Tableau de bord";
        $view = new View();
        $page = new Page($meta_title, $view->adminDashboard());
        echo $page->adminPage();
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
        $items = BddManager::getAllFrom(Model::getTableNameFrom($this->url[0]), $this->url[0]);
        $page = new Page($meta_title, $view->listItems($items, $this->url[0]));
        echo $page->adminPage();
    }

    /**
     * Controlleur appele lorque url = administrateurs
     * 
     * @return void
     */
    public function listAdminUsersAccounts()
    {
        $view = new View();
        $meta_title = "Utilisateurs";
        $accounts = BddManager::getAllFrom( Model::getTableNameFrom( $this->url[0] ), "utilisateur" );
        $page = new Page($meta_title, $view->listAccounts($accounts));
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
        $view = new View();
        $videos = BddManager::getchildrenOf("-1", "videos");
        $page = new Page($meta_title, $view->listMotivationPlusVideos($videos));
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
        $meta_title = Model::getCreateItemPageTitle($this->url[0]);
        $view = new View();

        if (isset($_POST['enregistrement'])) {
            $validator = new Validator($_POST);
            $errors = $validator->getErrors();
            if (empty($errors)) {
                Model::createItem($this->url[0], $_POST);
            }
        }

        $page = new Page($meta_title, $view->createItem($this->url[0], $errors));
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
        $view = new View();
        $meta_title = "Motivation plus &#8250 nouvelle vidéo";
        if (isset($_POST["enregistrement"])) {
            $validator = new Validator($_POST);
            $errors = $validator->getErrors();
            if (empty($errors)) {
                Model::createItem("videos", $_POST);
            }

        }
        $page = new Page($meta_title, $view->createMotivationPlusVideo($errors));
        echo $page->adminPage();
    }

    /**
     * Controlleur appelé lorque url = categorie/slug.
     * 
     * @return void
     */
    public function readItem()
    {
        $item = Model::getObjectBy("slug", $this->url[1], Model::getTableNameFrom($this->url[0]), $this->url[0]);
        $meta_title = ucfirst($item->get("categorie")) . ' &#8250; ' . ucfirst($item->get("title"));
        $view = new View();
        $page = new Page($meta_title, $view->readItem($item));
        echo $page->adminPage();
    }

    /**
     * Controlleur appelé lorque url = categorie/slug/edit.
     * 
     * @return void
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

        $page = new Page($meta_title, $view->editItem($item, $this->url[0], $errors));
        echo $page->adminPage();
    }

    /**
     * Controlleur appelé lorsque url = motivation-plus/delete
     * 
     * @return void
     */
    public function deleteMotivationPlusVideo()
    {
        $view = new View();
        $meta_title = "Motivation plus &#8250 supprimer";
        $to_delete = [];
        $page = new Page($meta_title, $view->deleteItems($to_delete, "motivation-plus"));
        echo $page->adminPage();
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
     * @return void
     */
    public function deleteManyItems()
    {
        $items = BddManager::getAllFrom(Model::getTableNameFrom($this->url[0]), $this->url[0]);
        $meta_title = "Supprimer des " . Model::getCategorieFormated($this->url[0], "pluriel");
        $view = new View();
        $error = null;

        if (isset($_POST["suppression"])) {
            if (empty($_POST["codes"])) {
                $notification = new Notification();
                $error = $notification->nothingSelected();
            } else {
                Model::deleteItems($this->url[0]);
                Utils::header(ADMIN_URL . "/" . $this->url[0]);
            }
        }

        $page = new Page($meta_title, $view->deleteItems($items, $this->url[0], $error));
        echo $page->adminPage();
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
        $view = new View();
        $page = new Page($meta_title, $view->adminError404());
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
        $view = new View();
        $meta_title = "Page non trouvée";
        $view = new Page($meta_title, $view->publicError404());
        echo $view->publicPage();
    }

}