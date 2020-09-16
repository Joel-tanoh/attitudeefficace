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
     * @param string $suscriberCode
     * 
     * @return void
     */
    public function __construct(string $suscriberCode)
    {
        $sqlQuery = new SqlQueryFormater();
        $query = $sqlQuery
            ->select("code, last_name, first_names, password, email_address, contact_1, contact_2, state")
            ->from(self::TABLE_NAME)
            ->where("code = ?")
            ->returnQueryString();
        $rep = self::connect()->prepare($query);
        $rep->execute([$suscriberCode]);
        $result = $rep->fetch();

        $this->code             = $result["code"];
        $this->lastName         = $result["last_name"];
        $this->firstName        = $result["first_names"];
        $this->password         = $result["password"];
        $this->emailAddress     = $result["email_address"];
        $this->role             = $result["role"];
        $this->contact1         = $result["contact_1"];
        $this->contact2         = $result["contact_2"];
        $this->state            = $result["state"];
        $this->categorie        = self::CATEGORIE;
    }

    /**
     * Retourne les items auxquels le suscriber a souscrit.
     * 
     * @return array
     */
    public function getSuscribedItems()
    {
        $result = parent::bddManager()->get(Subscription::TABLE_NAME, "suscriber_email_address", $this->code);
        foreach ($result as $item) {
            $code = parent::bddManager()->get("code", ItemParent::TABLE_NAME, "id", $result["id"]);
            $item = new ItemParent($code["code"]);
            $this->suscribedItems[] = $item;
        }

        return $this->suscribedItems;
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
     * Retourne la date à laquelle il a souscrit à un item.
     * 
     * @param \App\BackEnd\Models\Items\ItemParent $item L'item dont
     *                                                   on veut connaitre la date de
     *                                                   souscription.
     * 
     * @return string
     */
    public function getSuscribedAt(\App\BackEnd\Models\Items\ItemParent $item)
    {
        $query = "SELECT subscription_date FROM " . Subscription::TABLE_NAME
            . " WHERE suscriber_email_address = ? AND item_code = ?";
        
        $rep = parent::connect()->prepare($query);
        $rep->execute([$this->getCode(), $item->getCode()]);
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

    /**
     * Retourne la liste de tous ceux qui ont souscrit à un item.
     * 
     * @return array
     */
    public static function getAll()
    {
        $query = "SELECT code FROM " . self::TABLE_NAME;
        $rep = parent::connect()->query($query);
        $result = $rep->fetchAll();

        $suscribers = [];

        foreach($result as $suscriberCode) {
            $suscriber[] = new self($suscriberCode);
        }

        return $suscribers;
    }

}