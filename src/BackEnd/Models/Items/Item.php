<?php

namespace App\BackEnd\Models\Items;

use App\BackEnd\Bdd\SqlQueryFormater;
use Exception;
use App\BackEnd\Models\Items\ItemParent;
use App\BackEnd\Models\Items\ItemChild;
use App\BackEnd\Utils\Utils;
use App\BackEnd\Files\Image;
use App\BackEnd\Files\Pdf;

/**
 * Fichier de classe de gestion des Items.
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */
class Item extends \App\BackEnd\Models\Entity
{
    /**
     * Nom/titre de l'instance
     * 
     * @var string
     */
    protected $title;

    /**
     * Slug de l'instance  
     * 
     * @var string
     */
    protected $slug;
 
    /**
     * Description de l'instance  
     * 
     * @var string
     */
    protected $description;
 
    /**
     * Rang de l'item.
     * 
     * @var int
     */
    protected $rank;

    /**
     * La date de création.
     * 
     * @var string
     */
    protected $createdAt;
    
    /**
     * Prix de l'instance.  
     * 
     * @var int
     */
    protected $price;

    /**
     * Lien de la vidéo de description de l'instance  
     * 
     * @var string
     */
    protected $youtubeVideoLink;

    /**
     * Retourne le titre de l'item.
     * 
     * @return string
     */
    public function getTitle()
    {
        return $this->get("title");
    }

    /**
     * Retourne le slug de l'item.
     * 
     * @return string
     */
    public function getSlug()
    {
        return $this->get("slug");
    }

    /**
     * Retourne la description de l'élément courant.
     * 
     * @return string
     */
    public function getDescription() 
    {
        return nl2br(ucfirst(trim($this->get("description"))));
    }

    /**
     * Retourne le rang.
     * 
     * @return string
     */
    public function getRank()
    {
        return $this->get("rank");
    }

    /**
     * Retourne le prix de l'instance.
     * 
     * @return string
     */
    public function getPrice()  
    {
        return $this->get("price");
    }

    /**
     * Retourne la date de création.
     * 
     * @param string $precision La précision dans la date de création.
     * 
     * @return string
     */
    public function getCreatedAt(string $precision = null)
    {
        $sqlQuery = new SqlQueryFormater();
        $query = $sqlQuery
            ->select("created_at")
            ->from($this->tableName)
            ->where("code = ?")
            ->returnQueryString();

        $rep = parent::connect()->prepare($query);
        $rep->execute([$this->getCode()]);
        $result = $rep->fetch();

        return Utils::convertDate($result["created_at"], $precision);
    }

    /**
     * Retourne la date de mise à jour.
     * 
     * @param string $precision La partie dans la date que l'on veut de modification.
     * 
     * @return string
     */
    public function getUpdatedAt(string $precision = null)
    {
        $sqlQuery = new SqlQueryFormater();
        $query = $sqlQuery
            ->select("updated_at")
            ->from($this->tableName)
            ->where("code = ?")
            ->returnQueryString();

        $rep = parent::connect()->prepare($query);
        $rep->execute([$this->getCode()]);
        $result = $rep->fetch();

        return Utils::convertDate($result["updated_at"], $precision);
    }

    /**
     * Retourne la date de publication.
     * 
     * @param string $precision La partie qu'on veut récupérer dans la date de
     *                          publication.
     * 
     * @return string
     */
    public function getPostedAt(string $precision = null)
    {
        $sqlQuery = new SqlQueryFormater();
        $query = $sqlQuery
            ->select("posted_at")
            ->from($this->tableName)
            ->where("code = ?")
            ->returnQueryString();

        $rep = parent::connect()->prepare($query);
        $rep->execute([$this->getCode()]);
        $result = $rep->fetch();

        return Utils::convertDate($result["posted_at"], $precision);
    }

    /**
     * Retourne le lien de la vidéo descriptive et/ou explicative de l'élément.
     * 
     * @param string $hostedPlateform La plateforme d'hébergement de la vidéo.
     * 
     * @return string
     */
    public function getVideoLink(string $hostedPlateform = null)
    {
        if ($hostedPlateform === "youtube")
            return $this->get("youtube_video_link");
    }

    /**
     * Retourne le nombre de fois que l'élément courant a été
     * vue.
     * 
     * @return string
     */
    public function getViews()
    {
        return $this->get("views");
    }

