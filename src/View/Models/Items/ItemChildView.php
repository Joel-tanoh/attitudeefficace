<?php

namespace App\View\Models\Items;

use App\BackEnd\Models\Entity;
use App\BackEnd\Models\Items\ItemChild;
use App\Router;
use App\View\Notification;
use App\View\Pages\Template;
use App\View\Snippet;

/**
 * Classe de gestion des vues d'un item enfant.
 */
class ItemChildView extends ItemView
{
    protected $item;

    public function __construct(\App\BackEnd\Models\Items\ItemChild $item)
    {
        $this->item = $item;
    }

    /**
     * Retourne la page d'affichage d'un item enfant.
     * 
     * @return string
     */
    public function readView()
    {
        $readItemContentHeader = Snippet::readItemContentHeader($this);
        $showData = Snippet::showData($this);

        return <<<HTML
        <div id="res"></div>
        {$readItemContentHeader}
        {$showData}
        {$this->showArticle()}
HTML;
    }

    /**
     * La vue qui liste les minis services et affiche le résumé des commandes de minis
     * service.
     * 
     * @param array $items      La liste des items à lister.
     * 
     * @return string Code HTML de la page qui liste les mini services.
     */
    public static function listMiniservices(array $items)
    {
        $title = ucfirst(Entity::getCategorieFormated(Router::getUrlAsArray()[0], "pluriel"));

        $itemsNumber = ItemChild::countAllItems("mini-services");

        $contentHeader = Snippet::listItemsContentHeader($title, "Liste", $itemsNumber);
        $miniServiceCommandsResume = Snippet::miniServicesCommandsResume();

        if (empty($items)) {
            $notification = new Notification();
            $content = '<div>'. $notification->info($notification->noItems("mini-services")) .'</div>';
        } else {
            $content = Template::gridOfCards($items, "px-2");
        }

        return <<<HTML
        {$contentHeader}
        <section class="row mb-3">
            <section class="col-12 col-md-10 mb-3">
                {$content}
            </section>
            <section class="col-12 col-md-2">
                {$miniServiceCommandsResume}
            </section>
        </section>
HTML;
    }

    /**
     * Fiche détails d'un miniservice.
     * 
     * @return string HTML.
     */
    public function miniserviceDetailsCard()
    {
        return <<<HTML

HTML;
    }

    /**
     * Retourne une carte dans laquelle on a le contenu de l'article.
     * 
     * @return string
     */
    private function showArticle()
    {
        if ($this->articleContent) {
            return <<<HTML
            <div class="row mb-3">
                <div class="col-12">
                    <div class="bg-white">
                        <div class="border-bottom p-2">Contenu de l'article</div>
                        <article class="p-2">{$this->item->getArticleContent()}</article>
                    </div>
                </div>
            </div>
HTML;
        }
    }

    /**
     * Affiche le parent de l'item courant.
     * 
     * @return string
     */
    public function showParent()
    {
        if ($this->item->getParent() === null) {
            $result = "Aucun";
        } elseif ($this->item->getParent() === "motivation +") {
            $result = "Motivation +";
        } else {
            $result = '<a href="'. $this->item->getParent()->getUrl("administrate") . '">'. $this->item->getParent()->getTitle() .'</a>';
            $result .= " &#8250 " . ucfirst($this->item->getParent()->getCategorie());
        }

        return <<<HTML
        <tr>
            <td>Parent :</td>
            <td>{$result}</td>
        </tr>
HTML;
    }

}