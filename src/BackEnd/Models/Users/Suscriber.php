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
     * Les items auxquels le suscriber a souscrit
     * 
     * @var array
     */
    private $subscribed_items = [];

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

        $this->id = $result["id"];
        $this->code = $result["code"];
        $this->lastName = $result["last_name"];
        $this->firstNames = $result["first_names"];
        $this->password = $result["password"];
        $this->email_address = $result["email_address"];
        $this->role = $result["role"];
        $this->contact_1 = $result["contact_1"];
        $this->contact_2 = $result["contact_2"];
        $this->state = $result["state"];

        // Les items auxquel le suscriber a souscrit
        $result = parent::bddManager()->get("id", Subscription::TABLE_NAME, "suscriber_id", $this->id);
        foreach ($result as $item) {
            $code = parent::bddManager()->get("code", ItemParent::TABLE_NAME, "id", $result["id"]);
            $item = new ItemParent($code["code"]);
            $this->subscribed_items[] = $item;
        }
    }

    /**
     * Retourne les items auxquels le suscriber a souscrit.
     * 
     * @return array
     */
    public function getSuscribedItems()
    {
        return $this->subscribed_items;
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
}