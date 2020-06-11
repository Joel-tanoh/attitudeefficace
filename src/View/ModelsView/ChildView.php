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

use App\View\Snippet;

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
        $readItemContentHeader = Snippet::readItemContentHeader($this->item);
        $data = Snippet::showData($this->item);

        return <<<HTML
        {$readItemContentHeader}
        {$data}
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
        if ($this->item->getArticleContent()) {
            return <<<HTML
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white">Contenu de l'article</div>
                        <div class="card-body">
                            <article>{$this->item->getArticleContent()}</article>
                        </div>
                    </div>
                </div>
            </div>
HTML;
        }
    }
    
}