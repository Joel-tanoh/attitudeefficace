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
     * La personne ayant souscrit.
     * 
     * @var \App\BackEnd\Models\Users\Suscriber
     */
    private $suscriberEmailAddress;

    /**
     * Code de l'élément souscrit.
     * 
     * @var string
     */
    private $itemCode;

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
        $query = $sqlQuery->select("code, subscriber_email_address, item_code, suscribed_at")
            ->from(self::TABLE_NAME)
            ->where("code = ?")
            ->returnQueryString();
        
        $rep = self::connect()->prepare($query);
        $rep->execute([$code]);
        $result = $rep->fetch();

        $this->code                     = $result["code"];
        $this->suscriberEmailAddress    = $result["subscriber_email_address"];
        $this->itemID                   = $result["item_code"];
        $this->suscribedAt              = $result["suscribed_at"];
    }

    /**
     * Retourne le la personne qui a souscrit.
     * 
     * @return \App\BackEnd\Models\Users\Suscriber
     */
    public function getSuscriber()
    {
       
    }

    /**
     * Retourne l'item souscrit.
     * 
     * @return \App\BackEnd\Models\ItemParent
     */
    public function getSuscribedItem()
    {
        
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
        return Utility::formatDate($this->susbcriptionDate, $precision);
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
    
}