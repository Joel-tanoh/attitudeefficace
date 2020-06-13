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

use App\BackEnd\Bdd\BddManager;
use App\BackEnd\Models\Entity;
use App\BackEnd\Models\Items\ItemParent;
use App\BackEnd\Models\ItemChild;
use App\View\Card;
use App\View\Snippet;

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
    /**
     * Item de type parent.
     * 
     * @var \App\BackEnd\Models\Items\ItemParent
     */
    private $item;

    /**
     * Constructeur
     * 
     * @param \App\BackEnd\Models\Items\ItemParent
     */
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
    public function read()
    {
        $readItemContentHeader = Snippet::readItemContentHeader($this->item);
        $showData = Snippet::showData($this->item);

        return <<<HTML
        {$readItemContentHeader}
        {$showData}
        {$this->showChildren()}
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
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body pb-0">
                        {$this->showChildrenByCategorie('articles')}
                        {$this->showChildrenByCategorie('videos')}
                        {$this->showChildrenByCategorie('ebooks')}
                        {$this->showChildrenByCategorie('livres')}
                    </div>
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Affiche les parents enfants en fonction de leur catégorie.
     * 
     * @param $childrenType Le type des éléments qu'il faut qu'il faut afficher.
     * 
     * @return string
     */
    private function showChildrenByCategorie(string $childrenType)
    {
        $children = $this->item->getChildren();
        $childrenNumber = count($children);

        if (empty($children)) {
            $childrenList = '<div class="col-12 text-italic text-muted mb-2">Vide</div>';
        } else {
            $childrenList = null;

            foreach ($children as $child) {
                $childrenList .= Card::card(null, $child->getTitle(), $child->getUrl("administrate"));
            }
        }

        $childrenType = ucfirst($childrenType);

        return <<<HTML
        <div>
            <h5>
                {$childrenType}
                <span class="badge bg-primary text-white">{$childrenNumber}</span>
            </h5>
            <div class="row px-2">
                {$childrenList}
            </div>
        </div>
HTML;
    }

    /**
     * Affiche les tous ceux qui ont souscrits à l'item courante.
     * 
     * @return string
     */
    public function showSuscribers()
    {
        $suscribers = null;

        foreach ($this->item->getSuscribers() as $suscriber) {
            $suscribers .= $suscriber->getName();
        }

        return <<<HTML
        <div class="card">
            <div class="card-header">Liste des inscrits</div>
            <div class="card-body">
                {$suscribers}
            </div>
        </div>
HTML;
    }

    /**
     * Montre le nombre de personne ayant souscrit l'item parent courant.
     * 
     * @return string
     */
    public function showSuscribersNumber()
    {
        return <<<HTML
        <div>
            Nombre d'inscrit : {$this->item->getSuscribersNumber()}
        </div>
HTML;
    }

}