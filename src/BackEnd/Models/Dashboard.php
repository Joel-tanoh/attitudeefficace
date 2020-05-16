<?php

/**
 * Fichier de classe.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Data
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com License
 * @link     Link
 */


namespace App\BackEnd\Models;

/**
 * Gère le dashboard, les statistiques, les compteurs de vue, les performances.
 * 
 * @category Category
 * @package  Data
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com License
 * @link     Link
 */
class Dashboard
{
    
    /**
     * Compteur de vue. A revoir
     * 
     * @param $item 
     * 
     * @return bool
     */
    public function viewCounter($item)
    {
        $counteur = (int)$item->get("view");
        $counteur++;
        $item->set("view", $item::TABLE_NAME);
    }
    
}