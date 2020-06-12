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
use App\BackEnd\Utils\Utils;

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
            ->select("id, code, categorie, title, description, slug, price, rank, created_at, youtube_video_link, views")
            ->from(self::TABLE_NAME)
            ->where("code = ?")
            ->returnQueryString();

        $rep = $pdo->prepare($query);
        $rep->execute([$code]);
        $result = $rep->fetch();

        $this->id               = $result['id'];
        $this->code             = $result['code'];
        $this->categorie        = $result['categorie'];
        $this->title            = $result['title'];
        $this->slug             = $result['slug'];
        $this->description      = $result['description'];
        $this->youtubeVideoLink = $result['youtube_video_link'];
        $this->price            = $result['price'];
        $this->rank             = $result['rank'];
        $this->createdAt        = $result['created_at'];
        $this->views            = $result["views"];
        $this->tableName        = self::TABLE_NAME;
 
        // Children
        $result = parent::bddManager()->get("code", ItemChild::TABLE_NAME, "parent_id", $this->id);
        foreach ($result as $child) {
            $child = new ItemChild($child["code"]);
            $this->children[] = $child;
        }

        // Les souscrivants
        $result = parent::bddManager()->get("suscriber_id", Subscription::TABLE_NAME, "item_id", $this->id);
        foreach ($result as $subscriber) {
            $suscriberCode = parent::bddManager()->get("code", Suscriber::TABLE_NAME, "id", $result["suscriber_id"]);
            $suscriber = new Suscriber($suscriberCode["code"]);
            $this->suscribers[] = $suscriber;
        }
    }

    /**
     * Pemet de savoir s'il y'a des souscrivants à l'élément.
     * 
     * @return bool True s'il y'a des souscriptions, false dans le cas contraire.
     */
    public function isSuscribed()
    {
        $bddManager = parent::bddManager();
        $result = $bddManager->count("id", Subscription::TABLE_NAME, "item_id", $this->id);
        return $result != 0;
    }

    /**
     * Retourne ceux qui ont souscrit à l'item.
     * 
     * @return array
     */
    public function getSuscribers()
    {
        return $this->suscribers;
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
        if ($categorie !== null) {
            return array_map( function ($child, $categorie) {
                return $child->getCategorie() === $categorie;
            }, $this->children );
        }
        return $this->children;
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
     * Retourne toutes les catégories des Items parents.
     * 
     * @return array
     */
    public static function getCategories()
    {
        $query = "SELECT categories FROM " . self::TABLE_NAME;
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
        $code = Utils::generateCode();

        $title              = htmlspecialchars($_POST["title"]);
        $description        = htmlspecialchars($_POST["description"]);
        $rank               = $_POST["rank"]                ?? null;
        $price              = $_POST["price"]               ?? null;
        $youtubeVideoLink   = $_POST["youtube_video_link"]  ?? null;
        
        if (parent::insertNotNullData(self::TABLE_NAME, $code, $title, $description, $categorie)) {

            $newThis = new self($code);

            $slug = Utils::slugify($newThis->getTitle()) . '-' . $newThis->getID();
            $newThis->set("slug", $slug, self::TABLE_NAME);

            $newThis->setRank((int)$rank);

            $newThis->set("price", (int)$price, self::TABLE_NAME);

            $newThis->set("youtube_video_link", $youtubeVideoLink, self::TABLE_NAME);

            $newThis = $newThis->refresh();

            return $newThis;
        }
    }

    /**
     * Mets à jour un item parent.
     * 
     * @param
     * 
     * @return self
     */
    public function update()
    {
        $imageManager = new Image();

        $title              = htmlspecialchars($_POST["title"]);
        $description        = htmlspecialchars($_POST["description"]);
        $rank               = $_POST["rank"]                ?? null;
        $price              = $_POST["price"]               ?? null;
        $youtubeVideoLink   = $_POST["youtube_video_link"]  ?? null;
        
        if ($title === $this->getTitle() && !empty($_FILES["image_uploaded"]["name"])) {
            $imageManager->saveImages($this->getCategorie() . "-" . $this->getSlug());
            $slug = $this->getSlug();
        }

        if ($title !== $this->getTitle()) {

            $slug = Utils::slugify($title) . '-' . $this->getID();
            $oldThumbsName = $this->getThumbsName();
            $newThumbsName = $this->getCategorie() . "-" . $slug;

            if (empty($_FILES["image_uploaded"]["name"])) {
                $imageManager->renameImages($oldThumbsName, $newThumbsName);
            } else {
                $imageManager->saveImages($newThumbsName);
                $imageManager->deleteImages($oldThumbsName);
            }
        }

        $this->set("title", $title, $this->tableName, "id", $this->id);
        
        $this->set("description", $description, $this->tableName, "id", $this->id);

        $this->set("slug", $slug, $this->tableName, "id", $this->id);
        
        $this->set("price", (int)$price, $this->tableName, "id", $this->id);

        $this->setRank((int)$rank);

        $this->set("youtube_video_link", $youtubeVideoLink, $this->tableName, "id", $this->id);

        $itemUpdated = $this->refresh();

        Utils::header($itemUpdated->getUrl("administrate"));
    }

    /**
     * Retourne le nombre d'item parent.
     * 
     * @param string $categorie
     * 
     * @return int
     */
    public static function getNumber(string $categorie = null)
    {
        if (null !== $categorie) {
            $counter = parent::bddManager()->count("id", self::TABLE_NAME, "categorie", $categorie);
        } else {
            $counter = parent::bddManager()->count("id", self::TABLE_NAME);
        }

        return (int)$counter;
    }


}