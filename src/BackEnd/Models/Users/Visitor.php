<?php

/**
 * Fichier de classe
 * 
 * PHP verison 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @version  "GIT: <Joel-tanoh>"
 * @link     Link
 */

namespace App\BackEnd\Models\Users;

/**
 * Gère tout ce qui est en rapport au visiteur.
 * 
 * PHP verison 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @link     Link
 */
class Visitor extends User
{
    /**
     * Id de sa session.
     * 
     * @var string
     */
    private $sessionID;

    /**
     * Date de la visite.
     * 
     * @var string
     */
    private $dateVisit;

    /**
     * Nom de la table dans la base de donneés.
     * 
     * @var string
     */
    const TABLE_NAME = "visitors";

    /**
     * Id du panier du visiteur.
     * 
     * @var int
     */
    private $basketID;

    /**
     * Retourne toutes les visites.
     * 
     * @return array
     */
    public function getAll()
    {
        
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////                           LES VUES                                                /////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////

}
