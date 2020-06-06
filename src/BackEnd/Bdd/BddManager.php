<?php

/**
 * Fichier de classe.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "CVS: cvs_id"
 * @link     Link
 */

namespace App\BackEnd\Bdd;

use PDO;
use PDOException;
use App\BackEnd\Bdd\SqlQueryFormater;

/**
 * Gère la base de données.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @link     Link
 */
class BddManager
{
    private $sgbd;
    private $db_address; 
    private $db_name;
    private $db_charset;
    private $db_login;
    private $db_password;
    private $pdo;

    /**
     * Permet d'instanceier un BddManager.
     * 
     * @param string $db_name     Le nom de la base de données.
     * @param string $db_login    Le login pour se connecter à la base de données.
     * @param string $db_password Le mot de passe pour se connecter à la base de données.
     * @param string $db_address  L'adresse ip du serveur.
     * @param string $sgbd        Le système de gestion de la base de données.
     * @param string $db_charset  L'encodage des caractères.
     * 
     */
    public function __construct(
        string $db_name,
        string $db_login,
        string $db_password,
        string $db_address = "127.0.0.1",
        string $sgbd = "mysql",
        string $db_charset = "utf8"
    ) {
        $this->db_name = $db_name;
        $this->db_login = $db_login;
        $this->db_password = $db_password;
        $this->db_address = $db_address;
        $this->sgbd = $sgbd;
        $this->db_charset = $db_charset;
        $this->pdo = $this->connect();
    }

    /**
     * Méthode de connexion à la base de données. Retourne l'instance de connexion.
     * 
     * @return PDOInstance
     */
    public function connect() {
        try {
            return new PDO(
                $this->sgbd. ':host=' .$this->db_address. '; dbname=' .$this->db_name. '; charset=' .$this->db_charset, $this->db_login, $this->db_password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );

        } catch (PDOException $e) {
            echo '<h1>Erreur de connexion à la base de données, veuillez contacter votre administrateur !</h1>';
            die();
        }
    }

    /**
     * Retourne l'instance PDO.
     * 
     * @return PDOInstance
     */
    public function getPDO()
    {
        return $this->pdo;
    }
   
    /**
     * Récupère toutes les occurences de la table passée en paramètre. Prend en
     * paramètre le nom d'une table et optionnellement la catégorie de données à 
     * retourner.
     * 
     * @param string $table     Le nom de la table de laquelle récupérer les
     *                          occurences.
     * @param string $categorie Une clause de spécification de la catégorie des
     *                          données à renvoyer.
     * @param string $order_by  Le nom de la colonne par rapport à laquelle ordonner
     *                          les résultats de la requette.
     * 
     * @return array Tableau qui contient les occurences de la table passée en param.
     */
    public function getAllFrom(string $table, string $categorie = null)
    {
        $query = "SELECT code, slug FROM $table";
        if (null !== $categorie) {
            $query .= " WHERE categorie = ?";
            $rep = $this->pdo->prepare($query);
            $rep->execute([$categorie]);
        } else {
            $rep = $this->pdo->query($query);
        }
        return $rep->fetchAll();
    }

    /**
     * Retourne le code d'un item dont les paramètres sont passés en paramètres.
     * 
     * @param string $col       La colonne sur laquelle on fait la clause where.
     * @param string $col_value La valeur de la clause pour spécifier l'élément qu'on veut
     *                          précisement.
     * @param string $table     La table de laquelle on récupère la donnée.
     * 
     * @return string Code de l'item.
     */
    public function getItemBy(string $col = null, string $col_value = null, string $table = null)
    {
        $sql_query = new SqlQueryFormater();
        $query = $sql_query
            ->select("code")
            ->from($table)
            ->where($col . "= ?")
            ->returnQueryString();
        $rep = $this->pdo->prepare($query);
        $rep->execute([$col_value]);
        return $rep->fetch()["code"];
    }

    // /**
    //  * Retourne tous les slugs de la table passée en paramètre.
    //  * 
    //  * @param string $table Le nom de la table de laquelle on récupère le slug.
    //  * 
    //  * @return array
    //  */
    // public function getSlugsFrom(string $table)
    // {
    //     $slugs = [];
    //     foreach ($this->getAllFrom($table) as $row) {
    //         $slugs[] = $row["slug"];
    //     }
    //     return $slugs;
    // }

    /**
     * Compte toutes les occurences d'une table.
     * 
     * @param string $table     Le nom de table.
     * @param string $col       Une clause sur les éléments à compter.
     * @param mixed  $col_value La valeur de la colonne.
     * 
     * @return string|int
     */
    public function countTableItems(string $table = null, string $col = null, $col_value = null)
    {
        if (null !== $table) {
            $query = "SELECT COUNT(id) AS items_number FROM $table";
            if (null !== $col) {
                $query .= " WHERE $col = ?";
                $rep = $this->pdo->prepare($query);
                $rep->execute([$col_value]);
            } else {
                $rep = $this->pdo->query($query);
            }
            return $rep->fetch()["items_number"];
        }
    }

    /**
     * Permet de vérifier qu'une donnée existe dans le base de données.
     * 
     * @param string $table 
     * @param string $col 
     * @param string $col_value 
     * 
     * @return bool
     */
    public function checkIsset(string $table, string $col, string $col_value)
    {
        return $this->countTableItems($table, $col, $col_value) !== 0;
    }

