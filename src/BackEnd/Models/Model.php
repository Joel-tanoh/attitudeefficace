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
use App\BackEnd\Models\ItemParent;
use App\BackEnd\Models\ItemChild;
use App\BackEnd\Utils\Utils;
use App\BackEnd\Files\Image;
use App\BackEnd\Files\Pdf;
use App\BackEnd\Models\Persons\Administrateur;
use App\BackEnd\Models\Persons\Suscriber;

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
class Model
{
    /**
     * ID de l'instance dans la base de données
     * 
     * @var int
     */
    public $id;
    
    /**
     * Code de l'instance
     * 
     * @var string
     */
    public $code;

    /**
     * Catégorie de l'instance
     * 
     * @var string
     */
    public $categorie;
    
    /**
     * Nom/titre de l'instance
     * 
     * @var string
     */
    public $title;

    /**
     * Nom/titre de l'instance
     * 
     * @var string
     */
    public $name;

    /**
     * Slug de l'instance  
     * 
     * @var string
     */
    public $slug;
    
    /**
     * Description de l'instance  
     * 
     * @var string
     */
    public $description;
  
    /**
     * Lien de la vidéo de description de l'instance  
     * 
     * @var string
     */
    public $youtube_video_link;

    /**
     * Prix de l'instance.  
     * 
     * @var int
     */
    public $prix;

    /**
     * Rang de l'item.
     */
    public $rang;
    
    /**
     * Nom de l'image de couverture de l'instance  
     * 
     * @var string
     */
    public $image_name;

    /**
     * Chemin total de l'image miniature.
     * 
     * @var string
     */
    public $thumbs_path;

    /**
     * Source de l'image miniature
     * 
     * @var string
     */
    public $thumbs_src;

    /**
     * Chemin total de l'image de couverture.
     * 
     * @var string
     */
    public $original_image_path;

    /**
     * Source de l'image de couverture.
     * 
     * @var string
     */
    public $original_image_src;

    /**
     * La table où est stocké l'item.
     * 
     * @var string
     */
    public $table;

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
     * Retourne une propriéte de l'objet en fonction du nom de la prpriété passée
     * en paramètre.
     * 
     * @param string $property La propriété à retourner.
     * 
     * @return mixed
     */
    public function get(string $property)
    {
        if ($property == "id") return (int)$this->id;
        if ($property == "code") return $this->code;

        if ($property == "title") return $this->title;
        if ($property == "name") return ucfirst($this->name);
        if ($property == "categorie") return $this->categorie;
        if ($property == "slug") return $this->slug;
        if ($property == "login") return ucfirst($this->login);
        if ($property == "password") return $this->password;

        if ($property == "parent") return $this->parent;
        if ($property == "youtube_video_link") return $this->youtube_video_link;
        if ($property == "statut") return $this->statut;
        if ($property == "email") return $this->email;
        if ($property == "price" || $property == "prix")  return $this->price;
        if ($property == 'views') return $this->views;
        if ($property == "rang") return $this->rang;
        if ($property == 'posted') return $this->day_post ? "Oui" : "Non";
        if ($property == "description") return nl2br(ucfirst(trim($this->description)));
        if ($property == "classement") {
            if ($this->rang == 0 || $this->rang == null) {
                return "Non classé";
            } else {
                return $this->rang == 1 ? "Ordre : " . $this->rang . " er" : "Ordre : " . $this->rang . " eme";
            }
        }
        if ($property == "article_content") {
            if ($this->isChild()) {
                return ucfirst(nl2br(trim(htmlspecialchars_decode($this->article_content))));
            }
        }

        if ($property == "url") return $this->url;
        if ($property == "admin_url") return $this->admin_url;
        if ($property == "edit_url") return $this->edit_url;
        if ($property == "post_url") return $this->post_url;
        if ($property == "share_url") return $this->share_url;
        if ($property == "delete_url") return $this->delete_url;

        if ($property == "day_creation") return $this->day_creation;
        if ($property == "hour_creation") return $this->hour_creation;
        if ($property == "date_creation") return $this->day_creation . " à " . $this->hour_creation;
        if ($property == "day_modification") return $this->day_modification;
        if ($property == "hour_modification") return $this->hour_modification;
        if ($property == "date_modification") return $this->day_modification . " à " . $this->hour_modification;
        if ($property == "day_post") return $this->day_post;
        if ($property == "hour_post") return $this->hour_post;
        if ($property == "date_post") return $this->day_post . " à " . $this->hour_post;

        if ($property == "image_name") return $this->image_name;
        if ($property == "avatar_name") return $this->avatar_name;
        if ($property == "avatar_path") return $this->avatar_path;
        if ($property == "thumbs_src") return file_exists($this->thumbs_path) ? $this->thumbs_src : null;
        if ($property == "thumbs_path") return $this->thumbs_path;
        if ($property == "original_image_path") return $this->original_image_path;
        if ($property == "original_image_src")  return file_exists($this->original_image_path) ? $this->original_image_src : null;
        if ($property == "avatar_src") return file_exists($this->avatar_path) ? $this->avatar_src : DEFAULT_AVATAR;
    }

