<?php

namespace App\BackEnd\Ecommerce;

use App\BackEnd\Ecommerce\BasketContent;
use App\BackEnd\Models\Entity;
use App\BackEnd\Models\Users\Visitor;
use App\BackEnd\Utilities\Utility;

/**
 * Classe de gestion des paniers.
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */
class Basket extends Entity
{
    /**
     * Propriétaire du panier.
     * 
     * @var Visitor
     */
    protected $owner;

    /**
     * Id de session du panier.
     * 
     * @var string
     */
    protected $sessionId;

    /**
     * Statut du panier.
     * 
     * @var string
     */
    protected $state;

    /** Date de création du panier
     * @var string
     */
    protected $createdAt;

    /** Date de modification du panier
     * @var string
     */
    protected $updatedAt;

    /**
     * Le contenu du panier.
     * @var array
     */
    protected $content;

    /**
     * Nom de la table dans la base de données.
     * 
     * @var string
     */
    const TABLE_NAME = "baskets";

    /**
     * Constructeur d'un panier.
     * 
     * @param string $session_id Id de dession du visiteur.
     */
    public function __construct(string $sessionId)
    {
        $basketData = self::getData($sessionId);

        $this->sessionId = $basketData["session_id"];
        $this->createdAt = $basketData["created_at"];
        $this->updatedAt = $basketData["updated_at"];
        $this->state     = $basketData["state"];
        $this->content   = BasketContent::getAll($sessionId);
    }

    /**
     * Retourne les informations concernant un panier en fonction de l'Id
     * de session passée en paramètre.
     * 
     * @param string $sessionId
     * 
     * @return array Un tableau contenant les valeurs de la base de
     *               données.
     */
    public static function getData(string $sessionId)
    {
        $query = "SELECT session_id, created_at, updated_at, state FROM " . self::TABLE_NAME
            . " WHERE session_id = ?";
        
        $rep = parent::connect()->prepare($query);
        $rep->execute([$sessionId]);

        return $rep->fetch();
    }

    /**
     * Retourne l'id de session du panier.
     * 
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Retourne le contenu du panier.
     * 
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }

    /** 
     * Retourne la date de création du panier
     * 
     * @return string
     */
    public function getCreatedAt()
    {
        return Utility::formatDate($this->createdAt);
    }

    /**
     * Retourne l'état du panier.
     * 
     * @return string
     */
    public function  getState()
    {
        return $this->state;
    }

    public function getOwner()
    {
        return new Visitor($this->sessionId);
    }

    /**
     * Permet de créer un panier.
     * 
     * @param string $sessionId
     */
    public static function create(string $sessionId)
    {
        $query = "INSERT INTO " . self::TABLE_NAME . "(session_id) VALUES(?)";

        $rep = parent::connect()->prepare($query);
        $rep->execute([$sessionId]);

        return true;
    }

    /**
     * Permet de supprimer un panier.
     * 
     * @return bool
     */
    public function delete()
    {
        $query = "DELETE FROM " . self::TABLE_NAME
            . " WHERE session_id = ?";
        
        $rep = parent::connect()->prepare($query);
        $rep->execute([$this->sessionId]);

        return true;
    }

    /**
     * Permet d'ajouter un miniservice au panier.
     * 
     * @param string $miniserviceCode
     * 
     * @return bool
     */
    public function addItem(string $miniserviceCode)
    {
        if (BasketContent::add($this->sessionId, $miniserviceCode)) {
            return true;
        }
    }

    /**
     * Permet de supprimer un élément du panier.
     * 
     * @param string $miniserviceCode
     * 
     * @return bool
     */
    public function removeItem(string $miniserviceCode)
    {
        $basketContent = new BasketContent($miniserviceCode);
        if ($basketContent->delete()) {
            return true;
        }
    }

}