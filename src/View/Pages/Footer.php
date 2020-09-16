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

namespace App\View\Pages;

use App\View\View;

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
     * @author Joel
     * @return [[Type]] [[Description]]
     */
    public static function publicFooter() : string
    {
        return <<<HTML
        <footer class="footer bg-dark">
            <div class="container">
            </div>
        </footer>
HTML;
    }
}