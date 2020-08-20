<?php

namespace App\BackEnd\Utilities;

use App\BackEnd\Bdd\BddManager;
use App\BackEnd\Models\Users\Visitor;
use App\BackEnd\Utilities\Utility;
use App\BackEnd\Session;

/**
 * Fichier de classe gestionnaire des visites sur l'app.
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */
class VisitManager extends Utility
{
    /**
     * Date de la visite.
     * 
     * @var string
     */
    private $date;

    /**
     * Année de la visite.
     * 
     * @var string
     */
    private $year;

    /**
     * Mois de la visite.
     * 
     * @var string
     */
    private $month;

    /**
     * Jour de la visite.
     * 
     * @var string
     */
    private $day;

    /**
     * Nombre de visite.
     * 
     * @var $int
     */
    private $number;

    /**
     * Nom de la table.
     * 
     * @var string
     */
    const TABLE_NAME = "visits";

    /**
     * Constructeur.
     * 
     * @param string $date
     * 
     * @return void
     */
    public function __construct(string $date)
    {
        $bddManager = new BddManager();
        $query = "SELECT date, number"
                . " date_format(date, '%Y') year"
                . " date_format(date, '%m') month"
                . " date_format(date, '%d') day"
                . " FROM " . self::TABLE_NAME
                . " WHERE date = ?";
        $rep = $bddManager->getPDO()->prepare($query);
        $rep->execute([$date]);
        $result = $rep->fetch();

        $this->year = $result["year"];
        $this->month = $result["month"];
        $this->day = $result["day"];
        $this->number = $result["number"];
    }

    /**
     * Retourne l'année de la visite.
     * 
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Retourne le mois de la visite.
     * 
     * @return string
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Retourne le jour de la visite.
     * 
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Retourne le nombre de visite.
     * 
     * @return int
     */
    public function count()
    {
        return (int)$this->number;
    }

    /**
     * Permet de mettre à jour le compteur de visite de l'app.
     * 
     * @return void 
     */
    public static function manage()
    {
        if (!Session::visitorSessionIsActive()) {
            Visitor::manageVisitorPresence();
        }
        self::setVisit();
    }

    /**
     * Enregistre la visite dans la base de données ou incrémente le nombre de
     * visite du jour courant.
     * 
     * @return void
     */
    public static function setVisit()
    {
        $bddManager = new BddManager();
        $date = date('Y-m-d');
        $visit = self::verifyDateVisitIsset($date);
        
        if ($visit["dateIsset"]) {
            $bddManager->incOrDecColValue("increment", "number", self::TABLE_NAME, "date", $visit["date"]);
        } else {
            self::insertNewVisit($date, 1);
        }
    }

    /**
     * Vérifie si une date est déjà dans la table qui compte les visites sur l'app.
     * Cette méthode dépend fortement du format de la table comptant les visites dans
     * la base de données.
     * 
     * @param string $date
     * 
     * @return array
     */
    public static function verifyDateVisitIsset(string $date)
    {
        $bddManager = new BddManager();
        $query = "SELECT date, COUNT(*) as dateIsset"
                . " FROM " . self::TABLE_NAME
                . " WHERE date = ?";

        $rep = $bddManager->getPDO()->prepare($query);
        $rep->execute([$date]);

        return $rep->fetch();
    }

    /**
     * Insère une nouvelle date de visite dans la table compteur_visite. Cette méthode
     * dépend fortement du format de la table dans la base de données.
     * 
     * @param string $date
     * 
     * @return bool
     */
    public static function insertNewVisit(string $date, int $number = 1)
    {
        $bddManager = new BddManager();
        $query = "INSERT INTO " . self::TABLE_NAME
                . "(date, number) VALUES(:date, :number)";

        $rep = $bddManager->getPDO()->prepare($query);
        $rep->execute([
            "date" => $date,
            "number" => $number,
        ]);

        return true;
    }

}