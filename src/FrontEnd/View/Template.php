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
 * Une template est un type de disposition du contenu d'une page.
 * Le contenu de la page est une template, et à cette template, on passera
 * donc les vues.
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class Template extends View
{
    /**
     * Template de lecture d'un item.
     * 
     * @param mixed $item L'item qu'on veut afficher.
     * 
     * @return string
     */
    public function readItem($item = null)
    {

    }

    /**
     * Template de suppression d'items.
     * 
     * @param array $items     Les items qu'on veut supprimer.
     * @param array $categorie 
     * @param array $error     En cas d'erreur à afficher.
     * 
     * @return string
     */
    public function deleteItems($items, $categorie, $error = null)
    {

    }

    /**
     * Template avec une navbar fixe et une sidebar fixe.
     * 
     * @param mixed $navbar
     * @param mixed $sidebar
     * @param mixed $container_content
     * 
     * @return string
     */
    public function navbarAndSidebarAndContainer($navbar = null, $sidebar = null, $container_content = null)
    {
        return <<<HTML
        {$navbar}
        {$sidebar}
        <div class="container-fluid mb-3" id="container-with-fixed-sidebar-and-navbar">
            {$container_content}
        </div>
HTML;
    }

    /**
     * Disposition de page avec une navbar.
     * 
     * @param string $navbar
     * @param string $container_content
     * @param string $footer
     * 
     * @return string
     */
    public function navbarAndContainerAndFooter($navbar = null, $container_content = null, $footer = null)
    {
        return <<<HTML
        {$navbar}
        <section class="container">
            {$container_content}
        </section>
        {$footer}
HTML;
    }

    /**
     * Une template avec une navbar fixe et le contenu.
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
     * Template de la page d'administration. Elle comporte une navbar, une sidebar,
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
     * Template de liste de cartes.
     * 
     * @param array $items Un tableau contenant les items.
     * 
     * @return string
     */
    public function gridOfCards($items = null)
    {

    }

}

