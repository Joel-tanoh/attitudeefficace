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

namespace App\FrontEnd\View;

use App\BackEnd\Models\Personnes\Administrateur;

/**
 * Perlet de gérer tout ce qui concerne la barre de navigation supérieure.
 * 
 * @category Category
 * @package  App\FrontEnd\
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
        return <<<HTML
        <div class="navbar fixed-top bg-marron">
            <div class="container navbar-content w-100 d-flex justify-content-between">
                {$this->navbarBrand()}
            </div>
        </div>
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
        <div class="navbar fixed-top bg-white border-bottom">
            <div class="navbar-content w-100 d-flex justify-content-end">
                <ul class="navbar-nav d-flex align-items-center flex-row">
                    {$this->addItemsLinksView()}
                    {$this->getAdminManagementButtonsView()}
                </ul>
            </div>
        </div>
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
        $private_buttons = $admin_user->get("type") == "administrateur" ? $this->adminReservedAcrions() : null;

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
    private function adminReservedAcrions()
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

}