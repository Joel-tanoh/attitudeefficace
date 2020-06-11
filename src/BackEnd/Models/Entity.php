<?php

/**
 * Description
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

namespace App\BackEnd\Models;

use Exception;
use App\BackEnd\Bdd\BddManager;
use App\BackEnd\Models\Items\Item;
use App\BackEnd\Models\Items\ItemParent;
use App\BackEnd\Models\Items\ItemChild;
use App\BackEnd\Models\Users\User;
use App\BackEnd\Models\Users\Suscriber;
use App\BackEnd\Models\MiniserviceOrder;

/**
 * Classe de gestion des données.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @link     Link
 */
class Entity
{
    /**
     * ID de l'instance dans la base de données
     * 
     * @var int
     */
    protected $id;
    
    /**
     * Code de l'instance
     * 
     * @var string
     */
    protected $code;

    /**
     * Slug de l'instance  
     * 
     * @var string
     */
    protected $slug;
 
    /**
     * Catégorie de l'instance
     * 
     * @var string
     */
    protected $categorie;

    /**
     * Description de l'instance  
     * 
     * @var string
     */
    protected $description;
 
    /**
     * La table où est stocké l'item.
     * 
     * @var string
     */
    protected $tableName;

    /**
     * Retourne une instance BddManager.
     * 
     * @return BddManager
     */
    public static function bddManager()
    {
        return new BddManager(DB_NAME, DB_LOGIN, DB_PASSWORD);
    }

    /**
     * Permet de se connecter à la base de données et retourne l'instance PDO.
     * 
     * @return PDOInstance
     */
    public static function connect()
    {
        return self::bddManager()->getPDO();
    }

    /**
     * Retourne l'ID.
     * 
     * @return int
     */
    public function getID()
    {
        return (int)$this->id;
    }

    /**
     * Retourne le code.
     * 
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Retourne la catégorie de l'item.
     * 
     * @return string
     */
    public function getCategorie() 
    {
        return $this->categorie;
    }

    /**
     * Retourne le slug de l'item.
     * 
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Retourne la description de l'élément courant.
     * 
     * @return string
     */
    public function getDescription() 
    {
        return nl2br(ucfirst(trim($this->description)));
    }

    /**
     * Retourne la date de création.
     * 
     * @param string $precision La précision dans la date de création.
     * 
     * @return string
     */
    public function getCreatedAt(string $precision = null)
    {
        if ($precision === "day") return $this->dayCreatedAt;
        elseif ($precision === "hour") return $this->hourCreatedAt;
        else return $this->dayCreatedAt . " à " . $this->hourCreatedAt;
    }

    /**
     * Retourne la date de mise à jour.
     * 
     * @param string $precision La partie dans la date de modification.
     * 
     * @return string
     */
    public function getUpdatedAt(string $precision = null)
    {
        if ($precision === "day") { return $this->dayUpdatedAt; }
        elseif ($precision === "hour") { return $this->hourUpdatedAt; }
        else { return $this->dayUpdatedAt . " à " . $this->hourUpdatedAt; }
    }

    /**
     * Retourne la date de publication.
     * 
     * @param string $precision La partie qu'on veut récupérer dans la date de
     *                          publication.
     * 
     * @return string
     */
    public function getPostedAt(string $precision = null)
    {
        if ($precision === "day") { return $this->dayPostedAt; }
        elseif ($precision === "hour") { return $this->hourPostedAt; }
        else { return $this->dayPostedAt . " à " . $this->hourPostedAt; }
    }

    /**
     * Retourne toutes les catégories.
     * 
     * @return array
     */
    public static function getAllCategories()
    {
        return array_merge(ItemParent::CATEGORIES, ItemChild::CATEGORIES);
    }

    /**
     * Retourne tous les slugs.
     * 
     * @return array
     */
    public static function getAllSlugs()
    {
        return array_merge(ItemParent::getSlugs(), ItemChild::getSlugs());
    }

    /**
     * Retourne un objet en fonction du slug, de la table et de la classe.
     * 
     * @param string $colName       Le nom de la colonne par laquelle on récupère
     *                          l'objet.
     * @param string $colValue  La valeur que doit avoir cette colonne.
     * @param string $tableName La table de laquelle récupérer les données.
     * @param string $categorie La classe ou la categorie de l'objet.
     * 
     * @return $object
     */
    public static function getObjectBy(string $colName = null, string $colValue = null, string $tableName = null, string $categorie = null)
    {
        $code = self::bddManager()->get("code", $tableName, $colName, $colValue)[0];
        return self::returnObjectByCategorie($categorie, $code["code"]);
    }

    /**
     * Retourne un objet en fonction du nom de la catégorie et du code pour
     * l'instanciation.
     * 
     * @param string $categorie La catégorie ou la classe de l'objet.
     * @param string $code      Le code pour instancier l'objet.
     * 
     * @return $object
     */
    public static function returnObjectByCategorie(string $categorie, string $code)
    {
        if (Item::isParentCategorie($categorie)) return new ItemParent($code);

        elseif (Item::isChildCategorie($categorie) || $categorie === "motivation-plus") return new ItemChild($code);
        
        elseif ($categorie === "utilisateurs")  return new User($code);

        elseif ($categorie === "commandes") return new MiniserviceOrder($code);
        
        else {
            throw new Exception("La méthode returnObject ne gère pas encore cette catégorie ou classe $categorie.");
        }
    }
     
