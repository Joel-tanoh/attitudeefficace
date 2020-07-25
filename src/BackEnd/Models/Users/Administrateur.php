<?php

namespace App\BackEnd\Models\Users;

use App\BackEnd\Bdd\SqlQueryFormater;
use App\BackEnd\Files\Image;
use App\BackEnd\Utilities\Utility;
use Exception;

/**
 * Fichier de classe gestionnaire des administrateurs.
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */
class Administrateur extends User
{
    /**
     * Nom de la table.
     * 
     * @var string
     */
    const TABLE_NAME = "administrators";

    /**
     * Catégorie.
     * 
     * @var string
     */
    const CATEGORIE = "administrateurs";

    /**
     * Url de la catégorie.
     * 
     * @var string
     */
    const URL = ADMIN_URL."/". "administrateurs";
    
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
        $sqlQuery = new SqlQueryFormater();

        $query = $sqlQuery
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

        $this->id               = (int)$result['id'];
        $this->code             = $result['code'];
        $this->login            = $result['login'];
        $this->password         = $result['password'];
        $this->role             = (int)$result['role'];
        $this->state            = (int)$result['state'];
        $this->emailAddress     = $result['email_address'];
        $this->dayCreatedAt     = $result["day_created_at"];
        $this->hourCreatedAt    = $result["hour_created_at"];
        $this->dayUpdatedAt     = $result["day_modified_at"];
        $this->hourUpdatedAt    = $result["hour_modified_at"];
        $this->url              = self::TABLE_NAME . "/" . $this->code;
        $this->avatarName       = Utility::slugify($this->login) . "-" . $this->id . Image::EXTENSION;
        $this->avatarPath       = AVATARS_PATH . $this->avatarName;
        $this->avatarSrc        = AVATARS_DIR_URL . "/" . $this->avatarName;
        $this->tableName        = self::TABLE_NAME;
        $this->categorie        = self::CATEGORIE;
    }

    /**
     * Permet de sauvegarder les données pour un compte administrateur ou
     * utilisateur.
     * 
     * @return void
     */
    public static function create()
    {
        $code           = Utility::generateCode();
        $login          = mb_strtolower(htmlspecialchars( $_POST["login"] ));
        $passwordHashed = password_hash($_POST["login"], PASSWORD_DEFAULT);
        $emailAddress   = htmlspecialchars($_POST["email_address"]);
        $role           = htmlspecialchars($_POST["role"]);

        if (self::insertNotNullData( $code, $login, $passwordHashed )) {
            $newUser = new self($code);
        
            $newUser->set("email_address", $emailAddress, self::TABLE_NAME, "id", $newUser->getID());
        
            $newUser->set("role", $role, self::TABLE_NAME, "id", $newUser->getID());
            
            if (!empty($_FILES["avatar_uploaded"]["name"])) {
                $newUser->setAvatar();
            }

            return new self($code);

        } else {
            throw new Exception("Echec de l'enregistrement, veuillez réessayer ou si cela persiste, veuillez contacter l'administrateur");
        }
    }

    /**
     * Mets à jour un administrateur.
     * 
     * @return void
     */
    public function update()
    {

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
        $this->set("role", $role, $this->tableName, "id", $this->getID());
        return true;
    }

    /**
     * Supprime un administrateur définitivement.
     * 
     * @return void
     */
    public function delete()
    {
        parent::bddManager()->delete(self::TABLE_NAME, "id", $this->getID());
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
     * Crée un Administrateur par grâce à l'adresse email.
     * 
     * @param string $emailAddress
     * 
     * @return self
     */
    static function getByEmail(string $emailAddress)
    {
        $pdo = self::connect();
        $sql_query = new SqlQueryFormater();

        $query = $sql_query
                ->select("code, login, password")
                ->from(self::TABLE_NAME)
                ->where("email_address = ?")
                ->returnQueryString();
        
        $rep = $pdo->prepare($query);
        $rep->execute([mb_strtolower($emailAddress)]);
        $result = $rep->fetch();

        if ($result["code"]) return new self($result["code"]);
        else return false;
    }

    /**
     * Instancie un nouvel administrateur grâce à son login qui est unique.
     * 
     * @param string $login [[Description]]
     * 
     * @return self
     */
    public static function getByLogin(string $login)
    {
        $pdo = parent::connect();
        $sqlQuery = new SqlQueryFormater();
   
        $query = $sqlQuery
                ->select("code, login, password")
                ->from(self::TABLE_NAME)
                ->where("login = ?")
                ->returnQueryString();

        $rep = $pdo->prepare($query);
        $rep->execute([mb_strtolower($login)]);
        $result = $rep->fetch();
    
        if ($result["code"]) return new self($result["code"]);
        else return false;
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
     * Véririfie si l'administrateur a tous les droits.
     * 
     * @return bool
     */
    public function hasAllRights()
    {
        return $this->role == 3;
    }

    /**
     * Retourne tous les administrateurs qui ont le role passé en
     * paramètre.
     * 
     * @param int $role 
     * 
     * @return array
     */
    public static function getAll(int $role)
    {
        $result = parent::bddManager()->get("code", self::TABLE_NAME, "role", $role);
        $admins = [];

        foreach ($result as $admin) {
            $admins[] = new self($admin["code"]);
        }

        return $admins;
    }


    ///////////////////////// LES VUES ///////////////////////////
    
    /**
     * Liste tous les comptes administrateurs créées sur le site.
     * 
     * @param array $admins 
     * 
     * @return string
     */
    public static function list($admins)
    {
        $list = null;

        foreach ($admins as $admin) {
            $list .= self::listRow($admin);
        }

        return <<<HTML
        <div class="row">
            <div class="col-12">
                <table class="table border bg-white">
                    <thead class="thead-light">
                        <th>Login</th>
                        <th>Role</th>
                        <th>Adresse email</th>
                    </thead>
                    <tbody>
                        {$list}
                    </tbody>
                </table>
            </div>
        </div>
HTML;
    }

    /**
     * Unle ligne du tableau qui liste les comptes administrateurs.
     * 
     * @param self $admin
     * 
     * @return string
     */
    private static function listRow($admin)
    {
        return <<<HTML
        <tr>
            <td>{$admin->getLogin()}</td>
            <td>{$admin->getRole()}</td>
            <td>{$admin->getEmailAddress()}</td>
        </tr>
HTML;
    }


}