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

namespace App\View;

use App\BackEnd\Models\Entity;

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
     * Template avec une navbar fixe et une sidebar fixe.
     * 
     * @param mixed $navbar
     * @param mixed $sidebar
     * @param mixed $container_content
     * 
     * @return string
     */
    public static function navbarAndSidebarAndContainer($navbar = null, $sidebar = null, $container_content = null)
    {
        return <<<HTML
        <header>
            {$navbar}
        </header>
        <section>
            {$sidebar}
        </section>
        <div class="container-fluid pt-3 pr-4" id="container-with-fixed-sidebar-and-navbar">
            {$container_content}
        </div>
HTML;
    }

    /**
     * Disposition de page avec une navbar, un contenu et un footer.
     * 
     * @param string $navbar
     * @param string $container_content
     * @param string $footer
     * 
     * @return string
     */
    public static function navbarAndContainerAndFooter($navbar = null, $container_content = null, $footer = null)
    {
        return <<<HTML
        <header>
            {$navbar}
        </header>
        <section class="container">
            {$container_content}
        </section>
        <footer>
            {$footer}
        </footer>
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
    public static function fixedNavbarAndContainer($navbar = null, $container_content = null)
    {
        return <<<HTML
        <header>
            {$navbar}
        </header>
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
    public static function fixedSidebarAndContainer($sidebar = null, $container_content = null)
    {
        return <<<HTML
        <section>
            {$sidebar}
        </section>
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
    public static function singleColumn($container_content = null)
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
     * @param array  $itemsForCards Un tableau contenant des cartes.
     * @param string $className     La classe ou la catégorie des éléments passées permettant
     *                              d'instancier des objets.
     * @param string $cssClass      Classe css.
     * 
     * @return string
     */
    public static function gridOfCards(array $itemsForCards = null, string $className = null, string $cssClass = null)
    {
        $cards = "";
        foreach ($itemsForCards as $item) {
            $object = Entity::returnObjectByCategorie($className, $item["code"]);
            $cards .= Card::card($object->getThumbsSrc(), $object->getTitle(), $object->getUrl("administrate"), $object->getCreatedAt());
        }

        return <<<HTML
        <div class="row {$cssClass}">
            {$cards}
        </div>
HTML;
    }

}

