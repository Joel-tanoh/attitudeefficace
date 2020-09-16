<?php

namespace App\BackEnd\Models\Items;

use App\BackEnd\Models\Items\ItemParent;
use App\BackEnd\Models\Items\ItemChild;
use App\BackEnd\Utilities\Utility;
use App\BackEnd\Files\Image;
use App\BackEnd\Files\Pdf;
use App\BackEnd\Models\Entity;

/**
 * Fichier de classe de gestion des Items.
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */
abstract class Item extends Entity
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
     * La date de modification.
     * 
     * @var string
     */
    protected $updatedAt;

    /**
     * La date de publication.
     * 
     * @var string
     */
    protected $postedAt;
    
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
     * Le nombre de vue de l'item.
     * 
     * @var int
     */
    protected $views;

    /**
     * Retourne le titre de l'item.
     * 
     * @return string
     */
    public function getTitle()
    {
        if ($this->title) {
            return $this->title;
        } else {
            return "Motivation plus";
        }
    }

    /**
     * Retourne le slug de l'item.
     * 
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Retourne la description de l'élément courant.
     * 
     * @param int $charsNumber Le nombre de caractères à retourner.
     * 
     * @return string
     */
    public function getDescription(int $charsNumber = null) 
    {
        if ($charsNumber) {
            return substr($this->description, 0, $charsNumber);
        }
        return $this->description;
    }

    /**
     * Retourne le rang.
     * 
     * @return string
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Retourne le prix de l'instance.
     * 
     * @return string
     */
    public function getPrice()  
    {
        return $this->price;
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
        return Utility::formatDate($this->createdAt, $precision);
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
        return Utility::formatDate($this->updatedAt, $precision);
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
        return Utility::formatDate($this->postedAt, $precision);
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
        if ($hostedPlateform === "youtube" && null !== $this->youtubeVideoLink) {
            return $this->youtubeVideoLink;
        }
    }

    /**
     * Retourne le nombre de fois que l'élément courant a été
     * vue.
     * 
     * @return string
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Retourne le nom de l'image de couverture.
     * 
     * @return string
     */
    public function getThumbsName()
    {
        return $this->categorie . "-" . $this->slug . Image::EXTENSION;
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
        return file_exists($this->getOriginalThumbsPath()) ? ORIGINALS_THUMBS_DIR."/". $this->getThumbsName() : null;
    }

    /**
     * Retourne le classement.
     * 
     * @return string
     */
    public function getClassement()
    {
        if (null === $this->rank || $this->rank == 0) {
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
        return $this->postedAt ? true : false;
    }

    /**
     * Vérifie si l'item courant est parent.
     * 
     * @return bool
     */
    public function isParent()
    {
        return in_array($this->categorie, ItemParent::CATEGORIES);
    }

    /**
     * Vérifie si l'item courant est enfant.
     * 
     * @return bool
     */
    public function isChild()
    {
        return in_array($this->categorie, ItemChild::CATEGORIES);
    }

    /**
     * Permet de poster l'item courant, c'est à dire l'afficher sur la partie publique du site.
     * 
     * @return bool
     */
    public function post()
    {
        $this->set("posted_at", date("Y-m-d H:i:s"), $this->tableName, "code", $this->code);
        return true;
    }

    /**
     * Permet de ne plus poster (ne plus rendre public) un item.
     * 
     * @return bool
     */
    public function unpost()
    {
        $this->set("posted_at", null, $this->tableName, "code", $this->code);
        return true;
    }

    /**
     * Supprime l'item courant.
     * 
     * @return bool
     */
    public function delete()
    {
        $this->unsetRank();
        $this->deleteImage();
        parent::bddManager()->delete($this->tableName, "code", $this->code);
        return true;
    }

    /**
     * Supprime l'image de couverture et l'image miniature d'un item.
     * 
     * @return void
     */
    public function deleteImage()
    {
        Image::deleteImages($this->getThumbsName());
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
        if ($rank !== 0 && parent::bddManager()->checkIsset("rank", $this->tableName, "rank", $rank)) {

            $items = parent::bddManager()->getItemsOfValueMoreOrEqualTo("code", $this->tableName, "rank", $rank, "categorie", $this->categorie);
            
            foreach ($items as $item) {
                $obj = parent::createObjectByCategorieAndCode($this->categorie, $item["code"]);
                parent::bddManager()->incOrDecColValue("increment", "rank", $this->tableName, "code", $obj->getCode());
            }
        }

        $this->set("rank", (int)$rank, $this->tableName, "code", $this->code);
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
            $item = parent::createObjectByCategorieAndCode($this->categorie, $item["code"]);
            parent::bddManager()->incOrDecColValue("decrement", "rank", $this->tableName, "code", $item->code);
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
        if (ItemParent::isParentCategorie($categorie)) {
            $newItem = ItemParent::create($categorie);
        } else {
            $newItem = ItemChild::create($categorie);
        }

        if (!empty($_FILES["image_uploaded"]["name"])) {
            $imageName = $newItem->getCategorie() . "-" . $newItem->getSlug();

            if ($newItem->getCategorie() === "mini-services") {
                Image::saveImages($imageName, 340, 340);
            } else {
                Image::saveImages($imageName);
            }
        }

        if (!empty($_FILES["pdf_uploaded"]["name"])) {
            $pdfFileName = $newItem->getSlug();
            Pdf::savePdfFile($pdfFileName);
        }

        $newItem = $newItem->refresh();

        Utility::header($newItem->getUrl("administrate"));
    }

    /**
     * Mets à jour un item.
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
        $parentCode         = $_POST["parent_code"]           ?? null;
        $articleContent     = $_POST["article_content"]     ?? null;
        $author             = $_POST["author_name"]         ?? null;
        $provider           = $_POST["provider"]            ?? null;
        $pages              = isset($_POST["pages"]) ? (int)$_POST["pages"] : null;
        $price              = isset($_POST["price"]) ? (int)$_POST["price"] : null;
        $rank               = isset($_POST["rank"]) ? (int)$_POST["rank"] : null;
        $editionHome        = $_POST["edition_home"]        ?? null;
        $parutionYear       = $_POST["parution_year"]       ?? null;
        $youtubeVideoLink   = $_POST["youtube_video_link"]  ?? null;
        
        if ($title === $this->getTitle()) {
            $slug = $this->getSlug();

            if (!empty($_FILES["image_uploaded"]["name"])) {
                Image::saveImages($this->getCategorie() . "-" . $this->getSlug());
            }
        } else {
            $slug = Utility::slugify($title) .'-'. $this->getCode();
            $oldThumbsName = $this->getThumbsName();
            $newThumbsName = $this->getCategorie() . "-" . $slug;

            if (empty($_FILES["image_uploaded"]["name"])) {
                Image::renameImages($oldThumbsName, $newThumbsName);
            } else {

                if ($this->getCategorie() === "mini-services") {
                    $imageManager->saveImages($newThumbsName, 340, 340);
                } else {
                    $imageManager->saveImages($newThumbsName);
                }

                $imageManager->deleteImages($oldThumbsName);
            }
        }

        $this->set("title", $title, $this->tableName, "code", $this->getCode());
        
        $this->set("description", $description, $this->tableName, "code", $this->getCode());
        
        $this->set("slug", $slug, $this->tableName, "code", $this->getCode());
        
        $this->set("price", $price, $this->tableName, "code", $this->getCode());

        $this->set("youtube_video_link", $youtubeVideoLink, $this->tableName, "code", $this->getCode());

        $this->setRank($rank);

        if ($this->isChild()) {
            $this->set("parent_code", $parentCode, $this->tableName, "code", $this->getCode());
            $this->set("article_content", $articleContent, $this->tableName, "code", $this->getCode());
            $this->set("edition_home", $editionHome, $this->tableName, "code", $this->getCode());
            $this->set("parution_year", $parutionYear, $this->tableName, "code", $this->getCode());
            $this->set("author", $author, $this->tableName, "code", $this->getCode());
            $this->set("provider", $provider, $this->tableName, "code", $this->getCode());
            $this->set("pages", $pages, $this->tableName, "code", $this->getCode());
        }

        $itemUpdated = $this->refresh();

        Utility::header($itemUpdated->getUrl("administrate"));
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
            $item = parent::createObjectByCategorieAndCode($categorie, $code);
            $item->delete();
            $counter++;
        }

        return true;
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
     * Incrémente le nombre de visite de l'instance.
     * 
     * @return bool
     */
    public function incrementView() : bool
    {
        parent::bddManager()->incOrDecColValue("increment", "views", $this->tableName, "code", $this->getCode());
        return true;
    }

    /**
     * Retourne les vidéos de Motivation plus
     * 
     * @return array
     */
    public static function getMotivationPlusVideos()
    {
        $query = "SELECT code FROM " . ItemChild::TABLE_NAME
            . " WHERE categorie = 'videos' AND parent_code = 'MTVP'";
        
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
        if (null === $categorie) {
            return array_merge(ItemParent::getAllItems(), ItemChild::getAllItems());

        } elseif (ItemParent::isParentCategorie($categorie)) {
            return ItemParent::getAllItems($categorie);

        } else {
            return ItemChild::getAllItems($categorie);
        }
    }

    /**
     * Retourne le nombre des items selon la categorie.
     * 
     * @param string $categorie
     * 
     * @return int
     */
    public static function countAllItems(string $categorie = null)
    {
        if (ItemParent::isParentCategorie($categorie)) {
            return ItemParent::countAllItems($categorie);
        } elseif (ItemChild::isChildCategorie($categorie)) {
            return ItemChild::countAllItems($categorie);
        } else {
            return ItemParent::countAllItems($categorie) + ItemChild::countAllItems($categorie);
        }
    }

}