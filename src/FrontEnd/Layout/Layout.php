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

namespace App\FrontEnd\Layout;

use App\BackEnd\APIs\Bdd;
use App\BackEnd\Data\Data;

/**
 * Classe qui contient toutes les formes, les cartes, les modules qu'utilisent les
 * pages.
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class Layout
{
    /**
     * Retourne une barre de navigation en fonction de la partie passée en
     * paramètre.
     * 
     * @param string $app_part 
     * 
     * @return Navbar
     */
    public function navbar(string $app_part)
    {
        $navbar = new Navbar();
        if ($app_part == "public") return $navbar->publicNavbar();
        elseif ($app_part == "administration" || $app_part == "admin") return $navbar->adminNavbar();
    }

    /**
     * Retourne la sidebar.
     * 
     * @return Sidebar.
     */
    public function adminSidebar()
    {
        $sidebar = new Sidebar();
        return $sidebar->adminSidebar();
    }
    
    /**
     * Retourne le menu.
     * 
     * @return string
     */
    public function menu()
    {
        global $url;
        $link = "/" . $url[0];
        $active = null;

        return <<<HTML
        <div class="list-group menu mb-3">
            <div class="list-group-item">Menu
                <i class="fa fa-caret-down"></i>
            </div>
            {$this->_menuLink($link, "fas fa-clipboard-list", "Voir la liste")}
            {$this->_menuLink($link."/create", "fas fa-plus", "Ajouter")}
            {$this->_menuLink($link."/delete", "fas fa-trash-alt", "Supprimer", "border-danger bg-danger text-white")}
        </div>
HTML;
    }

    /**
     * Retourne les boutons pour publier, supprimer ou modifier l'instance.
     * 
     * @param $item          L'objet pour lequel on doit afficher le bouton.
     * @param bool $edit_button   
     * @param bool $post_button   
     * @param bool $share_button  
     * @param bool $delete_button 
     * 
     * @return string
     */
    public function manageButtons($item)
    {
        $buttons = '';
        $buttons .= $this->_button($item, "edit_url", "bg-blue mr-1", "far fa-edit fa-lg", "Editer");
        $buttons .= $this->_button($item, "post_url", "bg-success mr-1", "fas fa-reply fa-lg", "Poster");
        $buttons .= $this->_button($item, "share_url", "bg-success mr-1", "fas fa-share fa-lg", "Partager");
        $buttons .= $this->_button($item, "delete_url", "bg-danger mr-1", "far fa-trash-alt fa-lg", "Supprimer");
        
        return <<<HTML
        <div class="mb-4">
            {$buttons}
        </div>
HTML;
    }

    /**
     * Affiche une carte.
     * 
     * @param $item L'objet dont on affiche les données.
     *
     * @return string
     */
    public function listRow($item)
    {
        $title = ucfirst($item->get("title"));
        $children_number = $item->isParent() ? ParentLayout::itemchildrenNumber($item) : null;

        return <<<HTML
        <div class="col-12 mb-4">
            <h5 class="mb-0">{$title}</h5>
            <div class="">
                Créé le {$item->get("day_creation")} |
                Visité {$item->get("views")} fois |
                {$children_number} |
                {$item->get("classement")}
            </div>
            <div>
                <a href="{$item->get('url')}" class="text-success">Détails</a>
                <a href="{$item->get('editer')}" class="text-blue">Editer</a>
                <a href="{$item->get('delete_url')}" class="text-danger">Supprimer</a>
            </div>
        </div>
HTML;
    }

    /**
     * Description
     * 
     * @param $item 
     * 
     * @return string
     */
    public function smallCard($item)
    {
        $title = ucfirst($item->get("title"));
        return <<<HTML
        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <a href="{$item->get('url')}">
                <h5>{$title}</h4>
                <div class="mb-3">
                    <div>Crée le {$item->get("date_creation")}</div>
                    <div>Mis à jour {$item->get("date_modification")}</div>
                    <div>Posté : {$item->get("posted")}</div>
                </div>
            </a>
        </div>
HTML;
    }

    /**
     * Retourne certains caractères de la description.
     * 
     * @param $item 
     * @param int $length Le nombre de caractères qu'on veut.
     * 
     * @return string
     */
    public function getDescriptionExtrait($item, $length)
    {
        $description_length = strlen($item->get("description"));
        return $description_length > $length
            ? substr($item->get("description"), 0, $length) . '...'
            : $item->get("description");
    }

    /**
     * Affiche l'avatar d'un utilisateur.
     * 
     * @param Personne $item L'utilisateur
     * 
     * @return string
     */
    public function showAvatar($item)
    {
        return <<<HTML
        <img src="{$item->get('avatar_src')}" alt="{$item->get('login')}" class="img-fluid"/>
HTML;
    }

    /**
     * Affiche la vidéo de description de l'instance passé en paramètre.
     * 
     * @param $item L'objet dont on affiche les données.
     * 
     * @return string
     */
    public function showVideo($item)
    {
        if (null === $item->get("video_link") ) {
            $result = $this->_noVideo();
        } else {
            $result = $this->_showYoutubeVideo($item);
        }
        return <<<HTML
        <div class="app-card mb-3">
            <div class="app-card-header">Vidéo</div>
            <div class="app-card-body">
                {$result}
            </div>
        </div>
HTML;
    }

    /**
     * Retourne une liste "voir aussi" pour afficher les autres items de la même
     * catégorie que l'item courant en excluant l'item courant.
     * 
     * @param string $exclu Le titre de la méthode qu'on ne veut pas
     *                      afficher. 
     * 
     * @return $array
     */
    public function voirAussi($exclu)
    {
        $table = Data::getTableNameFrom($exclu->get("categorie"));
        $items = Bdd::getAllFromTableWithout($table, $exclu->get("id"), $exclu->get("categorie"));
        $list = '';
        foreach ($items as $i) {
            $item = Data::returnObject($exclu->get("categorie"), $i["code"]);
            $list .= $this->_voirAussiRow($item);
        }
        if (empty($list)) $list = '<div>Vide</div>';

        return <<<HTML
        <div class="col-12 col-md-3 mb-3">
            <div class="card">
                <h6 class="card-header bg-white">Voir aussi</h6>
                <div class="card-body">
                    {$list}
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Affiche les données.
     * 
     * @param $item L'item dont on affiche les données.
     * 
     * @return string
     */
    public function showData($item)
    {
        return <<<HTML
        <div class="row mb-3">
            {$this->_data($item)}
            {$this->_showImage($item)}
        </div> 
        {$this->showVideo($item)}
HTML;
    }

    /**
     * Retourne les d'utilisateurs qui suivent l'item courant.
     * 
     * @param $item 
     * 
     * @return string
     */
    public function learners($item)
    {

    }

    /**
     * Retourne un lien du menu.
     * 
     * @param string $href 
     * @param string $fontawesome_class 
     * @param string $text 
     * @param string $class 
     *
     * @return string
     */
    private function _menuLink(string $href, string $fontawesome_class, string $text, string $class = null)
    {
        $admin_url = ADMIN_URL;
        $active = $href === $_SERVER["REQUEST_URI"] ? "active disabled" : null;
        return <<<HTML
        <a class="{$class} {$active} list-group-item" href="{$admin_url}{$href}">
            <i class="{$fontawesome_class}"></i>
            <span>{$text}</span>
        </a>
HTML;
    }

    /**
     * Permet d'insérer une vidéo hébergée sur Youtube.
     * 
     * @param $item L'objet dont on affiche les données.
     * 
     * @return string
     */
    private function _showYoutubeVideo($item)
    {
        return <<<HTML
        <iframe src="https://www.youtube.com/embed/{$item->get('video_link')}"
            allow="accelerometer; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen class="w-100 h-100 video"></iframe>
HTML;
    }

    /**
     * Retourne l'image de couverture de l'instance passé en paramètre
     * <img src="" alt="" class="">.
     * 
     * @param $item L'objet dont on affiche les données.
     * 
     * @return string
     */
    private function _showImage($item)
    {
        $content = null === $item->get("cover_src") ? $this->_noImage() : $this->_cover($item);

        return <<<HTML
        <div class="col-12 col-md-6">
            <div class="app-card">
                <div class="app-card-header">Image de couverture</div>
                <div class="app-card-body">
                    {$content}
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Retourne qu'il n'y pas d'image.
     * 
     * @return string
     */
    private function _noImage()
    {
        return <<<HTML
        <div>Aucune image.</div>
HTML;
    }
    
    /**
     * Ce bloc est le bloc qui sera affiché si
     * l'instance concernée n'a pas de vidéo de description
     * 
     * @return string
     */
    private function _noVideo()
    {
        return <<<HTML
        <div>Aucune vidéo.</div>
HTML;
    }

    /**
     * Retourne l'image de l'item passé en paramètre.
     * 
     * @param $item 
     * 
     * @return string
     */
    private function _cover($item)
    {
        return <<<HTML
        <img src="{$item->get('cover_src')}" alt="{$item->get('image_name')}" class="img-fluid"/>
HTML;
    }

    /**
     * Retourne un bloc pour afficher les données de l'item courant.
     * 
     * @param $item 
     * 
     * @return string
     */
    private function _data($item)
    {
        return <<<HTML
        <div class="col-12 col-md-6 mb-3">
            <div class="app-card">
                <div class="app-card-header">Données</div>
                <div class="app-card-body">
                    <div class="mb-3">Description : {$item->get("description")}</div>
                    <div>Prix : {$item->get("prix")}</div>
                    <div>Date de création : {$item->get("date_creation")}</div>
                    <div>
                        Date de mise à jour : {$item->get("date_modification")}
                    </div>
                </div>
            </div>
        </div>
HTML;
    }
  
    /**
     * Descr
     *  
     * @param $item La catégorie qu'on veut afficher.
     * 
     * @return string
     */
    private function _voirAussiRow($item)
    {
        $title = ucfirst($item->get("title"));
        $thumbs_src = $item->get("thumbs_src");
        return <<<HTML
        <div class="">
            <div class="d-flex p-2">
                <div class="mr-2" style="width:5rem">
                    <img src="{$thumbs_src}" alt="{$item->get('slug')}" class="img-fluid">
                </div>
                <div>
                    <h5><a href="{$item->get('url')}">{$title}</a></h5>
                    <span class="text-muted float-right text-small">{$item->get("day_creation")}</span>
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Retourne le code pour un bouton dans le manageButtons.
     * 
     * @param $item     L'objet dont il faut afficher les liens dans les boutons.
     * @param string $link     Le lien url à afficher dans le bouton
     * @param string $class    La classe css pour le bouton (la balise <a>)
     * @param string $fa_class La classe fontawesome pour l'icone dans le bouton
     * @param string $text     Le texte à afficher dans le bouton
     * 
     * @return string
     */
    private function _button($item = null, string $link = null, string $class = null, string $fa_class = null, string $text = null)
    {
        return <<<HTML
        <a class="app-btn {$class} pb-2" href="{$item->get($link)}">
            <i class="{$fa_class}"></i>{$text}
        </a>
HTML;
    }

}

