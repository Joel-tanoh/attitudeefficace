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

namespace App\FrontEnd\View\ModelsView;

use App\BackEnd\APIs\Bdd;
use App\BackEnd\Models\Model;
use App\BackEnd\Models\ItemParent;
use App\BackEnd\Models\ItemChild;

/**
 * Gère tout ce qui concerne l'affichage au niveau des items parents dans l'app.
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class ParentView extends \App\FrontEnd\View\View
{
    /**
     * Retourne la page qui permet d'afficher un parent parent et toutes ses
     * informations.
     * 
     * @param $parent 
     * 
     * @return string
     */
    public function readParent($parent)
    {
        $view = new parent;
        $self_layout = new self;
        return <<<HTML
        <div class="mb-3">
            {$this->crumbs($parent->get("title"))}
            {$view->manageButtons($parent)}
            {$view->showData($parent)}
            {$self_layout->showChildren($parent)}
        </div>
HTML;
    }
    
    /**
     * Affiche les cartes des articles, des vidéos, des ebooks et des livres.
     * 
     * @param ItemParent $parent La catégorie dont il faut afficher les items
     *                         enfants.
     * 
     * @return string
     */
    private function showChildren($parent)
    {
        return <<<HTML
        <div class="app-card">
            <div class="card-body">
                {$this->showChildrenItemsByType($parent, 'articles')}
                {$this->showChildrenItemsByType($parent, 'videos')}
                {$this->showChildrenItemsByType($parent, 'ebooks')}
                {$this->showChildrenItemsByType($parent, 'livres')}
            </div>
        </div>
HTML;
    }

    /**
     * Affiche les items enfants en fonction de leur catégorie.
     * 
     * @param $parent          La catégorie dont il faut afficher les éléments.
     * @param $children_type Le type des éléments qu'il faut qu'il faut afficher.
     * 
     * @return string
     */
    private function showChildrenItemsByType($parent, string $children_type)
    {
        $children = Bdd::getchildrenOf($parent->get("id"), $children_type);
        $children_type = ucfirst($children_type);
        $children_number = count($children);
        $children_list = '';

        if (empty($children)) {
            $children_list = '<div class="col-12 text-italic text-muted">Vide</div>';
        } else {
            foreach ($children as $child) {
                $child = Model::returnObject($children_type, $child["code"]);
                $children_list .= $this->smallCard($child);
            }
        }

        return <<<HTML
        <div class="mb-2">
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
     * Affiche le type des items enfants et le nombre qu'il contient.
     * 
     * @param $parent 
     * 
     * @return string
     */
    public static function itemchildrenNumber($parent)
    {
        $articles = Bdd::getchildrenOf($parent->get("id"), "articles");
        $articles_number = count($articles);

        $videos = Bdd::getchildrenOf($parent->get("id"), "videos");
        $videos_number = count($videos);

        $livres = Bdd::getchildrenOf($parent->get("id"), "livres");
        $livres_number = count($livres);

        $ebooks = Bdd::getchildrenOf($parent->get("id"), "ebooks");
        $ebooks_number = count($ebooks);
        
        return <<<HTML
        Articles ({$articles_number})
        Vidéos ({$videos_number})
        Livres ({$livres_number})
        Ebooks ({$ebooks_number})
HTML;
    }

}