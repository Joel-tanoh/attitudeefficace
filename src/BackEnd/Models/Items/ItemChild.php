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
            ->select("id, code, categorie, slug, title, description, price, rank, youtube_video_link, article_content, views, parent_id")
            ->select("date_format(created_at, '%d %b. %Y') AS day_created_at")
            ->select("date_format(created_at, '%H:%i') AS hour_created_at")
            ->select("date_format(updated_at, '%d %b. %Y') AS day_modified_at")
            ->select("date_format(updated_at, '%H:%i') AS hour_modified_at")
            ->select("date_format(posted_at, '%d %b. %Y') AS day_posted_at")
            ->select("date_format(posted_at, '%H:%i') AS hour_posted_at")
            ->from(self::TABLE_NAME)
            ->where("code = ?")
            ->returnQueryString();
        $rep = $pdo->prepare($query);
        $rep->execute([$code]);
        $result = $rep->fetch();

        $this->id = $result['id'];
        $this->code = $result['code'];
        $this->categorie = $result['categorie'];
        $this->title = $result['title'];
        $this->description = $result['description'];
        $this->articleContent = $result["article_content"];
        $this->youtubeVideoLink = $result['youtube_video_link'];
        $this->price = $result['price'];
        $this->rank = $result['rank'];
        $this->dayCreatedAt = $result["day_created_at"];
        $this->hourCreatedAt = $result["hour_created_at"];
        $this->dayUpdatedAt = $result["day_modified_at"];
        $this->hourUpdatedAt = $result["hour_modified_at"];
        $this->dayPostdeAt = $result["day_posted_at"];
        $this->hourPostedAt = $result["hour_posted_at"];
        $this->getArticleContent = $result['article_content'];
        $this->views = $result["views"];
        $this->tableName = self::TABLE_NAME;

        $this->slug = $result["slug"];
        $this->thumbsName = $this->categorie . "-" . $this->slug . IMAGES_EXTENSION;
        $this->OriginalThumbsPath = ORIGINALS_THUMBS_PATH . $this->thumbsName;
        $this->thumbsPath = THUMBS_PATH . $this->thumbsName;
        $this->originalThumbsSrc = ORIGINALS_THUMBS_DIR . "/" . $this->thumbsName;
        $this->thumbsSrc = THUMBS_DIR_URL . "/" . $this->thumbsName;

        // Les urls de l'objet pour le localiser
        $this->url = $this->categorie . "/" . $this->slug;
        $this->public_url = PUBLIC_URL . "/" . $this->categorie . "/" . $this->slug;
        $this->administrationUrl = ADMIN_URL . "/" . $this->url;
        $this->editUrl = $this->administrationUrl . "/edit";
        $this->deleteUrl = $this->administrationUrl . "/delete";
        $this->postUrl = $this->administrationUrl . "/post";
        $this->shareUrl = $this->url . "/share";

        $this->parentId = $result["parent_id"];
        if ($this->parentId) {
            if ($this->parentId == "-1") {
                $this->parent = "motivation plus";
            } else {
                $result = parent::bddManager()->get("code", ItemParent::TABLE_NAME, "id", $this->parentId);
                if (!empty($result["code"])) {
                    $this->parent = new ItemParent($result["code"]);
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

        foreach ($bddSlugs as $r) {
            $slugs[] = $r["slug"];
        }

        return $slugs;
    }
}

