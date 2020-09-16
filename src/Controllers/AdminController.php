<?php

namespace App\Controllers;

use App\BackEnd\Ecommerce\Order;
use App\BackEnd\Models\Entity;
use App\BackEnd\Models\Items\Item;
use App\BackEnd\Models\Items\ItemChild;
use App\BackEnd\Models\Users\Administrator;
use App\BackEnd\Models\Users\Visitor;
use App\BackEnd\Utilities\Validator;
use App\BackEnd\Utilities\Utility;
use App\View\Models\Items\ItemChildView;
use App\View\View;
use App\View\Notification;
use App\View\Pages\PageBuilder;

/**
 * Controlleur de la partie admin
 */
class AdminController extends Controller
{
    /**
     * Constructeur du controlleur de la partie admin.
     * 
     * @param string $url
     */
    public function __construct($url)
    {
        parent::__construct($url);
    }
    
    /**
     * Controlleur appelé pour le dashboard de l'administration dashboard (Tableau de bord).
     * 
     * @return void
     */
    public function dashboard()
    {
        $page = new PageBuilder(null, View::administrationDashboard());
        $page->adminPage();
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
            $view = ItemChildView::listMiniservices($items);

        } elseif ($this->categorie === "motivation-plus") {
            $items = Item::getMotivationPlusVideos();
            $view = View::listItems($items, $this->categorie);

        } else {
            $view = View::listItems($items, $this->categorie);
        }

        $page = new PageBuilder(null, $view);
        $page->adminPage();
    }

    /**
     * Controlleur appele lorque url = administrateurs
     * 
     * @return void
     */
    public function listAdministrators()
    {
        $admins = Administrator::getAll(2);
        $page = new PageBuilder(null, View::listAdministrators($admins));
        $page->adminPage();
    }

    /**
     * Controlleur qui liste les commandes de mini services.
     * 
     * @return void
     */
    public function listMiniservicesCommands()
    {
        $commands = Order::getAll();
        $page = new PageBuilder(null, View::listMiniservicesOrders($commands));
        $page->adminPage();
    }

    /**
     * Controlleur appelé lorsque url = categorie/create.
     * 
     * @return void
     */
    public function createItem()
    {
        $errors = null;

        if (isset($_POST['enregistrement'])) {

            $validator = new Validator($_POST);
            $errors = $validator->getErrors();

            if (empty($errors)) {
                Item::createItem($this->categorie);
            }
        }
        
        $view = View::createItem($this->categorie, $errors);

        $page = new PageBuilder(null, $view);
        $page->adminPage();
    }

    /**
     * Controlleur appelé lorque url = categorie/slug.
     * 
     * @return void
     */
    public function readItem()
    {
        $item = Entity::getObjectBy("slug", $this->url[1], Entity::getTableName($this->categorie), $this->categorie);

        $page = new PageBuilder(null, View::readItem($item));

        $page->adminPage();
    }

    /**
     * Controlleur appelé lorque url = categorie/slug/edit.
     * 
     * @return void
     */
    public function updateItem()
    {
        $item = Entity::getObjectBy("slug", $this->url[1], Entity::getTableName($this->categorie), $this->categorie);
        $errors = null;

        if (isset($_POST["enregistrement"])) {
            $validator = new Validator($_POST);
            $errors = $validator->getErrors();

            if (empty($errors)) {
                $item->update($this->categorie, $_POST);
            }
        }

        $page = new PageBuilder(null, View::updateItem($item, $this->categorie, $errors));
        $page->adminPage();
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
            $toDelete = $bddManager->get("code", ItemChild::TABLE_NAME, "parent_code", "MTVP");
        } else {
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

        $page = new PageBuilder(null, View::deleteItems($toDelete, $this->categorie, $error));
        $page->adminPage();
    }

    /**
     * Contrilolleur appelé lorque url = categorie/slug/delete.
     * 
     * @return void
     */
    public function deleteItem()
    {
        $item = Entity::getObjectBy("slug", $this->itemIdentifier, Entity::getTableName($this->categorie), $this->categorie);
        if ($item->delete()) {
            Utility::header(ADMIN_URL . "/" . $this->categorie);
        }
    }

    /**
     * Controlleur qui gère le post (publication d'un item).
     * 
     * @return void
     */
    public function postItem()
    {
        $item = Entity::getObjectBy("slug", $this->itemIdentifier, Entity::getTableName($this->categorie), $this->categorie);
        if ($item->post()) {
            Utility::header($item->getUrl("administrate"));
        }
    }

    /**
     * Controlleur qui gère le post (publication d'un item).
     * 
     * @return void
     */
    public function unpostItem()
    {
        $item = Entity::getObjectBy("slug", $this->itemIdentifier, Entity::getTableName($this->categorie), $this->categorie);
        if ($item->unpost()) {
            Utility::header($item->getUrl("administrate"));
        }
    }

    /**
     * Retourne le nombre de visiteur en ligne.
     * 
     */
    public function getVisitorsOnlineNumber()
    {
        echo Visitor::countVisitorsOnline();
    }

    /**
     * Controlleur appelé sur la partie admin lorsque l'url n'est pas encore géré par 
     * le système.
     * 
     * @return void
     */
    public function error404()
    {
        $page = new PageBuilder(null, View::adminError404View());
        $page->adminPage();
    }


}