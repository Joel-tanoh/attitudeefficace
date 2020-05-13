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

namespace App\FrontEnd\Layout;

/**
 * Gère tout ce qui concerne le pied de page
 * 
 * @category Category
 * @package  Namespace_App\FrontEnd\Outils
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com License
 * @link     Link
 */
class Footer extends Layout
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