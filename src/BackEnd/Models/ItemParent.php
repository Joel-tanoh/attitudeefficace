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
use App\BackEnd\APIs\Bdd;
use App\BackEnd\APIs\SqlQuery;

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
class ItemParent extends Data
{
    const TABLE_NAME = "item_parents";
    const CATEGORIES = [
        "formation","formations",
        "theme","themes",
        "etape","etapes",
    ];

    /**
     * Les items de categorie éléments, enfants de la catégorie courante.
     * 
     * @var array
     */
    protected $children = [];

    /**
     * Constructeur d'une catégorie. Prend en paramètre le code
     * de la catégorie qu'on veut instancier.
     *
     * @param string $code Code de la catégorie.
     */
    public function __construct(string $code)
    {
        $bdd = Bdd::connectToDb();
        $sql_query1 = new SqlQuery();

        $query = $sql_query1
            ->select("id, code, slug, categorie, title, description, price, rang, video_link, views")
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
        $this->video_link = $result['video_link'];
        $this->prix = $result['price'];
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
        $this->image_name = $this->categorie . "-" . $this->slug . IMAGES_EXTENSION;
        $this->cover_src =  COVERS_DIR . "/" . $this->image_name;
        $this->thumbs_src = THUMBS_DIR . "/" . $this->image_name;
        $this->covers_path = COVERS_PATH . $this->image_name;
        $this->thumbs_path = THUMBS_PATH . $this->image_name;

        $this->url = ADMIN_URL . "/" . $this->categorie . "/" . $this->slug;
        $this->edit_url = $this->url . "/edit";
        $this->delete_url = $this->url . "/delete";
        $this->post_url = $this->url . "/post";
        $this->share_url = $this->url . "/share";

        // Children
        $query = "SELECT code FROM " . ItemChild::TABLE_NAME . " WHERE parent_id = ?";
        $rep = $bdd->prepare($query);
        $rep->execute([$this->id]);
        $this->children = $rep->fetchAll();
    }

}