    /**
     * Retourne le nom de l'image de couverture.
     * 
     * @return string
     */
    public function getThumbsName()
    {
        return $this->getCategorie() . "-" . $this->getSlug() . Image::EXTENSION;
    }

    /**
     * Retourne le chemin vers l'image de couverture (thumbs)
     * 
     * @return string
     */
    public function getThumbsPath()
    {
        return THUMBS_PATH . $this->getThumbsName();
    }

    /**
     * Retourne le chemin vers la version originale de l'image de couverture.
     * 
     * @return string
     */
    public function getOriginalThumbsPath()
    {
        return ORIGINALS_THUMBS_PATH . $this->getThumbsName();
    }

    /**
     * Retourne le chemin de l'image de couverture.
     * 
     * @return string
     */
    public function getThumbsSrc()
    {
        return file_exists($this->getThumbsPath()) ? THUMBS_DIR_URL."/".$this->getThumbsName() : null;
    }

    /**
     * Retourne la source de l'image originale.
     * 
     * @return string
     */
    public function getOriginalThumbsSrc()
    {
        return file_exists($this->getoriginalThumbsPath()) ? ORIGINALS_THUMBS_DIR."/". $this->getThumbsName() : null;
    }

    /**
     * Retourne le classement.
     * 
     * @return string
     */
    public function getClassement()
    {
        if ($this->rank == 0 || $this->rank == null) {
            return "Non classé";
        } else {
            return $this->rank == 1 ? $this->rank . " er" : $this->rank . " eme";
        }
    }

    /**
     * Permet de savoir si l'élément a été posté.
     * 
     * @return string
     */
    public function isPosted()
    {
        return $this->postedAt ? "posté(e)" : "Non posté(e)";
    }

