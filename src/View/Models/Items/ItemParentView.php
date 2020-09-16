<?php

namespace App\View\Models\Items;

use App\View\Card;
use App\View\Snippet;

/**
 * Classe de gestion des vues d'un item parent.
 */
class ItemParentView extends ItemView
{
    protected $item;

    public function __construct(\App\BackEnd\Models\Items\ItemParent $item)
    {
        $this->item = $item;
    }
    
    /**
     * Retourne la page qui permet d'afficher un parent parent et toutes ses
     * informations.
     * 
     * @return string
     */
    public function readView()
    {
        $contentHeader = Snippet::readItemContentHeader($this->item);
        $showData = Snippet::showData($this->item);

        return <<<HTML
        {$contentHeader}
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
                <div class="bg-white p-3">
                    {$this->showChildrenByCategorie('articles')}
                    {$this->showChildrenByCategorie('videos')}
                    {$this->showChildrenByCategorie('ebooks')}
                    {$this->showChildrenByCategorie('livres')}
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Affiche les parents enfants en fonction de leur catégorie.
     * 
     * @param $childrenCategorie La catégorie des items enfants qu'il faut qu'il faut afficher.
     * 
     * @return string
     */
    private function showChildrenByCategorie(string $childrenCategorie)
    {
        $children = $this->item->getChildren($childrenCategorie);
        $childrenNumber = count($children);

        if (empty($children)) {
            $childrenList = '<div class="col-12 text-italic text-muted mb-2">Vide</div>';
        } else {
            $childrenList = null;

            foreach ($children as $child) {
                $childrenList .= Card::card(null, $child->getTitle(), $child->getUrl("administrate"));
            }
        }

        $childrenCategorie = ucfirst($childrenCategorie);

        return <<<HTML
        <div>
            <h6>
                {$childrenCategorie}
                <span class="badge bg-primary text-white">{$childrenNumber}</span>
            </h6>
            <div class="row">
                <div class="col-12">
                    {$childrenList}
                </div>
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
        <tr>
            <td>Nombre d'inscrit :</td>
            <td>{$this->item->getSuscribersNumber()}</td>
        </tr>
HTML;
    }

}