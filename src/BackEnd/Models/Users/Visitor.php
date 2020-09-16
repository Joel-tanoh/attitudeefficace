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

use App\BackEnd\Ecommerce\Basket;
use App\BackEnd\Session;
use App\BackEnd\Utilities\Utility;

/**
 * Gère tout ce qui est en rapport au visiteur.
 * 
 * PHP verison 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @link     Link
 */
class Visitor extends User
{
    /**
     * Id de sa session.
     * 
     * @var string
     */
    protected $sessionId;

    /**
     * Date de la visite.
     * 
     * @var string
     */
    protected $dateVisit;

    /**
     * Date de la dernière action du visiteur.
     * 
     * @var string
     */
    protected $lastActionTimestamp;

    /**
     * Id du panier du visiteur.
     * 
     * @var Basket
     */
    protected $basket;

    /**
     * Nom de la table dans la base de donneés.
     * 
     * @var string
     */
    const TABLE_NAME = "visitors";

    /**
     * Constructeur d'un visiteur. Dès son arrivée sur le site web le visiteur est instancié,
     * on génère un id qu'on enregistre dans un cookie et dans la base de données.
     * 
     * @param string $sessionId
     * 
     * @return void
     */
    public function __construct(string $sessionId)
    {
        $visitor = self::getData($sessionId);

        $this->sessionId = $visitor["session_id"];
        $this->dateVisit = $visitor["date_visit"];
        $this->lastActionTimestamp = $visitor["last_action_timestamp"];
    }

    /**
     * Retourne l'id de session du visiteur.
     * 
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Retourne la date de visite du visiteur.
     * 
     * @return string Date de visite du visiteur
     */
    public function getDateVisit()
    {
        return Utility::formatDate($this->createdAt);
    }

    /**
     * Retourne la date de la dernière action du visiteur.
     * 
     * @return string
     */
    public function getLastActionTimestamp()
    {
        return $this->lastActionTimestamp;
    }

    /**
     * Retourne le panier du visiteur
     * 
     * @return Basket
     */
    public function getBasket() : Basket
    {
        return new Basket(Basket::getData($this->sessionId)["session_id"]);
    }

    /**
     * Lorsque le visiteur arrive sur le site, il faut le prendre en charge en crééant son
     * ID de session ($sessionId) et l'enregistrer.
     * 
     * @return void
     */
    public static function manageVisitorPresence()
    {
        if (!Session::visitorSessionIsActive()) {
            $sessionId = Utility::generateCode(26);
            self::saveSessionId($sessionId);
            self::createBasket($sessionId);
            $visitor = new self($sessionId);
            Session::setVisitorSessionId($visitor->getSessionId());
        }

        self::updateLastActionTimestamp();
    }

    /**
     * Dès l'arrivée du visiteur sur le site, on lui crée un panier.
     * 
     * @param string $sessionId
     * 
     * @return bool
     */
    public static function createBasket(string $sessionId)
    {
        Basket::create($sessionId);
    }

    /**
     * Vérifie que le visiteur a un panier.
     * 
     * @return bool
     */
    public function hasBasket()
    {
        $query = "SELECT COUNT(*) FROM ". Basket::TABLE_NAME . " WHERE session_id = ?";

        $rep = parent::connect()->prepare($query);
        $rep->execute([$this->sessionId]);
        return $rep->fetch() == 1;
    }

    /**
     * Retourne les visteurs en lignes.
     * 
     * @return array
     */
    public static function getVisitorsOnline()
    {
        $comparedTo = time() - (3*60);

        $query = "SELECT session_id FROM " . self::TABLE_NAME
            . " WHERE last_action_timestamp >= ?";

        $rep = parent::connect()->prepare($query);
        $rep->execute([$comparedTo]);
        $result = $rep->fetchAll();

        $onlined = [];
        foreach($result as $res) {
            $onlined[] = new self($res["session_id"]);
        }

        return $onlined;
    }

    /**
     * Retourne le nombre de personne connectée.
     * 
     * @return int
     */
    public static function countVisitorsOnline()
    {
        return count(self::getVisitorsOnline());
    }

    /**
     * Permet de mettre à jour la date de la dernière page chargée par le
     * visiteur afin de savoir s'il est en ligne.
     * 
     * @return void
     */
    private static function updateLastActionTimestamp()
    {
        $query = "UPDATE " . self::TABLE_NAME
            . " SET last_action_timestamp = ?"
            . " WHERE session_id = ?";

        $rep = parent::connect()->prepare($query);
        $rep->execute(
            [
                date("U")
                , Session::getVisitorSessionId()
            ]
        );
    }

    /**
     * Retourne les infos d'un visiteur grace à la variable de session créée manuellement.
     * 
     * @param string $sessionId
     * 
     * @return array
     */
    private static function getData(string $sessionId)
    {
        $query = "SELECT session_id, date_visit, last_action_timestamp FROM " . self::TABLE_NAME
            . " WHERE session_id = ?";
        
        $rep = parent::connect()->prepare($query);
        $rep->execute([$sessionId]);
        
        return $rep->fetch();
    }

    /**
     * Enregistre la visite dans la base de données.
     * 
     * @param string $sessionId
     * 
     * @return void
     */
    private static function saveSessionId(string $sessionId)
    {
        if (!self::sessionIsset($sessionId)) {
            $query = "INSERT INTO " . self::TABLE_NAME
                . "(session_id, last_action_timestamp) VALUES(?, ?)";

            $rep = parent::connect()->prepare($query);
            $rep->execute([$sessionId, date('U')]);

            return true;
        }
    }

    /**
     * Vérifie si la session existe.
     * 
     * @param string $sessionId
     * 
     * @return boolean
     */
    private static function sessionIsset(string $sessionId)
    {
        $query = "SELECT COUNT(session_id) AS count_sessions FROM " . self::TABLE_NAME
            . " WHERE session_id = ?";
        
        $rep = parent::connect()->prepare($query);
        $rep->execute([$sessionId]);
        $result = $rep->fetch();

        return $result["count_sessions"] != 0;
    }

    /**
     * Permet au visiteur d'ajouter un nouveau produit à son panier
     * 
     * @param string $miniserviceCode Le code du miniservice à ajouter.
     *                                au panier.
     * 
     * @return bool
     */
    public function addItemToBasket($miniserviceCode)
    {
        if ($this->getBasket()->addItem($miniserviceCode)) {
            return true;
        }
    }

    public function removeItemFromBasket($miniserviceCode)
    {
        if ($this->getBasket()->removeItem($miniserviceCode)) {
            return true;
        }
    }

}
