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

namespace App\BackEnd\Models\Personnes;

/**
 * Classe qui gère tout ce qui est en relation avec le client.
 * Le client est le visiteur qui a commandé un service et qui
 * est enregistré dans la base de données.
 * 
 * PHP verison 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @link     Link
 */
class Client extends Personne
{
    /**
     * Retourne la liste des personnes qui sont enregistrées dans la base de données.
     * 
     * @return array
     */
    public function getClients()
    {

    }

}
