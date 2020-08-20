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

use App\BackEnd\Cookie;
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
    private $sessionId;

    /**
     * Date de la visite.
     * 
     * @var string
     */
    private $dateVisit;

    /**
     * Date de la dernière action du visiteur.
     * 
     * @var string
     */
    private $lastActionDate;

    /**
     * Id du panier du visiteur.
     * 
     * @var int
     */
    private $basketID;

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
     * @param strig $sessionId
     * 
     * @return void
     */
    public function __construct(string $sessionId)
    {
        // On récupère les infos de la base de données pour s'assurer qu'elles ont été bien enregistrées
        $visitor = self::getInfos($sessionId);

        // On initialise les propriétés du visiteur
        $this->sessionId = $visitor["session_id"];
        $this->dateVisit = $visitor["date_visit"];
        $this->lastActionDate = $visitor["last_action_date"];
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
        return $this->dateVisit;
    }

    /**
     * Retourne la date de la dernière action du visiteur.
     * 
     * @return string
     */
    public function getLastActionDate()
    {
        return $this->lastActionDate;
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
            $sessionId = Utility::generateCode(26);;
            Session::setVisitorSessionId($sessionId);
            self::register($sessionId);
        }
    }

    /**
     * Retourne les infos d'un visiteur grace au PHPSESSIONID.
     * 
     * @param string $sessionId
     * 
     * @return array
     */
    private static function getInfos(string $sessionId)
    {
        $query = "SELECT id, session_id, date_visit FROM " . self::TABLE_NAME
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
    public static function register(string $sessionId)
    {
        $query = "INSERT INTO " . self::TABLE_NAME
            . " (session_id, date_visit, last_action_date)"
            . " VALUES(?, ?, ?)";
        $rep = parent::connect()->prepare($query);
        $rep->execute(
            [$sessionId, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]
        );
        return true;
    }

    /**
     * Vérifie si la session existe.
     * 
     * @return boolean
     */
    private static function sessionExist()
    {
        $query = "SELECT COUNT(id) AS session_counter FROM " .self::TABLE_NAME
            . " WHERE session_id = ?";
        
        $rep = parent::connect()->prepare($query);
        $rep->execute([Cookie::getVisitorSessionIdFromCookie()]);
        $result = $rep->fetch();

        return $result["session_counter"] != 0;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////                           LES VUES                                                /////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////

}
