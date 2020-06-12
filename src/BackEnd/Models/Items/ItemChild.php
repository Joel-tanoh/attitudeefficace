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
use App\BackEnd\Files\Image;
use App\BackEnd\Utils\Utils;

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
            ->select("id, code, categorie, parent_id, title, description, slug, article_content, author, provider, pages, price, rank, edition_home, parution_year, created_at, youtube_video_link, views")
            ->from(self::TABLE_NAME)
            ->where("code = ?")
            ->returnQueryString();

        $rep = $pdo->prepare($query);
        $rep->execute([$code]);
        $result = $rep->fetch();

        $this->id = $result['id'];
        $this->code = $result['code'];
        $this->categorie = $result['categorie'];
        $this->parentId = $result["parent_id"];
        $this->title = $result['title'];
        $this->description = $result['description'];
        $this->slug = $result['slug'];
        $this->articleContent = $result["article_content"];
        $this->author = $result["author"];
        $this->provider = $result["provider"];
        $this->pages = $result["pages"];
        $this->price = $result['price'];
        $this->rank = $result['rank'];
        $this->editionHome = $result['edition_home'];
        $this->parutionYear = $result['parution_year'];
        $this->createdAt = $result['created_at'];
        $this->youtubeVideoLink = $result['youtube_video_link'];
        $this->views = $result["views"];

        $this->tableName = self::TABLE_NAME;

        // Le parent
        if ($this->parentId) {
            if ($this->parentId === "-1") {
                $this->parent = "motivation plus";
            } else {
                $result = parent::bddManager()->get("code", ItemParent::TABLE_NAME, "id", $this->parentId);
                if (!empty($result["code"])) {
                    $this->parent = new ItemParent($result[0]["code"]);
                }
            }
        }
    }

    /**
     * Retourne le parent.
     * 
     * @return ItemParent
     */
    public function getParent()
    {
        return $this->parent;
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
        $code = Utils::generateCode();

        $title              = htmlspecialchars($_POST["title"]);
        $description        = htmlspecialchars($_POST["description"]);
        $parentId           = $_POST["parent_id"]           ?? null;
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
                                                            
            $slug = Utils::slugify($newThis->getTitle()) . '-' . $newThis->getID();
            $newThis->set("slug", $slug, self::TABLE_NAME);

            $newThis->setRank((int)$rank);
           
            $newThis->set("parent_id", (int)$parentId, self::TABLE_NAME);

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
     * Mets à jour un item enfant.
     * 
     * @param 
     * 
     * @return self
     */
    public function update()
    {
        $imageManager = new Image();

        $title              = htmlspecialchars($_POST["title"]);
        $description        = $_POST["description"];
        $parentId           = $_POST["parent_id"]           ?? null;
        $articleContent     = $_POST["article_content"]     ?? null;
        $author             = $_POST["author_name"]         ?? null;
        $provider           = $_POST["provider"]            ?? null;
        $pages              = $_POST["pages"]               ?? null;
        $price              = $_POST["price"]               ?? null;
        $rank               = $_POST["rank"]                ?? null;
        $editionHome        = $_POST["edition_home"]        ?? null;
        $parutionYear       = $_POST["parution_year"]       ?? null;
        $youtubeVideoLink   = $_POST["youtube_video_link"]  ?? null;
        
        if ($title === $this->getTitle() && !empty($_FILES["image_uploaded"]["name"])) {
            $imageManager->saveImages($this->getCategorie() . "-" . $this->getSlug());
            $slug = $this->getSlug();
        }

        if ($title !== $this->getTitle()) {

            $slug = Utils::slugify($title) .'-'. $this->getID();
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
        
        $this->set("article_content", $articleContent, $this->tableName, "id", $this->id);

        $this->set("price", (int)$price, $this->tableName, "id", $this->id);

        $this->setRank((int)$rank);

        $this->set("youtube_video_link", $youtubeVideoLink, $this->tableName, "id", $this->id);

        $itemUpdated = $this->refresh();

        Utils::header($itemUpdated->getUrl("administrate"));
    }
}

