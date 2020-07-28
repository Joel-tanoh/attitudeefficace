<?php

/**
 * Fichier de classe.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Users
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @link     link
 */

namespace App\BackEnd\Models\Users;

use App\BackEnd\Bdd\SqlQueryFormater;
use App\BackEnd\Files\Image;
use App\BackEnd\Models\Entity;
use App\BackEnd\Utilities\Utility;
use Exception;

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
class User extends \App\BackEnd\Models\Entity
{
    /**
     * Login
     * 
     * @var string
     */
    protected $login;
    
    /**
     * Nom/titre de l'instance
     * 
     * @var string
     */
    protected $lastName;

    /**
     * Prénom
     * 
     * @var string
     */
    protected $firstNames;
    
    /**
     * Password
     * 
     * @var string
     */
    protected $password;

    /**
     * Role de l'utilisateur.
     * 
     * @var int
     */
    protected $role;
  
    /**
     * Statut autorisé ou bloqué.
     * 
     * @var string
     */
    protected $state;

    /**
     * Date de naissance
     * 
     * @var string
     */
    protected $birthDay;

    /**
     * Numéro de téléphone
     * 
     * @var string
     */
    protected $contact1;
    
    /**
     * Numéro de téléphone
     * 
     * @var string
     */
    protected $contact2;
    
    /**
     * Adresse mail
     * 
     * @var string
     */
    protected $emailAddress;

    /**
     * Catégorie.
     * 
     * @var string
     */
    const CATEGORIE = "users";

    /**
     * Url de la catégorie.
     * 
     * @var string
     */
    const URL = ADMIN_URL."/". self::CATEGORIE;

    /**
     * Permet de sauvegarder l'avatar d'un compte qui vient d'être créé dans les
     * fichiers et d'enregistrer le nom dans la base de données.
     * 
     * @return bool
     */
    public function setAvatar()
    {
        $image = new Image();
        $avatarName = $this->getLogin() ."-". $this->getID();
        $image->saveAvatar($avatarName);
    }
    
    /**
     * Permet de mettre à jour le role de l'utilisateur.
     * 
     * @param string $role Nouveau role.
     * 
     * @return bool
     */
    public function setRole($role)
    {
        $this->set("role", $role, $this->tableName, "id", $this->id);
        return true;
    }

    /**
     * Supprime un administrateur définitivement.
     * 
     * @return void
     */
    public function delete()
    {
        parent::bddManager()->delete($this->tableName, "id", $this->id);
        return true;
    }

    /**
     * Retourne le login de l'utilisateur.
     * 
     * @return string
     */
    public function getLogin() 
    {
        return ucfirst($this->login);
    }

    /**
     * Retourne le nom entier de l'utilsateur.
     * 
     * @return string
     */
    public function getName()
    {
        return $this->getLastName() . " " . $this->getFirstNames();
    }

    /**
     * Retourne le nom d'un utilisateur.
     * 
     * @return string
     */
    public function getLastName()
    {
        return ucfirst($this->lastName);
    }

    /**
     * Retourne les prénoms
     * 
     * @reuturn string
     */
    public function getFirstNames()
    {
        return $this->firstNames;
    }

    /**
     * Retourne le mot de passe.
     * 
     * @return string
     */
    public function getPassword() 
    {
        return $this->password;
    }

    /**
     * Retourne le role de l'utilisateur.
     * 
     * @return string
     */
    public function getRole()
    {
        if ($this->role === "1") return "utilisateur";
        if ($this->role === "2") return "administrateur 1";
        if ($this->role === "3") return "administrateur 2";
    }

    /**
     * Retourne l'état du compte de l'utilisateur.
     * 
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Retourne l'adresse email.
     * 
     * @return string
     */
    public function getEmailAddress() 
    {
        return $this->emailAddress;
    }

    /**
     * Retourne la date de naissance de l'utilisateur.
     * 
     * @return string
     */
    public function getBirthDay()
    {
        return $this->birthDay;
    }

    /**
     * Retourne l'age de l'utilisateur.
     * 
     * @return string
     */
    public function getAge()
    {
        
    }
    
    /**
     * Retourne le nom du fichier avatar.
     * 
     * @return string
     */
    public function getAvatarName() 
    {
        return $this->avatarName;
    }

    /**
     * Retourne le chemin de l'avatar.
     * 
     * @return string
     */
    public function getAvatarPath()
    {
        return $this->avatarPath;
    }

    /**
     * Retourne l'uri (la source) de l'avatar.
     * 
     * @return string
     */
    public function getAvatarSrc()
    {
        return file_exists($this->avatarPath) ? $this->avatarSrc : Image::DEFAULT_AVATAR;
    }

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
        return $login === strtolower($this->getLogin()) && password_verify($password, $this->getPassword());
    }

    /**
     * Retourne les activités effectuée par l'utilisateur.
     * 
     * @return void
     */
    public function getActivities()
    {
        
    }
    
    /**
     * Retourne le premier contact.
     * 
     * @return string
     */
    public function getContact1()
    {
        return $this->contact1;
    }

    /**
     * Retourne le deuxième contact.
     * 
     * @return string
     */
    public function getContact2()
    {
        return $this->contact2;
    }
    
    ////////////////////////////////////////// LES VUES ///////////////////////////////////////////


}
