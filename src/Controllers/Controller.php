<?php

/**
 * Fichier de classe
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */

namespace App\Controllers;

use App\BackEnd\Models\Ecommerce\Order;
use App\BackEnd\Models\Entity;
use App\BackEnd\Models\Items\Item;
use App\BackEnd\Models\Items\ItemChild;
use App\BackEnd\Models\Users\Administrator;
use App\BackEnd\Models\Users\Visitor;
use App\BackEnd\Utilities\Validator;
use App\BackEnd\Utilities\Utility;
use App\View\PageBuilder;
use App\View\View;
use App\View\Notification;

/**
 * Gère le controlleur. Une méthode du controlleur peut être appelée en fonction du routage.
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */
class Controller{

    protected $url;
    protected $categorie;
    protected $itemIdentifier;
    protected $action;

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
        $this->itemIdentifier = !empty($this->url[1]) ? $this->url[1] : null;
        $this->action = !empty($this->url[2]) ? $this->url[2] : null;
    }

}