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

namespace App\FrontEnd\Layout;

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
class ChildLayout extends Layout
{
    /**
     * Retourne la page d'affichage d'un item enfant.
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
            {$self_layout->_showArticleContent($item)}
        </div>
HTML;
    }

    /**
     * Retourne une carte dans laquelle on a le contenu de l'article.
     * 
     * @param $item Un item de catégorie article.
     * 
     * @return string
     */
    private function _showArticleContent($item)
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