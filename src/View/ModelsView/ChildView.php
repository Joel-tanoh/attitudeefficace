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
    /**
     * Retourne la page d'affichage d'un item enfant.
     * 
     * @param $item 
     * 
     * @return string
     */
    public static function readChild($item)
    {
        $view = new parent;
        $self_view = new self;

        return <<<HTML
        <div class="row mb-2 px-2 mb-2">
            <h2 class="col-12 col-md-6 mb-2">{$item->get("title")}</h2>
            {$view->manageButtons($item)}
        </div>
        {$view->showData($item)}
        {$self_view->showArticle($item)}
HTML;
    }

    /**
     * Retourne une carte dans laquelle on a le contenu de l'article.
     * 
     * @param $item Un item de catégorie article.
     * 
     * @return string
     */
    private function showArticle($item)
    {
        if ($item->get("article_content")) {
            return <<<HTML
            <div class="card">
                <div class="card-header bg-white">Contenu de l'article
                </div>
                <div class="card-body">
                    <article>{$item->get("article_content")}</article>
                </div>
            </div>
HTML;
        }
    }
    
}