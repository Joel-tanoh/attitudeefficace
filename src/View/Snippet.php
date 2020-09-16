<?php

/**
 * Fichier de classe.
 * 
 * @author Joel <Joel.developpeur@gmail.com>
 */

namespace App\View;

use App\Router;
use App\BackEnd\Models\Entity;
use App\BackEnd\Ecommerce\Order;
use App\BackEnd\Models\Users\Visitor;
use App\View\Models\Items\ItemView;
use App\View\Models\Items\ItemParentView;
use App\View\Models\Items\ItemChildView;

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

        return <<<HTML
        <div class="row mb-2">
            <div class="col-6">
                <div class="d-flex align-items-center">
                    <span class="d-inline-block h6 bg-primary text-white px-2 py-1 mr-2">{$action}</span>
                    <div class="d-flex align-items-center">
                        <h3 class="mr-2">{$title}</h3>
                        <div class="badge bg-primary text-white">{$itemsNumber}</div>
                    </div>
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
        $itemView = new ItemView($item);
        $manageButtons = Snippet::manageButtons($item);

        return <<<HTML
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center bg-white p-2">
                    {$itemView->showTitle()}
                    {$manageButtons}
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
        $buttons = self::editButton($item);
        $buttons .= self::postUnpostButton($item);
        $buttons .= self::deleteButton($item);
        
        return <<<HTML
        <div class="d-flex">
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
//         $newCommandsNbr = Order::getNumber("news"); // $bddManager->count("code", Order::TABLE_NAME, "state", "news");
//         $newCommandsBoxInfo = Card::boxInfo($newCommandsNbr, "Nouvelles commandes", ADMIN_URL . "/mini-services/commands/new", "success");
        
//         $waitingCommandsNbr = Order::getNumber("wait"); // $bddManager->count("code", Order::TABLE_NAME, "state", "en attente");
//         $waitingCommandsBoxInfo = Card::boxInfo($waitingCommandsNbr, "Commandes en attente", ADMIN_URL . "/mini-services/commands/waiting", "warning");
        
//         $allCommandsNbr = Order::getNumber("*"); // $bddManager->count("code", Order::TABLE_NAME);
//         $allCommandsBoxInfo = Card::boxInfo($allCommandsNbr, "Commandes totales", ADMIN_URL . "/mini-services/commands/all", "primary");

//         return <<<HTML
//         <div class="row px-2">
//             {$newCommandsBoxInfo}
//             {$waitingCommandsBoxInfo}
//             {$allCommandsBoxInfo}
//         </div>
// HTML;
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
        <div class="bg-white p-2 mb-3">
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
        $createButton = self::button(Entity::getCategorieUrl(Router::getUrlAsArray()[0], ADMIN_URL)."/create", null, "btn btn-success mr-1", null, "fas fa-plus");

        return <<<HTML
        <div class="d-flex flex-row">
            {$createButton}
        </div>
