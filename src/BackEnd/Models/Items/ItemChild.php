<?php

/**
 * Fichier de classe.
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
use App\BackEnd\Utilities\Utility;
use App\Router;
use App\View\Notification;
use App\View\Snippet;
use App\View\Template;

/**
 * Gère tout ce qui concerne les éléments.
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class ItemChild extends Item
{
    /**
     * Nom de la table.
     * 
     * @var string
     */
    const TABLE_NAME = "items_child";

    /**
     * Les catégories.
     * 
     * @var array
     */
    const CATEGORIES = [
        "articles",
        "videos",
        "ebooks",
        "livres",
        "mini-services",
    ];

    /**
     * Instancie un nouvel élement en prenant en paramètre le code.
     * 
     * @param string $code Code de l'élément.
     * 
     * @return void
     */
    public function __construct(string $code)
    {
        $pdo = parent::connect();
        $sqlQuery = new SqlQueryFormater();
        $query = $sqlQuery
            ->select("code, categorie, parent_code, title, description, slug, article_content")
            ->select("author, provider, pages, price, rank, edition_home, parution_year, created_at")
            ->select("posted_at, updated_at, youtube_video_link, views")
            ->from(self::TABLE_NAME)
            ->where("code = ?")
            ->returnQueryString();

        $rep = $pdo->prepare($query);
        $rep->execute([$code]);
        $result = $rep->fetch();

        $this->code = $result['code'];
        $this->categorie = $result['categorie'];
        $this->parentCode = (int)$result["parent_code"];
        $this->title = $result['title'];
        $this->description = $result['description'];
        $this->slug = $result['slug'];
        $this->articleContent = $result["article_content"];
        $this->author = $result["author"];
        $this->provider = $result["provider"];
        $this->pages = (int)$result["pages"];
        $this->price = (int)$result['price'];
        $this->rank = (int)$result['rank'];
        $this->editionHome = $result['edition_home'];
        $this->parutionYear = $result['parution_year'];
        $this->createdAt = $result['created_at'];
        $this->updatedAt = $result['updated_at'];
        $this->postedAt = $result['posted_at'];
        $this->youtubeVideoLink = $result['youtube_video_link'];
        $this->views = (int)$result["views"];
        $this->tableName = self::TABLE_NAME;
    }

    /**
     * Retourne le parent.
     * 
     * @return \App\BackEnd\Models\Items\ItemParent
     */
    public function getParent()
    {
        if (empty($this->parentCode)) {
            $parent = null;
        } elseif ($this->parentCode === "MTVP") {
            $parent = "Motivation +";
        } else {
            $result = parent::bddManager()->get("code", ItemParent::TABLE_NAME, "code", $this->parentCode);

            if (!empty($result[0]["code"])) {
                $parent = new ItemParent($result[0]["code"]);
            } else {
                $parent = null;
            }
        }

        return $parent;
    }

    /**
     * Retourne le contenu de l'article.
     * 
     * @return string
     */
    public function getArticleContent()
    {
        return ucfirst(nl2br(trim(htmlspecialchars_decode($this->articleContent))));
    }

    /**
     * Vérifie si la chaîne passé en paramètre est un élément.
     * 
     * @param string $slug La chaîne à vérifier.
     * 
     * @return bool True si la chaîne passé en paramètre est un élément.
     */
    public static function isChildSlug(string $slug)
    {
        return in_array($slug, self::getSlugs());
    }

    /**
     * Vérifie si la catégorie passée en paramètre est une catégorie d'item enfant.
     * 
     * @param string $categorie La catégorie à vérifier.
     * 
     * @return bool
     */
    public static function isChildCategorie(string $categorie)
    {
        return in_array($categorie, self::CATEGORIES);
    }

    /**
     * Retourne tous les slugs des items enfants.
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
     * Permet de créer une nouvelle occurrence dans la table des items
     * enfants.
     * 
     * @param string $categorie 
     * 
     * @return \App\BackEnd\Models\Item
     */
    public static function create(string $categorie)
    {
        $code = Utility::generateCode();

        $title              = htmlspecialchars($_POST["title"]);
        $description        = htmlspecialchars($_POST["description"]);
        $parentCode         = $_POST["parent_code"]         ?? null;
        $articleContent     = $_POST["article_content"]     ?? null;
        $author             = $_POST["author"]              ?? null;
        $provider           = $_POST["provider"]            ?? null;
        $pages              = $_POST["pages"]               ?? null;
        $price              = $_POST["price"]               ?? null;
        $rank               = $_POST["rank"]                ?? null;
        $editionHome        = $_POST["edition_home"]        ?? null;
        $parutionYear       = $_POST["parution_year"]       ?? null;
        $youtubeVideoLink   = $_POST["youtube_video_link"]  ?? null;
        
        if ($categorie === "motivation-plus") $categorie = "videos";

        if (parent::insertNotNullData(self::TABLE_NAME, $code, $title, $description, $categorie)) {
            
            $newThis = new self($code);
                                                            
            $slug = Utility::slugify($newThis->title) . '-' . $newThis->getCode();
            
            $newThis->set("slug", $slug, self::TABLE_NAME);

            $newThis->setRank((int)$rank);
           
            $newThis->set("parent_code", $parentCode, self::TABLE_NAME);

            $newThis->set("price", (int)$price, self::TABLE_NAME);

            $newThis->set("article_content", htmlspecialchars($articleContent), self::TABLE_NAME);

            $newThis->set("author", $author, self::TABLE_NAME);

            $newThis->set("provider", $provider, self::TABLE_NAME);

            $newThis->set("pages", $pages, self::TABLE_NAME);

            $newThis->set("edition_home", $editionHome, self::TABLE_NAME);

            $newThis->set("parution_year", $parutionYear, self::TABLE_NAME);

            $newThis->set("youtube_video_link", $youtubeVideoLink, self::TABLE_NAME);

            $newThis = $newThis->refresh();

            return $newThis;
        }
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
     * Retourne tous les éléments.
     * 
     * @param string $categorie
     * 
     * @return array
     */
    public static function getAllItems(string $categorie = null)
    {
        if (null === $categorie) {
            $result = parent::bddManager()->get("code", self::TABLE_NAME);
        } else {
            $result = parent::bddManager()->get("code", self::TABLE_NAME, "categorie", $categorie);
        }

        $items = [];

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
            . " WHERE categorie = ?"
            . " AND views != 0";
        
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
            . " WHERE categorie = ?"
            . " AND price = 0";
        
        $rep = parent::connect()->prepare($query);
        $rep->execute([$categorie]);
        $result = $rep->fecthAll();

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
            . " WHERE categorie = ?"
            . " AND youtube_video_link IS NOT NULL";
        
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

}

