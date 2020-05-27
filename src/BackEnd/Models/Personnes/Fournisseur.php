<?php

/**
 * Fichier de classe
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Personnes
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com License
 * @version  GIT: <joel_tanoh>
 * @link     Link
 */

namespace App\BackEnd\Models\Personnes;

use App\BackEnd\BddManager;

/**
 * Classe qui gère tout ce qui est en relation avec le fournisseur de service.
 * 
 * @category Category
 * @package  Personnes
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com License
 * @link     Link
 */

class Fournisseur extends Personne
{
    /**
     * Permet de créer un nouveau fournisseur.
     * 
     * @return void
     */
    public function create()
    {
        $bdd = BddManager::connectToDb();
    }
    
    // /**
    //  * Retourne tous les noms de fournisseurs ordonnés par noms.
    //  * 
    //  * @return [[Type]] [[Description]]
    //  */
    // public static function getAll()
    // {
    //     global $bdd;
    //     $query = "SELECT *, provider_id as value, concat(provider_name, ' ', provider_first_name) as opt FROM services_providers ORDER BY provider_name";
    //     $rep = $bdd->query($query);
    //     return $rep->fetchAll();
    // }
}
