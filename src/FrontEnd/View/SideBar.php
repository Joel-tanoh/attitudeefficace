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

use App\BackEnd\APIs\Bdd;
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
        $this->links[] = $this->setLink("", "fas fa-home", "Tableau de bord");
        $this->links[] = $this->setLink("formations", "fas fa-box", "Formations", true);
        $this->links[] = $this->setLink("themes", "fas fa-tag", "Thèmes", true);
        $this->links[] = $this->setLink("etapes", "fas fa-check", "Etapes", true);
        $this->links[] = $this->setLink("motivation-plus", "fas fa-tv", "Motivation plus");
        $this->links[] = $this->setLink("articles", "fas fa-pen-square", "Articles", true);
        $this->links[] = $this->setLink("videos", "fas fa-video", "Vidéos", true);
        $this->links[] = $this->setLink("livres", "fas fa-book", "Livres", true);
        $this->links[] = $this->setLink("ebooks", "fas fa-book", "Ebooks", true);
        $this->links[] = $this->setLink("minis-services", "fas fa-shopping-basket", "Minis services", true);
        $this->links[] = $this->setLink("parameters", "fas fa-cog", "Paramètres");
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
        <div class="sidebar d-lg-none big-shadow">
            {$this->sidebarBrand()}
            {$this->sidebarUserAvatar($admin_user->get("avatar_src", $admin_user->get("login")))}
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
        <div class="sidebar d-none d-lg-block big-shadow">
            {$this->sidebarBrand()}
            {$this->sidebarUserAvatar($admin_user->get("avatar_src", $admin_user->get("login")))}
            {$this->links()}
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
        $public_url = PUBLIC_URL;
        $links = null;
        for ($i = 0; $i < count($this->links); $i++) {
            $links .= $this->links[$i];
        }
        
        return <<<HTML
        <div>
            <a class="py-2 px-3" href="{$public_url}">
                <div class="row align-items-center">
                    <i class="col-3 icons fas fa-home fa-lg"></i>
                    <span class="col-7 texts p-0">Aller vers le site</span>
                </div>
            </a>
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
     * @param bool   $badge             S'il doit avoir un badge
     * 
     * @return string
     */
    private function setLink(string $href, string $fontawesome_class, string $text, bool $badge = null)
    {
        $badge_box = null;
        $admin_url = ADMIN_URL;

        if ($badge) {
            $count = Bdd::countTableItems(Model::getTableNameFrom($href), "categorie", $href);
            if (!empty($count) || $count == 0) {
                $badge_box = '<span class="float-right badge bg-orange">' . $count . '</span>';
            }
        }
        
        $uri = substr($_SERVER["REQUEST_URI"], 1);
        $uri = explode("/", $uri);
        $active = in_array($href, $uri) ? "active" : null;

        return <<<HTML
        <a class="py-2 px-3 {$active}" href="{$admin_url}/{$href}">
            <div class="row align-items-center">
                <i class="col-3 icons {$fontawesome_class} fa-lg"></i>
                <span class="col-7 texts p-0">{$text}</span>
                {$badge_box}
            </div>
        </a>
HTML;
    }

}