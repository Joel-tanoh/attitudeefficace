<?php

namespace App\BackEnd\Models\Items;

use Exception;
use App\BackEnd\Models\Items\ItemParent;
use App\BackEnd\Models\Items\ItemChild;
use App\BackEnd\Utils\Utils;
use App\BackEnd\Files\Image;
use App\BackEnd\Files\Pdf;
use App\BackEnd\Models\Users\User;

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
     * Prix de l'instance.  
     * 
     * @var int
     */
    protected $price;

    /**
     * Rang de l'item.
     */
    protected $rank;
    
    /**
     * Nom de l'image de couverture de l'instance  
     * 
     * @var string
     */
    protected $thumbsName;

    /**
     * Chemin total de l'image miniature.
     * 
     * @var string
     */
    protected $thumbsPath;

    /**
     * Source de l'image miniature
     * 
     * @var string
     */
    protected $thumbsSrc;

    /**
     * Chemin total de l'image de couverture.
     * 
     * @var string
     */
    protected $originalImagePath;

    /**
     * Source de l'image de couverture.
     * 
     * @var string
     */
    protected $originalImageSrc;

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
        return $this->title;
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
        return $this->thumbsName;
    }

    /**
     * Retourne le chemin vers l'image de couverture (thumbs)
     * 
     * @return string
     */
    public function getThumbsPath()
    {
        return $this->thumbsPath;
    }

    /**
     * Retourne le chemin vers la version originale de l'image de couverture.
     * 
     * @return string
     */
    public function getOriginalThumbsPath()
    {
        return $this->originalImagePath;
    }

    /**
     * Retourne le chemin de l'image de couverture.
     * 
     * @return string
     */
    public function getThumbsSrc()
    {
        return file_exists($this->thumbsPath) ? $this->thumbsSrc : null;
    }

    /**
     * Retourne la source de l'image originale.
     * 
     * @return string
     */
    public function getOriginalThumbsSrc()
    {
        return file_exists($this->originalImagePath) ? $this->originalImageSrc : null;
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
    public function getUrl(string $action = null) {
        if     ($action === "administration") {return $this->administrationUrl;}
        elseif ($action === "edit"          ) {return $this->editUrl;}
        elseif ($action === "post"          ) {return $this->postUrl;}
        elseif ($action === "share"         ) {return $this->shareUrl;}
        elseif ($action === "delete"        ) {return $this->deleteUrl;}
        else return $this->url;
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
     * Modifie un item.
     * 
     * @param string $categorie La catégorie ou la classe de l'item.
     * @param array  $post      Les données issues du formulaire.
     * 
     * @return void
     */
    public function update($categorie = null, array $post = null)
    {
        extract($post);

        $image = new Image();

        if ($title == $this->getTitle() && !empty($_FILES["image_uploaded"]["name"])) {
            $image->saveImages($this->getCategorie() . "-" . $this->getSlug());
        }

        if ($title !== $this->getTitle()) {
            $slug = Utils::slugify($title) . '-' . $this->getID();
            $oldThumbsName = $this->getThumbsName();
            $newThumbsName = $this->getCategorie() . "-" . $slug;

            if (empty($_FILES["image_uploaded"]["name"])) {
                $image->renameImages($oldThumbsName, $newThumbsName);
            } else {
                $image->saveImages($newThumbsName);
                $image->deleteImages($oldThumbsName);
            }
        }

        if (isset($title)) {
            $this->updateProp("title", $title, $this->tableName, "id", $this->id);
        }

        if (isset($description)) {
            $this->updateProp("description", $description, $this->tableName, "id", $this->id);
        }

        if (isset($article_content)) {
            $this->updateProp("article_content", $article_content, $this->tableName, "id", $this->id);
        }

        if (isset($price)) {
            $this->updateProp("price", (int)$price, $this->tableName, "id", $this->id);
        }

        if (isset($rank)) {
            $this->setRank((int)$rank);
        }

        if (isset($youtube_video_link)){
            $this->updateProp("youtube_video_link", $youtube_video_link, $this->tableName, "id", $this->id);
        }

        if (isset($slug)) {
            $this->updateProp("slug", $slug, $this->tableName, "id", $this->id);
        }

        $itemUpdated = parent::returnObjectByCategorie($categorie, $this->code);

        Utils::header($itemUpdated->getUrl("administration"));
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
        $image = new Image();
        $image->deleteImages($this->imageName);
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
        if ($rank!== 0 && parent::bddManager()->checkIsset($this->tableName, "rank", $rank)) {
            $items = parent::bddManager()->getItemsOfValueMoreOrEqualTo($this->tableName, "rank", $rank, $this->categorie );
            foreach ($items as $item) {
                $obj = parent::returnObjectByCategorie($this->categorie, $item["code"]);
                parent::bddManager()->incOrDecColValue("increment", "rank", $this->tableName, "id", $obj->getID());
            }
        }
        $this->updateProp("rank", (int)$rank, $this->tableName, "id", $this->id);
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
     * @param string $data      Le tableau contenant les données du formulaire.
     * 
     * @return void
     */
    public static function createItem(string $categorie, array $data)
    {
        $code = Utils::generateCode();

        $newItem = self::insertPostData($code, $data, $categorie);

        if (!empty($_FILES["image_uploaded"]["name"])) {
            $image = new Image();
            $image->saveImages($newItem->getCategorie() . "-" . $newItem->getSlug());
        }

        if (!empty($_FILES["pdf_uploaded"]["name"])) {
            $pdf = new Pdf();
            $pdf_file_name = $newItem->getTitle() . "-" . $newItem->getID();
            $pdf->savePdfFile($pdf_file_name);
        }

        if (self::isParentCategorie($categorie)) $newItem = new ItemParent($newItem->getCode());
        else $newItem = new ItemChild($newItem->getCode());

        Utils::header($newItem->getUrl("administration"));
    }

    /**
     * Permet de sauvegarder les données de création d'un item en base de données.
     * 
     * parent::returnObjectByCategorie@param string $code      Le code de l'item qu'on veut enregistrer.
     * @param array  $data      Les données issues du formulaire.
     * @param string $categorie La catégorie de l'item qu'on veut enregistrer.
     * 
     * @return self
     */
    private static function insertPostData(string $code, array $data, $categorie = null)
    {
        extract($data);
        $tableName = parent::getTableName($categorie);

        if (self::insertNotNullData($tableName, $code, $title, $description, $categorie)) {
            
            $newItem = parent::returnObjectByCategorie($categorie, $code);
            
            $slug = Utils::slugify($newItem->getTitle()) . '-' . $newItem->getID();
            $newItem->updateProp("slug", $slug, $tableName);

            if (!empty($rank)) {
                $newItem->setRank((int)$rank);
            }
           
            if (isset($parent_id)) {
                $newItem->updateProp("parentId", (int)$parent_id, $tableName);
            }

            if (!empty($price)) {
                $newItem->updateProp("price", (int)$price, $tableName);
            }

            if (!empty($article_content)) {
                $newItem->updateProp("article_content", htmlspecialchars($article_content), $tableName);
            }

            if (!empty($autheur_livre)) {
                $newItem->updateProp("autheur", $autheur, $tableName);
            }

            if (!empty($fournisseur)) {
                $newItem->updateProp("fournisseur", $fournisseur, $tableName);
            }

            if (!empty($nombre_pages)) {
                $newItem->updateProp("nombre_pages", $nombre_pages, $tableName);
            }

            if (!empty($edition_home)) {
                $newItem->updateProp("edition_home", $edition_home, $tableName);
            }

            if (!empty($parution_year)) {
                $newItem->updateProp("parution_year", $parution_year, $tableName);
            }

            if (!empty($youtube_video_link)) {
                $newItem->updateProp("youtube_video_link", $youtube_video_link, $tableName);
            }

            return parent::returnObjectByCategorie($categorie, $code);

        } else {
            throw new Exception("Echec de l'enregistrement des données");
        }
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
    private static function insertNotNullData(string $tableName = null, string $code = null, string $title = null, string $description = null, string $categorie = null)
    {
        $query = "INSERT INTO $tableName(code, title, description, categorie) VALUES(?, ?, ?, ?)";
        $rep = self::connect()->prepare($query);
        $rep->execute([$code, $title, $description, $categorie]);
        return true;
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

}