<?php

/**
 * Fichier de classe
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
class ChildView extends \App\View\View
{
    private $item;

    public function __construct($item = null)
    {
        $this->item = $item;
    }

    /**
     * Retourne la page d'affichage d'un item enfant.
     * 
     * @return string
     */
    public function readChild()
    {
        return <<<HTML
        <div class="row">
            <h2 class="col-12 col-md-6">{$this->item->get("title")}</h2>
            {$view->manageButtons($this->item)}
        </div>
        {$view->showData($this->item)}
        {$this->showArticle()}
HTML;
    }

    /**
     * Retourne une carte dans laquelle on a le contenu de l'article.
     * 
     * @return string
     */
    private function showArticle()
    {
        if ($this->item->get("article_content")) {
            return <<<HTML
            <div class="card">
                <div class="card-header bg-white">Contenu de l'article
                </div>
                <div class="card-body">
                    <article>{$this->item->get("article_content")}</article>
                </div>
            </div>
HTML;
        }
    }
    
}