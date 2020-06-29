<?php

namespace App\BackEnd\Models\Items;

use App\BackEnd\Bdd\SqlQueryFormater;
use App\BackEnd\Models\Items\ItemParent;
use App\BackEnd\Models\Items\ItemChild;
use App\BackEnd\Utilities\Utility;
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
            return ucfirst($this->title);
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
     * @return string
     */
    public function getDescription() 
    {
        return nl2br(ucfirst(trim($this->description)));
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
        $money = "F CFA";

        return $this->price . " " . $money;
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
        return Utility::convertDate($this->createdAt, $precision);
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
        return Utility::convertDate($this->updatedAt, $precision);
    }

    /**
     * Permet de poster l'item courant.
     * 
     * @return bool
     */
    public function post()
    {
        $query = "UPDATE " . $this->tableName
                . " SET posted_at = " . date("Y-m-d H:i:s")
                . " WHERE id = " . $this->id;

        parent::connect()->query($query);

        return true;
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
        return Utility::convertDate($this->postedAt, $precision);
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
            return $this->youtubeVideoLink;
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
        return $this->postedAt ? "Posté(e)" : "Non posté(e)";
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

        elseif ($action === "public") {
            return PUBLIC_URL . "/" . $url;
        }

        elseif ($action === "administrate") {
            return $administrateUrl;
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
     * Supprime l'item courant.
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
                $obj = parent::createObjectByCategorieAndCode($this->categorie, $item["code"]);
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
            $item = parent::createObjectByCategorieAndCode($this->categorie, $item["code"]);
            parent::bddManager()->incOrDecColValue("decrement", "rank", $this->tableName, "id", $item->id);
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
            $imageName = $newItem->getCategorie() . "-" . $newItem->getSlug();

            if ($newItem->getCategorie() === "mini-services") {
                $imageManager->saveImages($imageName, 340, 340);
            } else {
                $imageManager->saveImages($imageName);
            }
        }

        if (!empty($_FILES["pdf_uploaded"]["name"])) {
            $pdf = new Pdf();
            $pdfFileName = $newItem->getSlug();
            $pdf->savePdfFile($pdfFileName);
        }

        $newItem = $newItem->refresh();

        Utility::header($newItem->getUrl("administrate"));
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
        return self::createObjectByCategorieAndCode($this->categorie, $this->code);
    }

    /**
     * Incrémente le nombre de visite de l'instance.
     * 
     * @return bool
     */
    public function incrementView() : bool
    {
        parent::bddManager()->incOrDecColValue("increment", "views", $this->tableName, "id", $this->getID());
        return true;
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
        if (null === $categorie) {
            return array_merge(ItemParent::getAllItems($categorie), ItemChild::getAllItems($categorie));

        } elseif (self::isParentCategorie($categorie)) {
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
        if (null === $categorie) {
            return ItemParent::count($categorie) + ItemChild::count($categorie);

        } elseif (self::isParentCategorie($categorie)) {
            return ItemParent::count($categorie);

        } else {
            return ItemChild::count($categorie);
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////// LES VUES ///////////////////////////////////////////////////////

    /**
     * Affiche le titre de l'item courant.
     * 
     * @return string
     */
    public function showTitle()
    {
        return <<<HTML
        <div class="d-flex align-items-center">
            <span class="h6 p-1 bg-primary text-white rounded mr-2">{$this->getCategorie()} &#8250</span>
            <h2>{$this->getTitle()}</h2>
        </div>
HTML;
    }

    /**
     * Affiche la description de l'item
     * 
     * @return string
     */
    public function showDescription()
    {
        return <<<HTML
        <div class="my-2">
            <p class="m-0">Description :</p>
            <p>{$this->getDescription()}</p>
        </div>
HTML;
    }

    /**
     * Affiche le nombre de vue de l'item courant
     * 
     * @return string
     */
    public function showViews()
    {
        return <<<HTML
        <div>
            Vue {$this->getViews()} fois
        </div>
HTML;
    }

}