    /**
     * Vérifie si la chaîne passée en paramètre la catégorie d'un item.
     * 
     * @param string $string La chaîne sur laquelle on fait la vérification.
     * 
     * @return bool
     */
    public static function isCategorie(string $string)
    {
        return self::isParentCategorie($string) || self::isChildCategorie($string);
    }
    
    /**
     * Vérifie si la chaine de caractère passé en paramètre est un slug.
     * Retourne true si oui.
     * 
     * @param string $string La chaîne de caractère à tester.
     * 
     * @return bool
     */
    public static function isSlug($string)
    {
        return self::isParentSlug($string) || self::isChildSlug($string);
    }

    /**
     * Vérifie si la chaine de caractère passé en paramètre est un slug.
     * Retourne true si oui.
     * 
     * @param string $string La chaîne de caractère à tester.
     * 
     * @return bool
     */
    public static function isAction($string)
    {
        $actions = ["create", "read", "edit", "delete", "list",];
        return in_array($string, $actions);
    }

    /**
     * Vérifie si la chaîne passé en paramètre est une l'une des catégories des items
     * parents.
     * 
     * @param string $string La chaîne à vérifier.
     * 
     * @return bool.
     */
    public static function isParentCategorie(string $string)
    {
        return in_array($string, ItemParent::CATEGORIES);
    }

    /**
     * Vérifie si la chaîne passé en paramètre est une catégorie d'item enfant.
     * 
     * @param string $string La chaîne à vérifier.
     * 
     * @return bool
     */
    public static function isChildCategorie(string $string)
    {
        return in_array($string, ItemChild::CATEGORIES);
    }

    /**
     * Vérifie si un item est parent.
     * 
     * @return bool
     */
    public function isParent()
    {
        return in_array($this->categorie, ItemParent::CATEGORIES);
    }

