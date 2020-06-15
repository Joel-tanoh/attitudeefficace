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

namespace App\BackEnd\Models\Items;

use App\BackEnd\Bdd\SqlQueryFormater;
use App\BackEnd\Files\Image;
use App\BackEnd\Utilities\Utility;
use App\Router;
use App\View\Notification;
use App\View\Snippet;
use App\View\Template;

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
class ItemChild extends Item
{
    /**
     * Nom de la table.
     * 
     * @var string
     */
    const TABLE_NAME = "items_child";

    /**
     * Les catégories.
     * 
     * @var array
     */
    const CATEGORIES = [
        "articles",
        "videos",
        "ebooks",
        "livres",
        "mini-services",
    ];

    /**
     * Le parent de l'élément courant.
     * 
     * @var \App\BackEnd\Models\Items\ItemParent
     */
    private $parent;

    /**
     * Instancie un nouvel élement en prenant en paramètre le code.
     * 
     * @param string $code Code de l'élément.
     * 
     * @return void
     */
    public function __construct(string $code)
    {
        $pdo = parent::connect();
        $sqlQuery = new SqlQueryFormater();
        $query = $sqlQuery
            ->select("id, code, categorie, parent_id, title, description, slug, article_content")
            ->select("author, provider, pages, price, rank, edition_home, parution_year, created_at")
            ->select("updated_at, youtube_video_link, views")
            ->from(self::TABLE_NAME)
            ->where("code = ?")
            ->returnQueryString();

        $rep = $pdo->prepare($query);
        $rep->execute([$code]);
        $result = $rep->fetch();

        $this->id = (int)$result['id'];
        $this->code = $result['code'];
        $this->categorie = $result['categorie'];
        $this->parentID = (int)$result["parent_id"];
        $this->title = $result['title'];
        $this->description = $result['description'];
        $this->slug = $result['slug'];
        $this->articleContent = $result["article_content"];
        $this->author = $result["author"];
        $this->provider = $result["provider"];
        $this->pages = (int)$result["pages"];
        $this->price = (int)$result['price'];
        $this->rank = (int)$result['rank'];
        $this->editionHome = $result['edition_home'];
        $this->parutionYear = $result['parution_year'];
        $this->createdAt = $result['created_at'];
        $this->updatedAt = $result['updated_at'];
        $this->youtubeVideoLink = $result['youtube_video_link'];
        $this->views = (int)$result["views"];
        $this->tableName = self::TABLE_NAME;
    }

    /**
     * Retourne le parent.
     * 
     * @return \App\BackEnd\Models\Items\ItemParent
     */
    public function getParent()
    {
        $parent = null;

        if ($this->parentID) {
            if ($this->parentID === "-1") {
                $parent = "Motivation plus";
            } else {
                $result = parent::bddManager()->get("code", ItemParent::TABLE_NAME, "id", $this->parentID);

                if (!empty($result[0]["code"])) {
                    $parent = new ItemParent($result[0]["code"]);
                }
            }
        }

        $this->parent = $parent;

        return $this->parent;
    }

    /**
     * Retourne le contenu de l'article.
     * 
     * @return string
     */
    public function getArticleContent()
    {
        return ucfirst(nl2br(trim(htmlspecialchars_decode($this->articleContent))));
    }

