<?php

namespace App\BackEnd\Models;

use App\BackEnd\Bdd\SqlQueryFormater;
use App\BackEnd\Models\Items\ItemParent;
use App\BackEnd\Models\Users\Suscriber;

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
     * ID de l'élément souscrit.
     * 
     * @var string
     */
    private $itemID;
    
    /**
     * La personne ayant souscrit.
     * 
     * @var \App\BackEnd\Models\Users\Suscriber
     */
    private $suscriber;

    /**
     * L'item souscrit.
     * 
     * @var \App\BackEnd\Models\ItemParent
     */
    private $suscribedItem;

    /**
     * Jour de la souscription.
     * 
     * @var string
     */
    private $subscriptionDay;

    /**
     * Heure de la souscription.
     * 
     * @var string
     */
    private $subscriptionHour;

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
        $query = $sqlQuery->select("id, code, subscriber_id, item_id")
                          ->select("date_format(subscription_date, '%d %b. %Y') as subscription_day")
                          ->select("date_format(subscription_date, '%H:%i') as subscription_hour")
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
        $this->subscriptionDay = $result["subscription_day"];
        $this->subscriptionHour = $result["subscription_hour"];

        // La personne qui a souscrit
        $result = parent::bddManager()->get("code", Suscriber::TABLE_NAME, "id", $this->suscriberID);
        if ($result["code"]) {
            $this->suscriber = new Suscriber($result["code"]);
        }

        // L'item souscrit
        $result = parent::bddManager()->get("code", ItemParent::TABLE_NAME, "id", $this->itemID);
        if ($result["code"]) {
            $this->suscribedItem = new ItemParent($result["code"]);
        }
    }

    /**
     * Retourne le la personne qui a souscrit.
     * 
     * @return \App\BackEnd\Models\Users\Suscriber
     */
    public function getSuscriber()
    {
        return $this->suscriber;
    }

    /**
     * Retourne l'item souscrit.
     * 
     * @return \App\BackEnd\Models\ItemParent
     */
    public function getSuscribedItem()
    {
        return $this->suscribedItem;
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
        if ($precision === "day") {
            return $this->subscriptionDay;
        } elseif ($precision === "hour") {
            return $this->subscrptionHour;
        } else {
            return $this->subscriptionDay . ' à ' . $this->subscrptionHour;
        }
    }

    /**
     * Retourne toutes les souscriptions.
     * 
     * @return array
     */
    public static function getAll()
    {
        $rsl = parent::bddManager()->get("code", self::TABLE_NAME);
        $subscriptions = [];
        foreach ($rsl as $sub) {
            $subscriptions[] = new self($rsl["code"]);
        }
        
        return $subscriptions;
    }
}