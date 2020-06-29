<?php

/**
 * Fichier de classe
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Users
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com License
 * @version  GIT: <joel_tanoh>
 * @link     Link
 */

namespace App\BackEnd\Models\Users;

use App\BackEnd\Bdd\BddManager;

/**
 * Classe qui gère tout ce qui est en relation avec le fournisseur de service.
 * 
 * @category Category
 * @package  Users
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com License
 * @link     Link
 */

class ServiceProvider extends User
{
    /**
     * Nom de la table dans la base de données.
     * 
     * @var string
     */
    const TABLE_NAME = "services_providers";
    
    /**
     * Retourne une instance de suscriber.
     * 
     * @param string $emailAddress
     * 
     * @return self
     */
    public static function getByEmail(string $emailAddress)
    {
        $query = "SELECT code FROM " . self::TABLE_NAME . " WHERE email_address = ?";
        $rep = parent::connect()->prepare($query);
        $rep->execute([$emailAddress]);
        $result = $rep->fetch();

        if ($result["code"]) {
            return new self($result["code"]);
        }
    }

}
