<?php

/**
 * Fichier de classe.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Personnes
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @link     link
 */

namespace App\BackEnd\Models\Personnes;

use App\BackEnd\Models\Model;

/**
 * Gère tout ce qui concerne une personne.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @link     link
 */
class Personne extends Model
{
    /**
     * ID
     * 
     * @var int
     */
    public $id;

    /**
     * Code unique qui identifie une personne.
     * 
     * @var string
     */
    public $code;
    
    /**
     * Login
     * 
     * @var string
     */
    public $login;
 
    /**
     * Password
     * 
     * @var string
     */
    public $password;
  
    /**
     * Statut autorisé ou bloqué
     * 
     * @var string
     */
    public $statut;
       
    /**
     * Nom
     * 
     * @var string
     */
    public $name;

    /**
     * Prénom
     * 
     * @var string
     */
    public $first_name;
    
    /**
     * Date de naissance
     * 
     * @var string
     */
    public $date_naissance;
    
    /**
     * Date de création
     * 
     * @var string
     */
    public $date_creation;
    
    /**
     * Date de modication
     * 
     * @var string
     */
    public $date_modification;

    /**
     * Age
     * 
     * @var string
     */
    public $age;
    
    /**
     * Numéro de téléphone
     * 
     * @var string
     */
    public $contact_1;
    
    /**
     * Numéro de téléphone
     * 
     * @var string
     */
    public $contact_2;
    
    /**
     * Adresse mail
     * 
     * @var string
     */
    public $email;

    /**
     * Vérifie si une personne est authentifiée.
     * 
     * @param string $login 
     * @param string $password 
     * 
     * @return bool True si la personne s'est authentifiée
     */
    public function isAuthentified($login, $password)
    {
        return $login === strtolower($this->get("login")) && password_verify($password, $this->get("password"));
    }

    /**
     * Initalise les variables de sessions.
     * 
     * @param string $sess_key 
     * 
     * @return void
     */
    public function setSession($sess_key)
    {
        $_SESSION[$sess_key] = ucfirst($this->get("login"));
    }

    /**
     * Initialise les variables de cookie.
     * 
     * @param mixed  $cook_key La clé
     * @param mixed  $value    La valeur
     * @param string $domain 
     * 
     * @return void
     */
    public function setCookie($cook_key, $value, $domain = null)
    {
        setcookie(
            $cook_key,
            ucfirst($value),
            time()+(30*24*3600),
            null,
            $domain,
            false,
            true
        );
    }

    /**
     * Retourne les activités effectuée par l'utilisateur.
     * 
     * @return void
     */
    public function getActivities()
    {
        
    }
    
}
