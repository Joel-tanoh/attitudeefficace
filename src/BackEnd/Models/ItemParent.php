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

use App\BackEnd\Models\Model;
use App\BackEnd\Bdd\BddManager;
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
class ItemParent extends Model
{
    const TABLE_NAME = "item_parents";
    const CATEGORIES = [
        "formations",
        "themes",
        "etapes",
    ];

    /**
     * Les items de categorie éléments, enfants de la catégorie courante.
     * 
     * @var array
     */
    public $children = [];

    /**
     * Constructeur d'une catégorie. Prend en paramètre le code
     * de la catégorie qu'on veut instancier.
     *
     * @param string $code Code de la catégorie.
     */
    public function __construct(string $code)
    {
        $bdd = BddManager::connectToDb();
        $sql_query1 = new SqlQueryFormater();

        $query = $sql_query1
            ->select("id, code, slug, categorie, title, description, price, rang, youtube_video_link, views")
            ->select("date_format(date_creation, '%d/%m/%Y') AS day_creation")
            ->select("date_format(date_creation, '%H:%i') AS hour_creation")
            ->select("date_format(date_modification, '%d/%m/%Y') AS day_modification")
            ->select("date_format(date_modification, '%H:%i') AS hour_modification")
            ->select("date_format(date_post, '%d/%m/%Y') AS day_post")
            ->select("date_format(date_post, '%H:%i') AS hour_post")
            ->from(self::TABLE_NAME)
            ->where("code = ?")
            ->returnQueryString();

        $rep = $bdd->prepare($query);
        $rep->execute([$code]);
        $result = $rep->fetch();

        $this->id = $result['id'];
        $this->code = $result['code'];
        $this->categorie = $result['categorie'];
        $this->title = $result['title'];
        $this->description = $result['description'];
        $this->youtube_video_link = $result['youtube_video_link'];
        $this->price = $result['price'];
        $this->rang = $result['rang'];
        $this->day_creation = $result["day_creation"];
        $this->hour_creation = $result["hour_creation"];
        $this->day_modification = $result["day_modification"];
        $this->hour_modification = $result["hour_modification"];
        $this->day_post = $result["day_post"];
        $this->hour_post = $result["hour_post"];
        $this->views = $result["views"];
        $this->table = self::TABLE_NAME;
        $this->slug = $result["slug"];
        
        // variables relatives à l'image
        $this->image_name = $this->categorie . "-" . $this->slug . IMAGES_EXTENSION;
        $this->thumbs_path = THUMBS_PATH . $this->image_name;
        $this->thumbs_src = THUMBS_DIR_URL . "/" . $this->image_name;
        $this->original_image_path = ORIGINALS_IMAGES_PATH . $this->image_name;
        $this->original_image_src =  ORIGINALS_IMAGES_DIR . "/" . $this->image_name;

        // Les urls de l'objet pour le localiser
        $this->url = $this->categorie . "/" . $this->slug;
        $this->public_url = PUBLIC_URL . "/" . $this->url;
        $this->admin_url = ADMIN_URL . "/" . $this->url;
        $this->edit_url = $this->admin_url . "/edit";
        $this->delete_url = $this->admin_url . "/delete";
        $this->post_url = $this->admin_url . "/post";
        $this->share_url = $this->admin_url . "/share";

        // Children
        $query = "SELECT code FROM " . ItemChild::TABLE_NAME . " WHERE parent_id = ?";
        $rep = $bdd->prepare($query);
        $rep->execute([$this->id]);
        $this->children = $rep->fetchAll();
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
        return BddManager::getSlugsFrom(self::TABLE_NAME);
    }

}