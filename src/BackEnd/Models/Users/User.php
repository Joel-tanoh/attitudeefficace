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
use App\BackEnd\Utils\Utils;
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
     * @var string
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
     * Nom de la table.
     * 
     * @var string
     */
    const TABLE_NAME = "users";

    /**
     * Url de la catégorie.
     * 
     * @var string
     */
    const URL = ADMIN_URL."/". self::TABLE_NAME;
    
    /**
     * Construit un administrateur en prenant comme paramètre son code et remplit 
     * toutes les propriétés.
     * 
     * @param string $code Le code identificateur de l'administrateur dans la base de
     *                     données.
     */
    public function __construct(string $code)
    {
        $pdo = self::connect();
        $sql_query = new SqlQueryFormater();

        $query = $sql_query
                ->select("id, code, login, password, email_address, role, state")
                ->select("date_format(created_at, '%d %b. %Y') AS day_created_at")
                ->select("date_format(created_at, '%H:%i') AS hour_created_at")
                ->select("date_format(updated_at, '%d %b. %Y') AS day_modified_at")
                ->select("date_format(updated_at, '%H:%i') AS hour_modified_at")
                ->from(self::TABLE_NAME)
                ->where("code = ?")
                ->returnQueryString();

        $rep = $pdo->prepare($query);
        $rep->execute([$code]);
        $result = $rep->fetch();

        $this->id = $result['id'];
        $this->code = $result['code'];
        $this->login = $result['login'];
        $this->password = $result['password'];
        $this->role = $result['role'];
        $this->state = $result['state'];
        $this->email_address = $result['email_address'];
        $this->dayCreatedAt = $result["day_created_at"];
        $this->hourCreatedAt = $result["hour_created_at"];
        $this->dayUpdatedAt = $result["day_modified_at"];
        $this->hourUpdatedAt = $result["hour_modified_at"];
        $this->url = ADMIN_URL . '/' . self::TABLE_NAME . "/" . $this->code;
        $this->avatarName = Utils::slugify($this->login) . "-" . $this->id . IMAGES_EXTENSION;
        $this->avatarPath = AVATARS_PATH . $this->avatarName;
        $this->avatarSrc = AVATARS_DIR_URL . "/" . $this->avatarName;
        $this->tableName = self::TABLE_NAME;
    }

    /**
     * Permet de sauvegarder les données pour un compte administrateur ou
     * utilisateur.
     * 
     * @param string $code Un code uniqe alphanumérique généré aléatoirement.
     * @param string $data 
     * 
     * @return void
     */
    public static function create(string $code, array $data)
    {
        extract($data);

        $login = mb_strtolower( htmlspecialchars( $login ) );
        $password_hashed = password_hash( $password, PASSWORD_DEFAULT );

        if (self::insertNotNullData( $code, $login, $password_hashed )) {
            $newUser = new self($code);
            
            if (!empty($email_address)) {
                $newUser->updateProp("email_address", $email_address, self::TABLE_NAME, "id", $new_user->getID());
            }

            if (!empty($account_type)) {
                $newUser->updateProp("role", $account_type, self::TABLE_NAME, "id", $new_user->getID());
            }

            if (!empty($_FILES["avatar_uploaded"]["name"])) {
                $new_user->setAvatar();
            }

            return new self($code);

        } else {
            throw new Exception("Echec de l'enregistrement, veuillez réessayer ou si cela persiste, veuillez contacter l'administrateur");
        }
    }

    /**
     * Permet de sauvegarder l'avatar d'un compte qui vient d'être créé dans les
     * fichiers et d'enregistrer le nom dans la base de données.
     * 
     * @return bool
     */
    public function setAvatar()
    {
        $image = new Image();
        $avatar_name = $this->getLogin() ."-". $this->getID();
        $image->saveAvatar($avatar_name);
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
        $this->updateProp("role", $role, $this->tableName, "id", $this->id);
        return true;
    }

    /**
     * Supprime un administrateur définitivement.
     * 
     * @return void
     */
    public function delete()
    {
        parent::bddManager()->delete(self::TABLE_NAME, "id", $this->id);
        return true;
    }

    /**
     * Vérifie si le login existe dans la base de données.
     * 
     * @param string $login [[Description]]
     * 
     * @return bool [[Description]]
     */
    static function loginIsset(string $login) : bool
    {
        $pdo = self::connect();

        $sql_query = new SqlQueryFormater();
        $query = $sql_query
                ->select("COUNT(id) AS user")
                ->from(self::TABLE_NAME)
                ->where("login = ?")
                ->returnQueryString();

        $rep = $pdo->prepare($query);
        $rep->execute([$login]);
        $counter = $rep->fetch();
        return $counter['user'] == 1;
    }

    /**
     * Crée un objet User par grâce à l'adresse email.
     * 
     * @param string $email_address
     * 
     * @return self
     */
    static function getByEmail(string $email_address)
    {
        $pdo = self::connect();
        $sql_query = new SqlQueryFormater();

        $query = $sql_query
                ->select("code, login, password")
                ->from(self::TABLE_NAME)
                ->where("email_address = ?")
                ->returnQueryString();
        
        $rep = $pdo->prepare($query);
        $rep->execute([mb_strtolower($email_address)]);
        $result = $rep->fetch();

        return new self($result["code"]);
    }

    /**
     * Retourne le login et le mot de passe de l'administrateur en prenant en
     * paramètre le login.
     * 
     * @param string $login [[Description]]
     * 
     * @author Joel
     * @return self
     */
    public static function getByLogin(string $login)
    {
        $pdo = self::connect();
        $sql_query = new SqlQueryFormater();

        $query = $sql_query
                ->select("code, login, password")
                ->from(self::TABLE_NAME)
                ->where("login = ?")
                ->returnQueryString();
        
        $rep = $pdo->prepare($query);
        $rep->execute([mb_strtolower($login)]);
        $result = $rep->fetch();

        return new self($result["code"]);
    }

    /**
     * Insère le code, le login, le mot de passe, et le categorie dans la base de données.
     * 
     * @param string $code     Code.
     * @param string $login    Login.
     * @param string $password Mot de passe.
     * 
     * @return bool
     */
    private static function insertNotNullData($code, $login, $password)
    {
        $pdo = self::connect();
        $query = "INSERT INTO " . self::TABLE_NAME . " (code, login, password) VALUES(?, ?, ?)";
        $rep = $pdo->prepare($query);
        $rep->execute([$code, $login, $password]);
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
        if ($this->role === "2") return "administrateur droits limités";
        if ($this->role === "3") return "administrateur tous droits";
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
        return null;
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
        return file_exists($this->avatarPath) ? $this->avatar_src : DEFAULT_AVATAR;
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
     * Initalise les variables de sessions.
     * 
     * @param string $sessionKey 
     * 
     * @return void
     */
    public function setSession($sessionKey)
    {
        $_SESSION[$sessionKey] = ucfirst($this->getLogin());
    }

    /**
     * Initialise les variables de cookie.
     * 
     * @param mixed  $cookieKey La clé identifiant le cookie.
     * @param mixed  $value     La valeur
     * @param string $domain 
     * 
     * @return void
     */
    public function setCookie($cookieKey, $value, $domain = null)
    {
        setcookie(
            $cookieKey,
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
