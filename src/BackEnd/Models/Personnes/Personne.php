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
    protected $id;

    /**
     * Code unique qui identifie une personne.
     * 
     * @var string
     */
    protected $code;
    
    /**
     * Login
     * 
     * @var string
     */
    protected $login;
 
    /**
     * Password
     * 
     * @var string
     */
    protected $password;
  
    /**
     * Statut autorisé ou bloqué
     * 
     * @var string
     */
    protected $statut;
       
    /**
     * Nom
     * 
     * @var string
     */
    protected $name;

    /**
     * Prénom
     * 
     * @var string
     */
    protected $first_name;
    
    /**
     * Date de naissance
     * 
     * @var string
     */
    protected $date_naissance;
    
    /**
     * Date de création
     * 
     * @var string
     */
    protected $date_creation;
    
    /**
     * Date de modication
     * 
     * @var string
     */
    protected $date_modification;

    /**
     * Age
     * 
     * @var string
     */
    protected $age;
    
    /**
     * Numéro de téléphone
     * 
     * @var string
     */
    protected $contact_1;
    
    /**
     * Numéro de téléphone
     * 
     * @var string
     */
    protected $contact_2;
    
    /**
     * Adresse mail
     * 
     * @var string
     */
    protected $email;

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
