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

use App\BackEnd\Models\Entity;
use App\BackEnd\Ecommerce\Order;

/**
 * Classe qui gère tout ce qui est en relation avec le client.
 * Le client est le visiteur qui a commandé un service et qui
 * est enregistré dans la base de données.
 * 
 * PHP verison 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @link     Link
 */
class Customer extends Entity
{
    /**
     * Les commandes faites par le client.
     * 
     * @var array
     */
    private $orders;

    /**
     * Le nom de la table.
     * 
     * @var string
     */
    const TABLE_NAME = "miniservices_customers";

    /** Catégorie de l'item
     * @var string
     */
    const CATEGORIE = "miniservices_customers";

    /**
     * Constructeur d'un client de miniservice.
     * 
     * @param string $code
     * 
     * @return void
     */
    public function __construct(string $code)
    {
        $result = parent::bddManager()->get("last_name, first_names, email_address, contact_1, contact_2", self::TABLE_NAME, "code", $code)[0];

        $this->code = $result["code"];
        $this->firstName = $result["first_names"];
        $this->lastName = $result["last_name"];
        $this->emailAddress = $result["email_address"];
        $this->contact1 = $result["contact_1"];
        $this->contact2 = $result["contact_2"];
        $this->categorie = self::CATEGORIE;

        /* On récupère les commandes éffectuées par le client */
        $result = parent::bddManager()->get("code", Order::TABLE_NAME, "customer_code", $this->code);
        foreach ($result as $order) {
            $order = new Order($order["code"]);
            $this->orders[] = $order;
        }

    }

    /**
     * Retourne toute les commandes éffectuées par le client.
     * 
     * @return array
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Retourne la liste des personnes qui sont enregistrées dans la base de données.
     * 
     * @return array
     */
    public function getAll()
    {
        $result = parent::bddManager()->get("code", self::TABLE_NAME);
        $customers = [];
        foreach($result as $customer) {
            $customer = new self($customer["code"]);
            $customers[] = $customer;
        }
        return $customers;
    }

}
