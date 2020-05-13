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
use App\BackEnd\Data\ItemParent;
use App\BackEnd\Data\ItemChild;

/**
 * Gère tout ce qui concerne l'affichage au niveau des catégories
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class ParentLayout extends Layout
{
    /**
     * Retourne la page qui permet d'afficher un item parent et toutes ses
     * informations.
     * 
     * @param $item 
     * 
     * @return string
     */
    public static function read($item)
    {
        $layout = new parent;
        $self_layout = new self;
        return <<<HTML
        <div class="mb-3">
            {$layout->manageButtons($item, true, true, true, true)}
            {$layout->showData($item)}
            {$self_layout->_showchildren($item)}
        </div>
HTML;
    }
    
    /**
     * Affiche les cartes des articles, des vidéos, des ebooks et des livres.
     * 
     * @param ItemParent $item La catégorie dont il faut afficher les items
     *                         enfants.
     * 
     * @return string
     */
    private function _showchildren($item)
    {
        return <<<HTML
        <div class="app-card">
            <div class="app-card-body">
                {$this->_showchildrenItems($item, 'articles')}
                {$this->_showchildrenItems($item, 'videos')}
                {$this->_showchildrenItems($item, 'ebooks')}
                {$this->_showchildrenItems($item, 'livres')}
            </div>
        </div>
HTML;
    }

    /**
     * Affiche les items enfants en fonction de leur catégorie.
     * 
     * @param $item          La catégorie dont il faut afficher les éléments.
     * @param $children_type Le type des éléments qu'il faut qu'il faut afficher.
     * 
     * @return string
     */
    private function _showchildrenItems($item, string $children_type)
    {
        $children = Bdd::getchildrenOf($item->get("id"), $children_type);
        $children_type = ucfirst($children_type);
        $children_number = count($children);
        $children_list = '';

        if (empty($children)) {
            $children_list = '<div class="col-12 text-italic">Vide</div>';
        } else {
            foreach ($children as $e) {
                $child = Data::returnObject($children_type, $e["code"]);
                $children_list .= $this->smallCard($child);
            }
        }

        return <<<HTML
        <div class="mb-3">
            <h5>
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
     * @param $item 
     * 
     * @return string
     */
    public static function itemchildrenNumber($item)
    {
        $articles = Bdd::getchildrenOf($item->get("id"), "articles");
        $articles_number = count($articles);

        $videos = Bdd::getchildrenOf($item->get("id"), "videos");
        $videos_number = count($videos);

        $livres = Bdd::getchildrenOf($item->get("id"), "livres");
        $livres_number = count($livres);

        $ebooks = Bdd::getchildrenOf($item->get("id"), "ebooks");
        $ebooks_number = count($ebooks);
        
        return <<<HTML
        Articles ({$articles_number})
        Vidéos ({$videos_number})
        Livres ({$livres_number})
        Ebooks ({$ebooks_number})
HTML;
    }

}