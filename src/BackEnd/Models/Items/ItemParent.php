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
use App\BackEnd\Files\Image;
use App\BackEnd\Models\Subscription;
use App\BackEnd\Models\Users\Suscriber;
use App\BackEnd\Utilities\Utility;
use App\View\Card;
use App\View\Snippet;

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
        $sqlQuery1 = new SqlQueryFormater();
        $query = $sqlQuery1
            ->select("id, code, categorie, title, description, slug, price, rank, created_at, updated_at, posted_at, youtube_video_link, views")
            ->from(self::TABLE_NAME)
            ->where("code = ?")
            ->returnQueryString();

        $rep = $pdo->prepare($query);
        $rep->execute([$code]);
        $result = $rep->fetch();

        $this->id               = (int)$result['id'];
        $this->code             = $result['code'];
        $this->categorie        = $result['categorie'];
        $this->title            = $result['title'];
        $this->description      = $result['description'];
        $this->slug             = $result['slug'];
        $this->price            = (int)$result['price'];
        $this->rank             = $result['rank'];
        $this->createdAt        = $result['created_at'];
        $this->updatedAt        = $result['updated_at'];
        $this->postedAt         = $result['posted_at'];
        $this->youtubeVideoLink = $result['youtube_video_link'];
        $this->views            = (int)$result["views"];
        $this->tableName        = self::TABLE_NAME;
    }

    /**
     * Pemet de savoir s'il y'a des souscrivants à l'élément.
     * 
     * @return bool True s'il y'a des souscriptions, false dans le cas contraire.
     */
    public function isSuscribed()
    {
        $bddManager = parent::bddManager();
        $result = $bddManager->count("id", Subscription::TABLE_NAME, "item_id", $this->id);
        return $result != 0;
    }

    /**
     * Retourne ceux qui ont souscrit à l'item.
     * 
     * @return array
     */
    public function getSuscribers()
    {
        $dbSuscribersIDs = parent::bddManager()->get("suscriber_id", Subscription::TABLE_NAME, "item_id", $this->id);

        $suscribers = [];

        if (!empty($dbSuscribersIDs)) {

            foreach ($dbSuscribersIDs as $subscriber) {
                $suscriber = parent::bddManager()->get("code", Suscriber::TABLE_NAME, "id", $subscriber["suscriber_id"]);
                $suscribers[] = new Suscriber($suscriber[0]["code"]);
            }
        }

        $this->suscribers = $suscribers;

        return $this->suscribers;
    }

    /**
     * Retourne le nombre de personnes ayant souscrit à cette 
     * instance.
     * 
     * @return int
     */
    public function getSuscribersNumber()
    {
        return (int)count($this->getSuscribers());
    }

    /**
     * Retourne les enfants de l'item courant.
     * 
     * @param string $categorie La catégorie des items enfants.
     * 
     * @return array
     */
    public function getChildren(string $categorie = null)
    {
        if (null === $categorie) {
            $dbChildren = parent::bddManager()->get("code", ItemChild::TABLE_NAME, "parent_id", $this->id);
        } else {
            $query = "SELECT code "
                    . " FROM " . ItemChild::TABLE_NAME
                    . " WHERE parent_id = ? AND categorie = ?";

            $rep = parent::connect()->prepare($query);
            $rep->execute([$this->id, $categorie]);
            $dbChildren = $rep->fetchAll();
        }

        $children = [];

        if (!empty($dbChildren)) {
            foreach ($dbChildren as $child) {
                $children[] = new ItemChild($child["code"]);
            }
        }

        $this->children = $children;

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

        foreach ($bddSlugs as $row) {
            $slugs[] = $row["slug"];
        }

        return $slugs;
    }

    /**
     * Retourne toutes les catégories des Items parents.
     * 
     * @return array
     */
    public static function getCategories()
    {
        $query = "SELECT DISTINCT categories FROM " . self::TABLE_NAME;
        $rep = parent::connect()->query($query);

        return $rep->fetchAll();
    }

    /**
     * Permet de créer une nouvelle occurrence dans la table des 
     * items parents.
     * 
     * @param string $categorie
     * 
     * @return \App\BackEnd\Models\Item
     */
    public static function create(string $categorie)
    {
        $code = Utility::generateCode();

        $title              = htmlspecialchars($_POST["title"]);
        $description        = htmlspecialchars($_POST["description"]);
        $rank               = $_POST["rank"]                ?? null;
        $price              = $_POST["price"]               ?? null;
        $youtubeVideoLink   = $_POST["youtube_video_link"]  ?? null;
        
        if (parent::insertNotNullData(self::TABLE_NAME, $code, $title, $description, $categorie)) {

            $newThis = new self($code);

            $slug = Utility::slugify($newThis->title) . '-' . $newThis->id;
            $newThis->set("slug", $slug, self::TABLE_NAME);

            $newThis->setRank((int)$rank);

            $newThis->set("price", (int)$price, self::TABLE_NAME);

            $newThis->set("youtube_video_link", $youtubeVideoLink, self::TABLE_NAME);

            $newThis = $newThis->refresh();

            return $newThis;
        }
    }

    /**
     * Mets à jour un item parent.
     * 
     * @param
     * 
     * @return void
     */
    public function update()
    {
        $imageManager = new Image();

        $title              = htmlspecialchars($_POST["title"]);
        $description        = htmlspecialchars($_POST["description"]);
        $rank               = $_POST["rank"]                ?? null;
        $price              = $_POST["price"]               ?? null;
        $youtubeVideoLink   = $_POST["youtube_video_link"]  ?? null;
        
        if ($title === $this->getTitle() && !empty($_FILES["image_uploaded"]["name"])) {
            $imageManager->saveImages($this->getCategorie() . "-" . $this->getSlug());
            $slug = $this->getSlug();
        }

        if ($title !== $this->getTitle()) {

            $slug = Utility::slugify($title) . '-' . $this->getID();
            $oldThumbsName = $this->getThumbsName();
            $newThumbsName = $this->getCategorie() . "-" . $slug;

            if (empty($_FILES["image_uploaded"]["name"])) {
                $imageManager->renameImages($oldThumbsName, $newThumbsName);
            } else {
                $imageManager->saveImages($newThumbsName);
                $imageManager->deleteImages($oldThumbsName);
            }
        }

        $this->set("title", $title, $this->tableName, "id", $this->getID());
        
        $this->set("description", $description, $this->tableName, "id", $this->getID());

        $this->set("slug", $slug, $this->tableName, "id", $this->getID());
        
        $this->set("price", (int)$price, $this->tableName, "id", $this->getID());

        $this->setRank((int)$rank);

        $this->set("youtube_video_link", $youtubeVideoLink, $this->tableName, "id", $this->getID());

        $itemUpdated = $this->refresh();

        Utility::header($itemUpdated->getUrl("administrate"));
    }

    /**
     * Retourne tous les éléments.
     * 
     * @param string $categorie Un filtre sur la catégorie.
     * 
     * @return array Unt tableau contenant tous les objets parent.
     */
    public static function getAllItems(string $categorie = null)
    {
        if (null === $categorie) {
            $result = parent::bddManager()->get("code", self::TABLE_NAME);
        } else {
            $result = parent::bddManager()->get("code", self::TABLE_NAME, "categorie", $categorie);
        }

        $items = [];

        foreach ($result as $item) {
            $items[] = new self($item["code"]);
        }

        return $items;
    }

    /**
     * Retourne le nombre d'item.
     * 
     * @param string $categorie
     * 
     * @return int
     */
    public static function count(string $categorie = null)
    {
        return count(self::getAllItems($categorie));
    }


    //////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////// LES VUES ///////////////////////////////////////////////////////

    
    /**
     * Vue de création d'un item parent.
     * 
     * @return string
     */
    public static function createView(string $categorie = null, $errors = null)
    {
        return <<<HTML

HTML;
    }

    /**
     * Retourne la page qui permet d'afficher un parent parent et toutes ses
     * informations.
     * 
     * @return string
     */
    public function readView()
    {
        $contentHeader = Snippet::readItemContentHeader($this);
        $showData = Snippet::showData($this);

        return <<<HTML
        {$contentHeader}
        {$showData}
        {$this->showChildren()}
HTML;
    }

    /**
     * Vue de mise à jour d'un item parent.
     * 
     * @return string
     */
    public function updateView($errors = null)
    {
        
    }
    
    /**
     * Affiche les cartes des articles, des vidéos, des ebooks et des livres.
     * 
     * @return string
     */
    private function showChildren()
    {
        return <<<HTML
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body py-3">
                        {$this->showChildrenByCategorie('articles')}
                        {$this->showChildrenByCategorie('videos')}
                        {$this->showChildrenByCategorie('ebooks')}
                        {$this->showChildrenByCategorie('livres')}
                    </div>
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Affiche les parents enfants en fonction de leur catégorie.
     * 
     * @param $childrenCategorie La catégorie des items enfants qu'il faut qu'il faut afficher.
     * 
     * @return string
     */
    private function showChildrenByCategorie(string $childrenCategorie)
    {
        $children = $this->getChildren($childrenCategorie);
        $childrenNumber = count($children);

        if (empty($children)) {
            $childrenList = '<div class="col-12 text-italic text-muted mb-2">Vide</div>';
        } else {
            $childrenList = null;

            foreach ($children as $child) {
                $childrenList .= Card::card(null, $child->getTitle(), $child->getUrl("administrate"));
            }
        }

        $childrenCategorie = ucfirst($childrenCategorie);

        return <<<HTML
        <div>
            <h5>
                {$childrenCategorie}
                <span class="badge bg-primary text-white">{$childrenNumber}</span>
            </h5>
            <div class="row px-2">
                {$childrenList}
            </div>
        </div>
HTML;
    }

    /**
     * Affiche les tous ceux qui ont souscrits à l'item courante.
     * 
     * @return string
     */
    public function showSuscribers()
    {
        $suscribers = null;

        foreach ($this->item->getSuscribers() as $suscriber) {
            $suscribers .= $suscriber->getName();
        }

        return <<<HTML
        <div class="card">
            <div class="card-header">Liste des inscrits</div>
            <div class="card-body">
                {$suscribers}
            </div>
        </div>
HTML;
    }

    /**
     * Montre le nombre de personne ayant souscrit l'item parent courant.
     * 
     * @return string
     */
    public function showSuscribersNumber()
    {
        return <<<HTML
        <div>
            Nombre d'inscrit : {$this->getSuscribersNumber()}
        </div>
HTML;
    }


}