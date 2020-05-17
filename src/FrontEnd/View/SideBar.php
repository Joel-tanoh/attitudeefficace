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
        $this->links[] = $this->setLink(PUBLIC_URL, "fas fa-home", "Aller vers le site");
        $this->links[] = $this->setLink(ADMIN_URL, "fas fa-desktop", "Tableau de bord");
        $this->links[] = $this->setLink(ADMIN_URL."/formations", "fas fa-box", "Formations", true);
        $this->links[] = $this->setLink(ADMIN_URL."/themes", "fas fa-box", "Thèmes", true);
        $this->links[] = $this->setLink(ADMIN_URL."/etapes", "fas fa-box", "Etapes", true);
        $this->links[] = $this->setLink(ADMIN_URL."/motivation-plus", "fas fa-tv", "Motivation plus");
        $this->links[] = $this->setLink(ADMIN_URL."/articles", "fas fa-pen-square", "Articles", true);
        $this->links[] = $this->setLink(ADMIN_URL."/videos", "fas fa-video", "Vidéos", true);
        $this->links[] = $this->setLink(ADMIN_URL."/livres", "fas fa-book", "Livres", true);
        $this->links[] = $this->setLink(ADMIN_URL."/ebooks", "fas fa-book", "Ebooks", true);
        $this->links[] = $this->setLink(ADMIN_URL."/minis-services", "fas fa-shopping-basket", "Minis services", true);
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
        <div class="sidebar d-none d-lg-block">
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
     * @param bool   $badge             S'il doit avoir un badge
     * 
     * @return string
     */
    private function setLink(string $href, string $fontawesome_class, string $text, bool $badge = null)
    {
        $badge_box = null;
        
        if ($badge) {
            $item_type = explode("/", $href)[array_key_last(explode("/", $href))];
            $count = Bdd::countTableItems(Model::getTableNameFrom($item_type), "categorie", $item_type);
            if (!empty($count) || $count == 0) {
                $badge_box = '<span class="float-right badge bg-orange">' . $count . '</span>';
            }
        }
        
        $uri = substr($_SERVER["REQUEST_URI"], 1);
        $uri = explode("/", $uri);
        $active = in_array($href, $uri) ? "active" : null;

        return <<<HTML
        <a class="py-2 px-3 {$active}" href="{$href}">
            <div class="row align-items-center">
                <i class="col-3 icons {$fontawesome_class} fa-lg"></i>
                <span class="col-7 texts p-0">{$text}</span>
                {$badge_box}
            </div>
        </a>
HTML;
    }

}