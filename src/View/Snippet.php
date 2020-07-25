<?php

/**
 * Fichier de classe.
 * 
 * @author Joel <Joel.developpeur@gmail.com>
 */

namespace App\View;

use App\Router;
use App\BackEnd\Models\Entity;
use App\BackEnd\Models\MiniserviceOrder;

/**
 * Gère les fragments de code.
 * 
 * @author Joel <Joel.developpeur@gmail.com>
 */
class Snippet extends View
{

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
     * @param string $youtubeVideoLink L'identifiant de la vidéos sur Youtube.
     * 
     * @return string
     */
    public static function showVideo(string $youtubeVideoLink = null)
    {
        if (null == $youtubeVideoLink || $youtubeVideoLink == "") {
            $result = self::noVideoBox();
        } else {
            $result = self::youtubeIframe($youtubeVideoLink);
        }

        return <<<HTML
        <div class="bg-white">
            <div class="border-bottom p-2">Vidéo descriptive</div>
            <div class="p-2">{$result}</div>
        </div>
HTML;
    }

    /**
     * Retourne un listItemsContentHeader.
     * 
     * @param string $title
     * @param string $action 
     * @param mixed  $itemsNumber
     * 
     * @return string
     */
    public static function listItemsContentHeader(string $title = null, string $action = null, $itemsNumber = null)
    {
        $title = ucfirst($title);
        $contextMenu = self::contextMenu();

        $itemsNumber = '<div class="badge bg-primary text-white px-2 py-1 rounded">' . $itemsNumber . '</div>';

        return <<<HTML
        <div class="row mb-3">
            <div class="col-6">
                <div>
                    <div class="d-flex align-items-center">
                        <h3 class="mr-2">{$title}</h3>
                        {$itemsNumber}
                    </div>
                    <span class="d-inline-block h6 bg-primary text-white rounded px-2 py-1">{$action}</span>
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
     * @param \App\BackEnd\Models\Items\ItemParent|\App\BackEnd\Models\Items\ItemChild $item
     * 
     * @return string
     */
    public static function readItemContentHeader($item)
    {
        $manageButtons = Snippet::manageButtons($item);

        return <<<HTML
        <div class="row mb-3">
            <div class="col-12">
            <div class="bg-white p-2">
                <div class="d-flex justify-content-between align-items-center">
                    {$item->showTitle()}
                    {$manageButtons}
                </div>
            </div>
            </div>
        </div>
HTML;
    }

    /**
     * Retourne les boutons pour publier, supprimer ou modifier l'instance.
     * 
     * @param \App\BackEnd\Models\Items\ItemParent|\App\BackEnd\Models\Items\ItemChild $item L'objet pour lequel on doit afficher le bouton.
     * 
     * @return string
     */
    public static function manageButtons($item)
    {
        $buttons = self::button($item->getUrl("edit"), "Editer", null, null, "fas fa-edit", "editButton");

        if ($item->isPosted()) {
            $buttons .= self::button($item->getUrl("unpost"), "Ne plus poster", "text-success", null, "fas fa-times", "unpostButton");
        } else {
            $buttons .= self::button($item->getUrl("post"), "Poster", null, "text-success", "fas fa-reply", "postButton");
        }

        $buttons .= self::button($item->getUrl("delete"), "Supprimer", null, "text-danger", "fas fa-trash-alt", "deleteItemButton");
        
        return <<<HTML
        <div class="float-sm-right">
            {$buttons}
        </div>
HTML;
    }

    /**
     * Affiche le résumé des commandes de minis services. Les nouvelles commandes,
     * les commandes en attentes et toutes les commandes.
     * 
     * @return string
     */
    public static function miniServicesCommandsResume()
    {
        $bddManager = Entity::bddManager();

        $newCommandsNbr = $bddManager->count("id", MiniserviceOrder::TABLE_NAME, "state", "news");
        $newCommandsBoxInfo = Card::boxInfo($newCommandsNbr, "Nouvelles commandes", ADMIN_URL . "/mini-services/commands/new", "success");
        
        $waitingCommandsNbr = $bddManager->count("id", MiniserviceOrder::TABLE_NAME, "state", "en attente");
        $waitingCommandsBoxInfo = Card::boxInfo($waitingCommandsNbr, "Commandes en attente", ADMIN_URL . "/mini-services/commands/waiting", "warning");
        
        $allCommandsNbr = $bddManager->count("id", MiniserviceOrder::TABLE_NAME);
        $allCommandsBoxInfo = Card::boxInfo($allCommandsNbr, "Commandes totales", ADMIN_URL . "/mini-services/commands/all", "primary");

        return <<<HTML
        <div class="row px-2">
            {$newCommandsBoxInfo}
            {$waitingCommandsBoxInfo}
            {$allCommandsBoxInfo}
        </div>
HTML;
    }

    /**
     * Affiche les données.
     * 
     * @param \App\BackEnd\Models\Items\ItemParent|\App\BackEnd\Models\Items\ItemChild $item L'item dont on affiche les données.
     * 
     * @return string
     */
    public static function showData($item)
    {
        $showBddData = self::showBddData($item);
        $showThumbs = self::showThumbs($item);
        $videoBox = self::showVideo($item->getVideoLink("youtube"));

        return <<<HTML
        <div class="row mb-3">
            <div class="col-12 col-md-6">
                {$showBddData}
            </div>
            <div class="col-12 col-md-6">
                {$showThumbs}
                {$videoBox}
            </div>
        </div>
HTML;
    }

    /**
     * Retourne l'image de l'item passé en paramètre.
     * 
     * @param \App\BackEnd\Models\Items\ItemChild|\App\BackEnd\Models\Items\ItemParent $item 
     * 
     * @return string
     */
    public static function showThumbs($item)
    {
        $content = null !== $item->getThumbsSrc() ? self::thumbs($item) : self::noThumbsBox();

        return <<<HTML
        <div class="mb-3">
            {$content}
        </div>
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
        $createButton = self::button(Entity::getCategorieUrl(Router::getUrlAsArray()[0], ADMIN_URL)."/create", null, "btn btn-success", null, "fas fa-plus");
        $deleteItemsButton = self::button(Entity::getCategorieUrl(Router::getUrlAsArray()[0], ADMIN_URL)."/delete", null, "btn btn-danger", null, "fas fa-trash-alt");

        return <<<HTML
        {$createButton}
        {$deleteItemsButton}
HTML;
    }

    /**
     * Retourne une vue qui affiche l'ensemble des données principales
     * pour l'item passé en paramètre.
     * 
     * @param \App\BackEnd\Models\Items\ItemParent|\App\BackEnd\Models\Items\ItemChild $item
     * 
     * @return string
     */
    public static function showBddData($item)
    {
        $suscriberNumber = $item->isParent() ? $item->showSuscribersNumber() : null;
        $parent = $item->isChild() ? $item->showParent() : null;

        return <<<HTML
        <table class="table bg-white p-3 mb-3">
            {$item->showCategorie()}
            {$parent}
            {$item->showPrice()}
            {$item->showViews()}
            {$item->showCreatedAt()}
            {$item->showUpdatedAt()}
            {$item->showPostedAt()}
            {$suscriberNumber}
            {$item->showDescription()}
        </table>

HTML;
    }

    /**
     * Table qui permet de lister les éléménts.
     * 
     * @return string
     */
    public static function listingTable($items)
    {
        return <<<HTML
        <form id="myForm" method="post" enctype="multipart/form-data" action="{$_SERVER['REQUEST_URI']}">
            <table class="table mb-3">
                <thead>
                    <th><input type="checkbox" id="checkAllItemsForDelete"></th><th>Titre</th>
                </thead>
            </table>
        </form>
HTML;
    }

    /**
     * Une ligne du tableau qui liste les éléments.
     * 
     * @return string
     */
    public function tableRow()
    {

    }

    /**
     * Retourne un tableau sur la page de suppression d'items dans lequel les éléments sont
     * affichés par ligne afin de les supprimer.
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
            $item = Entity::createObjectByCategorieAndCode($categorie, $item["code"]);
            $tableRows .= self::deleteItemsTableRow($item);
        }

        return <<<HTML
        <form id="myForm" method="post" enctype="multipart/form-data" action="{$_SERVER['REQUEST_URI']}">
            <table class="table mb-3">
                <thead>
                    <th><input type="checkbox" id="checkAllItemsForDelete"></th><th>Titre</th>
                </thead>
                {$tableRows}
            </table>
            {$submitButton}
        </form>
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
        <div class="app-search-bar bg-white mx-3 mt-3 mb-2 pl-2">
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
     * Retourne un lien du contextMenu.
     * 
     * @param string $href 
     * @param string $caption 
     * @param string $btnClass 
     * @param string $captionClass
     * @param string $faIconClass
     *
     * @return string
     */
    public static function button(string $href, string $caption = null, string $btnClass = null, string $captionClass = null, string $faIconClass = null, string $id = null)
    {
        if (null !== $faIconClass) {
            $faIconClass = '<i class="' . $faIconClass. '"></i>';
        }

        return <<<HTML
        <a class="{$btnClass}" href="{$href}" id="{$id}">
            {$faIconClass}
            <span class="{$captionClass}">{$caption}</span>
        </a>
HTML;
    }

    /**
     * Retourne le vue pour lire la vidéo issue de Youtube.
     * 
     * @param string $youtubeVideoLink
     * 
     * @return string
     */
    private static function youtubeIframe(string $youtubeVideoLink)
    {
        return <<<HTML
        <iframe src="https://www.youtube.com/embed/{$youtubeVideoLink}" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen class="w-100 video" style="height:20rem"></iframe>
HTML;
    }

    /**
     * Retourne un bouton qui dirige vers la page pour se connecter grâce
     * à Facebook.
     * 
     * @return string Code du bouton.
     */
    private static function connexionFormFacebookButton()
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
    private static function connexionFormGoogleButton()
    {
        return <<<HTML
        <a href="" class="d-block text-center bg-danger text-white rounded p-2">
            Se connecter avec Google
        </a>
HTML;
    }

    /**
     * Retourne l'image de couverture de l'item passé en paramètre.
     * 
     * @param \App\BackEnd\Models\Items\Item $item
     * 
     * @return string
     */
    private static function thumbs(\App\BackEnd\Models\Items\Item $item)
    {
        return <<<HTML
        <div>
            <div class="bg-white p-2">Image de couverture</div>
            <div>
                <img src="{$item->getOriginalThumbsSrc()}" alt="{$item->getTitle()}" class="img-fluid"/>
            </div>
        </div>          
HTML;
    }

    /**
     * Retourne qu'il n'y pas d'image.
     * 
     * @return string
     */
    private static function noThumbsBox()
    {
        return <<<HTML
        <div>
            Aucune image de couverture.
        </div>
HTML;
    }
   
    /**
     * Ce bloc est le bloc qui sera affiché si
     * l'instance concernée n'a pas de vidéo de description
     * 
     * @return string
     */
    private static function noVideoBox()
    {
        return <<<HTML
        <div>
            Aucune vidéo.
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
    private static function deleteItemsTableRow($item)
    {
        return <<<HTML
        <tr>
            <td><input type="checkbox" name="codes[]" id="{$item->getSlug()}" value="{$item->getCode()}"></td>
            <td><label for="{$item->getSlug()}">{$item->getTitle()}</label></td>
        </tr>
HTML;
    }

}