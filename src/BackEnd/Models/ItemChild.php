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

namespace App\BackEnd\Models;

use App\BackEnd\Models\Model;
use App\BackEnd\APIs\Bdd;
use App\BackEnd\APIs\SqlQuery;

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
class ItemChild extends Model
{
    const TABLE_NAME = "item_childs";
    const CATEGORIES = [
        "article","articles",
        "video","videos",
        "ebook","ebooks",
        "livre","livres",
        "mini-service","mini-services","minis-service","minis-services",
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
        $bdd = Bdd::connectToDb();
        $sql_query = new SqlQuery();
        $query = $sql_query
            ->select("id, code, categorie, slug, title, description, price, video_link, article_content, views, parent_id")
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
        $this->price = $result['price'];
        $this->day_creation = $result["day_creation"];
        $this->hour_creation = $result["hour_creation"];
        $this->day_modification = $result["day_modification"];
        $this->hour_modification = $result["hour_modification"];
        $this->day_post = $result["day_post"];
        $this->hour_post = $result["hour_post"];
        $this->article_content = $result['article_content'];
        $this->views = $result["views"];
        $this->table = self::TABLE_NAME;

        $this->slug = $result["slug"];
        $this->image_name = $this->categorie . "-" . $this->slug . IMAGES_EXTENSION;
        $this->covers_path = COVERS_PATH . $this->image_name;
        $this->thumbs_path = THUMBS_PATH . $this->image_name;
        $this->cover_src = COVERS_DIR . "/" . $this->image_name;
        $this->thumbs_src = THUMBS_DIR . "/" . $this->image_name;

        $this->url = ADMIN_URL . "/" . $this->categorie . "/" . $this->slug;
        $this->edit_url = $this->url . "/edit";
        $this->delete_url = $this->url . "/delete";
        $this->post_url = $this->url . "/post";
        $this->share_url = $this->url . "/share";

        $this->parent_id = $result["parent_id"];
        if ($this->parent_id) {
            if ($this->parent_id == "-1") {
                $this->parent = "Motivation plus";
            } else {
                $sql_query = new SqlQuery();
                $query = $sql_query->
                    select("code, categorie")
                    ->from(ItemParent::TABLE_NAME)
                    ->where("id = ?")
                    ->returnQueryString();
                $rep = $bdd->prepare($query);
                $rep->execute([$this->parent_id]);
                $result = $rep->fetch();
                if (!empty($result)) {
                    $this->parent = Model::returnObject($result["categorie"], $result["code"]);
                }
            }
        } else {
            $this->parent = null;
        }
    }

}

