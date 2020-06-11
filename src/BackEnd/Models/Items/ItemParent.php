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
        $sql_query1 = new SqlQueryFormater();
        $query = $sql_query1
            ->select("id, code, slug, categorie, title, description, price, rank, youtube_video_link, views")
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
        $this->youtubeVideoLink = $result['youtube_video_link'];
        $this->price = $result['price'];
        $this->rank = $result['rank'];
        $this->dayCreatedAt = $result["day_created_at"];
        $this->hourCreatedAt = $result["hour_created_at"];
        $this->dayUpdatedAt = $result["day_modified_at"];
        $this->hourUpdatedAt = $result["hour_modified_at"];
        $this->dayPostedAt = $result["day_posted_at"];
        $this->hourPostedAt = $result["hour_posted_at"];
        $this->views = $result["views"];
        $this->slug = $result["slug"];
        $this->tableName = self::TABLE_NAME;
        
        // variables relatives à l'image
        $this->thumbsName = $this->categorie . "-" . $this->slug . IMAGES_EXTENSION;
        $this->thumbsPath = THUMBS_PATH . $this->thumbsName;
        $this->thumbsSrc = THUMBS_DIR_URL . "/" . $this->thumbsName;
        $this->originalThumbsPath = ORIGINALS_THUMBS_PATH . $this->thumbsName;
        $this->originalThumbsSrc =  ORIGINALS_THUMBS_DIR . "/" . $this->thumbsName;

        // Les urls de l'objet pour le localiser
        $this->url = $this->categorie . "/" . $this->slug;
        $this->publicUrl = PUBLIC_URL . "/" . $this->url;
        $this->administrationUrl = ADMIN_URL . "/" . $this->url;
        $this->editUrl = $this->administrationUrl . "/edit";
        $this->deleteUrl = $this->administrationUrl . "/delete";
        $this->postUrl = $this->administrationUrl . "/post";
        $this->shareUrl = $this->administrationUrl . "/share";

        // Children
        $result = parent::bddManager()->get("code", ItemChild::TABLE_NAME, "parent_id", $this->id);
        foreach ($result as $child) {
            $child = new ItemChild($child["code"]);
            $this->children[] = $child;
        }

        // Les souscrivants
        // $result = parent::bddManager()->get("")
    }

    /**
     * Pemet de savoir s'il y'a des souscrivants à l'élément.
     * 
     * @return bool
     */
    public function isSuscribed()
    {

    }

    /**
     * Retourne les enfants de l'item courant.
     * 
     * @return array
     */
    public function getChildren()
    {
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

        foreach ($bddSlugs as $r) {
            $slugs[] = $r["slug"];
        }

        return $slugs;
    }

}