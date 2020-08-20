<?php

namespace App\BackEnd\Models\Ecommerce;

use App\BackEnd\Models\Entity;

/**
 * Classe de gestion des paniers.
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */
class Basket extends Entity
{
    /**
     * Id de session du panier.
     * 
     * @var string
     */
    protected $sessionId;

    /**
     * Email de l'utilisateur a qui appartient le panier.
     * 
     * @var string
     */
    protected $emailAddress;

    /**
     * Codes des produits dans qui sont dans le panier.
     * 
     * @var array
     */
    protected $productsCodes;

    /**
     * Statut du panier.
     * 
     * @var string
     */
    protected $statut;

    /**
     * Nom de la table dans la base de données.
     * 
     * @var string
     */
    const TABLE_NAME = "baskets";

    /**
     * Constructeur d'un panier.
     * 
     * 
     */
    public function __construct()
    {
        
    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////                           LES VUES                                                /////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
}