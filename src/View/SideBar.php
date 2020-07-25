<?php

/**
 * Description
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "CVS: cvs_id"
 * @link     Link
 */

namespace App\View;

use App\BackEnd\Models\Items\Item;
use App\BackEnd\Utilities\Utility;

/**
 * Permet de gérer les barres de menu sur le coté.
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class SideBar extends View
{
    /**
     * Barre de gauche sur la partie Administration.
     * 
     * @return string
     */
    public static function adminSidebar()
    {
        return self::sidebar();
    }

    /**
     * SideBar qui est visible sur les grands écrans.
     * 
     * @return string 
     **/
    public static function sidebar()
    {
        $sidebarBrand = self::sidebarBrand(LOGOS_DIR_URL. "/logo_3.png", ADMIN_URL);
        $searchBar = Snippet::searchBar();
        $links = self::links();

        return <<<HTML
        <input type="checkbox" id="check">
        <label for="check">
            <i class="fas fa-bars" id="commandSidebar"></i>
        </label>
        <div id="sidebar" class="sidebar">
            {$sidebarBrand}
            {$searchBar}
            {$links}
        </div>
HTML;
    }

    /**
     * SideBar qui est visible sur les grands écrans.
     * 
     * @return string 
     **/
    public static function largeScreenSideBar()
    {
        $sidebarBrand = self::sidebarBrand(LOGOS_DIR_URL. "/logo_3.png", ADMIN_URL);
        $searchBar = Snippet::searchBar();
        $links = self::links();

        return <<<HTML
        <input type="checkbox" id="check" checked>
        <label for="check">
            <i class="fas fa-bars" id="commandSidebar"></i>
        </label>
        <div id="sidebar" class="sidebar d-none d-lg-block">
            {$sidebarBrand}
            {$searchBar}
            {$links}
        </div>
HTML;
    }

    /**
     * SideBar qui est visible sur les petits écrans.
     * 
     * @return string 
     **/
    public static function smallScreenSideBar()
    {
        $sidebarBrand = self::sidebarBrand(LOGOS_DIR_URL. "/logo_3.png", ADMIN_URL);
        $searchBar = Snippet::searchBar();
        $links = self::links();

        return <<<HTML
        <input type="checkbox" id="check" class="d-lg-none">
        <label for="check">
            <i class="fas fa-bars" id="commandSidebar"></i>
        </label>
        <div id="sidebar" class="sidebar d-lg-none">
            {$sidebarBrand}
            {$searchBar}
            {$links}
        </div>
HTML;
    }

    /**
     * Affiche le logo dans la sidebar.
     *
     * @param string $brandSrc Le lien vers l'image.
     * @param string $href     L'url exécuté lors du click sur le logo.
     * 
     * @return string
     */
    public static function sidebarBrand(string $brandSrc, string $href = null) : string
    {
        return <<<HTML
        <a class="brand text-center" href="{$href}">
            <img src="{$brandSrc}" alt="Attitude efficace" class="brand sidebar-brand mb-2">
        </a>
HTML;
    }

    /**
     * Peremet d'afficher l'avatar de l'utilisateur dans la sidebar.
     * 
     * @param string $avatarSrc
     * @param string $altText
     * 
     * @return string
     */
    public static function sidebarUserAvatar(string $avatarSrc, string $altText = null)
    {
        return <<<HTML
        <div class="text-center my-2">
            <img src="{$avatarSrc}" alt="{$altText}" class="sidebar-user-avatar img-circle img-fluid"/>
        </div>
HTML;
    }

    /**
     * Retourne les liens.
     * 
     * @return string
     */
    private static function links()
    {
        $links = self::setLink(null, "fas fa-home", "Aller vers le site");
        $links .= self::setLink("administration", "fas fa-desktop", "Tableau de bord");
        $links .= self::setLink("administration/formations", "fas fa-box", "Formations");
        $links .= self::setLink("administration/themes", "fas fa-box", "Thèmes");
        $links .= self::setLink("administration/etapes", "fas fa-box", "Etapes");
        $links .= self::setLink("administration/motivation-plus", "fas fa-tv", "Motivation plus");
        $links .= self::setLink("administration/articles", "fas fa-pen-square", "Articles");
        $links .= self::setLink("administration/videos", "fas fa-video", "Vidéos");
        $links .= self::setLink("administration/livres", "fas fa-book", "Livres");
        $links .= self::setLink("administration/ebooks", "fas fa-book", "Ebooks");
        $links .= self::setLink("administration/mini-services", "fas fa-shopping-basket", "Mini services");

        return $links;
    }

    /**
     * Retourne une ligne dans la sidebar. Prend en paramètre le lien de la sidebar,
     * la classe pour l'icône fontawesome et le texte qui sera affiché dans la
     * sidebar.
     * 
     * @param string $href                 Le lien vers lequel le bouton va diriger.
     * @param string $fontawesomeIconClass La classe fontawesome pour l'icône.
     * @param string $caption              Le texte qui sera visible dans la sidebar.
     * 
     * @return string
     */
    private static function setLink(string $href = null, string $fontawesomeIconClass = null, string $caption = null)
    {
        $badge = null;

        if ($caption !== "Aller vers le site"
            && $caption !== "Tableau de bord"
        ) {
            $badge = '<span class="badge badge-success">' . Item::countAllItems(Utility::slugify($caption)) . '</span>';
        }

        return <<<HTML
        <a class="py-2 px-4" href="{$href}">
            <div class="row">
                <span class="col-3"><i class="{$fontawesomeIconClass} fa-lg"></i></span>
                <span class="col-8">{$caption}</span>
                {$badge}
            </div>
        </a>
HTML;
    }

}