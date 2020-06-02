<?php

/**
 * Description
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com License
 * @link     Link
 */

namespace App\View;

use App\BackEnd\Models\Personnes\Administrateur;

/**
 * Perlet de gérer tout ce qui concerne la barre de navigation supérieure.
 * 
 * @category Category
 * @package  App\
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com License
 * @link     Link
 */
class Navbar extends View
{
    /**
     * Barre de navigation supérieure de la partie publique.
     * 
     * @return string
     */
    public function publicNavbar()
    {
        $brand_src = LOGOS_DIR . "/logo_3.png";
        return <<<HTML
        <nav class="navbar navbar-expand-md navbar-dark bg-marron mb-3">
            <div class="container">
                {$this->navbarBrand($brand_src, PUBLIC_URL, APP_NAME)}
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="navbar-toggler-icon"></i>
                </button>
                <div class="collapse navbar-collapse d-md-flex justify-content-between" id="navbarNav">
                    {$this->publicNavbarLinks()}
                </div>
            </div>
        </nav>
HTML;
    }

    /**
     * Barre de navigation supérieure de la partie administration.
     *
     * @author Joel
     * @return string
     */
    public function adminNavBar()
    {
        return <<<HTML
        <div class="navbar fixed-top navbar-content bg-white border-bottom w-100 d-flex justify-content-end">
            <ul class="navbar-nav d-flex align-items-center flex-row">
                {$this->addItemsLinksView()}
                {$this->getAdminManagementButtonsView()}
            </ul>
        </div>
HTML;
    }
    
    /**
     * Permet d'afficher le logo dans la navbar.
     * 
     * @param string $brand_src        Le lien vers l'image.
     * @param string $click_direction  L'url exécuté lors du click sur le logo.
     * @param string $alt_information  Le texte à afficher si l'image introuvable.
     * 
     * @return string
     */
    public function navbarBrand(string $brand_src, string $click_direction = null, string $alt_information = null)
    {
        return <<<HTML
        <a class="navbar-brand" href="{$click_direction}">
            <img src="{$brand_src}" alt="{$alt_information}" class="brand">
        </a>
HTML;
    }

    /**
     * Affiche les liens pour créer des catégories et des éléments.
     * 
     * @return string code HTML
     */
    public function addItemsLinksView()
    {
        $admin_url = ADMIN_URL;
        return <<<HTML
        <li id="addButton" class="mr-3">
            <a class="add-button-icon">
                <i class="fas fa-plus"></i>
            </a>
            <ul class="add-button-content list-unstyled">
                <li>
                    <a href="{$admin_url}/formations/create" class="text-primary">Formation</a>
                </li>
                <li>
                    <a href="{$admin_url}/articles/create" class="text-primary">Article</a>
                </li>
                <li>
                    <a href="{$admin_url}/videos/create" class="text-primary">Vidéo</a>
                </li>
                <li>
                    <a href="{$admin_url}/livres/create" class="text-primary">Livre</a>
                </li>
                <li>
                    <a href="{$admin_url}/ebooks/create" class="text-primary">Ebook</a>
                </li>
            </ul>
        <li>
HTML;
    }

    /**
     * Bouton administrateur se trouvant dans la TopBar.
     * 
     * @author Joel
     * @return string
     */
    public function getAdminManagementButtonsView()
    {
        $admin_url = ADMIN_URL;
        $admin_user = Administrateur::getByLogin($_SESSION["admin_login"] ?? $_COOKIE["admin_login"]);
        $private_buttons = $admin_user->get("categorie") === "administrateur" ? $this->administratorActions() : null;

        return <<<HTML
        <li class="btn-administrateur">
            <a id="btnAdministrateurIcon" class="nav-link d-flex align-items-center">
                {$this->navbarUserAvatar($admin_user->get("avatar_src"), $admin_user->get("login"))}
                <span class="fas fa-caret-down"></span>
            </a>
            <ul id="btnAdministrateurContent" class="content border list-unstyled">
                <li>
                    <a href="{$admin_url}/administrateurs/me" class="text-primary">Mon profil</a>
                </li>
                {$private_buttons}
                <li>
                    <a class="bg-danger text-white" href="{$admin_url}/deconnexion">Déconnexion</a>
                </li>
            </ul>
        </li>
HTML;
    }

    /**
     * Retourne les liens réservés qu'aux administrateurs dans la barre de navigation
     * supérieure de la partie adminsitration.
     * 
     * @return string
     */
    private function administratorActions()
    {
        $admin_url = ADMIN_URL;
        return <<<HTML
        <li>
            <a href="{$admin_url}/administrateurs" class="text-primary">Liste</a>
        </li>
        <li>
            <a href="{$admin_url}/administrateurs/create" class="text-primary">Ajouter</a>
        </li>
        <li>
            <a href="{$admin_url}/administrateurs/delete" class="text-primary">Supprimer</a>
        </li>
HTML;
    }

    /**
     * Retourne les liens de la navbar publique.
     * 
     * @return string
     */
    private function publicNavbarLinks()
    {
        $public_url = PUBLIC_URL;
        return <<<HTML
        <ul class="navbar-nav">
            {$this->setLink(PUBLIC_URL . "/a_propos", "A propos")}
            {$this->setLink(PUBLIC_URL . "/rejoindre_la_communaute", "Rejoindre la communauté")}
        </ul>
        <ul class="navbar-nav">
            {$this->setLink(PUBLIC_URL, "Accueil")}
            {$this->setLink(PUBLIC_URL . "/articles", "Articles")}
            {$this->setLink(PUBLIC_URL . "/videos", "Vidéos")}
        </ul>
HTML;
    }

    /**
     * Permet de créer un lien dans la navbar.
     * 
     * @param string $href
     * @param string $caption
     * 
     * @return string
     */
    private function setLink(string $href = null, string $caption = null)
    {
        return <<<HTML
        <li class="nav-item">
            <a class="nav-link" href="{$href}">{$caption}</a>
        </li>
HTML;
    }

}