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

namespace App\BackEnd\Models\Items;

use App\BackEnd\Bdd\SqlQueryFormater;
use App\BackEnd\Files\Image;
use App\BackEnd\Models\Subscription;
use App\BackEnd\Models\Users\Suscriber;
use App\BackEnd\Utilities\Utility;
use App\View\Card;
use App\View\Snippet;

/**
 * Gère une catégorie
 *
 * @category Category
 * @package  App\BackEnd\Parents
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class ItemParent extends Item
{
    /**
     * Nom de la table
     */
    const TABLE_NAME = "items_parent";

    /**
     * Tableau des catégories d'item parent.
     */
    const CATEGORIES = [
        "formations",
        "themes",
        "etapes",
    ];

    /**
     * Les items enfants de l'item courant.
     * 
     * @var array
     */
    private $children = [];

    /**
     * Tableau des souscrivants à l'item courant.
     * 
     * @var array
     */
    private $suscribers = [];

    /**
     * Constructeur d'une catégorie. Prend en paramètre le code
     * de la catégorie qu'on veut instancier.
     *
     * @param string $code Code de la catégorie.
     */
    public function __construct(string $code)
    {
        $pdo = parent::connect();
        $sqlQuery1 = new SqlQueryFormater();
        $query = $sqlQuery1
            ->select("code, categorie, title, description, slug, price, rank, created_at, updated_at, posted_at, youtube_video_link, views")
            ->from(self::TABLE_NAME)
            ->where("code = ?")
            ->returnQueryString();

        $rep = $pdo->prepare($query);
        $rep->execute([$code]);
        $result = $rep->fetch();

        $this->code             = $result['code'];
        $this->categorie        = $result['categorie'];
        $this->title            = $result['title'];
        $this->description      = $result['description'];
        $this->slug             = $result['slug'];
        $this->price            = (int)$result['price'];
        $this->rank             = $result['rank'];
        $this->createdAt        = $result['created_at'];
        $this->updatedAt        = $result['updated_at'];
        $this->postedAt         = $result['posted_at'];
        $this->youtubeVideoLink = $result['youtube_video_link'];
        $this->views            = (int)$result["views"];
        $this->tableName        = self::TABLE_NAME;
    }

    /**
     * Pemet de savoir s'il y'a des souscrivants à l'élément.
     * 
     * @return bool True s'il y'a des souscriptions, false dans le cas contraire.
     */
    public function isSuscribed()
    {
        $bddManager = parent::bddManager();
        $result = $bddManager->count("code", Subscription::TABLE_NAME, "item_code", $this->code);
        return $result != 0;
    }

    /**
     * Retourne ceux qui ont souscrit à l'item.
     * 
     * @return array
     */
    public function getSuscribers()
    {
        $dbSuscribersIDs = parent::bddManager()->get("suscriber_email_address", Subscription::TABLE_NAME, "item_code", $this->code);

        $suscribers = [];

        if (!empty($dbSuscribersIDs)) {

            foreach ($dbSuscribersIDs as $subscriber) {
                $suscriber = parent::bddManager()->get("code", Suscriber::TABLE_NAME, "id", $subscriber["suscriber_email_address"]);
                $suscribers[] = new Suscriber($suscriber[0]["code"]);
            }
        }

        $this->suscribers = $suscribers;

        return $this->suscribers;
    }

    /**
     * Retourne le nombre de personnes ayant souscrit à cette 
     * instance.
     * 
     * @return int
     */
    public function getSuscribersNumber()
    {
        return (int)count($this->getSuscribers());
    }

    /**
     * Retourne les enfants de l'item courant.
     * 
     * @param string $categorie La catégorie des items enfants.
     * 
     * @return array
     */
    public function getChildren(string $categorie = null)
    {
        if (null === $categorie) {
            $dbChildren = parent::bddManager()->get("code", ItemChild::TABLE_NAME, "parent_code", $this->code);
        } else {
            $query = "SELECT code FROM " . ItemChild::TABLE_NAME
                . " WHERE parent_code = ? AND categorie = ?";

            $rep = parent::connect()->prepare($query);
            $rep->execute([$this->code, $categorie]);
            $dbChildren = $rep->fetchAll();
        }

        $children = [];

        if (!empty($dbChildren)) {
            foreach ($dbChildren as $child) {
                $children[] = new ItemChild($child["code"]);
            }
        }

        $this->children = $children;

        return $this->children;
    }

    /**
     * Retourne true si la chaine passée en paramètre est une catégorie.
     * 
     * @param string $slug 
     * 
     * @return bool
     */
    public static function isParentSlug(string $slug)
    {
        return in_array($slug, self::getSlugs());
    }

    /**
     * Vérifie si la catégorie passée en paramètre est une l'une des catégories des items
     * parents.
     * 
     * @param string $categorie La catégorie à vérifier.
     * 
     * @return bool.
     */
    public static function isParentCategorie(string $categorie)
    {
        return in_array($categorie, self::CATEGORIES);
    }

    /**
     * Retourne tous les slugs des items parents.
     * 
     * @return array
     */
    public static function getSlugs()
    {
        $slugs = [];
        $bddSlugs = parent::bddManager()->get("slug", self::TABLE_NAME);

        foreach ($bddSlugs as $row) {
            $slugs[] = $row["slug"];
        }

        return $slugs;
    }

    /**
     * Permet de vérifier que la chaine passée en paramètre est un code d'item 
     * Parent.
     * 
     * @param string $code 
     * 
     * @return bool
     */
    public static function isParentCode(string $code)
    {
        return in_array($code, self::getCodes());
    }

    /**
     * Retourne tous les codes des items parents.
     * 
     * @return array
     */
    public static function getCodes()
    {
        $codes = ["MTVP"];
        $bddCodes = parent::bddManager()->get("code", self::TABLE_NAME);

        foreach ($bddCodes as $row) {
            $codes[] = $row["code"];
        }

        return $codes;
    }

    /**
     * Retourne toutes les catégories des Items parents.
     * 
     * @return array
     */
    public static function getCategories()
    {
        $query = "SELECT DISTINCT categories FROM " . self::TABLE_NAME;
        $rep = parent::connect()->query($query);

        return $rep->fetchAll();
    }

    /**
     * Permet de créer une nouvelle occurrence dans la table des 
     * items parents.
     * 
     * @param string $categorie
     * 
     * @return \App\BackEnd\Models\Item
     */
    public static function create(string $categorie)
    {
        $code               = Utility::generateCode();
        $title              = htmlspecialchars($_POST["title"]);
        $description        = htmlspecialchars($_POST["description"]);
        $rank               = $_POST["rank"]                ?? null;
        $price              = $_POST["price"]               ?? null;
        $youtubeVideoLink   = $_POST["youtube_video_link"]  ?? null;
        
        if (parent::insertNotNullData(self::TABLE_NAME, $code, $title, $description, $categorie)) {

            $newThis = new self($code);

            $slug = Utility::slugify($newThis->title) . '-' . $newThis->id;
            $newThis->set("slug", $slug, self::TABLE_NAME);

            $newThis->setRank((int)$rank);

            $newThis->set("price", (int)$price, self::TABLE_NAME);

            $newThis->set("youtube_video_link", $youtubeVideoLink, self::TABLE_NAME);

            $newThis = $newThis->refresh();

            return $newThis;
        }
    }

    /**
     * Retourne tous les éléments.
     * 
     * @param string $categorie Un filtre sur la catégorie.
     * 
     * @return array Unt tableau contenant tous les objets parent sous forme d'objets.
     */
    public static function getAllItems(string $categorie = null)
    {
        $items = [];

        if (null === $categorie) {
            $result = parent::bddManager()->get("code", self::TABLE_NAME);
        } else {
            $result = parent::bddManager()->get("code", self::TABLE_NAME, "categorie", $categorie);
        }

        foreach ($result as $item) {
            $items[] = new self($item["code"]);
        }

        return $items;
    }

    /**
     * Retourne le nombre d'item.
     * 
     * @param string $categorie
     * 
     * @return int
     */
    public static function countAllItems(string $categorie = null)
    {
        return count(self::getAllItems($categorie));
    }

    /**
     * Retourne les items postés en fonction de la catégorie.
     * 
     * @param string $categorie La catégorie des items postés qu'on veut récupérer.
     * 
     * @return array $itemsPosted Un tableau qui contient les items objets
     */
    public static function getPosted(string $categorie = null)
    {
        $query = "SELECT code from " . self::TABLE_NAME
            . " WHERE categorie = ?"
            . " AND posted_at IS NOT NULL";
        
        $rep = parent::connect()->prepare($query);
        $rep->execute([$categorie]);
        $result = $rep->fecthAll();

        $itemsPosted = [];

        foreach($result as $item) {
            $item = new self($item["code"]);
            $itemsPosted[] = $item;
        }

        return $itemsPosted;
    }

    /**
     * Retourne les items qui ont été vus.
     * 
     * @param string $categorie La catégorie des items vus qu'on veut récupérer.
     * 
     * @return array $itemsPosted Un tableau qui contient les items objets
     */
    public static function getViewed(string $categorie = null)
    {
        $query = "SELECT code from " . self::TABLE_NAME
            . " WHERE categorie = ? AND views != 0";
        
        $rep = parent::connect()->prepare($query);
        $rep->execute([$categorie]);
        $result = $rep->fecthAll();

        $itemsViewed = [];

        foreach($result as $item) {
            $item = new self($item["code"]);
            $itemsViewed[] = $item;
        }

        return $itemsViewed;
    }

    /**
     * Retourne les items qui sont gratuits.
     * 
     * @param string $categorie La catégorie des items gratuits qu'on veut récupérer.
     * 
     * @return array $itemsFree Un tableau qui contient les items objets
     */
    public static function getFree(string $categorie = null)
    {
        $query = "SELECT code from " . self::TABLE_NAME
            . " WHERE categorie = ? AND price = 0";
        
        $rep = parent::connect()->prepare($query);
        $rep->execute([$categorie]);
        $result = $rep->fetchAll();

        $itemsFree = [];

        foreach($result as $item) {
            $item = new self($item["code"]);
            $itemsFree[] = $item;
        }

        return $itemsFree;
    }

    /**
     * Rétourne les items qui ont une vidéo de description.
     * 
     * @param string $categorie La catégorie des items qu'on veut récupérer.
     * 
     * @return array $itemsWithVideo Un tableau qui contient les items objets
     */
    public static function getItemsWithVideo(string $categorie = null)
    {
        $query = "SELECT code from " . self::TABLE_NAME
            . " WHERE categorie = ? AND youtube_video_link IS NOT NULL";
        
        $rep = parent::connect()->prepare($query);
        $rep->execute([$categorie]);
        $result = $rep->fecthAll();

        $items = [];

        foreach($result as $item) {
            $item = new self($item["code"]);
            $items[] = $item;
        }

        return $items;
    }

    /**
     * Retourne un tableau contenant la ou les propriétées passées en paramètre
     * pour des items parents.
     * 
     * @param string $property La propriété qu'on veut.
     * 
     * @return array
     */
    public static function getProperty(string $property)
    {
        $query = "SELECT ? FROM " .self::TABLE_NAME;

        $rep = parent::connect()->prepare($query);
        $rep->execute([$property]);

        return $rep->fetchAll();
    }

}