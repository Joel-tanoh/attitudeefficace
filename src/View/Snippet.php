<?php

/**
 * Fichier de classe.
 * 
 * @author Joel <Joel.developpeur@gmail.com>
 */

namespace App\View;

use App\BackEnd\BddManager;
use App\BackEnd\Models\Model;
use App\Router;

/**
 * Gère les fragments de code.
 * 
 * @author Joel <Joel.developpeur@gmail.com>
 */
class Snippet extends View
{
    /**
     * Retourne un tableau sur la page de suppression d'items.
     * 
     * @param mixed $items
     * @param string $categorie
     * 
     * @return string
     */
    public static function deleteItemsTable($items, string $categorie)
    {
        $tableRows = '';
        $submitButton = Form::submitButton("suppression", "Supprimer");

        foreach($items as $item) {
            $item = Model::returnObject($categorie, $item["code"]);
            $tableRows .= self::deleteItemsTableRow($item);
        }

        return <<<HTML
        <form id="myForm" method="post" enctype="multipart/form-data" action="{$_SERVER['REQUEST_URI']}">
            <table>
                <thead>
                    <th><input type="checkbox" id="checkAllItemsForDelete"></th>
                    <th>Titre</th>
                </thead>
                {$tableRows}
            </table>
            {$submitButton}
        </form>
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
    public static function manageButtons($item)
    {
        $buttons = self::button($item->get("edit_url"), "Editer", "btn-primary mr-1", "far fa-edit fa-lg");
        $buttons .= self::button($item->get("post_url"), "Poster", "btn-success mr-1", "fas fa-reply fa-lg");
        $buttons .= self::button($item->get("share_url"), "Partager", "btn-success mr-1", "fas fa-share fa-lg");
        $buttons .= self::button($item->get("delete_url"), "Supprimer", "btn-danger mr-1", "far fa-trash-alt fa-lg");
        
        return <<<HTML
        <div class="float-sm-right">
            {$buttons}
        </div>
HTML;
    }

    /**
     * Affiche l'avatar d'un utilisateur.
     * 
     * @param string $avatar_src
     * @param string $alt_information
     * 
     * @return string
     */
    public static function showAvatar(string $avatar_src, string $alt_information = null)
    {
        return <<<HTML
        <div>
            <img src="{$avatar_src}" alt="{$alt_information}" class="user-avatar img-fluid"/>
        </div>
HTML;
    }

    /**
     * Affiche la vidéo de description de l'instance passé en paramètre.
     * 
     * @param string $video_link L'identifiant de la vidéos sur Youtube.
     * 
     * @return string
     */
    public static function showVideo(string $video_link = null)
    {
        if (null !== $video_link) {
            $result = self::youtubeIframe($video_link);
        } else {
            $result = self::noVideoBox();
        }

        return <<<HTML
        <div class="row mb-3">
            <div class="col-12">
                {$result}
            </div>
        </div>
HTML;
    }

    /**
     * Retourne un listItemsContentHeader.
     * 
     * @param string $title
     * @param mixed  $number_of_items
     * 
     * @return string
     */
    public static function listItemsContentHeader(string $title = null, $number_of_items = null)
    {
        $title = ucfirst($title);
        $contextMenu = self::contextMenu();

        if ($number_of_items) {
            $number_of_items = '<div class="badge bg-primary text-white px-2 py-1 rounded">' . $number_of_items . '</div>';
        } else {
            $number_of_items = null;
        }
        
        return <<<HTML
        <div class="row mb-3">
            <div class="col-6">
                <div class="d-flex align-items-center">
                    <h3 class="mr-2">{$title}</h3>
                    {$number_of_items}
                </div>
            </div>
            <div class="col-6">
                <div class="float-right">
                    {$contextMenu}
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Retourne l'entête sur la page de lecture d'un item.
     * 
     * @param $item
     * 
     * @return string
     */
    public static function readItemContentHeader($item)
    {
        $manageButtons = Snippet::manageButtons($item);
        return <<<HTML
        <div class="row mb-3">
            <h2 class="col-12 col-md-5">{$item->get("title")}</h2>
            <div class="col-12 col-sm-7">
                {$manageButtons}
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
    public static function voirAussi($exclu)
    {
        $table = Model::getTableNameFrom($exclu->get("categorie"));
        $items = BddManager::getAllFromTableWithout($table, $exclu->get("id"), $exclu->get("categorie"));
        $list = '';
        foreach ($items as $item) {
            $item = Model::returnObject($exclu->get("categorie"), $item["code"]);
            $list .= self::voirAussiRow($item);
        }
        if (empty($list)) $list = '<div>Vide</div>';

        return <<<HTML
        <div class="col-md-3">
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
    public static function showData($item)
    {
        $videoBox = self::showVideo($item->get("video_link"));
        $bddData = self::data($item);
        $thumbs = self::showThumbs($item);

        return <<<HTML
        <div class="row mb-3">
            <div class="col-12 col-md-6">
                {$bddData}
            </div>
            <div class="col-12 col-md-6">
                {$thumbs}
            </div>
        </div>
        {$videoBox}
HTML;
    }

    /**
     * Retourne les boutons pour ajouter un nouvel élément ou supprimer des éléments
     * en fonction de la catégorie.
     * 
     * @return string
     */
    public static function contextMenu()
    {
        $createButton = self::button(Model::getCategorieUrl(Router::getUrlAsArray()[0], ADMIN_URL)."/create", null, "btn-success",  "fas fa-plus");
        $deleteItemsButton = self::button(Model::getCategorieUrl(Router::getUrlAsArray()[0], ADMIN_URL)."/delete", null, "btn-danger", "fas fa-trash-alt");

        return <<<HTML
        {$createButton}
        {$deleteItemsButton}
HTML;
    }

    /**
     * Retourne une vue pour une barre de recherche.
     * 
     * @return string
     */
    public static function searchBar()
    {
        $searchBar = Form::input("search", "recherche", "rechercheInput", null, "Rechercher", "app-search-bar-input p-1");

        return <<<HTML
        <div class="app-search-bar bg-white mx-3 my-2 pl-2">
            <form action="" method="post" class="d-flex justify-content-between">
                {$searchBar}
                <button type="submit" class="app-search-bar-button">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
HTML;
    }

    /**
     * Retourne le vue pour lire la vidéo issue de Youtube.
     * 
     * @param string $video_link
     * 
     * @return string
     */
    public static function youtubeIframe(string $video_link)
    {
        return <<<HTML
        <iframe src="https://www.youtube.com/embed/{$video_link}" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen class="w-100 video" style="height:30rem"></iframe>
HTML;
    }

    /**
     * Retourne une vue qui affiche l'ensemble des données principales
     * pour l'item passé en paramètre.
     * 
     * @param $item 
     * 
     * @return string
     */
    public static function data($item)
    {
        return <<<HTML
        <div class="card">
            <div class="card-header">Données</div>
            <div class="card-body">
                <div>Description : {$item->get("description")}</div>
                <div>Prix : {$item->get("prix")}</div>
                <div>Date de création : {$item->get("date_creation")}</div>
                <div>Date de mise à jour : {$item->get("date_modification")}</div>
            </div>
        </div>
HTML;
    }

    /**
     * Retourne une checkbox pour activer les variables cookies. Si l'utilisateur
     * coche cette checkbox, les cookies sont activées.
     * 
     * @return string
     */
    public static function activateSessionButton()
    {
        return <<<HTML
        <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox" name="activate_cookie" id="customCheckbox1" value="oui">
            <label for="customCheckbox1" class="custom-control-label">Se souvenir de moi</label>
        </div>
HTML;
    }

    /**
     * Retourne une vue pour permmetre à l'utilisateur de se connecter
     * par les réseaux sociaux.
     * 
     * @return string
     */
    public static function connectBySocialsNetworks()
    {
        $googleFormButton = self::connexionFormGoogleButton();
        $facebookFormButton = self::connexionFormFacebookButton();

        return <<<HTML
        <div class="text-center text-muted h5">- OU -</div>
        <div>
            <div>{$facebookFormButton}</div>
            <div>{$googleFormButton}</div>
        </div>
HTML;
    }

    /**
     * Retourne un bouton qui dirige vers la page pour se connecter grâce
     * à Facebook.
     * 
     * @return string Code du bouton.
     */
    public static function connexionFormFacebookButton()
    {
        return <<<HTML
        <a href="" class="d-block text-center bg-facebook text-white rounded p-2">
            Se connecter avec Facebook
        </a>
HTML;
    }

    /**
     * Retourne un bouton qui dirige vers la page pour se connecter grâce
     * à Google.
     * 
     * @return string Code du bouton.
     */
    public static function connexionFormGoogleButton()
    {
        return <<<HTML
        <a href="" class="d-block text-center bg-danger text-white rounded p-2">
            Se connecter avec Google
        </a>
HTML;
    }

    /**
     * Retourne un lien du contextMenu.
     * 
     * @param string $href 
     * @param string $text 
     * @param string $btn_class 
     * @param string $fa_icon_class 
     *
     * @return string
     */
    public static function button(string $href, string $text = null, string $btn_class = null, string $fa_icon_class = null)
    {
        if (null !== $fa_icon_class) {
            $fa_icon_class = '<i class="' . $fa_icon_class. '"></i>';
        }

        return <<<HTML
        <a class="btn {$btn_class}" href="{$href}">
            {$fa_icon_class}
            <span>{$text}</span>
        </a>
HTML;
    }

    /**
     * Retourne l'image de l'item passé en paramètre.
     * 
     * @param $item 
     * 
     * @return string
     */
    public static function showThumbs($item)
    {
        $content = null !== $item->get("thumbs_src") ? self::thumbs($item) : self::noThumbsBox();

        return $content;
    }

    /**
     * Retourne l'image de couverture de l'item passé en paramètre.
     * 
     * @param mixed $item
     * 
     * @return string
     */
    public static function thumbs($item)
    {
        return <<<HTML
        <div class="card">
            <div class="card-header">Image de couverture</div>
            <img src="{$item->get('thumbs_src')}" alt="{$item->get('image_name')}" class="img-fluid"/>
        </div>
HTML;
    }

    /**
     * Retourne qu'il n'y pas d'image.
     * 
     * @return string
     */
    public static function noThumbsBox()
    {
        return <<<HTML
        <div class="card">
            <div class="card-body">
                Aucune image de couverture.
            </div>
        </div>
HTML;
    }
   
    /**
     * Ce bloc est le bloc qui sera affiché si
     * l'instance concernée n'a pas de vidéo de description
     * 
     * @return string
     */
    public static function noVideoBox()
    {
        return <<<HTML
        <div class="card">
            <div class="card-body">
                Aucune vidéo.
            </div>
        </div>
HTML;
    }

    /**
     * Retourne une vue pour afficher les autres items de même type
     * que celui passé en paramètre.
     *  
     * @param $item La catégorie qu'on veut afficher.
     * 
     * @return string
     */
    public static function voirAussiRow($item)
    {
        $title = ucfirst($item->get("title"));
        $thumbs_src = $item->get("thumbs_src");

        return <<<HTML
        <div class="d-flex p-2">
            <div class="mr-2" style="width:5rem">
                <img src="{$thumbs_src}" alt="{$item->get('slug')}" class="img-fluid">
            </div>
            <div>
                <h5><a href="{$item->get('public_url')}">{$title}</a></h5>
                <span class="text-muted float-right text-small">{$item->get("day_creation")}</span>
            </div>
        </div>
HTML;
    }

    /**
     * Retourne une ligne dans le tableau de suppression des éléments.
     * 
     * @param $item
     * 
     * @return string
     */
    public static function deleteItemsTableRow($item)
    {
        return <<<HTML
        <tr>
            <td><input type="checkbox" name="codes[]" id="{$item->get('slug')}" value="{$item->get('code')}"></td>
            <td><label for="{$item->get('slug')}">{$item->get("title")}</label></td>
        </tr>
HTML;
    }

}