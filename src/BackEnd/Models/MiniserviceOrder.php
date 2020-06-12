<?php

namespace App\BackEnd\Models;

use App\BackEnd\Bdd\SqlQueryFormater;
use App\BackEnd\Models\Entity;
use App\BackEnd\Models\Items\ItemChild;
use App\BackEnd\Models\Users\MiniserviceCustomer;

/**
 * Classe de gestion des commandes de mini services.
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */
class MiniserviceOrder extends Entity
{
    /**
     * ID du miniservice commandé
     * 
     * @var mixed
     */
    private $miniserviceID;

    /**
     * Le miniservice commandé.
     * 
     * @var \App\BackEnd\Models\ItemChild
     */
    private $miniservice;

    /**
     * ID de la personne ayant commandé le miniservice.
     * 
     * @var mixed
     */
    private $customerID;

    /**
     * La personne ayant commandé le miniservice
     * 
     * @var \App\BackEnd\Models\Users\MiniserviceCustomer
     */
    private $customer;

    /**
     * Etat de la commande.
     * 
     * @var string
     */
    private $state;

    /**
     * Jour de la commande.
     * 
     * @var string
     */
    private $orderDay;

    /**
     * Heure de la commande.
     * 
     * @var string
     */
    private $orderHour;

    /**
     * Nom de la table des commandes dans la base de données.
     * 
     * @var string
     */
    const TABLE_NAME = "miniservices_orders";

    /**
     * Constructeur
     * 
     * @param string $code Le code identifiant la commande dans la base de données.
     * 
     * @return void
     */
    public function __construct(string $code)
    {
        $this->pdo = self::connect();
        $sql_query = new SqlQueryFormater();

        $query = $sql_query->select("id, code, miniservice_id, customer_id, description, state")
            ->select("date_format(ordered_at, '%d %b. %Y') AS order_day")
            ->select("date_format(ordered_at, '%H:%i') AS order_hour")
            ->from(self::TABLE_NAME)
            ->where("code = ?")
            ->returnQueryString();

        $rep = $this->pdo->prepare($query);
        $rep->execute([$code]);
        $result = $rep->fetch();

        $this->id = $result['id'];
        $this->code = $result['code'];
        $this->miniserviceID = $result['miniservice_id'];
        $this->customerID = $result['customer_id'];
        $this->description = $result['description'];
        $this->state = $result['state'];
        $this->orderDay = $result["order_day"];
        $this->orderHour = $result['order_hour'];

        // On récupère le mini service commandé
        $miniservice = parent::bddManager()->get("code", ItemChild::TABLE_NAME, "id", $this->miniserviceID);
        if ($miniservice["code"]) {
            $this->miniservice = new ItemChild($miniservice["code"]);
        }

        /* On récupère la personne qui a commandé le miniservice */
        $customer = parent::bddManager()->get("code", MiniserviceCustomer::TABLE_NAME, "id", $this->customerID);
        if ($customer["code"]) {
            $this->customer = new MiniserviceCustomer($customer["code"]);
        }
        
    }

    /**
     * Retourne le client qui a commandé le miniservice.
     * 
     * @return \App\BackEnd\Models\Users\MiniserviceCustomer
     */
    public function getCustomer()
    {
        return $this->customer;
    }
    
    /**
     * Retourne le mini service commandé
     * 
     * @return \App\BackEnd\Models\ItemChild
     */
    public function getMiniservice()
    {
        return $this->miniservice;
    }

    /**
     * Retourne la date de la commande.
     * 
     * @param string $precision
     * 
     * @return string
     */
    public function getOrderedAt(string $precision = null)
    {
        if ($precision === "day") {
            return $this->orderDay;
        } elseif ($precision === "hour") {
            return $this->orderHour;
        } else {
            return $this->orderDay . ' à ' . $this->orderHour;
        }
    }

    /**
     * Retourne l'état de la commande.
     * 
     * @return string
     */
    public function getState()
    {
        if ($this->state == 1) {
            return "nouvelle commande";
        } elseif ($this->state == 2) {
            return "commande en attente";
        } elseif ($this->state == 3) {
            return "commande gérée";
        }
    }

    /**
     * Retourne toutes les commandes.
     * 
     * @return array
     */
    public static function getAll()
    {
        $bddManager = parent::bddManager();
        $result = $bddManager->get("code", self::TABLE_NAME);
        $orders = [];
        foreach ($result as $order) {
            $order = new self($order["code"]);
            $orders[] = $order;
        }
        return $orders;
    }

    /**
     * Retourne la description de la commande.
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

}