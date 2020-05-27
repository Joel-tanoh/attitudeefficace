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

namespace App\BackEnd\APIs;

use App\BackEnd\Models\ItemChild;
use App\BackEnd\Models\Personnes\Suscriber;
use PDO;
use PDOException;

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
class Bdd
{
    /**
     * Méthode de connexion à la base de données. Retourne l'instance de connexion.
     * 
     * @return PDOInstance
     */
    public static function connectToDb()
    {
        try {
            $charset = "utf8";
            $sgbd = 'mysql';
            $bdd = new PDO(
                $sgbd. ':host=' . DB_ADDRESS . '; dbname=' . DB_NAME . '; charset=' . $charset, DB_LOGIN, DB_PASSWORD,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
            return $bdd;

        } catch (PDOException $e) {
            echo '<h1>Erreur de connexion à la base de données, veuillez contacter votre administrateur !</h1>';
        }
    }

    /**
     * Retourne le code d'un item dont les paramètres sont passés en paramètres.
     * 
     * @param string $col 
     * @param string $col_value 
     * @param string $table 
     * 
     * @return string Code de l'item.
     */
    public static function getItemBy(string $col = null, string $col_value = null, string $table = null)
    {
        $sql_query = new SqlQuery();
        $query = $sql_query
            ->select("code")
            ->from($table)
            ->where($col . "= ?")
            ->returnQueryString();
        $rep = self::connectToDb()->prepare($query);
        $rep->execute([$col_value]);
        return $rep->fetch()["code"];
    }

    /**
     * Retourne tous les slugs de la table passée en paramètre.
     * 
     * @param string $table Le nom de la table de laquelle on récupère le slug.
     * 
     * @return array
     */
    public static function getSlugsFrom(string $table)
    {
        $slugs = [];
        foreach (self::getAllFrom($table) as $row) {
            $slugs[] = $row["slug"];
        }
        return $slugs;
    }

    /**
     * Retourne les items enfants en prenant en paramètre l'id du parent et la
     * catégorie des items enfants à retourner.
     *
     * @param string $parent_id   
     * @param string $children_categorie La catégorie des éléments qu'on veut prendre
     *                                   de la base de données.
     *
     * @return array
     */
    public static function getchildrenOf($parent_id, $children_categorie)
    {
        $query = "SELECT code FROM " . ItemChild::TABLE_NAME . " WHERE parent_id = ? AND categorie = ?";
        $rep = self::connectToDb()->prepare($query);
        $rep->execute([$parent_id, $children_categorie]);
        return $rep->fetchAll();
    }

    /**
     * Compte toutes les occurences d'une table.
     * 
     * @param string $table     Le nom de table.
     * @param string $col       Une clause sur les éléments à compter.
     * @param mixed  $col_value La valeur de la colonne.
     * 
     * @return string|int
     */
    public static function countTableItems(string $table = null, string $col = null, $col_value = null)
    {
        if (null !== $table) {
            $query = "SELECT COUNT(id) AS item_number FROM $table";
            if (null !== $col) {
                $query .= " WHERE $col = ?";
                $rep = self::connectToDb()->prepare($query);
                $rep->execute([$col_value]);
            } else {
                $rep->query($query);
            }
            return $rep->fetch()["item_number"];
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
    public static function dataIsset(string $table, string $col, string $col_value)
    {
        return self::countTableItems($table, $col, $col_value) != 0;
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
    public static function getAllFromTableWithout(string $table, $exclu_id, string $categorie = null)
    {
        $bdd = self::connectToDb();
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
    public static function getMaxValueOf(
        string $col,
        string $table,
        string $group_by = null,
        string $having = null,
        string $having_value = null
    ) {
        $alias = $col."_max";
        $query = "SELECT MAX($col) as $alias FROM $table";
        if (null !== $group_by) {
            $query .= " GROUP BY $group_by";
        }
        if (null !== $having) {
            $query .= " HAVING $having = '$having_value'";
        }
        $rep = self::connectToDb()->query($query);
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
    public static function getItemsOfColValueMoreOrEqualTo(
        string $table = null,
        string $col = null,
        int $col_value = null,
        string $categorie = null
    ) {
        $query = "SELECT code FROM $table WHERE $col >= ? AND categorie = ?";
        $rep = self::connectToDb()->prepare($query);
        $rep->execute([$col_value, $categorie,]);
        return $rep->fetchAll();
    }
    
    /**
     * Permet d'insérer les données principale d'un item parent ou enfant.
     * 
     * @param string $table       La catégorie de l'item
     * @param string $code        Le code de l'item
     * @param string $title       Le titre de l'item
     * @param string $description La description de l'item
     * @param string $categorie   La catégorie de l'item
     * 
     * @return bool True si les données ont été bien insérées.
     */
    public static function insertPincipalsData(
        string $table = null,
        string $code = null,
        string $title = null,
        string $description = null,
        string $categorie = null
    ) {
        $query = "INSERT INTO $table(code, title, description, categorie) VALUES(?, ?, ?, ?)";
        $rep = self::connectToDb()->prepare($query);
        $rep->execute([$code, $title, $description, $categorie]);
        return true;
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
    public static function incOrDecColValue(string $action, string $col, string $table, $id)
    {
        $query = "UPDATE $table SET $col = ";
        $query .= $action == "increment" ? "$col+1" : "$col-1";
        $query .= " WHERE id = " . $id;
        self::connectToDb()->query($query);
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
    public static function verifyDateVisitIsset(string $year, string $month, string $day)
    {
        $query = "SELECT id, COUNT(id) as date_isset FROM compteur_visites WHERE year = :year AND month = :month AND day = :day";
        $rep = self::connectToDb()->prepare($query);
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
    public static function insertNewVisit(string $year, string $month, string $day, int $nombre_visite = 1)
    {
        $query = "INSERT INTO compteur_visites(year, month, day, nombre_visite)
            VALUES(:year, :month, :day, :nombre_visite)";
        $rep = self::connectToDb()->prepare($query);
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
    public static function set($col, $value, $table, $id)
    {
        $query = "UPDATE $table SET $col = :col_value WHERE id = :id";
        $rep = self::connectToDb()->prepare($query);
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
    public static function deleteById(string $table, $id)
    {
        $query = "DELETE FROM $table WHERE id = ?";
        $rep = self::connectToDb()->prepare($query);
        $rep->execute([$id]);
        return true;
    }

    /**
     * Retourne toutes adresses emails enregistrées dans le base de données.
     * 
     * @return array
     */
    public static function getAllEmails()
    {
        $newsletter_mails = self::select("adresse_email", "newsletters");
        $suscribers_mails = self::select("adresse_email", Suscriber::TABLE_NAME);
        return array_merge($newsletter_mails, $suscribers_mails);
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
    public static function select(string $to_select, string $table, string $clause = null, string $clause_value = null) 
    {
        $bdd = self::connectToDb();
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
    public static function getAllFrom(string $table, string $categorie = null)
    {
        $query = "SELECT code, slug FROM $table";
        if (null !== $categorie) {
            $query .= " WHERE categorie = ?";
            $rep = self::connectToDb()->prepare($query);
            $rep->execute([$categorie]);
        } else {
            $rep = self::connectToDb()->query($query);
        }
        return $rep->fetchAll();
    }

}