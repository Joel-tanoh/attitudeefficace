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

use App\BackEnd\Bdd\SqlQueryFormater;
use App\BackEnd\Models\Items\ItemParent;
use App\BackEnd\Models\Subscription;

/**
 * Un Suscriber est celui ou celle qui suit(lit) un item.

 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @link     Link
 */
class Suscriber extends User
{
    /**
     * Nom de la table.
     * 
     * @var string
     */
    const TABLE_NAME = "suscribers";

    /**
     * Catégorie.
     * 
     * @var string
     */
    const CATEGORIE = "suscribers";

    /**
     * Url de la catégorie.
     * 
     * @var string
     */
    const URL = ADMIN_URL."/". self::CATEGORIE;

    /**
     * Les items auxquels le suscriber a souscrit
     * 
     * @var array
     */
    private $suscribedItems = [];

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
        $query = $sqlQuery
                ->select("id, code, last_name, first_names, password, email_address, role, contact_1, contact_2, state")
                ->from(self::TABLE_NAME)
                ->where("code = ?")
                ->returnQueryString();
        $rep = self::connect()->prepare($query);
        $rep->execute([$code]);
        $result = $rep->fetch();

        $this->id               = $result["id"];
        $this->code             = $result["code"];
        $this->lastName         = $result["last_name"];
        $this->firstNames       = $result["first_names"];
        $this->password         = $result["password"];
        $this->emailAddress     = $result["email_address"];
        $this->role             = $result["role"];
        $this->contact1         = $result["contact_1"];
        $this->contact2         = $result["contact_2"];
        $this->state            = $result["state"];
        $this->categorie        = self::CATEGORIE;

        // Les items auxquels le suscriber a souscrit
        $result = parent::bddManager()->get("id", Subscription::TABLE_NAME, "suscriber_id", $this->id);
        foreach ($result as $item) {
            $code = parent::bddManager()->get("code", ItemParent::TABLE_NAME, "id", $result["id"]);
            $item = new ItemParent($code["code"]);
            $this->suscribedItems[] = $item;
        }
    }

    /**
     * Retourne les items auxquels le suscriber a souscrit.
     * 
     * @return array
     */
    public function getSuscribedItems()
    {
        return $this->suscribedItems;
    }

    /**
     * Retourne la liste de tous ceux qui ont souscrit à un élément.
     * 
     * @return array
     */
    public function getAll()
    {
        
    }

    /**
     * Retourne toutes les adresses emails des souscrivants.
     * 
     * @return array
     */
    public static function getAllEmailsAddress()
    {
        return parent::bddManager()->get("email_address", self::TABLE_NAME);
    }

    /**
     * Retourne la date à laquelle il a souscrit.
     * 
     * @param \App\BackEnd\Models\Items\ItemParent $item L'item dont
     *                                                   on veut connaitre la date de
     *                                                   souscription.
     * 
     * @return string
     */
    public function getSubscriptionDate(\App\BackEnd\Models\Items\ItemParent $item)
    {
        $query = "SELECT date_format(subscritption_date, '%d %b. %Y à %H:%i') as subscription_date"
                . " FROM " . Subscription::TABLE_NAME
                . " WHERE suscriber_id = ? AND item_id = ?";
        
        $rep = parent::connect()->prepare($query);
        $rep->execute([$this->getID(), $item->getID()]);
        return $rep->fetch()["subscription_date"];
    }

    /**
     * Retourne une instance de suscriber grâce à son adresse email.
     * 
     * @param string $emailAddress
     * 
     * @return self
     */
    public static function getByEmail(string $emailAddress)
    {
        $query = "SELECT code FROM " . self::TABLE_NAME . " WHERE email_address = ?";
        $rep = parent::connect()->prepare($query);
        $rep->execute([$emailAddress]);
        $result = $rep->fetch();

        if ($result["code"]) {
            return new self($result["code"]);
        }
    }

}