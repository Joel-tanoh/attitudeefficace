<?php

namespace App\BackEnd\Ecommerce;

use App\BackEnd\Bdd\BddManager;
use App\BackEnd\Utilities\Utility;
use App\BackEnd\Ecommerce\Basket;

/**
 * Classe de gestion d'un élément d'un panier.
 * 
 * @author Joel
 */
class BasketContent extends Basket
{
    /**
     * Code du miniservice
     * 
     * @var string
     */
    protected $code;

    /**
     * Panier auquel appartient l'item courant
     * 
     * @var Basket
     */
    protected $basket;

    /** Date d'ajout de l'item dans le panier
     * @var string
     */
    protected $addedAt;

    /** Nom de la table
     * @var string
     */
    const TABLE_NAME = "baskets_content";

    /**
     * Constructeur d'un item faisant parti du panier.
     * 
     * @param int $miniserviceCode Code de l'item contenu dans le panier, vue
     *                             le panier d'un visiteur ne contiendra que des
     *                             miniservices.
     * 
     * @return void
     */
    public function __construct(int $miniserviceCode)
    {
        $query = "SELECT basket_session_id, miniservice_code, added_at FROM " . self::TABLE_NAME 
            . " WHERE miniservice_id = ?";

        $rep = parent::connect()->prepare($query);
        $rep->execute([$miniserviceCode]);

        $miniservice = $rep->fetch();
        
        $this->code = $miniservice["miniservice_code"];
        $this->basket = new parent($miniservice["basket_session_id"]);
        $this->addedAt = $miniservice["added_at"];
    }

    /**
     * Retourne le code de l'élément ajouté.
     * 
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Retourne le panier dans lequel se trouve l'élément.
     * 
     * @return Basket
     */
    public function getBasket()
    {
        return $this->basket;
    }

    /**
     * Retourne la date à laquelle à été ajoutée l'élément.
     * 
     * @return string
     */
    public function getAddedAt()
    {
        return Utility::formatDate($this->addedAt);
    }

    /**
     * Retourne tous les miniservices contenu dans le panier passé dont l'id de
     * session est passé en paramètre.
     * 
     * @param string $basketSessionId La sessionId du basket.
     * 
     * @return array Un tableau contenant la liste des items contenu dans le 
     *               panier
     */
    public static function getAll(string $basketSessionId)
    {
        $content = (new BddManager())->get("miniservice_code", self::TABLE_NAME, "basket_session_id", $basketSessionId);

        $toReturn = [];

        foreach($content as $itemCode) {
            $toReturn[] = new self($itemCode);
        }

        return $toReturn;
    }

    /**
     * Permet d'ajouter un élément à un panier.
     * 
     * @param string $basketSessionId L'id de session du panier.
     * @param string $miniserviceCode Le code du miniservice à ajouter dans 
     *                                le panier.
     * 
     * @return true
     */
    public static function add(string $basketSessionId, string $miniserviceCode)
    {
        $query = "INSERT INTO " . self::TABLE_NAME
            . " VALUES(?, ?)";

        $rep = parent::connect()->prepare($query);
        $rep->execute([$basketSessionId, $miniserviceCode]);

        return true;
    }

    /**
     * Permet de supprimer un élément d'un panier.
     * 
     * @return bool
     */
    public function delete()
    {
        $query = "DELETE FROM " . self::TABLE_NAME
            . " WHERE miniservice_code = ?";
        
        $rep = parent::connect()->prepare($query);
        $rep->execute([$this->code]);

        return true;
    }

}