    /**
     * Retourne les occurences d'une table en excluant celui dont l'id est passé en
     * paramètre.
     * 
     * @param string $table 
     * @param string $exclu_id 
     * @param string $categorie 
     * 
     * @return array
     */
    public function getAllFromTableExcepted(string $table, $exclu_id, string $categorie = null)
    {
        $bdd = $this->pdo;
        $query = "SELECT code FROM $table WHERE id !== ?";
        if (null !== $categorie) {
            $query .= " AND categorie = ?";
            $rep = $bdd->prepare($query);
            $rep->execute([(int)$exclu_id, $categorie]);
        } else {
            $rep = $bdd->prepare($query);
            $rep->execute([$exclu_id]);
        }
        return $rep->fetchAll();
    }
    
    /**
     * Retourne la valeur maximale d'un champ.
     * 
     * @param string $col          La colonne.
     * @param string $table        La table
     * @param string $group_by     La colonne sur laquelle on fait un regroupement.
     * @param string $having       La colonne de la clause HAVING
     * @param string $having_value La valeur de triage de la clause HAVING
     * 
     * @return array
     */
    public function getMaxValueOf(string $col, string $table, string $group_by = null, string $having = null, string $having_value = null)
    {
        $alias = $col."_max";
        $query = "SELECT MAX($col) as $alias FROM $table";
        if (null !== $group_by) {
            $query .= " GROUP BY $group_by";
        }
        if (null !== $having) {
            $query .= " HAVING $having = '$having_value'";
        }
        $rep = $this->pdo->query($query);
        return (int)$rep->fetch()[$alias];
    }
    
    /**
     * Retourne les items qui ont une valeur supérieure ou égale à la valeur passée
     * en paramètre.
     * 
     * @param string $table     Le nom de la table où on doit récupérer les items.
     * @param string $col       Un nom de champ dont les valeurs doivent être des
     *                          entiers.
     * @param int    $col_value La valeur de comparaison.
     * @param string $categorie      
     * 
     * @return array
     */
    public function getItemsOfColValueMoreOrEqualTo(string $table = null, string $col = null, int $col_value = null, string $categorie = null)
    {
        $query = "SELECT code FROM $table WHERE $col >= ? AND categorie = ?";
        $rep = $this->pdo->prepare($query);
        $rep->execute([$col_value, $categorie,]);
        return $rep->fetchAll();
    }

    /**
     * Incrémente ou décrémente une propriété dont la valeur est un entier.
     * 
     * @param string $action Increment ou decrement.
     * @param string $col    La colonne dont on veut incrémenter ou décrémenter la valeur.
     * @param string $table  Le nom de la table de l'item à modifier.
     * @param $id     Id L'item dont on veut incrémenter ou décrémenter la valeur.
     * 
     * @return bool
     */
    public function incOrDecColValue(string $action, string $col, string $table, $id)
    {
        $query = "UPDATE $table SET $col = ";
        $query .= $action == "increment" ? "$col+1" : "$col-1";
        $query .= " WHERE id = " . $id;
        $this->pdo->query($query);
        return true;
    }

    /**
     * Vérifie si une date est déjà dans la table qui compte les visites sur l'app.
     * 
     * @param string $year
     * @param string $month
     * @param string $day
     * 
     * @return array
     */
    public function verifyDateVisitIsset(string $year, string $month, string $day)
    {
        $query = "SELECT id, COUNT(id) as date_isset FROM compteur_visites WHERE year = :year AND month = :month AND day = :day";
        $rep = $this->pdo->prepare($query);
        $rep->execute([
            "year" => $year,
            "month" => $month,
            "day" => $day
        ]);
        return $rep->fetch();
    }

    /**
     * Insère une nouvelle date de visite dans la table compteur_visite.
     * 
     * @param string $year
     * @param string $month
     * @param string $day
     * @param int    $nombre_visite
     * 
     * @return bool
     */
    public function insertNewVisit(string $year, string $month, string $day, int $nombre_visite = 1)
    {
        $query = "INSERT INTO compteur_visites(year, month, day, nombre_visite)
            VALUES(:year, :month, :day, :nombre_visite)";
        $rep = $this->pdo->prepare($query);
        $rep->execute([
            "year" => $year,
            "month" => $month,
            "day" => $day,
            "nombre_visite" => $nombre_visite,
        ]);
        return true;
    }

    /**
     * Modifie la valeur du champ d'une table.
     * 
     * @param string $col 
     * @param string $value 
     * @param string $table 
     * @param $id   
     * 
     * @return bool
     */
    public function set($col, $value, $table, $id)
    {
        $query = "UPDATE $table SET $col = :col_value WHERE id = :id";
        $rep = $this->pdo->prepare($query);
        $rep->execute(
            [
                "col_value" => $value,
                "id" => $id
            ]
        );
        return true;
    }

    /**
     * Supprime un item de la base de données.
     * 
     * @param string $table 
     * @param $id 
     * 
     * @return bool
     */
    public function deleteById(string $table, $id)
    {
        $query = "DELETE FROM $table WHERE id = ?";
        $rep = $this->pdo->prepare($query);
        $rep->execute([$id]);
        return true;
    }

    /**
     * Récupère les données spécifiés de la table spécifiée selon les clauses
     * spécifiées en paramètre.
     * 
     * @param string $to_select    Les colonnes à prendre dans la même chaîne de
     *                             caractère séparée. Les champs doivent être séparés
     *                             par une virgule.
     * @param string $table        La table.
     * @param string $clause 
     * @param string $clause_value 
     * 
     * @return array Un tableau qui contient les données retournées.
     */
    public function select(string $to_select, string $table, string $clause = null, string $clause_value = null) 
    {
        $bdd = $this->pdo;
        $query = "SELECT $to_select FROM $table";
        if (null !== $clause) {
            $query .= " WHERE $clause = ?";
            $rep = $bdd->prepare($query);
            $rep->execute([$clause_value]);
        } else {
            $rep = $bdd->query($query);
        }
        return $rep->fetchAll();
    }

}