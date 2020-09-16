<?php

namespace App\Controllers;

use App\View\Pages\PageBuilder;
use App\View\View;

/**
 * Controlleur de la partie publique du site.
 */
class PublicController extends Controller
{
    /**
     * Constructeur du controlleur de la partie publique.
     * 
     * @param string $url
     */
    public function __construct($url)
    {
        parent::__construct($url);
    }
    
    /**
     * Controlleur appelé pour la page d'accueil de la partie publique.
     * 
     * @return void
     */
    public function index()
    {
        $metaTitle = "Bienvenu sur " . APP_NAME;
        $page = new PageBuilder($metaTitle, View::publicAccueilView());
        $page->publicPage();
    }

    /**
     * Controlleur appelé sur la partie publique lorsque l'url n'est pas encore géré par 
     * le système.
     * 
     * @return void
     */
    public function error404()
    {
        $metaTitle = "Page non trouvée !";
        $page = new PageBuilder($metaTitle, View::publicError404View());
        $page->publicPage();
    }

}