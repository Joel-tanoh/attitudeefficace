<?php

/**
 * Fichier de classe.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @link     link
 */

namespace App\BackEnd\Data\Personnes;

use App\BackEnd\APIs\Bdd;
use App\BackEnd\APIs\Files\Image;
use App\BackEnd\APIs\SqlQuery;
use App\BackEnd\Utils\Utils;
use Exception;

/**
 * Gère tout ce qui est en rapport à l'administrateur.
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @link     link
 */
class Administrateur extends Personne
{
    const TABLE_NAME = "administrateurs";
    const URL = ADMIN_URL . "/" . self::TABLE_NAME;
    
    /**
     * Construit un administrateur en prenant comme paramètre son code et remplit 
     * toutes les propriétés.
     * 
     * @param string $code Le code identificateur de l'administrateur dans la base de
     *                     données.
     */
    public function __construct(string $code)
    {
        $bdd = Bdd::connectToDb();
        $sql_query = new SqlQuery();

        $query = $sql_query
            ->select("id, code, login, password, email, type, statut")
            ->select("date_format(date_creation, '%d/%m/%Y') AS day_creation")
            ->select("date_format(date_creation, '%H:%i') AS hour_creation")
            ->select("date_format(date_modification, '%d/%m/%Y') AS day_modification")
            ->select("date_format(date_modification, '%H:%i') AS hour_modification")
            ->from(self::TABLE_NAME)
            ->where("code = ?")
            ->returnQueryString();

        $rep = $bdd->prepare($query);
        $rep->execute([$code]);
        $result = $rep->fetch();

        $this->id = $result['id'];
        $this->code = $result['code'];
        $this->login = $result['login'];
        $this->password = $result['password'];
        $this->type = $result['type'];
        $this->statut = $result['statut'];
        $this->email = $result['email'];
        $this->day_creation = $result["day_creation"];
        $this->hour_creation = $result["hour_creation"];
        $this->day_modification = $result["day_modification"];
        $this->hour_modification = $result["hour_modification"];
        $this->url = ADMIN_URL . '/' . self::TABLE_NAME . "/" . $this->code;
        $this->avatar_name = Utils::slugify($this->login)
            . "-" . $this->id . IMAGES_EXTENSION;
        $this->avatar_path = AVATARS_PATH . $this->avatar_name;
        $this->avatar_src = AVATARS_DIR . "/" . $this->avatar_name;
        $this->table = self::TABLE_NAME;
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
    public static function save(string $code, array $data)
    {
        extract($data);
        $login = mb_strtolower(htmlspecialchars($login));
        $password_hashed = password_hash(
            htmlspecialchars($password),
            PASSWORD_DEFAULT
        );
        if (self::_insertData($code, $login, $password_hashed)) {
            $new_account = new Administrateur($code);
            if (!empty($email)) {
                $new_account->set("email", $email, self::TABLE_NAME);
            }
            if (!empty($account_type)) {
                $new_account->set("type", $account_type, self::TABLE_NAME);
            }
            if (!empty($_FILES["avatar_uploaded"]["name"])) {
                $new_account->saveAvatar();
            }
            return true;
        } else {
            throw new Exception("Echec de l'enregistrement, veuillez réessayer ou contacter l'administrateur");
        }
    }

    /**
     * Permet de sauvegarder l'avatar d'un compte qui vient d'être créé dans les
     * fichiers et d'enregistrer le nom dans la base de données.
     * 
     * @return bool
     */
    public function saveAvatar()
    {
        $image = new Image();
        $avatar_name = $this->get("login") . "-" . $this->get("id");
        $image->saveAvatar($avatar_name);
        Utils::header(self::URL);
    }
    
    /**
     * Change le statut.
     * 
     * @param string $new_statut 
     * 
     * @return bool
     */
    public function changeStatut($new_statut)
    {
        $bdd = Bdd::connectToDb();
        $query = $bdd->prepare('UPDATE administrateurs SET statut = ? WHERE id = ?');
        $query->execute([$new_statut, $this->id]);
        return true;
    }

    /**
     * Supprime un administrateur définitivement.
     * 
     * @return void
     */
    public function delete()
    {
        $bdd = Bdd::connectToDb();
        $query = 'DELETE FROM administrateurs WHERE id = ?';
        $rep = $bdd->prepare($query);
        $rep->execute([$this->id]);
        Utils::header(self::URL);
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
        $bdd = Bdd::connectToDb();
        $sql_query = new SqlQuery();
        $query = $sql_query
            ->select("COUNT(id) AS administrateur")
            ->from(self::TABLE_NAME)
            ->where("login = ?")
            ->returnQueryString();
        $rep = $bdd->prepare($query);
        $rep->execute([$login]);
        $counter = $rep->fetch();
        return $counter['administrateur'] == 1;
    }

    /**
     * Retourne le login et le mot de passe de l'administrateur en prenant en
     * paramètre le login.
     * 
     * @param string $login [[Description]]
     * 
     * @author Joel
     * @return array [[Description]]
     */
    public static function getByLogin(string $login)
    {
        $bdd = Bdd::connectToDb();
        $sql_query = new SqlQuery();

        $query = $sql_query
            ->select("code, login, password")
            ->from(self::TABLE_NAME)
            ->where("login = ?")
            ->returnQueryString();
        
        $rep = $bdd->prepare($query);
        $rep->execute([mb_strtolower($login)]);
        $result = $rep->fetch();
        return new Administrateur($result["code"]);
    }

    /**
     * Insère le code, le login, le mot de passe, et le type dans la base de données.
     * 
     * @param string $code     Code.
     * @param string $login    Login.
     * @param string $password Mot de passe.
     * 
     * @return bool
     */
    private static function _insertData($code, $login, $password)
    {
        $bdd = Bdd::connectToDb();
        $query = "INSERT INTO " . self::TABLE_NAME
            . "(code, login, password)"
            . " VALUES(?, ?, ?)";
        $rep = $bdd->prepare($query);
        $rep->execute([$code,$login,$password]);
        return true;
    }

}
