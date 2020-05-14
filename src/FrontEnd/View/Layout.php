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

namespace App\FrontEnd\View;

/**
 * Une layout est un type de disposition du contenu d'une page.
 * Le contenu de la page est une layout, et à cette layout, on passera
 * donc les vues.
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class Layout
{
    /**
     * Une dispostion de page avec une navbar et une sidebar fixe.
     * 
     * @param mixed $navbar
     * @param mixed $sidebar
     * @param mixed $container_content
     * 
     * @return string
     */
    public function navbarAndFixedSidebar($navbar = null, $sidebar = null, $container_content = null)
    {
        return <<<HTML
        {$navbar}
        {$sidebar}
        <div class="container">
            {$container_content}
        </div>
HTML;
    }

    /**
     * Layout avec une navbar fixe et une sidebar fixe.
     * 
     * @param mixed $navbar
     * @param mixed $sidebar
     * @param mixed $container_content
     * 
     * @return string
     */
    public function fixedNavbarAndFixedSidebarAndContainer($navbar = null, $sidebar = null, $container_content = null)
    {
        return <<<HTML
        {$navbar}
        {$sidebar}
        <div class="container-with-fixed-sidebar-and-navbar px-3">
            {$container_content}
        </div>
HTML;
    }

    /**
     * Disposition de page avec une navbar.
     * 
     * @param mixed $navbar
     * @param mixed $container_content
     * 
     * @return string
     */
    public function navbarAndContainer($navbar = null, $container_content = null)
    {
        return <<<HTML
        {$navbar}
        <div class="container">
            {$container_content}
        </div>
HTML;
    }

    /**
     * Une layout avec une navbar fixe et le contenu.
     * 
     * @param mixed $navbar
     * @param mixed $container_content
     * 
     * @return string
     */
    public function fixedNavbarAndContainer($navbar = null, $container_content = null)
    {
        return <<<HTML
        {$navbar}
        <div class="container">
            {$container_content}
        </div>
HTML;
    }

    /**
     * Layout de la page d'administration. Elle comporte une navbar, une sidebar,
     * le contenu de la page et le footer.
     * 
     * @param string $sidebar La barre de gauche à fixer.
     * @param mixed  $container_content Le contenu à afficher à code de la sidebar.
     * 
     * @return string
     */
    public function fixedSidebarAndContainer($sidebar, $container_content = null)
    {
        return <<<HTML
        {$sidebar}
        <div id="container_content">
            {$container_content}
        </div>
HTML;
    }

    /**
     * Retourne la disposition de la page principale de la partie
     * publique.
     * 
     * @param mixed $container_content
     * 
     * @return string
     */
    public function singleColumn($container_content)
    {
        return <<<HTML
        <div class="container">
            {$container_content}
        </div>
HTML;
    }

    /**
     * Layout de liste de cartes.
     * 
     * @param 
     */
}

