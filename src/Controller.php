<?php

/**
 * Fichier de classe
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */

namespace App;

use App\BackEnd\Models\MiniserviceOrder;
use App\BackEnd\Models\Entity;
use App\BackEnd\Models\Items\Item;
use App\BackEnd\Models\Items\ItemChild;
use App\BackEnd\Utils\Validator;
use App\BackEnd\Utils\Utils;
use App\BackEnd\Utils\VisitManager;
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
    function publicAccueilPage()
    {
        $metaTitle = "Bienvenu sur " . APP_NAME;
        $page = new PageBuilder($metaTitle, View::publicAccueilView());
        VisitManager::appVisitCounter();
        echo $page->publicPage();
    }

    /**
     * Controlleur appelé pour le dashboard de l'administration dashboard (Tableau de bord).
     * 
     * @return void
     */
    function dashboard()
    {
        $metaTitle = "Tableau de bord";
        $page = new PageBuilder($metaTitle, View::administrattionDashboard());
        echo $page->adminPage();
    }
      
    /**
     * Controlleur appelé lorsque url = categorie
     * 
     * @return void
     */
    function listItems()
    {
        $bddManager = Entity::bddManager();
        
        $items = $bddManager->get(
            "code"
            , Entity::getTableName($this->categorie)
            , "categorie"
            , $this->categorie
        );

        if ($this->categorie === "mini-services") {
            $metaTitle = "Mes mini services";
            $view = View::listMiniservicesView($items);

        } else {
            $metaTitle = "Mes " . Entity::getCategorieFormated($this->categorie, "pluriel");
            $view = View::listItemsView($items, $this->categorie);
        }

        $page = new PageBuilder($metaTitle, $view);
        echo $page->adminPage();
    }

    /**
     * Controlleur appele lorque url = administrateurs
     * 
     * @return void
     */
    function listAdminUsersAccounts()
    {
        $bddManager = Entity::bddManager();
        $metaTitle = "Utilisateurs";
        $accounts = $bddManager->get("code", Entity::getTableName( $this->categorie ), "categorie", "utilisateur");
        $page = new PageBuilder($metaTitle, View::listAccountsView($accounts));
        echo $page->adminPage();
    }

    /**
     * Controller appelé lorsque url = motivation-plus
     * 
     * @return void
     */
    function listMotivationPlusVideo()
    {
        $bddManager = Entity::bddManager();
        $metaTitle = "Motivation plus";
        $videos = $bddManager->get("code", ItemChild::TABLE_NAME, "parent_id", "-1");
        $page = new PageBuilder($metaTitle, View::listMotivationPlusVideosView($videos));
        echo $page->adminPage();
    }

    /**
     * Controlleur qui liste les commandes de mini services.
     * 
     * @return void
     */
    function listMiniservicesCommands()
    {
        $metaTitle = "Mini services &#8250 Commandes";
        $commands = MiniserviceOrder::getAll();
        $page = new PageBuilder($metaTitle, View::listMiniservicesCommandsView($commands));
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
            $metaTitle = "Motivation plus &#8250 nouvelle vidéo";
        } else {
            $metaTitle = Entity::getCreateItemPageTitle($this->categorie);
        }

        if (isset($_POST['enregistrement'])) {

            $validator = new Validator($_POST);
            $errors = $validator->getErrors();

            if (empty($errors)) {
                Item::createItem($this->categorie);
            }
        }

        if ($this->categorie === "motivation-plus") {
            $view = View::createMotivationPlusVideoView($errors);
        } else {
            $view = View::createItemView($this->categorie, $errors);
        }

        $page = new PageBuilder($metaTitle, $view);
        echo $page->adminPage();
    }

    /**
     * Controlleur appelé lorque url = categorie/slug.
     * 
     * @return void
     */
    function readItem()
    {
        $item = Entity::getObjectBy("slug", $this->url[1], Entity::getTableName($this->categorie), $this->categorie);

        $metaTitle = ucfirst($item->getCategorie()) . ' &#8250; ' . ucfirst($item->getTitle());

        $page = new PageBuilder($metaTitle, View::readItemView($item));

        echo $page->adminPage();
    }

    /**
     * Controlleur appelé lorque url = categorie/slug/edit.
     * 
     * @return void
     */
    function editItem()
    {
        $item = Entity::getObjectBy("slug", $this->url[1], Entity::getTableName($this->categorie), $this->categorie);
        $errors = null;
        $metaTitle = ucfirst($item->getCategorie()) . " &#8250 " . ucfirst($item->getTitle()) . " &#8250 Editer";

        if (isset($_POST["enregistrement"])) {
            $validator = new Validator($_POST);
            $errors = $validator->getErrors();

            if (empty($errors)) {
                $item->update($this->categorie, $_POST);
            }
        }

        $page = new PageBuilder($metaTitle, View::editItemView($item, $this->categorie, $errors));
        echo $page->adminPage();
    }

    /**
     * Controlleur appelé lorque url = categorie/delete.
     * 
     * @return void
     */
    function deleteItems()
    {
        $error = null;
        $bddManager = Entity::bddManager();
        
        if ($this->categorie === "motivation-plus") {
            $metaTitle = "Motivation plus &#8250 supprimer";
            $toDelete = $bddManager->get("code", ItemChild::TABLE_NAME, "parent_id", "-1");
        } else {
            $metaTitle = "Supprimer des " . Entity::getCategorieFormated($this->categorie, "pluriel");
            $toDelete = $bddManager->get("code", Entity::getTableName($this->categorie), "categorie", $this->categorie);
        }

        if (isset($_POST["suppression"])) {
            if (empty($_POST["codes"])) {
                $notification = new Notification();
                $error = $notification->nothingSelected();
            } else {
                Item::deleteItems($this->categorie);
                Utils::header(ADMIN_URL . "/" . $this->categorie);
            }
        }

        $page = new PageBuilder($metaTitle, View::deleteItemsView($toDelete, $this->categorie, $error));
        echo $page->adminPage();
    }

    /**
     * Contrilolleur appelé lorque url = categorie/slug/delete.
     * 
     * @return void
     */
    function deleteItem()
    {
        $item = Entity::getObjectBy("slug", $this->url[1], Entity::getTableName($this->categorie), $this->categorie);
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
        $metaTitle = "Page non trouvée";
        $page = new PageBuilder($metaTitle, View::adminError404View());
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
        $metaTitle = "Page non trouvée";
        $page = new PageBuilder($metaTitle, View::publicError404View());
        echo $page->publicPage();
    }

}