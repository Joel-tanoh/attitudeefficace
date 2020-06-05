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

use App\BackEnd\Bdd\BddManager;

/**
 * Gère les requêtes SQL.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @link     Link
 */
class SqlQueryFormater
{
    private $data;
    private $select_length;
    private $select;

    private $from;

    private $conditions;
    private $where;
    private $where_length;

    private $order;

    private $insert_into;
    private $cols;
    private $cols_length;
    private $colonnes;

    private $vals;
    private $vals_length;
    private $values;

    private $update;

    private $set;
    private $set_length;
    private $sets;

    /**
     * Requête finale
     * 
     * @var string
     */
    private $query;

    /**
     * Permet de spécifier les données à récupérer de la base de données.
     * 
     * @param string $data le nom de la colonne dans laquelle on récupère les données.
     * 
     * @return self Retourne la même instance.
     */
    public function select(string $data)
    {
        $this->data .= $data . ", ";
        $this->select_length = strlen($this->data);
        $this->select = substr($this->data, 0, $this->select_length - 2);
        return $this;
    }

    /**
     * Permet de spécifier la table de laquelle on récupère les données.
     * 
     * @param string $table Le nom de la table.
     * 
     * @return self Retourne la même instance.
     */
    public function from(string $table)
    {  
        if ($this->select) {
            $this->from = $table;
        }
        return $this;
    }

    /**
     * Permet de spécifier des clauses WHERE dans la requête.
     * 
     * @param string $where La clause.
     * 
     * @return self Retourne la même instance.
     */
    public function where(string $where)
    {
        if ($this->select || $this->update) {
            $this->conditions .= $where . " AND ";
            $this->where_length = strlen($this->conditions);
            $this->where = substr($this->conditions, 0, $this->where_length - 5);
        }
        return $this;
    }
    
    /**
     * Permet de spécifier un ordre de retour des données.
     * 
     * @param string $col La colonne sur laquelle ordonner les retours.
     * 
     * @return self Retourne la même instance.
     */
    public function orderBy(string $col)
    {
        if ($this->where) {
            $this->order = $col;
        }
        return $this;
    }
    
    /**
     * Permet d'insérer des données dans une table.
     * 
     * @param string $table La table dans laquelle on insère les données.
     * 
     * @return self Retourne la même instance.
     */
    public function insertInto(string $table)
    {
        $this->insert_into = $table;
        return $this;
    }
    
    /**
     * Permet de spécifier le colonne dans laquelle on insère la donnée.
     * 
     * @param string $col La colonne dans laquelle on insère la donnée.
     * 
     * @return self Retourne la même instance.
     */
    public function cols(string $col)
    {
        $this->cols .= $col . ", ";
        $this->cols_length = strlen($this->cols);
        $this->colonnes = substr($this->cols, 0, $this->cols_length - 2);
        return $this;
    }
    
    /**
     * Permet de donner la valeur à insérer dans la colonne.
     * 
     * @param string $value La valeur à insérer dans la colonne.
     * 
     * @return self Retourne la même instance.
     */
    public function values(string $value)
    {
        $this->vals .= "'$value', ";
        $this->vals_length = strlen($this->vals);
        $this->values = substr($this->vals, 0, $this->vals_length - 2);
        return $this;
    }
    
    /**
     * Permet de mettre à jour les données d'une base d'une table.
     * 
     * @param string $table La table à mettre à jour.
     * 
     * @return self Retourne la même instance.
     */
    public function update(string $table)
    {
        $this->update = $table;
        return $this;
    }
    
    /**
     * Permet de spécifier la colonne à mettre à jour
     * 
     * @param string $col Le nom de la colonne à mettre à jour.
     * 
     * @return self Retourne la même instance.
     */
    public function sets(string $col)
    {
        $this->set .= $col . " AND ";
        $this->set_length = strlen($this->set);
        $this->sets = substr($this->set, 0, $this->set_length - 5);
        return $this;
    }
    
    /**
     * Méthode non terminée.
     * 
     * @param string $table 
     * 
     * @return self Retourne la même instance.
     */
    public function alter(string $table)
    {
        return $this;
    }

    /**
     * Retourne la requête finale sous forme de chaîne de caractère.
     * 
     * @return string
     */
    public function returnQueryString()
    {
        if ($this->select) {
            $this->query = "SELECT $this->select";

            if ($this->from) {
                $this->query .= " FROM $this->from";
            }

            
            if ($this->where) {
                $this->query .= " WHERE $this->where";
            }

            if ($this->order) {
                $this->query .= " ORDER BY $this->order";
            }
        }

        if ($this->insert_into) {
            $this->query = "INSERT INTO $this->insert_into";

            if ($this->cols) {
                $this->query .= "($this->colonnes)";
            }

            if ($this->values) {
                $this->query .= " VALUES ($this->values)";
            }
        }

        if ($this->update) {
            $this->query = "UPDATE $this->update";

            if ($this->sets) {
                $this->query .= " SET $this->sets";
            }

            if ($this->where) {
                $this->query .= " WHERE $this->where";
            }
        }

        return $this->query;
    }

    /**
     * Permet de compter le nombre d'entrée dans une table.
     * 
     * @param string $table_name [[Description]]
     * 
     * @return [[Type]] [[Description]]
     */
    public static function countTableItem($table_name)
    {
        $bdd = BddManager::connectToDb();
        $query = $bdd->prepare("SELECT COUNT(*) FROM $table_name");
        $query->execute(
            ["table_name" => $table_name]
        );
        return $query->fetch();
    }
    
    /**
     * Remet l'auto_increment d'une table à 1 si elle ne contient pas d'occurrences.
     * 
     * @param string $table_name [[Description]]
     * 
     * @return bool
     */
    public static function autoIncrementTo1($table_name)
    {
        $bdd = BddManager::connectToDb();
        if (self::countTableItem($table_name) == 0) {
            $query = $bdd->prepare("ALTER TABLE $table_name auto_increment = 1");
            $query->execute(
                ["table_name" => $table_name]
            );
        }
    }
}