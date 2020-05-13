<?php

/**
 * Fichier de classe
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */

namespace App;

use App\BackEnd\APIs\Bdd;
use App\BackEnd\Data\Data;
use App\BackEnd\Utils\Notification;
use App\FrontEnd\Page;
use App\BackEnd\Utils\Validator;
use App\BackEnd\Utils\Utils;

/**
 * Gère le controlleur. Une méthode du controlleur peut être appelée en fonction du routage.
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */
class Controller
{
    private $url;
    private $model;
    private $page;

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
    }

    /**
     * Controlleur de la page d'accueil de la partie publique du site.
     * 
     * @return array
     */
    public function publicAccueilPage()
    {
        $page_title = "Bienvenu sur " . APP_NAME;
        $page = new Page($page_title);
        return [
            "title" => $page_title,
            "content" => $page->publicAccueilPage()
        ];
    }

    /**
     * Controlleur du dashboard (Tableau de bord).
     * 
     * @return array
     */
    function dashboard()
    {
        $page_title = "Tableau de bord";
        $page = new Page($page_title);
        return [
            "title" => $page_title,
            "content" => $page->dashboard(),
        ];
    }
      
    /**
     * Controlleur de création d'un nouvel élément.
     * 
     * @return array
     */
    function create()
    {
        $errors = null;
        $page_title = Data::getCreatePageTitle($this->url[0]);
        if (isset($_POST['enregistrement'])) {
            $validator = new Validator($_POST);
            $errors = $validator->getErrors();
            if (empty($errors)) {
              Data::create($this->url[0], $_POST);
            }
        }
        $page = new Page($page_title);
        return [
            "title" => $page_title,
            "content" => $page->create($this->url[0], $errors),
        ];
    }

    /**
     * Controlleur d'affichage d'un item.
     * 
     * @return array
     */
    function read()
    {
        if (isset($this->url[1])) {
            $item = Data::getObjectBy("slug", $this->url[1], Data::getTableNameFrom($this->url[0]), $this->url[0]);
            $page_title = ucfirst($item->get("categorie")) . ' &#8250; ' . ucfirst($item->get("title"));
            $page = new Page($page_title);
            $page_content = $page->read($item);

        } elseif ($this->url[0] == "administrateurs") {
            $page_title = "Comptes";
            $accounts = Bdd::getAllFrom(Data::getTableNameFrom($this->url[0]), "utilisateur");
            $page = new Page($page_title);
            $page_content = $page->listAccounts($accounts);

        } else {
            $page_title = "Mes " . Data::getTypeFormated($this->url[0], "pluriel");
            if ($this->url[0] == "motivation-plus") { $items = Bdd::getchildrenOf("-1", "videos"); }
            else { $items = Bdd::getAllFrom(Data::getTableNameFrom($this->url[0]), $this->url[0]); }
            $page = new Page($page_title);
            $page_content = $page->listItems($items, $this->url[0]);
        }

        return [
            "title" => $page_title,
            "content" => $page_content,
        ];
    }

    /**
     * Controlleur d'édition d'un item.
     * 
     * @return array
     */
    function edit()
    {
        $item = Data::getObjectBy("slug", $this->url[1], Data::getTableNameFrom($this->url[0]), $this->url[0]);
        $errors = null;
        $page_title = ucfirst($item->get("categorie")) . " &#8250 " . ucfirst($item->get("title")) . " &#8250 Editer";

        if (isset($_POST["enregistrement"])) {
            $validator = new Validator($_POST);
            $errors = $validator->getErrors();
            if (empty($errors)) {
                $item->edit($this->url[0], $_POST);
            }
        }
        
        $page = new Page($page_title);
        return [
            "title" => $page_title,
            "content" => $page->edit($item, $this->url[0], $errors),
        ];
    }

    /**
     * Controlleur de suppression d'un item ou de plusieurs items.
     * 
     * @return array
     */
    function delete()
    {
        if (isset($this->url[2])) {
            $item = Data::getObjectBy("slug", $this->url[1], Data::getTableNameFrom($this->url[0]), $this->url[0]);
            if ($item->delete()) {
                Utils::header(ADMIN_URL . "/" . $this->url[0]);
            }
        } else {
            $items = Bdd::getAllFrom(Data::getTableNameFrom($this->url[0]), $this->url[0]);
            $page_title = "Supprimer des " . Data::getTypeFormated($this->url[0], "pluriel");
    
            if (isset($_POST["suppression"])) {
                if (empty($_POST["codes"])) {
                    $notification = new Notification();
                    $error = $notification->nothingSelected();
                } else {
                    Data::deleteItems($this->url[0]);
                    Utils::header(ADMIN_URL . "/" . $this->url[0]);
                }
            }
    
            $page = new Page($page_title);
            return [
                "title" => $page_title,
                "content" => $page->delete($items, $this->url[0], $error),
            ];
        }
    }

    /**
     * Controlleur de page d'erreur 404 sur la partie administration.
     * 
     * @return array
     */
    function adminError404()
    {
        $page_title = "Page non trouvée";
        $page = new Page($page_title);
        return [
            "title" => $page_title,
            "content" => $page->adminError404(),
        ];
    }

    /**
     * Controlleur de page d'erreur 404 sur la partie publique.
     * 
     * @return array
     */
    function publicError404()
    {
        $page_title = "Page non trouvée";
        $page = new Page($page_title);
        return [
            "title" => $page_title,
            "content" => $page->publicError404(),
        ];
    }

}