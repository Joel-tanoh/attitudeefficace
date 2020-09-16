<?php

namespace App\View\Models\Items;

use App\BackEnd\Models\Entity;
use App\View\Models\EntityView;

/**
 * Classe de gestion des vues des items.
 */
class ItemView extends EntityView
{
    protected $item;

    /**
     * @param \App\BackEnd\Models\Items\ItemParent|\App\BackEnd\Models\Items\ItemChild $item
     */
    public function __construct($item)
    {
        $this->item = $item;
    }
    
    /**
     * Affiche le titre de l'item courant.
     * 
     * @return string
     */
    public function showTitle()
    {
        $categorie = ucfirst(Entity::getCategorieFormated($this->item->getCategorie()));

        return <<<HTML
        <div class="d-flex align-items-center">
            <span class="mr-2">{$categorie} &#8250 </span>
            <span class="h3">{$this->item->getTitle()}</span>
        </div>
HTML;
    }

    /**
     * Affiche la description de l'item
     * 
     * @param int $charsNumber Le nom de caractère à afficher.
     * 
     * @return string
     */
    public function showDescription(int $charsNumber = null)
    {
        return <<<HTML
        <tr>
            <td>Description :</td>
            <td>{$this->item->getDescription($charsNumber)}</td>
        </tr>
HTML;
    }

    /**
     * Affiche le nombre de vue de l'item courant
     * 
     * @return string
     */
    public function showViews()
    {
        return <<<HTML
        <tr>
            <td>Vue :</td>
            <td>{$this->item->getViews()}</td>
        </tr>
HTML;
    }

    /**
     * Affiche le prix d'un item
     * 
     * @return string
     */
    public function showPrice()
    {
        $devise = "F CFA";
        $prix = $this->item->getPrice() == 0 ? "Gratuit" : $this->item->getPrice() . $devise;

        return <<<HTML
        <tr>
            <td>Prix :</td>
            <td>{$prix}</td>
        </tr>
HTML;
    }

    /**
     * Affiche la date de création d'un item
     * 
     * @return string
     */
    public function showCreatedAt()
    {
        return <<<HTML
        <tr>
            <td>Date de création :</td>
            <td>{$this->item->getCreatedAt()}</td>
        </tr>
HTML;
    }

    /**
     * Affiche la date de modification (mise à jour)
     * 
     * @return string
     */
    public function showUpdatedAt()
    {
        if (null !== $this->item->getUpdatedAt()) {
            return <<<HTML
            <tr>
                <td>Date de mise à jour :</td>
                <td>{$this->item->getUpdatedAt()}</td>
            </tr>
HTML;
        }
    }

    /**
     * Affiche la de publication (de post)
     * 
     * @return string
     */
    public function showPostedAt()
    {
        if ($this->item->isPosted()) {
            return <<<HTML
            <tr>
                <td>Date de publication :</td>
                <td>{$this->item->getPostedAt()}</td>
            </tr>
HTML;
        }

        return <<<HTML
        <tr>
            <td>Publié :</td>
            <td>non</td>
        </tr>
HTML;
    }

    /**
     * Affiche la catégorie.
     * 
     * @return string
     */
    public function showCategorie()
    {
        $categorie = ucfirst(Entity::getCategorieFormated($this->item->getCategorie()));
        return <<<HTML
        <tr>
            <td>Catégorie :</td>
            <td>{$categorie}</td>
        </tr>
HTML;
    }

}