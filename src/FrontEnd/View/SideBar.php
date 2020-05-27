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

namespace App\FrontEnd\View;

use App\BackEnd\BddManager;
use App\BackEnd\Models\Personnes\Administrateur;
use App\BackEnd\Models\Model;

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
    private $links = [];

    /**
     * Constructeur
     * 
     * @return void
     */
    public function __construct()
    {
        $this->links[] = $this->setLink(PUBLIC_URL, "fas fa-home", "Aller vers le site");
        $this->links[] = $this->setLink(ADMIN_URL, "fas fa-desktop", "Tableau de bord");
        $this->links[] = $this->setLink(ADMIN_URL."/formations", "fas fa-box", "Formations");
        $this->links[] = $this->setLink(ADMIN_URL."/themes", "fas fa-box", "Thèmes");
        $this->links[] = $this->setLink(ADMIN_URL."/etapes", "fas fa-box", "Etapes");
        $this->links[] = $this->setLink(ADMIN_URL."/motivation-plus", "fas fa-tv", "Motivation plus");
        $this->links[] = $this->setLink(ADMIN_URL."/articles", "fas fa-pen-square", "Articles");
        $this->links[] = $this->setLink(ADMIN_URL."/videos", "fas fa-video", "Vidéos");
        $this->links[] = $this->setLink(ADMIN_URL."/livres", "fas fa-book", "Livres");
        $this->links[] = $this->setLink(ADMIN_URL."/ebooks", "fas fa-book", "Ebooks");
        $this->links[] = $this->setLink(ADMIN_URL."/minis-services", "fas fa-shopping-basket", "Minis services");
    }

    /**
     * Barre de gauche sur la partie Administration.
     * 
     * @return string
     */
    public function adminSidebar()
    {
        return <<<HTML
        {$this->smallScreenSideBar()}
        {$this->largeScreenSideBar()}
HTML;
    }

    /**
     * SideBar qui est visible sur les petits écrans.
     * 
     * @return string 
     **/
    public function smallScreenSideBar()
    {
        $admin_user = Administrateur::getByLogin($_SESSION["admin_login"] ?? $_COOKIE["admin_login"]);
        return <<<HTML
        <input class="d-lg-none" type="checkbox" id="check">
        <label class="d-lg-none" for="check">
            <i class="fas fa-bars" id="btn"></i>
            <i class="fas fa-bars" id="cancel"></i>
        </label>
        <div class="sidebar d-lg-none">
            {$this->sidebarBrand(LOGOS_DIR. "/logo_3.png", ADMIN_URL)}
            {$this->sidebarUserAvatar($admin_user->get("avatar_src", $admin_user->get("login")))}
            {$this->searchBar()}
            {$this->links()}
        </div>
HTML;
    }

    /**
     * SideBar qui est visible sur les grands écrans.
     * 
     * @return string 
     **/
    public function largeScreenSideBar()
    {
        $admin_user = Administrateur::getByLogin($_SESSION["admin_login"] ?? $_COOKIE["admin_login"]);
        return <<<HTML
        <input type="checkbox" id="check" checked> 
        <label class="d-lg-none" for="check">
            <i class="fas fa-bars" id="btn"></i>
            <i class="fas fa-bars" id="cancel"></i>
        </label>
        <div class="sidebar d-none d-lg-block">
            {$this->sidebarBrand(LOGOS_DIR. "/logo_3.png", ADMIN_URL)}
            {$this->sidebarUserAvatar($admin_user->get("avatar_src", $admin_user->get("login")))}
            {$this->searchBar()}
            {$this->links()}
        </div>
HTML;
    }

    /**
     * Affiche le logo dans la sidebar.
     *
     * @param string $brand_src        Le lien vers l'image.
     * @param string $click_direction  L'url exécuté lors du click sur le logo.
     * 
     * @return string
     */
    public function sidebarBrand(string $brand_src, string $click_direction = null) : string
    {
        return <<<HTML
        <a class="brand text-center" href="{$click_direction}">
            <img src="{$brand_src}" alt="Attitude efficace" class="brand sidebar-brand my-2">
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
    public function sidebarUserAvatar(string $avatar_src, string $alt_information = null)
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
    private function links()
    {
        $links = null;
        for ($i = 0; $i < count($this->links); $i++) {
            $links .= $this->links[$i];
        }
        
        return <<<HTML
        <div>
            {$links}
        </div>
HTML;
    }

    /**
     * Retourne une ligne dans la sidebar. Prend en paramètre le lien de la sidebar,
     * la classe pour l'icône fontawesome et le texte qui sera affiché dans la
     * sidebar.
     * 
     * @param string $href              Le lien vers lequel le bouton va diriger.
     * @param string $fontawesome_class La classe fontawesome pour l'icône.
     * @param string $text              Le texte qui sera visible dans la sidebar.
     * 
     * @return string
     */
    private function setLink(string $href, string $fontawesome_class, string $text)
    {
        return <<<HTML
        <a class="py-2 px-4" href="{$href}">
            <div class="row">
                <span class="col-2">
                    <i class="{$fontawesome_class} fa-lg"></i>
                </span>
                <span class="col-9">{$text}</span>
            </div>
        </a>
HTML;
    }

}