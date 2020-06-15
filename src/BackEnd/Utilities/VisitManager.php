<?php

namespace App\BackEnd\Utilities;

use App\BackEnd\Bdd\BddManager;
use App\BackEnd\Utilities\Utility;

/**
 * Fichier de classe gestionnaire des visites sur l'app.
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */
class VisitManager extends Utility
{
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
    const TABLE_NAME = "visit_counter";

    /**
     * Constructeur.
     * 
     * @param string $year  Format YYYY.
     * @param string $month Format MM.
     * @param string $day   Format DD.
     * 
     * @return void
     */
    public function __construct(string $year, string $month, string $day)
    {
        $bddManager = new BddManager();
        $query = "SELECT year, month, day, number FROM " . self::TABLE_NAME
                . " WHERE year = ? AND month = ? AND day = ?";
        $rep = $bddManager->getPDO()->prepare($query);
        $rep->execute([
            $year, $month, $day
        ]);
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
     * @return bool 
     */
    public static function appVisitCounter()
    {
        $bddManager = new BddManager();
        $year = date("Y");
        $month = date("m");
        $day = date("d");
        $visite = self::verifyDateVisitIsset($year, $month, $day);

        if (self::isNewVisit()) {
            if ($visite["date_isset"]) {
                $bddManager->incOrDecColValue("increment", "number", self::TABLE_NAME, $visite["id"]);
            } else {
                self::insertNewVisit($year, $month, $day, 1);
            }
        }
    }

    /**
     * Insère une nouvelle date de visite dans la table compteur_visite. Cette méthode
     * dépend fortement du format de la table dans la base de données.
     * 
     * @param string $tableName Le nom de la table ou on insère le compteur de visite.
     * @param string $year
     * @param string $month
     * @param string $day
     * @param int    $number
     * 
     * @return bool
     */
    public static function insertNewVisit(string $year, string $month, string $day, int $number = 1)
    {
        $bddManager = new BddManager();
        $tableName = self::TABLE_NAME;
        $query = "INSERT INTO $tableName(year, month, day, number)
                  VALUES(:year, :month, :day, :number)";

        $rep = $bddManager->getPDO()->prepare($query);
        $rep->execute([
            "year" => $year,
            "month" => $month,
            "day" => $day,
            "number" => $number,
        ]);

        return true;
    }
    /**
     * Vérifie si une date est déjà dans la table qui compte les visites sur l'app.
     * Cette méthode dépend fortement du format de la table comptant les visites dans
     * la base de données.
     * 
     * @return array
     */
    public static function verifyDateVisitIsset(string $year, string $month, string $day)
    {
        $bddManager = new BddManager();
        $query = "SELECT id, COUNT(id) as date_isset"
                  . " FROM " . self::TABLE_NAME
                  . " WHERE year = :year AND month = :month AND day = :day";

        $rep = $bddManager->getPDO()->prepare($query);
        $rep->execute([
            "year" => $year,
            "month" => $month,
            "day" => $day
        ]);

        return $rep->fetch();
    }

}