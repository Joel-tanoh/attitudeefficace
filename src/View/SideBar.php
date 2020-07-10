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

use App\BackEnd\Models\Entity;
use App\BackEnd\Models\Items\Item;
use App\BackEnd\Models\Users\Administrateur;
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
        return
            self::smallScreenSideBar() .
            self::largeScreenSideBar();
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
        <input class="d-lg-none" type="checkbox" id="check">
        <label class="d-lg-none" for="check">
            <i class="fas fa-bars" id="btn"></i>
            <i class="fas fa-bars" id="cancel"></i>
        </label>
        <div class="sidebar d-lg-none">
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
        <label class="d-lg-none" for="check">
            <i class="fas fa-bars" id="btn"></i>
            <i class="fas fa-bars" id="cancel"></i>
        </label>
        <div class="sidebar d-none d-lg-block">
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
        <a class="brand" href="{$href}">
            <img src="{$brandSrc}" alt="Attitude efficace" class="brand sidebar-brand m-3">
        </a>
HTML;
    }

    /**
     * Peremet d'afficher l'avatar de l'utilisateur dans la sidebar.
     * 
     * @param string $avatar_src
     * @param string $alt_information
     * 
     * @return string
     */
    public static function sidebarUserAvatar(string $avatar_src, string $alt_information = null)
    {
        return <<<HTML
        <div class="text-center my-2">
            <img src="{$avatar_src}" alt="{$alt_information}" class="sidebar-user-avatar img-circle img-fluid"/>
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
        $links = self::setLink(PUBLIC_URL, "fas fa-home", "Aller vers le site");
        $links .= self::setLink(ADMIN_URL, "fas fa-desktop", "Tableau de bord");
        $links .= self::setLink(ADMIN_URL."/formations", "fas fa-box", "Formations");
        $links .= self::setLink(ADMIN_URL."/themes", "fas fa-box", "Thèmes");
        $links .= self::setLink(ADMIN_URL."/etapes", "fas fa-box", "Etapes");
        $links .= self::setLink(ADMIN_URL."/motivation-plus", "fas fa-tv", "Motivation plus");
        $links .= self::setLink(ADMIN_URL."/articles", "fas fa-pen-square", "Articles");
        $links .= self::setLink(ADMIN_URL."/videos", "fas fa-video", "Vidéos");
        $links .= self::setLink(ADMIN_URL."/livres", "fas fa-book", "Livres");
        $links .= self::setLink(ADMIN_URL."/ebooks", "fas fa-book", "Ebooks");
        $links .= self::setLink(ADMIN_URL."/mini-services", "fas fa-shopping-basket", "Mini services");

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
    private static function setLink(string $href, string $fontawesomeIconClass, string $caption)
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
                <span class="col-2"><i class="{$fontawesomeIconClass} fa-lg"></i></span>
                <span class="col-9">{$caption}</span>
                {$badge}
            </div>
        </a>
HTML;
    }

}