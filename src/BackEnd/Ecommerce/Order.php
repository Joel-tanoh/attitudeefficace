<?php

namespace App\BackEnd\Ecommerce;

use App\BackEnd\Bdd\SqlQueryFormater;
use App\BackEnd\Models\Entity;
use App\BackEnd\Models\Items\ItemChild;
use App\BackEnd\Models\Users\Customer;
use App\BackEnd\Utilities\Utility;

/**
 * Classe de gestion des commandes de mini services.
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */
class Order extends Entity
{
    /**
     * Code de la personne ayant commandé le miniservice.
     * 
     * @var mixed
     */
    private $customerCode;

    /**
     * Etat de la commande.
     * 
     * @var string
     */
    private $state;

    /**
     * Date de la commande.
     * 
     * @var string
     */
    private $orderedAt;

    /**
     * Nom de la table des commandes dans la base de données.
     * 
     * @var string
     */
    const TABLE_NAME = "orders";

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

        $query = $sql_query->select("code, miniservice_id, customer_id, description, state, ordered_at")
            ->from(self::TABLE_NAME)
            ->where("code = ?")
            ->returnQueryString();

        $rep = $this->pdo->prepare($query);
        $rep->execute([$code]);
        $result = $rep->fetch();

        $this->code = $result['code'];
        $this->miniserviceCode = $result['miniservice_code'];
        $this->customerCode = $result['customer_code'];
        $this->description = $result['description'];
        $this->state = $result['state'];
        $this->orderedAt = $result['ordered_at'];  
    }

    /**
     * Retourne le client qui a commandé le miniservice.
     * 
     * @return \App\BackEnd\Models\Users\Customer
     */
    public function getCustomer()
    {
        $res = parent::bddManager()->get("code", Customer::TABLE_NAME, "code", $this->customerCode);
        if ($res["code"]) {
            return new Customer($res["code"]);
        }
    }
    
    /**
     * Retourne le mini service commandé.
     * 
     * @return \App\BackEnd\Models\ItemChild
     */
    public function getMiniservice()
    {
        $res = parent::bddManager()->get("code", ItemChild::TABLE_NAME, "code", $this->miniserviceCode);
        if ($res["code"]) {
            return new ItemChild($res["code"]);
        }
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

    /**
     * Retourne la date de la commande.
     * 
     * @param string $precision
     * 
     * @return string
     */
    public function getOrderedAt(string $precision = null)
    {
        return Utility::formatDate($this->orderedAt, $precision);
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


    ////////////////////////////////////////// LES VUES ///////////////////////////////////////////

    /**
     * Vue permettant de lister toutes les commandes.
     * 
     * @return string
     */
    public static function listAll()
    {
        return <<<HTML

HTML;
    }

}