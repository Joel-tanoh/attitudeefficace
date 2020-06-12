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
    private $dbAddress; 
    private $dbName;
    private $dbCharset;
    private $dbLogin;
    private $dbPassword;
    private $pdo;

    /**
     * Permet d'instanceier un BddManager.
     * 
     * @param string $dbName     Le nom de la base de données.
     * @param string $dbLogin    Le login pour se connecter à la base de données.
     * @param string $dbPassword Le mot de passe pour se connecter à la base de données.
     * @param string $dbAddress  L'adresse ip du serveur.
     * @param string $sgbd       Le système de gestion de la base de données.
     * @param string $dbCharset  L'encodage des caractères.
     * 
     */
    public function __construct(
        string $dbName = null,
        string $dbLogin = null ,
        string $dbPassword = null ,
        string $dbAddress = "127.0.0.1",
        string $sgbd = "mysql",
        string $dbCharset = "utf8"
    ) {
        $this->dbName = null !== DB_NAME ? DB_NAME : $dbName;
        $this->dbLogin = null !== DB_LOGIN ? DB_LOGIN : $dbLogin;
        $this->dbPassword = null !== DB_PASSWORD ? DB_PASSWORD : $dbPassword;
        $this->dbAddress = null !== DB_ADDRESS ? DB_ADDRESS : $dbAddress;
        $this->sgbd = $sgbd;
        $this->dbCharset = $dbCharset;
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
                $this->sgbd.':host='.$this->dbAddress.';dbname='.$this->dbName.'; charset='.$this->dbCharset, $this->dbLogin, $this->dbPassword,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );

        } catch (PDOException $e) {
            die('<h1>Erreur de connexion à la base de données, veuillez contacter votre administrateur !</h1>');
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
     * @param string $toGet         La ou les noms des colonnes qu'on veut récupérer.
     *                              Si vous voulez récupérer plusieurs colonnes, vous passez les noms
     *                              des colonnes dans la même chaîne de caractères en les séparant par
     *                              une virgule.
     * @param string $tableName     Le nom de la table de laquelle récupérer les
     *                              occurences.
     * @param string $whereColName  La colonne sur laquelle on fait la clause where
     *                              spécifier l'élément à retourner.
     * @param string $whereColValue La valeur de la clause pour spécifier l'élément qu'on veut
     *                              précisement.
     * 
     * @return array Tableau qui contient les occurences demandées.
     */
    public function get(string $toGet, string $tableName, string $whereColName = null, $whereColValue = null)
    {
        $query = "SELECT $toGet FROM $tableName";

        if (null !== $whereColName) {
            $query .= " WHERE $whereColName = ?";
            $rep = $this->pdo->prepare($query);
            $rep->execute([$whereColValue]);
        } else {
            $rep = $this->pdo->query($query);
        }

        return $rep->fetchAll();
    }

    /**
     * Compte toutes les occurences d'une table. On peut lui passer une clause where
     * 
     * @param string $colToCount    La colonne à compter.
     * @param string $tableName     Le nom de table.
     * @param string $whereColName  Une clause sur les éléments à compter.
     * @param mixed  $whereColValue La valeur de la colonne.
     * 
     * @return string
     */
    public function count(string $colToCount, string $tableName = null, string $whereColName = null, $whereColValue = null)
    {
        $query = "SELECT COUNT(" . $colToCount . ") AS count FROM " . $tableName;

        if (null !== $whereColName) {
            $query .= " WHERE $whereColName = ?";
            $rep = $this->pdo->prepare($query);
            $rep->execute([$whereColValue]);
        } else {
            $rep = $this->pdo->query($query);
        }
        
        return $rep->fetch()["count"];
    }

    /**
     * Modifie la valeur du champ d'une table.
     * 
     * @param string $col   Le nom de la colonne à mettre à jour.
     * @param string $value 
     * @param string $tableName 
     * @param string $whereColName 
     * @param $whereColValue
     * 
     * @return bool
     */
    public function update(string $col, $value, string $tableName, string $whereColName, $whereColValue)
    {
        $query = "UPDATE $tableName SET $col = ? WHERE $whereColName = ?";
        $rep = $this->pdo->prepare($query);
        $rep->execute(
            [$value, $whereColValue]
        );
        return true;
    }

    /**
     * Supprime un item de la base de données.
     * 
     * @param string $tableName           La table de laquelle on supprime la donnée.
     * @param string $whereColName  Le nom dela colonne à prendre en compte pour supprimer
     *                                la données.
     * @param string $whereColValue Le contenu que la colonne à checker pour supprimer la donnée.
     * 
     * @return bool
     */
    public function delete(string $tableName, string $whereColName, $whereColValue)
    {
        $query = "DELETE FROM $tableName WHERE $whereColName = ?";
        $rep = $this->pdo->prepare($query);
        $rep->execute([$whereColValue]);
        return true;
    }

    /**
     * Permet de vérifier qu'une donnée existe dans le base de données. On peut lui
     * passer une clause where pour spécifier une occurrence.
     * 
     * @param string $colCheck      La colonne à vérifier.
     * @param string $tableName     Le nom de la table.
     * @param string $whereColName  Une clause where sur cette colonne pour spécifier
     *                              quelle occurennce on veut checker.
     * @param string $whereColValue La valeur qui permet de donner l spécification.
     * 
     * @return bool
     */
    public function checkIsset(string $colToCheck, string $tableName, string $whereColName = null, $whereColValue = null)
    {
        return $this->count($colToCheck, $tableName, $whereColName, $whereColValue) != 0;
    }

    /**
     * Retourne les occurences d'une table en excluant celui dont la valeur de propriété
     * est passée en paramètre.
     * 
     * @param string $colToGet      La ou les colonnes qu'on veut récupérer.
     * @param string $tableName     Le nom de la table.
     * @param string $colForExcept  C'est le nom de la colonne à prendre en compte pour rétirer
     *                              l'élémént.
     * @param string $exceptedValue Id de l'élément à exclure de la liste des résultats.
     * @param string $whereColName  Une colonne sur laquelle on peut passer une clause pour
     *                              spécifier les occurences
     *                              à retourner.
     * @param string $whereColValue On passe cette variable si on veut donner une clause
     *                              where sur le champ catégorie.
     * 
     * @return array
     */
    public function getTableOccurencesExecepted(string $colToGet, string $tableName, string $colForExcept, $exceptedValue, string $whereColName, string $whereColValue = null)
    {
        $pdo = $this->pdo;
        $query = "SELECT $colToGet FROM $tableName WHERE $colForExcept !== ?";

        if (null !== $whereColName) {
            $query .= " AND $whereColName = ?";
            $rep = $pdo->prepare($query);
            $rep->execute([$exceptedValue, $whereColValue]);
        } else {
            $rep = $pdo->prepare($query);
            $rep->execute([$exceptedValue]);
        }

        return $rep->fetchAll();
    }
    
    /**
     * Retourne la valeur maximale d'un champ.
     * 
     * @param string $colName       Le nom de la colonne.
     * @param string $tableName     Le nom de la table.
     * @param string $groupBy       La colonne sur laquelle on fait un regroupement.
     * @param string $havingColName La colonne de la clause HAVING
     * @param string $valueToHave   La valeur de triage de la clause HAVING
     * 
     * @return int
     */
    public function getMaxValueOf(string $colName, string $tableName, string $groupBy = null, string $havingColName = null, string $valueToHave = null)
    {
        $alias = $colName."_max";
        $query = "SELECT MAX($colName) as $alias FROM $tableName";

        if (null !== $groupBy) {
            $query .= " GROUP BY $groupBy";
        }

        if (null !== $havingColName) {
            $query .= " HAVING $havingColName = '$valueToHave'";
        }

        $rep = $this->pdo->query($query);
        return (int)$rep->fetch()[$alias];
    }
    
    /**
     * Retourne les occurrences qui ont une valeur supérieure ou égale à la valeur passée
     * en paramètre.
     * 
     * @param string $colToGet             Le nom de la colonne à récupérer.
     * @param string $tableName            Le nom de la table où on doit récupérer les occurrences.
     * @param string $colToCompare         Un nom de champ. Les valeurs de ce champ doivent être des
     *                                     entiers ou des dates.
     * @param int|\Date $colToCompareValue La valeur de comparaison.
     * 
     * @return array
     */
    public function getItemsOfValueMoreOrEqualTo(string $colToGet, string $tableName = null, string $colToCompare = null, $colToCompareValue = null, string $whereColName, string $whereColValue = null)
    {
        $query = "SELECT $colToGet FROM $tableName WHERE $colToCompare >= ?";

        if (null !== $whereColName) {
            $query .= " AND $whereColName = ?";
            $rep = $this->pdo->prepare($query);
            $rep->execute([$colToCompareValue, $whereColValue]);
        } else {
            $rep = $this->pdo->prepare($query);
            $rep->execute([$colToCompareValue]);
        }

        return $rep->fetchAll();
    }

    /**
     * Incrémente ou décrémente une propriété dont la valeur est un entier.
     * 
     * @param string $action        Increment ou decrement.
     * @param string $colName       La colonne dont on veut incrémenter ou décrémenter la valeur.
     * @param string $tableName     Le nom de la table de l'item à modifier.
     * @param string $whereColName  Une clause where sur cette colonne pour donner une précision sur
     *                              les résultats.
     * @param mixed  $whereColValue Id L'item dont on veut incrémenter ou décrémenter la valeur.
     * 
     * @return bool
     */
    public function incOrDecColValue(string $action, string $colName, string $tableName, string $whereColName = null, $whereColValue = null)
    {
        $query = "UPDATE $tableName SET $colName = ";
        $query .= $action === "increment" ? "$colName+1" : "$colName-1";

        if (null !== $whereColName) {
            $query .= " WHERE $whereColName = " . $whereColValue;
        }

        $this->pdo->query($query);
        return true;
    }

}