HTML;
    }

    /**
     * Table qui permet de lister les éléménts.
     * 
     * @return string
     */
    public static function listingTable($items)
    {
        $itemsList = null;
        foreach($items as $item) {
            $itemsList .= self::listingItemsRow($item);
        }

        $actionsRow = self::listingItemsTableActionsRow();

        return <<<HTML
        <form id="myForm" method="post" enctype="multipart/form-data" action="{$_SERVER['REQUEST_URI']}">
            <div class="row">
                <div class="col-12">
                    <table class="table bg-white">
                        <thead>
                            <th><label for="checkAllItems"><input type="checkbox" id="checkAllItems"> Tout cocher</label></th>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Date de création</th>
                        </thead>
                        <tbody>
                            {$actionsRow}
                            {$itemsList}
                            {$actionsRow}
                        </tbody>
                        <tfoot>
                            <th><label for="checkAllItems">Tout cocher</label></th>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Date de création</th>
                        </tfoot>
                    </table>
                </div>
            </div>
        </form>
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
        $itemView = new ItemView($item);

        if ($item->isChild()) {
            $itemChildView = new ItemChildView($item);
            $parent = $itemChildView->showParent();
            $suscriberNumber = null;
        } else {
            $itemParentView = new ItemParentView($item);
            $suscriberNumber = $itemParentView->showSuscribersNumber();
            $parent = null;
        }

        return <<<HTML
        <table class="table bg-white p-3 mb-3">
            {$itemView->showCategorie()}
            {$parent}
            {$itemView->showPrice()}
            {$itemView->showViews()}
            {$itemView->showCreatedAt()}
            {$itemView->showUpdatedAt()}
            {$itemView->showPostedAt()}
            {$suscriberNumber}
            {$itemView->showDescription()}
        </table>

HTML;
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
     * Bouton d'édition d'un item.
     * 
     * @param \App\BackEnd\Models\Items\ItemParent|\App\BackEnd\Models\Items\ItemChild $item L'objet pour lequel on doit afficher le bouton.
     * 
     * @return string
     */
    public static function editButton($item)
    {
        return self::button($item->getUrl("edit"), "Editer", "text-success mr-1", null, "fas fa-edit mr-1", "editButton");
    }

    /**
     * Bouton de post ou de unpost d'un item.
     * 
     * @param \App\BackEnd\Models\Items\ItemParent|\App\BackEnd\Models\Items\ItemChild $item L'objet pour lequel on doit afficher le bouton.
     * 
     * @return string
     */
    public static function postUnpostButton($item)
    {
        if ($item->isPosted()) {
            return self::button($item->getUrl("unpost"), "Ne plus poster", "text-warning mr-1", null, "fas fa-times mr-1", "unpostButton");
        } else {
            return self::button($item->getUrl("post"), "Poster", "text-warning mr-1", null, "fas fa-reply mr-1", "postButton");
        }
    }

    /**
     * Bouton de suppression d'un item.
     * 
     * @param \App\BackEnd\Models\Items\ItemParent|\App\BackEnd\Models\Items\ItemChild $item L'objet pour lequel on doit afficher le bouton.
     * 
     * @return string
     */
    public static function deleteButton($item)
    {
        return self::button($item->getUrl("delete"), "Supprimer", "text-danger", null, "fas fa-trash-alt", "deleteItemButton");
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
        <a class="d-flex align-items-center {$btnClass}" href="{$href}" id="{$id}">
            {$faIconClass} <span class="{$captionClass}">{$caption}</span>
        </a>
HTML;
    }

    /**
     * Ligne qui permet de faire des actions sur le tableau qiui liste les items.
     * 
     * @return string
     */
    public static function listingItemsTableActionsRow()
    {
        return <<<HTML
        <tr>
            <td colspan="4"><input class="btn-sm btn-danger" name="suppression" type="submit" value="Supprimer"></td>
        </tr>
HTML;
    }

    /**
     * Le bloc de code HTML qui permet d'afficher le nombre
     * de personnes en ligne.
     * 
     * @return string
     */
    public static function showVisitorsOnlineNumber()
    {
        $visitorsOnline = Visitor::countVisitorsOnline();

        return <<<HTML
        <div class="small-box text-small text-white bg-success rounded p-2">
            <div class="inner">
                <h3 id="visitorsOnlineNumber">{$visitorsOnline}</h3>
                <p>visiteur(s) en ligne</p>
            </div>
        </div>
HTML;
    }

    // METHODE PRIVEES //

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
            Aucune vidéo de description.
        </div>
HTML;
    }

    /**
     * Retourne une ligne dans le tableau de suppression des éléments.
     * 
     * @param \App\BackEnd\Models\Items\ItemParent|\App\BackEnd\Models\Items\ItemChild $item
     * 
     * @return string
     */
    private static function listingItemsRow($item)
    {
        $itemManageButtons = self::manageButtons($item);

        return <<<HTML
        <tr>
            <td><input type="checkbox" name="codes[]" id="{$item->getSlug()}" value="{$item->getCode()}"></td>
            <td><label for="{$item->getSlug()}"> <a href="{$item->getUrl('administrate')}">{$item->getTitle()}</a></label></td>
            <td>
                <label for="{$item->getSlug()}">{$item->getDescription(50)}</label>
                {$itemManageButtons}
            </td>
            <td><label for="{$item->getSlug()}">{$item->getCreatedAt()}</label></td>
        </tr>
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