    /**
     * Retourne tous les slugs des items enfants.
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
     * Permet de créer une nouvelle occurrence dans la table des items
     * enfants.
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
        $parentID           = $_POST["parent_id"]           ?? null;
        $articleContent     = $_POST["article_content"]     ?? null;
        $author             = $_POST["author"]              ?? null;
        $provider           = $_POST["provider"]            ?? null;
        $pages              = $_POST["pages"]               ?? null;
        $price              = $_POST["price"]               ?? null;
        $rank               = $_POST["rank"]                ?? null;
        $editionHome        = $_POST["edition_home"]        ?? null;
        $parutionYear       = $_POST["parution_year"]       ?? null;
        $youtubeVideoLink   = $_POST["youtube_video_link"]  ?? null;
        
        if ($categorie === "motivation-plus") $categorie = "videos";

        if (parent::insertNotNullData(self::TABLE_NAME, $code, $title, $description, $categorie)) {
            
            $newThis = new self($code);
                                                            
            $slug = Utility::slugify($newThis->title) . '-' . $newThis->id;
            $newThis->set("slug", $slug, self::TABLE_NAME);

            $newThis->setRank((int)$rank);
           
            $newThis->set("parent_id", (int)$parentID, self::TABLE_NAME);

            $newThis->set("price", (int)$price, self::TABLE_NAME);

            $newThis->set("article_content", htmlspecialchars($articleContent), self::TABLE_NAME);

            $newThis->set("author", $author, self::TABLE_NAME);

            $newThis->set("provider", $provider, self::TABLE_NAME);

            $newThis->set("pages", $pages, self::TABLE_NAME);

            $newThis->set("edition_home", $editionHome, self::TABLE_NAME);

            $newThis->set("parution_year", $parutionYear, self::TABLE_NAME);

            $newThis->set("youtube_video_link", $youtubeVideoLink, self::TABLE_NAME);

            $newThis = $newThis->refresh();

            return $newThis;
        }
    }

    /**
     * Mets à jour un item enfant.
     * 
     * @param 
     * 
     * @return self
     */
    public function update()
    {
        $imageManager = new Image();

        $title              = htmlspecialchars($_POST["title"]);
        $description        = $_POST["description"];
        $parentID           = $_POST["parent_id"]           ?? null;
        $articleContent     = $_POST["article_content"]     ?? null;
        $author             = $_POST["author_name"]         ?? null;
        $provider           = $_POST["provider"]            ?? null;
        $pages              = $_POST["pages"]               ?? null;
        $price              = $_POST["price"]               ?? null;
        $rank               = $_POST["rank"]                ?? null;
        $editionHome        = $_POST["edition_home"]        ?? null;
        $parutionYear       = $_POST["parution_year"]       ?? null;
        $youtubeVideoLink   = $_POST["youtube_video_link"]  ?? null;
        
        if ($title === $this->title && !empty($_FILES["image_uploaded"]["name"])) {
            $imageManager->saveImages($this->categorie . "-" . $this->slug);
            $slug = $this->slug;
        }

        if ($title !== $this->title) {

            $slug = Utility::slugify($title) .'-'. $this->id;
            $oldThumbsName = $this->getThumbsName();
            $newThumbsName = $this->categorie . "-" . $slug;

            if (empty($_FILES["image_uploaded"]["name"])) {
                $imageManager->renameImages($oldThumbsName, $newThumbsName);
            } else {

                if ($this->categorie === "mini-services") {
                    $imageManager->saveImages($newThumbsName, 340, 340);
                } else {
                    $imageManager->saveImages($newThumbsName);
                }

                $imageManager->deleteImages($oldThumbsName);
            }
        }

        $this->set("parent_id", $parentID, $this->tableName, "id", $this->id);

        $this->set("title", $title, $this->tableName, "id", $this->id);
        
        $this->set("description", $description, $this->tableName, "id", $this->id);
        
        $this->set("slug", $slug, $this->tableName, "id", $this->id);
        
        $this->set("article_content", $articleContent, $this->tableName, "id", $this->id);

        $this->set("author", $author, $this->tableName, "id", $this->id);

        $this->set("provider", $provider, $this->tableName, "id", $this->id);

        $this->set("pages", $pages, $this->tableName, "id", $this->id);

        $this->set("price", $price, $this->tableName, "id", $this->id);

        $this->set("price", (int)$price, $this->tableName, "id", $this->id);

        $this->setRank((int)$rank);

        $this->set("edition_home", $editionHome, $this->tableName, "id", $this->id);

        $this->set("parution_year", $parutionYear, $this->tableName, "id", $this->id);

        $this->set("youtube_video_link", $youtubeVideoLink, $this->tableName, "id", $this->id);

        $itemUpdated = $this->refresh();

        Utility::header($itemUpdated->getUrl("administrate"));
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
     * Retourne tous les éléments.
     * 
     * @param string $categorie
     * 
     * @return array
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
     * Vue de création d'un item.
     * 
     * @return string
     */
    public static function createView(string $categorie = null, $errors = null)
    {
        
    }

    /**
     * Retourne la page d'affichage d'un item enfant.
     * 
     * @return string
     */
    public function readView()
    {
        $readItemContentHeader = Snippet::readItemContentHeader($this);
        $showData = Snippet::showData($this);

        return <<<HTML
        {$readItemContentHeader}
        {$showData}
        {$this->showArticle()}
HTML;
    }

    /**
     * Vue de mise à jour d'un item.
     * 
     * @param string $categorie
     * @param string $errors
     * 
     * @return string
     */
    public function updateView($errors = null)
    {
        
    }

    /**
     * La vue qui liste les minis services et affiche le résumé des commandes de minis
     * service.
     * 
     * @param array $items      La liste des items à lister.
     * 
     * @return string Code HTML de la page qui liste les mini services.
     */
    public static function listMiniservices(array $items)
    {
        $title = ucfirst(parent::getCategorieFormated(Router::getUrlAsArray()[0], "pluriel"));

        $itemsNumber = ItemChild::count("mini-services");

        $contentHeader = Snippet::listItemsContentHeader($title, $itemsNumber);
        $miniServiceCommandsResume = Snippet::miniServicesCommandsResume();

        if (empty($items)) {
            $notification = new Notification();
            $content = '<div>'. $notification->info($notification->noItems("mini-services")) .'</div>';
        } else {
            $content = Template::gridOfCards($items, "px-2");
        }

        return <<<HTML
        {$contentHeader}
        <section class="row mb-3">
            <section class="col-12 col-md-10 mb-3">
                {$content}
            </section>
            <section class="col-12 col-md-2">
                {$miniServiceCommandsResume}
            </section>
        </section>
HTML;
    }

    /**
     * Fiche détails d'un miniservice.
     * 
     * @return string HTML.
     */
    public function miniserviceDetailsCard()
    {
        return <<<HTML

HTML;
    }

    /**
     * Retourne une carte dans laquelle on a le contenu de l'article.
     * 
     * @return string
     */
    private function showArticle()
    {
        if ($this->articleContent) {
            return <<<HTML
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white">Contenu de l'article</div>
                        <div class="card-body">
                            <article>{$this->articleContent}</article>
                        </div>
                    </div>
                </div>
            </div>
HTML;
        }
    }

    /**
     * Affiche le parent de l'item courant.
     * 
     * @return string
     */
    public function showParent()
    {
        return <<<HTML
        <div>
            Parent : {$this->getParent()->getTitle()}
            <span class="bg-primary p-1 rounded text-white text-small">{$this->getParent()->getCategorie()}</span>
        </div>
HTML;
    }


}