    /**
     * Retourne le nom d'une table de la base de données en fonction d'une chaîne
     * de caractère passée en paramètre. Cette chaîne de caractère peut est la catégorie
     * d'un élément.
     * 
     * @param string $categorie La categorie
     * 
     * @return string Le nom de la table.
     */
    public static function getTableName(string $categorie = null)
    {
        if ($categorie == "utilisateurs") return User::TABLE_NAME;

        elseif (Item::isParentCategorie($categorie)) return ItemParent::TABLE_NAME;

        elseif (Item::isChildCategorie($categorie) || $categorie === "motivation-plus") return ItemChild::TABLE_NAME;

        elseif ($categorie === "commandes") return MiniserviceOrder::TABLE_NAME;

        else throw new Exception("La méthode getTableName ne gère pas encore la classe ou la catégorie $categorie.");
    }

    /**
     * Retourne l'url de la catégorie passée en paramètre.
     * 
     * @param string $categorie
     * @param string $appPartUrl
     * 
     * @return string
     */
    public static function getCategorieUrl(string $categorie, string $appPartUrl = PUBLIC_URL)
    {
        return $appPartUrl."/".$categorie;
    }

    /**
     * Retourne les catégories bien formatées, avec les accents, les tirets, etc.
     * 
     * @param string $categorie La catégorie à transformer
     * @param string $nombre    Singulier ou pluriel. Par défaut le nombre est au
     *                          singulier
     * 
     * @return string La catégorie bien formaté.
     */
    public static function getCategorieFormated(string $categorie, string $nombre = "singulier")
    {
        if ($categorie == "themes") { $categorie = "thème"; }
        if ($categorie == "videos") { $categorie = "vidéo"; }
        if ($categorie == "etapes") { $categorie = "étape"; }
        if ($categorie == "mini-services") { $categorie = "mini service"; }
        if ($categorie == "motivation-plus") { $categorie = "motivation plus"; }
            
        $categorieWordLength = strlen($categorie);
        $lastCategorieWordLetter = substr($categorie, $categorieWordLength - 1, 1);

        if ($nombre == "singulier" && $lastCategorieWordLetter == "s" && $categorie != "motivation plus") { 
            $categorie = substr($categorie, 0, $categorieWordLength - 1);
        }

        if ($nombre == "pluriel" && $lastCategorieWordLetter !== "s" && $categorie != "motivation plus") {
            $categorie .= "s";
        }

        return $categorie;
    }

    /**
     * Retourne le titre de la page de création en fonction de la catégorie.
     * 
     * @param string $categorie La catégorie
     * 
     * @return string
     */
    public static function getCreateItemPageTitle(string $categorie)
    {
        $femaleCategorieWords = ["formations", "etapes", "videos"];
        $categorieBeginningByVowel = ["articles", "ebooks"];

        if ($categorie == User::TABLE_NAME) {
            return "Nouveau utilisateur";
        } elseif (in_array($categorie, $femaleCategorieWords)) {
            return "Nouvelle " . self::getCategorieFormated($categorie);
        } elseif (in_array($categorie, $categorieBeginningByVowel)) {
            return "Nouvel " . self::getCategorieFormated($categorie);
        } else {
            return "Nouveau " . self::getCategorieFormated($categorie);
        }

    }
    
    /**
     * Retourne toutes adresses emails enregistrées dans le base de données.
     * 
     * @return array
     */
    public static function getAllEmails()
    {
        $newsletterEmailsAddress = self::bddManager()->get("email_address", "newsletters");
        $subscribersEmailsAddress = Suscriber::getAllEmailsAddress();
        return array_merge($newsletterEmailsAddress, $subscribersEmailsAddress);
    }

    /**
     * Mets à jour une propriété de l'élément.
     * 
     * @param string $colToUpdate La colonne qu'on veut mettre à jour.
     * @param mixed  $valueToPut  La valeur à insérer dans lcette colonne.
     * @param string $tableName   Le nom de la table.
     * @param string $idColName   La colonne à prendre en compte pour identifier 
     *                            l'élément à mettre à jour.
     * @param mixed  $idValue     L'identifiant de l'élément dont on veut mettre à jour
     *                            la propriété.
     * 
     * @return bool
     */
    protected function updateProp(string $colToUpdate, $valueToPut, string $tableName = null, string $idColName = null, $id = null) : bool
    {
        if (null === $id) $id = $this->id;
        if (null === $tableName) $tableName = $this->tableName;
        if (null === $idColName) $idColName = "id";

        self::bddManager()->update($colToUpdate, $valueToPut, $tableName, $idColName, $id);
        self::bddManager()->update("updated_at", date("Y-m-d H:i:s"), $tableName, $idColName, $id);

        return true;
    }

}