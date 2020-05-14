<?php

/**
 * Description
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com License
 * @link     Link
 */

namespace App\FrontEnd\View;

/**
 * Gère tout ce qui concerne le pied de page
 * 
 * @category Category
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com License
 * @link     Link
 */
class Footer extends View
{
    /**
     * Pied de page
     * 
     * @param string $footer_content Contenu du pied de page
     * 
     * @author Joel
     * @return [[Type]] [[Description]]
     */
    public static function footer(string $footer_content) : string
    {
        return <<<HTML
        <p class="footer fixed-bottom text-center border-top" style="background-color:#fff">
            {$footer_content}
        </p>
HTML;
    }
}