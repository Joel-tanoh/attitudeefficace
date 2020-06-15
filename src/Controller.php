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
use App\BackEnd\Models\Users\Administrateur;
use App\BackEnd\Utilities\Validator;
use App\BackEnd\Utilities\Utility;
use App\BackEnd\Utilities\VisitManager;
use App\View\ModelsView\ChildView;
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
    public function __construct($url)
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
    public function dashboard()
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
    public function listItems()
    {
        $items = Item::getAll($this->categorie);

        if ($this->categorie === "mini-services") {
            $metaTitle = "Mes mini services";
            $view = ItemChild::listMiniservices($items);

        } elseif ($this->categorie === "motivation-plus") {
            $metaTitle = "Motivation plus";
            $items = Item::getMotivationPlusVideos();
            $view = View::listItems($items, $this->categorie);

        } else {
            $metaTitle = "Mes " . Entity::getCategorieFormated($this->categorie, "pluriel");
            $view = View::listItems($items, $this->categorie);
        }

        $page = new PageBuilder($metaTitle, $view);
        echo $page->adminPage();
    }

    /**
     * Controlleur appele lorque url = administrateurs
     * 
     * @return void
     */
    public function listAdmins()
    {
        $metaTitle = "Administrateurs";
        $admins = Administrateur::getAll(2);
        $page = new PageBuilder($metaTitle, View::listAdmins($admins));
        echo $page->adminPage();
    }

    /**
     * Controlleur qui liste les commandes de mini services.
     * 
     * @return void
     */
    public function listMiniservicesCommands()
    {
        $metaTitle = "Mini services &#8250 Commandes";
        $commands = MiniserviceOrder::getAll();
        $page = new PageBuilder($metaTitle, View::listMiniservicesOrders($commands));
        echo $page->adminPage();
    }

    /**
     * Controlleur appelé lorsque url = categorie/create.
     * 
     * @return void
     */
    public function createItem()
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
        
        $view = View::createItem($this->categorie, $errors);

        $page = new PageBuilder($metaTitle, $view);
        echo $page->adminPage();
    }

    /**
     * Controlleur appelé lorque url = categorie/slug.
     * 
     * @return void
     */
    public function readItem()
    {
        $item = Entity::getObjectBy("slug", $this->url[1], Entity::getTableName($this->categorie), $this->categorie);

        $metaTitle = ucfirst($item->getCategorie()) . ' &#8250; ' . ucfirst($item->getTitle());

        $page = new PageBuilder($metaTitle, View::readItem($item));

        echo $page->adminPage();
    }

    /**
     * Controlleur appelé lorque url = categorie/slug/edit.
     * 
     * @return void
     */
    public function editItem()
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

        $page = new PageBuilder($metaTitle, View::updateItem($item, $this->categorie, $errors));
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
                Utility::header(ADMIN_URL . "/" . $this->categorie);
            }
        }

        $page = new PageBuilder($metaTitle, View::deleteItems($toDelete, $this->categorie, $error));
        echo $page->adminPage();
    }

    /**
     * Contrilolleur appelé lorque url = categorie/slug/delete.
     * 
     * @return void
     */
    public function deleteItem()
    {
        $item = Entity::getObjectBy("slug", $this->url[1], Entity::getTableName($this->categorie), $this->categorie);
        if ($item->delete()) {
            Utility::header(ADMIN_URL . "/" . $this->categorie);
        }
    }

    /**
     * Controlleur appelé sur la partie admin lorsque l'url n'est pas encore géré par 
     * le système.
     * 
     * @return void
     */
    public function adminError404()
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
    public function publicError404()
    {
        $metaTitle = "Page non trouvée";
        $page = new PageBuilder($metaTitle, View::publicError404View());
        echo $page->publicPage();
    }

}