    /**
     * Vérifie si un item est enfant.
     * 
     * @return bool
     */
    public function isChild()
    {
        return in_array($this->categorie, ItemChild::CATEGORIES);
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
     * Retourne tous les slugs de la table passée en paramètre.
     * 
     * @param string $table Le nom de la table de laquelle on récupère le slug.
     * 
     * @return array
     */
    public static function getSlugsFrom(string $table)
    {
        $slugs = [];
        foreach (self::bddManager()->get("slug", $table) as $row) {
            $slugs[] = $row["slug"];
        }
        return $slugs;
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
     * Retourne true si la chaine passée en paramètre est une catégorie.
     * 
     * @param string $slug 
     * 
     * @return bool
     */
    public static function isParentSlug(string $slug)
    {
        return in_array($slug, self::getSlugsFrom(ItemParent::TABLE_NAME));
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
        return in_array($slug, self::getSlugsFrom(ItemChild::TABLE_NAME));
    }

    /**
     * Retourne un objet en fonction du slug, de la table et de la classe.
     * 
     * @param string $col       Le nom de la colonne par laquelle on récupère
     *                          l'objet.
     * @param string $col_value La valeur que doit avoir cette colonne.
     * @param string $table     La table de laquelle récupérer les données.
     * @param string $categorie La classe ou la categorie de l'objet.
     * 
     * @return $object
     */
    public static function getObjectBy(string $col = null, string $col_value = null, string $table = null, string $categorie = null)
    {
        $code = self::bddManager()->getItemBy("code", $col, $col_value, $table);
        return self::returnObject($categorie, $code);
    }

    /**
     * Retourne un objet en fonction du nom de la classe et du code pour
     * l'instanciation.
     * 
     * @param string $categorie La catégorie ou la classe de l'objet.
     * @param string $code      Le code pour instancier l'objet.
     * 
     * @return $object
     */
    public static function returnObject(string $categorie, string $code)
    {
        if (self::isParentCategorie($categorie)) {
            return new ItemParent($code);
        } elseif (self::isChildCategorie($categorie) || $categorie === "motivation-plus") {
            return new ItemChild($code);
        } elseif ($categorie === "administrateurs") {
            return new Administrateur($code);
        } else {
            throw new Exception("La classe $categorie n'existe pas encore ou n'est pas bien géré.");
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
    public static function getTableNameFrom(string $categorie = null)
    {
        if ($categorie == "administrateurs") $table = Administrateur::TABLE_NAME;
        elseif (self::isParentCategorie($categorie)) $table = ItemParent::TABLE_NAME;
        elseif (self::isChildCategorie($categorie) || $categorie === "motivation-plus") $table = ItemChild::TABLE_NAME;
        else throw new Exception("La table $categorie n'existe pas");
        return $table;
    }

    /**
     * Retourne l'url de la catégorie passée en paramètre.
     * 
     * @param string $categorie
     * @param string $app_part_url
     * 
     * @return string
     */
    public static function getCategorieUrl(string $categorie, string $app_part_url = PUBLIC_URL)
    {
        return $app_part_url . "/" . $categorie;
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
        if ($categorie == "minis-services") { $categorie = "mini service"; }
        if ($categorie == "motivation-plus") { $categorie = "motivation plus"; }
            
        $categorie_length = strlen($categorie);
        $last_categorie_letter = substr($categorie, $categorie_length - 1, 1);

        if ($nombre == "singulier" && $last_categorie_letter == "s" && $categorie != "motivation plus") { 
            $categorie = substr($categorie, 0, $categorie_length - 1);
        }

        if ($nombre == "pluriel" && $last_categorie_letter !== "s" && $categorie != "motivation plus") {
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
        $femininCategorie = ["formations", "etapes", "videos"];
        $voyelleCategorie = ["articles", "ebooks"];

        if ($categorie == Administrateur::TABLE_NAME) {
            $page_title = "Nouveau administrateur";
        } elseif (in_array($categorie, $femininCategorie)) {
            $page_title = "Nouvelle " . self::getCategorieFormated($categorie);
        } elseif (in_array($categorie, $voyelleCategorie)) {
            $page_title = "Nouvel " . self::getCategorieFormated($categorie);
        } else {
            $page_title = "Nouveau " . self::getCategorieFormated($categorie);
        }

        return $page_title;
    }
    
    /**
     * Retourne toutes adresses emails enregistrées dans le base de données.
     * 
     * @return array
     */
    public static function getAllEmails()
    {
        $newsletter_mails = self::bddManager()->get("adresse_email", "newsletters");
        $suscribers_mails = self::bddManager()->get("adresse_email", Suscriber::TABLE_NAME);
        return array_merge($newsletter_mails, $suscribers_mails);
    }

    /**
     * Retourne les items enfants en prenant en paramètre l'id du parent et la
     * catégorie des items enfants à retourner.
     *
     * @param string $parent_id   
     * @param string $children_categorie La catégorie des éléments qu'on veut prendre
     *                                   de la base de données.
     *
     * @return array
     */
    public static function getchildrenOf($parent_id, $children_categorie)
    {
        $query = "SELECT code FROM " . ItemChild::TABLE_NAME . " WHERE parent_id = ? AND categorie = ?";
        $rep = self::connect()->prepare($query);
        $rep->execute([$parent_id, $children_categorie]);
        return $rep->fetchAll();
    }

    /**
     * Permet de modifier une propriété de l'instance appelant la méthode.
     * 
     * @param string $col   Le champ de la table dans laquelle on insère la
     *                      nouvelle valeur de la propriété.
     * @param mixed  $value Le contenu à insérer dans le nouveau champ
     * @param string $table Le nom de la table dans laquelle on insère la nouvelle
     *                      propriété.
     * @param string $where_col_name
     * @param string $where_col_value
     * 
     * @return bool
     */
    public function update(string $col, $value, string $table, string $where_col_name, $where_col_value)
    {
        self::bddManager()->update($col, $value, $table, $where_col_name, $where_col_value);
        $this->modified($table);
        return true;
    }

    /**
     * Permet d'insérer les données issues du formulaire de création/ajout d'un
     * nouvel dans la table passée en paramètre.
     * 
     * @param string $categorie La catégorie de l'item qu'on veut créer.
     * @param string $data      Le tableau contenant les données du formulaire.
     * 
     * @return void
     */
    public static function createItem(string $categorie, array $data)
    {
        $code = Utils::generateCode();

        if ($categorie === "administrateurs") {
           $new_item = Administrateur::save($code, $data);
        } elseif (self::isParentCategorie($categorie) || self::isChildCategorie($categorie)) {
           $new_item = self::insertPostData($code, $data, $categorie);
        }

        if (!empty($_FILES["image_uploaded"]["name"])) {
            if (self::isParentCategorie($categorie) || self::isChildCategorie($categorie)) {
                $image = new Image();
                $image->saveImages($new_item->get("categorie") . "-" . $new_item->get("slug"));
            }
        }

        if (!empty($_FILES["pdf_uploaded"]["name"])) {
            $pdf = new Pdf();
            $pdf_file_name = $new_item->get("title") . "-" . $new_item->get("id");
            $pdf->savePdfFile($pdf_file_name);
        }

        $new_item = self::returnObject($categorie, $new_item->get("code"));
        Utils::header($new_item->get("admin_url"));
    }
        
    /**
     * Modifie un item.
     * 
     * @param string $categorie La catégorie ou la classe de l'item.
     * @param array  $data      Les données issues du formulaire.
     * 
     * @return void
     */
    public function editItem($categorie = null, array $data = null)
    {
        extract($data);
        $table = self::getTableNameFrom($this->categorie);
        $image = new Image();

        if ($title == $this->get("title") && !empty($_FILES["image_uploaded"]["name"])) {
            $image->saveImages($this->get("categorie") . "-" . $this->get("slug"));
        }

        if ($title !== $this->get("title")) {
            $slug = Utils::slugify($title) . '-' . $this->get("id");
            $old_name = $this->get("image_name");
            $new_image_name = $this->get("categorie") . "-" . $slug;

            if (empty($_FILES["image_uploaded"]["name"])) {
                $image->renameImages($old_name, $new_image_name);
            } else {
                $image->saveImages($new_image_name);
                $image->deleteImages($old_name);
            }
        }

        if (isset($title)) {
            $this->update("title", $title, $table, "id", $this->id);
        }

        if (isset($description)) {
            $this->update("description", $description, $table, "id", $this->id);
        }

        if (isset($article_content)) {
            $this->update("article_content", $article_content, $table, "id", $this->id);
        }

        if (isset($prix)) {
            $this->update("price", (int)$prix, $table, "id", $this->id);
        }

        if (isset($rang)) {
            $this->setRang((int)$rang);
        }

        if (isset($youtube_video_link)){
            $this->update("youtube_video_link", $youtube_video_link, $table, "id", $this->id);
        }

        if (isset($slug)) {
            $this->update("slug", $slug, $table, "id", $this->id);
        }

        $item = self::returnObject($categorie, $this->code);
        Utils::header($item->get("admin_url"));
    }

    /**
     * Supprime plusieurs items.
     * 
     * @param string $categorie La catégorie des items qu'in veut supprimer.
     * 
     * @return bool
     */
    public static function deleteItems($categorie)
    {
        $counter = 0;
        foreach ($_POST["codes"] as $code) {
            $item = self::returnObject($categorie, $code);
            $item->delete();
            $counter++;
        }
        return true;
    }

    /**
     * Supprime un item.
     * 
     * @return bool
     */
    public function delete()
    {
        $this->unsetRang();
        $this->deleteImage();
        self::bddManager()->delete($this->table, "id", $this->id);
        return true;
    }

    /**
     * Supprime l'image de couverture et l'image miniature d'un item.
     * 
     * @return void
     */
    public function deleteImage()
    {
        $image = new Image();
        $image->deleteImages($this->image_name);
    }

    /**
     * Enregistre le rang d'un item.
     * 
     * @param int $rang 
     * 
     * @return bool
     */
    public function setRang(int $rang)
    {
        $table = $this->table;
        if ($rang !== 0 && self::bddManager()->checkIsset($table, "rang", $rang)) {
            $items = self::bddManager()->getItemsOfColValueMoreOrEqualTo( $table, "rang", $rang, $this->categorie );
            foreach ($items as $item) {
                $obj = self::returnObject($this->categorie, $item["code"]);
                self::bddManager()->incOrDecColValue("increment", "rang", $table, $obj->id);
            }
        }
        $this->update("rang", (int)$rang, $table, "id", $this->id);
    }

    /**
     * Enlève le rang d'un item.
     * 
     * @return bool
     */
    public function unsetRang()
    {
        $table = $this->table;
        $items = self::bddManager()->getItemsOfColValueMoreOrEqualTo($table, "rang", $this->rang, $this->categorie);
        foreach ($items as $item) {
            $item = self::returnObject($this->categorie, $item["code"]);
            self::bddManager()->incOrDecColValue("decrement", "rang", $table, $item->get("id"));
        }
        return true;
    }

    /**
     * Permet de sauvegarder les données de création d'un item en base de données.
     * 
     * @param string $code      Le code de l'item qu'on veut enregistrer.
     * @param array  $data      Les données issues du formulaire.
     * @param string $categorie La catégorie de l'item qu'on veut enregistrer.
     * 
     * @return void
     */
    private static function insertPostData(string $code, array $data, $categorie = null)
    {
        extract($data);
        $table = self::getTableNameFrom($categorie);

        if (self::insertPincipalsData($table, $code, $title, $description, $categorie)) {
            $new_item = self::returnObject($categorie, $code);
            
            $slug = Utils::slugify($new_item->get("title")) . '-' . $new_item->get("id");
            $new_item->update("slug", $slug, $table);

            if (!empty($rang)) {
                $new_item->setRang((int)$rang);
            }
           
            if (isset($parent_id)) {
                $new_item->update("parent_id", (int)$parent_id, $table);
            }

            if (!empty($prix)) {
                $new_item->update("price", (int)$prix, $table);
            }

            if (!empty($article_content)) {
                $new_item->update("article_content", htmlspecialchars($article_content), $table);
            }

            if (!empty($autheur_livre)) {
                $new_item->update("autheur", $autheur, $table);
            }

            if (!empty($fournisseur)) {
                $new_item->update("fournisseur", $fournisseur, $table);
            }

            if (!empty($nombre_pages)) {
                $new_item->update("nombre_pages", $nombre_pages, $table);
            }

            if (!empty($edition_home)) {
                $new_item->update("edition_home", $edition_home, $table);
            }

            if (!empty($parution_year)) {
                $new_item->update("parution_year", $parution_year, $table);
            }

            if (!empty($youtube_video_link)) {
                $new_item->update("youtube_video_link", $youtube_video_link, $table);
            }

            return self::returnObject($categorie, $code);

        } else {
            throw new Exception("Echec de l'enregistrement des données");
        }
    }
    
    /**
     * Permet d'insérer les données principale d'un item parent ou enfant.
     * 
     * @param string $table       La catégorie de l'item
     * @param string $code        Le code de l'item
     * @param string $title       Le titre de l'item
     * @param string $description La description de l'item
     * @param string $categorie   La catégorie de l'item
     * 
     * @return bool True si les données ont été bien insérées.
     */
    public static function insertPincipalsData(
        string $table = null,
        string $code = null,
        string $title = null,
        string $description = null,
        string $categorie = null
    ) {
        $query = "INSERT INTO $table(code, title, description, categorie) VALUES(?, ?, ?, ?)";
        $rep = self::connect()->prepare($query);
        $rep->execute([$code, $title, $description, $categorie]);
        return true;
    }

    /**
     * Mets à jour la date de modification de la catégorie.
     * 
     * @return bool
     */
    private function modified() : bool
    {
        self::bddManager()->update("date_modification", date("Y-m-d H:i:s"), $this->table, "id", $this->id);
        return true;
    }

}