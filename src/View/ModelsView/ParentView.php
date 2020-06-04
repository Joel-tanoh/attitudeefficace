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

namespace App\View\ModelsView;

use App\BackEnd\BddManager;
use App\BackEnd\Models\Model;
use App\BackEnd\Models\ItemParent;
use App\BackEnd\Models\ItemChild;
use App\View\Card;

/**
 * Gère tout ce qui concerne l'affichage au niveau des parents parents dans l'app.
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class ParentView extends \App\View\View
{
    private $item;

    public function __construct($item = null)
    {
        $this->item = $item;
    }

    /**
     * Retourne la page qui permet d'afficher un parent parent et toutes ses
     * informations.
     * 
     * @return string
     */
    public function readParent()
    {
        return <<<HTML
        <div class="row">
            <h2 class="col-12 col-md-6">{$this->item->get("title")}</h2>
            {$view->manageButtons($this->item)}
        </div>
        {$view->showData($this->item)}
        {$this->showChildren($this->item)}
HTML;
    }
    
    /**
     * Affiche les cartes des articles, des vidéos, des ebooks et des livres.
     * 
     * @return string
     */
    private function showChildren()
    {
        return <<<HTML
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body pb-2">
                        {$this->showChildrenItemsByType($this->item, 'articles')}
                        {$this->showChildrenItemsByType($this->item, 'videos')}
                        {$this->showChildrenItemsByType($this->item, 'ebooks')}
                        {$this->showChildrenItemsByType($this->item, 'livres')}
                    </div>
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Affiche les parents enfants en fonction de leur catégorie.
     * 
     * @param $children_type Le type des éléments qu'il faut qu'il faut afficher.
     * 
     * @return string
     */
    private function showChildrenItemsByType(string $children_type)
    {
        $children = BddManager::getchildrenOf($this->item->get("id"), $children_type);
        $children_type = ucfirst($children_type);
        $children_number = count($children);

        if (empty($children)) {
            $children_list = '<div class="col-12 text-italic text-muted">Vide</div>';
        } else {
            $children_list = null;
            foreach ($children as $child) {
                $child = Model::returnObject($children_type, $child["code"]);
                $children_list .= Card::card(null, $child->get("title"), $child->get("admin_url"));
            }
        }

        return <<<HTML
        <div>
            <h5 class="m-0">
                {$children_type}
                <span class="badge bg-primary text-white">{$children_number}</span>
            </h5>
            <div class="row">
                {$children_list}
            </div>
        </div>
HTML;
    }

    /**
     * Affiche le type des parents enfants et le nombre qu'il contient.
     * 
     * @return string
     */
    public function parentchildrenNumber()
    {
        $articles = BddManager::getchildrenOf($this->item->get("id"), "articles");
        $articles_number = count($articles);

        $videos = BddManager::getchildrenOf($this->item->get("id"), "videos");
        $videos_number = count($videos);

        $livres = BddManager::getchildrenOf($this->item->get("id"), "livres");
        $livres_number = count($livres);

        $ebooks = BddManager::getchildrenOf($this->item->get("id"), "ebooks");
        $ebooks_number = count($ebooks);
        
        return <<<HTML
        Articles ({$articles_number})
        Vidéos ({$videos_number})
        Livres ({$livres_number})
        Ebooks ({$ebooks_number})
HTML;
    }

}