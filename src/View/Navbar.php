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

use App\BackEnd\Models\Users\Administrateur;

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
    public static function publicNavbar()
    {
        $navbarBrand = self::navbarBrand(LOGOS_DIR_URL."/logo_3.png", PUBLIC_URL, APP_NAME);
        $publicNavbarLinks = self::publicNavbarLinks();

        return <<<HTML
        <nav class="navbar navbar-expand-md navbar-dark bg-marron mb-3">
            <div class="container">
                {$navbarBrand}
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="navbar-toggler-icon"></i>
                </button>
                <div class="collapse navbar-collapse d-md-flex justify-content-between" id="navbarNav">
                    {$publicNavbarLinks}
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
    public static function adminNavBar()
    {
        $addItemsLinksView = self::addItemsLinksView();
        $getAdminManagementButtonsView = self::getAdminManagementButtonsView();

        return <<<HTML
        <nav class="navbar fixed-top navbar-content bg-white border-bottom w-100 d-flex justify-content-end">
            <ul class="navbar-nav d-flex align-items-center flex-row">
                {$addItemsLinksView}
                {$getAdminManagementButtonsView}
            </ul>
        </nav>
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
    public static function navbarBrand(string $brand_src, string $click_direction = null, string $alt_information = null)
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
    public static function addItemsLinksView()
    {
        $adminUrl = ADMIN_URL;
        return <<<HTML
        <li id="addButton" class="mr-3">
            <a class="add-button-icon">
                <i class="fas fa-plus"></i>
            </a>
            <ul class="add-button-content list-unstyled">
                <li>
                    <a href="{$adminUrl}/formations/create" class="text-primary">Formation</a>
                </li>
                <li>
                    <a href="{$adminUrl}/articles/create" class="text-primary">Article</a>
                </li>
                <li>
                    <a href="{$adminUrl}/videos/create" class="text-primary">Vidéo</a>
                </li>
                <li>
                    <a href="{$adminUrl}/livres/create" class="text-primary">Livre</a>
                </li>
                <li>
                    <a href="{$adminUrl}/ebooks/create" class="text-primary">Ebook</a>
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
    public static function getAdminManagementButtonsView()
    {
        $adminUrl = ADMIN_URL;
        
        $login = $_SESSION["admin_login"] ?? $_COOKIE["admin_login"];

        $adminUser = Administrateur::getByLogin($login);

        $privateButtons = $adminUser->hasAllRights()
            ? self::administrateurReservedActions() : null;

        $navbarUserAvatar = self::navbarUserAvatar($adminUser->getAvatarSrc(), $adminUser->getLogin());

        return <<<HTML
        <li class="btn-administrateur">
            <a id="btnUserIcon" class="nav-link d-flex align-items-center">
                {$navbarUserAvatar}
                <span class="fas fa-caret-down"></span>
            </a>
            <ul id="btnUserContent" class="content border list-unstyled">
                <li>
                    <a href="{$adminUrl}/administrateurs/me" class="text-primary">Mon profil</a>
                </li>
                {$privateButtons}
                <li>
                    <a class="bg-danger text-white" href="{$adminUrl}/deconnexion">Déconnexion</a>
                </li>
            </ul>
        </li>
HTML;
    }

    /**
     * Retourne l'image miniature de l'utilisateur connecté dans la navbar.
     * 
     * @param string $avatar_src
     * @param string $alt_information
     * 
     * @param $user 
     * 
     * @return string
     */
    public static function navbarUserAvatar(string $avatar_src, string $alt_information = null)
    {
        return <<<HTML
        <div>
            <img src="{$avatar_src}" alt="{$alt_information}" class="navbar-user-avatar img-circle shdw mr-2"/>
        </div>
HTML;
    }

    /**
     * Retourne les liens réservés qu'aux administrateurs dans la barre de navigation
     * supérieure de la partie adminsitration.
     * 
     * @return string
     */
    private static function administrateurReservedActions()
    {
        $adminUrl = ADMIN_URL;
        return <<<HTML
        <li>
            <a href="{$adminUrl}/administrateurs" class="text-primary">Liste</a>
        </li>
        <li>
            <a href="{$adminUrl}/administrateurs/create" class="text-primary">Ajouter</a>
        </li>
        <li>
            <a href="{$adminUrl}/administrateurs/delete" class="text-primary">Supprimer</a>
        </li>
HTML;
    }

    /**
     * Retourne les liens de la navbar publique.
     * 
     * @return string
     */
    private static function publicNavbarLinks()
    {
        $public_url = PUBLIC_URL;
        return <<<HTML
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{$public_url}/a_propos">A propos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{$public_url}/communaute">Rejoindre la communauté</a>
            </li>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{$public_url}">Accueil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{$public_url}/articles">Articles</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{$public_url}/videos">Vidéos</a>
            </li>
        </ul>
HTML;
    }

}