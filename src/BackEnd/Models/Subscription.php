<?php

namespace App\BackEnd\Models;

use App\BackEnd\Bdd\SqlQueryFormater;
use App\BackEnd\Models\Items\ItemParent;
use App\BackEnd\Models\Users\Suscriber;
use App\BackEnd\Utilities\Utility;

/**
 * Fichier de classe, qui gère les souscriptions.
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */
class Subscription extends Entity
{
    /**
     * ID du subscriber.
     * 
     * @var string
     */
    private $subscriberID;
    
    /**
     * La personne ayant souscrit.
     * 
     * @var \App\BackEnd\Models\Users\Suscriber
     */
    private $suscriber;

    /**
     * ID de l'élément souscrit.
     * 
     * @var string
     */
    private $itemID;

    /**
     * L'item souscrit.
     * 
     * @var \App\BackEnd\Models\ItemParent
     */
    private $suscribedItem;

    /**
     * Date de la souscription.
     * 
     * @var string
     */
    private $susbcriptionDate;

    /**
     * Le nom de la table.
     * 
     * @var string
     */
    const TABLE_NAME = "subscriptions";

    /**
     * Constructeur.
     * 
     * @param string $code
     * 
     * @return void
     */
    public function __construct(string $code)
    {
        $sqlQuery = new SqlQueryFormater();
        $query = $sqlQuery->select("id, code, subscriber_id, item_id, subscription_date")
                          ->from(self::TABLE_NAME)
                          ->where("code = ?")
                          ->returnQueryString();
        
        $rep = self::connect()->prepare($query);
        $rep->execute([$code]);
        $result = $rep->fetch();

        $this->id = $result["id"];
        $this->code = $result["code"];
        $this->subscriberID = $result["subscriber_id"];
        $this->itemID = $result["item_id"];
        $this->subscriptionDate = $result["subscription_date"];
    }

    /**
     * Retourne le la personne qui a souscrit.
     * 
     * @return \App\BackEnd\Models\Users\Suscriber
     */
    public function getSuscriber()
    {
        $result = parent::bddManager()->get("code", Suscriber::TABLE_NAME, "id", $this->suscriberID);
        return new Suscriber($result[0]["code"]);
    }

    /**
     * Retourne l'item souscrit.
     * 
     * @return \App\BackEnd\Models\ItemParent
     */
    public function getSuscribedItem()
    {
        $result = parent::bddManager()->get("code", ItemParent::TABLE_NAME, "id", $this->itemID);
        return new ItemParent($result[0]["code"]);
    }

    /**
     * Retourne la date de la souscription.
     * 
     * @param string $precision La partie de la date qu'on veut récupérer.
     * 
     * @return string
     */
    public function getSubscriptionDate(string $precision = null)
    {
        return Utility::convertDate($this->susbcriptionDate, $precision);
    }

    /**
     * Retourne toutes les souscriptions.
     * 
     * @return array
     */
    public static function getAll()
    {
        $result = parent::bddManager()->get("code", self::TABLE_NAME);
        $subscriptions = [];

        foreach ($result as $subscription) {
            $subscriptions[] = new self($subscription["code"]);
        }
        
        return $subscriptions;
    }

    ////////////////////////////////////////// LES VUES ///////////////////////////////////////////

    
}