    /**
     * Retourne l'url pour localier l'élément.
     * 
     * @param string $action L'url retourne change en fonction de la chaîne
     *                       de caractère passée en paramètre. Les chaînes autorisées sont
     *                       administration, edit, post, share, delete.
     * 
     * @return string
     */
    public function getUrl(string $action = null)
    {
        $url = $this->categorie . "/" . $this->slug;
        $administrateUrl = ADMIN_URL . "/" . $url;

        if (null === $action) {
            return $url;
        }

        elseif ($action === "administrate") {
            return $administrateUrl;
        }

        elseif ($action === "public") {
            return PUBLIC_URL . "/" . $url;
        }

        elseif ($action === "edit") {
            return $administrateUrl . "/edit";
        }

        elseif ($action === "post") {
            return $administrateUrl . '/post';
        }

        elseif ($action === "delete") {
            return $administrateUrl . "/delete";
        }
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
     * Supprime un item.
     * 
     * @return bool
     */
    public function delete()
    {
        $this->unsetRank();
        $this->deleteImage();
        parent::bddManager()->delete($this->tableName, "id", $this->id);
        return true;
    }

    /**
     * Supprime l'image de couverture et l'image miniature d'un item.
     * 
     * @return void
     */
    public function deleteImage()
    {
        $imageManager = new Image();
        $imageManager->deleteImages($this->getThumbsName());
    }

    /**
     * Enregistre le rang d'un item.
     * 
     * @param int $rank
     * 
     * @return bool
     */
    public function setRank(int $rank)
    {
        if ($rank!== 0 && parent::bddManager()->checkIsset("rank", $this->tableName, "rank", $rank)) {

            $items = parent::bddManager()->getItemsOfValueMoreOrEqualTo("code", $this->tableName, "rank", $rank, "categorie", $this->categorie );
            
            foreach ($items as $item) {
                $obj = parent::returnObjectByCategorie($this->categorie, $item["code"]);
                parent::bddManager()->incOrDecColValue("increment", "rank", $this->tableName, "id", $obj->getID());
            }
        }

        $this->set("rank", (int)$rank, $this->tableName, "id", $this->id);
    }

    /**
     * Enlève le rang d'un item.
     * 
     * @return bool
     */
    public function unsetRank()
    {
        $items = parent::bddManager()->getItemsOfValueMoreOrEqualTo("code", $this->tableName, "rank", $this->rank, "categorie", $this->categorie);
        foreach ($items as $item) {
            $item = parent::returnObjectByCategorie($this->categorie, $item["code"]);
            parent::bddManager()->incOrDecColValue("decrement", "rank", $this->tableName, "id", $item->getID());
        }

        return true;
    }

    /**
     * Permet d'insérer les données issues du formulaire de création/ajout d'un
     * nouvel dans la table passée en paramètre.
     * 
     * @param string $categorie La catégorie de l'item qu'on veut créer.
     * 
     * @return void
     */
    public static function createItem(string $categorie)
    {
        if (self::isParentCategorie($categorie)) {
            $newItem = ItemParent::create($categorie);
        } else {
            $newItem = ItemChild::create($categorie);
        }

        if (!empty($_FILES["image_uploaded"]["name"])) {
            $imageManager = new Image();
            $imageManager->saveImages($newItem->getCategorie() . "-" . $newItem->getSlug());
        }

        if (!empty($_FILES["pdf_uploaded"]["name"])) {
            $pdf = new Pdf();
            $pdf_file_name = $newItem->getTitle() . "-" . $newItem->getID();
            $pdf->savePdfFile($pdf_file_name);
        }

        $newItem = $newItem->refresh();

        Utils::header($newItem->getUrl("administrate"));
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
            $item = parent::returnObjectByCategorie($categorie, $code);
            $item->delete();
            $counter++;
        }

        return true;
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
        return in_array($slug, ItemParent::getSlugs());
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
        return in_array($slug, ItemChild::getSlugs());
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
        return in_array($categorie, ItemParent::CATEGORIES);
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
        return in_array($categorie, ItemChild::CATEGORIES);
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
     * Permet d'insérer les données principale d'un item parent ou enfant qui ne 
     * doivent pas être nulle dès la création.
     * 
     * @param string $tableName   La catégorie de l'item
     * @param string $code        Le code de l'item
     * @param string $title       Le titre de l'item
     * @param string $description La description de l'item
     * @param string $categorie   La catégorie de l'item
     * 
     * @return bool True si les données ont été bien insérées.
     */
    protected static function insertNotNullData(string $tableName = null, string $code, string $title, string $description, string $categorie)
    {
        $query = "INSERT INTO $tableName(code, title, description, categorie) VALUES(?, ?, ?, ?)";

        $rep = parent::connect()->prepare($query);
        $rep->execute([$code, $title, $description, $categorie]);

        return true;
    }

    /**
     * Permet de rafraichir un item.
     * 
     * @return self
     */
    protected function refresh()
    {
        return self::returnObjectByCategorie($this->categorie, $this->code);
    }

    /**
     * Incrément le nombre de visite de l'instance.
     * 
     * @return bool
     */
    public function viewPlusOne() : bool
    {
        parent::bddManager()->incOrDecColValue("increment", "views", $this->tableName, "id", $this->getID());
        return true;
    }

    /**
     * Retourne le nombre des items selon la categorie.
     * 
     * @param string $categorie
     * 
     * @return int
     */
    public static function getNumber(string $categorie = null)
    {
        if (null === $categorie) {
            return ItemParent::getNumber($categorie) + ItemChild::getNumber($categorie);
        } elseif (self::isParentCategorie($categorie)) {
            return ItemParent::getNumber($categorie);
        } else {
            return ItemChild::getNumber($categorie);
        }
    }

    /**
     * Retourne les vidéos de Motivation plus
     * 
     * @return array
     */
    public static function getMotivationPlusVideos()
    {
        $query = "SELECT code"
                . " FROM " . ItemChild::TABLE_NAME
                . " WHERE categorie = 'videos' AND parent_id = -1";
        
        $rep = self::connect()->query($query);
        $result = $rep->fetchAll();

        $videos = [];

        foreach ($result as $video) {
            $videos[] = new ItemChild($video["code"]);
        }

        return $videos;
    }

    /**
     * Retourne le nombre de vidéos de motivation plus.
     * 
     * @return int
     */
    public static function getMotivationPlusVideosNumber()
    {
        return (int)count(self::getMotivationPlusVideos());
    }

    /**
     * Retourne tous les items en fonction de leur catégorie.
     * 
     * @param string $categorie
     * 
     * @return array
     */
    public static function getAll(string $categorie = null)
    {
        if (self::isParentCategorie($categorie)) {
            return ItemParent::getAll($categorie);
        } else {
            return ItemChild::getAll($categorie);
        }
    }

}