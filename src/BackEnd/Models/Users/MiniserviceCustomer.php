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
use App\BackEnd\Models\MiniserviceOrder;

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
class MiniserviceCustomer extends Entity
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

    /**
     * Constructeur d'un client de miniservice.
     * 
     * @param string $code
     * 
     * @return void
     */
    public function __construct(string $code)
    {
        $result = parent::bddManager()->get("id, last_name, first_names, email_address, contact_1, contact_2", self::TABLE_NAME, "code", $code);

        $this->id = $result["id"];
        $this->code = $result["code"];
        $this->lastName = $result["last_name"];
        $this->firstNames = $result["first_names"];
        $this->emailAddress = $result["email_address"];
        $this->contact1 = $result["contact_1"];
        $this->contact2 = $result["contact_2"];

        /* On récupère les commandes éffectuées par le client */
        $result = parent::bddManager()->get("code", MiniserviceOrder::TABLE_NAME, "customer_id", $this->id);
        foreach ($result as $order) {
            $order = new MiniserviceOrder($order["code"]);
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
