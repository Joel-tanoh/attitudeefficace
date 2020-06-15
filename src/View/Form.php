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

namespace App\View;

use App\BackEnd\Bdd\BddManager;
use App\BackEnd\Models\Items\ItemParent;
use App\BackEnd\Models\Entity;
use App\BackEnd\Models\Items\Item;
use App\BackEnd\Utilities\Utility;

/**
 * Classe qui gère les formulaires.
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class Form extends View
{
    /**
     * Retourne un formulaire en fonction de la catégorie passée en paramètre.
     * 
     * @param string $categorie La catégorie.
     * @param mixed  $item 
     * 
     * @return string Le contenu du formulaire.
     */
    public static function getForm($categorie, $item = null)
    {
        if ($categorie === "administrateurs") $formContent = self::addAdminUserForm($item);
        elseif (Item::isParentCategorie($categorie)) $formContent = self::parentForm($item, $categorie);
        elseif ($categorie === "videos") $formContent = self::addVideoForm($item);
        elseif ($categorie === "mini-services") $formContent = self::addMiniserviceForm($item, $categorie);
        elseif (Item::isChildCategorie($categorie)) $formContent = self::childForm($item, $categorie);
        else Utility::header(ADMIN_URL);
        $submitButton = self::submitButton('enregistrement', 'Enregistrer');

        return self::returnForm($formContent);
    }

    /**
     * Retourne le formulaire pour administrateur.
     * 
     * @param $admin 
     * 
     * @return string
     */
    public static function addAdminUserForm($admin = null)
    {
        $form_content = self::loginInput($admin, "col-12 form-control");
        $form_content .= self::passwordInput("col-12 form-control");
        $form_content .= self::confirmPasswordInput("col-12 form-control");
        $form_content .= self::emailInput($admin, "col-12 form-control");
        $form_content .= self::chooseAdminAccountType();
        $form_content .= self::avatarInput();
        $submitButton = self::submitButton('enregistrement', 'Enregistrer');

        return <<<HTML
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form id="myForm" method="post" enctype="multipart/form-data" action="{$_SERVER['REQUEST_URI']}">
                            {$form_content}
                            {$submitButton}
                        </form>
                    </div>
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Formulaire d'un item parent.
     * 
     * @param mixed $item 
     * @param string $categorie
     * 
     * @return string Le formulaire.
     */
    public static function parentForm($item = null, string $categorie)
    {
        $prix_label = <<<HTML
        Prix :
        <p class="notice">Ce sera la somme que les utilisateurs devront payer pour
        accéder à cet élément</p>
HTML;
        return self::commonItemsInformations($item, $prix_label, $categorie);
    }
 
    /**
     * Formulaire d'un item enfant.
     * 
     * @param mixed  $item 
     * @param string $categorie
     * 
     * @return string Le formulaire.
     */
    public static function childForm($item = null, string $categorie = null)
    {
        $prix_label = <<<HTML
        Prix :
        <p class="notice">Ce sera la somme que les utilisateurs devront payer pour
        avoir accès à cet élément</p>
HTML;

        return 
            self::commonItemsInformations($item, $prix_label, $categorie) .
            self::articleContentTextarea($item, $categorie);
    }

    /**
     * Retourne le formulaire pour ajouter une formation.
     * 
     * @param $item
     * 
     * @return string
     */
    public static function addFormationForm($item = null)
    {
        $prix_label = <<<HTML
        Prix :
        <p class="notice">Ce sera la somme que les utilisateurs devront payer pour
        avoir accès à cet élément</p>
HTML;

        $titleInput = self::titleInput($item);
        $descriptionTextarea = self::descriptionTextarea($item);
        $videoInput = self::videoInput($item);
        $prixInput = self::prixInput($item, $prix_label);
        $rankInput = self::rankInput($item, "formations");
        $imageInput = self::imageInput();
        $notifyUserBox = self::notifyUsersBox();

        return <<<HTML
        <div class="row mb-2">
            <div class="col-md-7">
                {$titleInput}
                {$descriptionTextarea}
            </div>
            <div class="col-md-5">
                {$prixInput}
                {$rankInput}
                {$videoInput}
                {$imageInput}
                {$notifyUserBox}
            </div>
        </div>
HTML;
    }

    /**
     * Formumaire d'ajout d'une vidéo.
     * 
     * @param mixed $item Dans le cas ou le formulaire charge pour une modification.
     * 
     * @return string
     */
    public static function addVideoForm($item = null)
    {
        $prix_label = <<<HTML
        Prix :
        <p class="notice">Ce sera la somme que les utilisateurs devront payer pour
        avoir accès à cet élément</p>
HTML;
        $selectParent = self::selectParent("videos");
        $titleInput = self::titleInput($item);
        $descriptionTextarea = self::descriptionTextarea($item);
        $videoInput = self::videoInput($item);
        $prixInput = self::prixInput($item, $prix_label);
        $rankInput = self::rankInput($item, "videos");
        $imageInput = self::imageInput();
        $notifyUserBox = self::notifyUsersBox();

        return <<<HTML
        <div class="row">
            <div class="col-md-7">
                {$selectParent}
                {$titleInput}
                {$descriptionTextarea}
                {$videoInput}
            </div>
            <div class="col-md-5">
                {$prixInput}
                {$rankInput}
                {$imageInput}
                {$notifyUserBox}
            </div>
        </div>
HTML;
    }

    /**
     * Formulaire pour ajouter un mini service.
     * 
     * @param $item      A passer dans le cas ou on veut modifier un miniservice.
     * @param $categorie 
     * 
     * @return string
     */
    public static function addMiniserviceForm($item = null, string $categorie = null)
    {
        $mini_service_label = <<<HTML
        Prix :
        <p class="notice">
            Cette somme sera affichée aux utilisateurs qui voudront ce service
        </p>
HTML;
        $form_content = self::commonItemsInformations($item, $mini_service_label, $categorie);
        return $form_content;
    }

    /**
     * Retourne un bloc qui affiche le titre et la description sur la même
     * ligne.
     * 
     * @param mixed  $item       L'item à passer en paramètre si c'est dans le
     *                           cas de la modification d'un item.
     * @param string $prix_label 
     * @param string $categorie  
     * 
     * @return string
     */
    public static function commonItemsInformations($item = null, $prix_label = null, $categorie = null)
    {
        $uploadPdf = $categorie === "ebooks" ? true : false;
        $selectParent = self::selectParent($categorie);
        $titleInput = self::titleInput($item);
        $descriptionTextarea = self::descriptionTextarea($item);
        $videoInput = self::videoInput($item);
        $prixInput = self::prixInput($item, $prix_label);
        $rankInput = self::rankInput($item, $categorie);
        $imageInput = self::imageInput();
        $pdfFileInput = self::pdfFileInput($uploadPdf);
        $notifyUserBox = self::notifyUsersBox();

        return <<<HTML
        <div class="row mb-2">
            <div class="col-md-7">
                {$selectParent}
                {$titleInput}
                {$descriptionTextarea}
            </div>
            <div class="col-md-5">
                {$videoInput}
                {$prixInput}
                {$rankInput}
                {$imageInput}
                {$pdfFileInput}
                {$notifyUserBox}
            </div>
        </div>
HTML;
    }

    /**
     * Retourne le code pour un input pour entrer le login d'un compte qu'on veut
     * créer.
     * 
     * @param mixed  $user 
     * @param string $class 
     * 
     * @return string
     */
    public static function loginInput($user = null, string $class = null)
    {
        $login = !is_null($user) ? $user->getLogin() : "";
        $label = self::label("login", "Login");
        extract($_POST);
        $input = self::text('login', 'login', $login, "Login", $class);
        return <<<HTML
        <div class="form-group">
            {$label}
            {$input}
        </div>
HTML;
    }

    /**
     * Retourne un input pour saisir un mot de passe.
     * 
     * @param string $class 
     * 
     * @return string
     */
    public static function passwordInput(string $class = null)
    {
        $password = "";
        $label = self::label("password", "Entrez le mot de passe");
        extract($_POST);
        $input = self::passwordInput('password', 'password', $password, "Saisir le mot de passe", $class);
        return <<<HTML
        <div class="form-group">
            {$label}
            {$input}
        </div>
HTML;
    }

    /**
     * Retourne un champ pour saisir un email avec son label.
     * 
     * @param mixed  $user 
     * @param string $class 
     * 
     * @return string
     */
    public static function emailInput($user = null, string $class = null)
    {
        $email = !is_null($user) ? $user->getEmailAddress(): "";
        $label = self::label("email", "Adresse email");
        extract($_POST);
        $input = self::email('email', 'email', $email, "johny@mail.com", $class);

        return <<<HTML
        <div class="form-group">
            {$label}
            {$input}
        </div>
HTML;
    }

    /**
     * Retourne deux boutons radios pour choisir le type de compte.
     * 
     * @return string
     */
    public static function chooseAdminAccountType()
    {
        $label = self::label("", "Type de compte :");
        $adminRadio = self::radio("role", "3", "Administrateur");
        $userRadio = self::radio("role", "2", "Utilisateur");
        
        return <<<HTML
        {$label}
        <div class="row mb-2">
            <span class="col-6">
                {$adminRadio}
            </span>
            <span class="col-6">
                {$userRadio}
            </span>
        </div>
HTML;
    }

    /**
     * Retourne un input pour confirmer le mot de passe.
     * 
     * @param string $class 
     * 
     * @return string
     */
    public static function confirmPasswordInput(string $class = null)
    {
        $confirmInputPassword = "";
        $label = self::label("confirmPassword", "Confirmez le mot de passe");
        extract($_POST);
        $input = self::passwordInput("confirminputPassword", "confirmPassword", $confirmInputPassword, "Confirmer le mot de passe", $class);

        return <<<HTML
        <div class="form-group">
            {$label}
            {$input}
        </div>
HTML;
    }

    /**
     * Retourne une liste de bouton radio pour choisir le type de l'item parent
     * et une liste dans laquelle sera affiché les items dont le type a été choisi
     * dans la liste.
     * 
     * @param string $categorie
     * 
     * @return string
     */
    public static function selectParent(string $categorie = null)
    {
        if (null !== $categorie && Item::isChildCategorie($categorie) && $categorie !== "mini-services") {
            $label = self::label("selectParentList", "Choisir le parent :");
            $parentListOptions = self::parentList("themes", "Thèmes");

            return <<<HTML
            <div id="chooseParentBox" class="mb-2">
                {$label}
                <select name="parent_id" id="selectParentList" class="select2 col-12 form-control">
                    <option value="0">-- Sans parent --</option>
                    <option value="-1">Motivation plus</option>
                    {$parentListOptions}
                </select>
            </div>
HTML;
        }
    }

    /**
     * Retourne un champ dans le formulaire pour le titre.
     * 
     * @param mixed $item 
     * 
     * @return string Le code HTML pour le champ.
     */
    public static function titleInput($item = null)
    {
        $title = null !== $item ? $item->getTitle() : "";

        extract($_POST);

        $labelAndInput =
            self::label("title", "Titre") .
            self::text('title', 'title', $title, "Saisir le titre", "col-12 form-control");

        return <<<HTML
        <div class="form-group">
            {$labelAndInput}
        </div>
HTML;
    }

    /**
     * Retourne un champ de type textarea pour le champ de la description
     * de l'item à ajouter.
     * 
     * @param mixed $item 
     * 
     * @return string Le code HTML de la description.
     */
    public static function descriptionTextarea($item)
    {
        $description = !is_null($item) ? $item->getDescription() : "";

        extract($_POST);

        $labelAndInput =
            self::label("descriptionTextarea", "Description") .
            self::textarea('description', 'descriptionTextarea', "Saisir la description...", $description, "form-control", "10");

        return <<<HTML
        <div class="form-group">
            {$labelAndInput}
        </div>
HTML;
    }
    
    /**
     * Retourne un champ de type textarea pour écrire le contenu d'un article.
     * 
     * @param $item      A passer dans le cas ou on veut modifier un item.
     * @param string $categorie 
     * 
     * @return string Le code HTML pour le champ du contenu de l'article.
     */
    public static function articleContentTextarea($item = null, string $categorie = null)
    {
        $article_content = null !== $item ? $item->getArticleContent() : $categorie === "articles" ? "" : null;

        extract($_POST);

        $textarea = self::textarea('article_content', "summernote", null, $article_content);

        if (null !== $article_content) {
            return <<<HTML
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        {$textarea}
                    </div>
                </div>
            </div>
HTML;
        }
    }

    /**
     * Retourne un champ dans le formulaire pour le price.
     * 
     * @param mixed  $item 
     * @param string $label 
     * 
     * @return string Le code HTML pour le champ.
     */
    public static function prixInput($item = null, $label = null)
    {
        $price =  !is_null($item) ? $item->getPrice() : "";

        extract($_POST);

        $labelAndInput =
            self::label("Prix", $label) .
            self::number('price', 'Prix', $price, "Prix", "col-12 form-control", 0);

        return <<<HTML
        <div class="form-group">
            {$labelAndInput}
        </div>
HTML;
    }

    /**
     * Retourne un champ dans le formulaire pour le rang.
     * 
     * @param mixed $item 
     * @param string $categorie 
     * 
     * @return string Le code HTML pour le champ.
     */
    public static function rankInput($item = null, string $categorie = null)
    {
        if (null !== $item) {
            $rank = $item->getRank();
            $rank_actuel = ($rank == "1") ? $rank . "er" : $rank . " eme";
            $label = <<<HTML
            Donnez un rank à cet élément :
            <p class="notice"> Cet élément apparaîtra : {$rank_actuel}</p>
HTML;
        } else {
            $bddManager = Entity::bddManager();
            $rank = $bddManager->getMaxValueOf("rank", Entity::getTableName( $categorie ), "categorie", "categorie", $categorie ) + 1;
            $rank_actuel = ($rank == "1") ? $rank . "er" : $rank . " eme";
            $label = <<<HTML
            Donnez un rank à cet élément :
            <p class="notice"> Cet élément apparaîtra : {$rank_actuel} par défaut</p>
HTML;
        }

        extract($_POST);

        $labelAndInput = 
            self::label("rank", $label) .
            self::number('rank', 'Rang', $rank, "Rang", "col-12 form-control", 0);

        return <<<HTML
        <div class="form-group">
            {$labelAndInput}
        </div>
HTML;
    }
        
    /**
     * Champ pour entrer le lien d'une vidéo.
     * 
     * @param mixed $item 
     * 
     * @return string|null
     */
    public static function videoInput($item = null)
    {
        $youtube_video_link = null !== $item ? $item->getVideoLink("youtube") : "";
        $label = <<<HTML
        Coller l'id de la vidéo de Youtube :
        <p class="notice">Cette vidéo peut être une vidéo de description</p>
HTML;
        extract($_POST);
        
        $labelAndInput = 
            self::label("videoLink", $label) .
            self::text('youtube_video_link', 'videoLink', $youtube_video_link, 'www.youtube.com?v=...', "col-12 form-control");

        return <<<HTML
        <div class="form-group">
            {$labelAndInput}
        </div>
HTML;
    }
       
    /**
     * Retourne un champ de type file pour pouvoir uploader un fichier image.
     * 
     * @return string Le code HTML pour le champ.
     */
    public static function avatarInput()
    {
        $labelAndInput = 
            self::label("avatarUploaded", "Importer un avatar :") .
            self::FileInput("avatar_uploaded", "avatarUploaded");

        return <<<HTML
        <div class="form-group">
            {$labelAndInput}
        </div>
HTML;
    }

    /**
     * Retourne un champ de type file pour pouvoir uploader un fichier image.
     * 
     * @param bool $image_uploaded Permet de dire si le formulaire doit contenir
     *                             un champ pour une image de couverture.
     * 
     * @return string Le code HTML pour le champ.
     */
    public static function imageInput(bool $image_uploaded = null)
    {
        $labelAndInput = 
            self::label("imageUploaded", "Importer une image de couverture :") .
            self::FileInput("image_uploaded", "imageUploaded");

        return <<<HTML
        <div class="form-group">
            {$labelAndInput}
        </div>
HTML;
    }
   
    /**
     * Retourne un champ de type file pour pouvoir uploader un fichier pdf.
     * 
     * @param bool $pdf_uploaded Permet de dire si le formulaire doit contenir
     *                           un champ pour un fichier pdf.
     * 
     * @return string Le code HTML pour le champ.
     */
    public static function pdfFileInput(bool $pdf_uploaded = null)
    {
        $labelAndInput =
            self::label("pdfUploaded", "Importer un fichier PDF :") .
            self::FileInput("pdf_uploaded", "pdfUploaded");

        if ($pdf_uploaded) {
            return <<<HTML
            <div class="form-group">
                {$labelAndInput}
            </div>
HTML;
        }
    }

    /**
     * Retourne une checkbox que l'utilisateur peut cocher au cas où il veut informer
     * les abonnés de la création d'un nouvel item.
     * 
     * @return string|null
     */
    public static function notifyUsersBox()
    {
        return <<<HTML
        <div class="card p-3">
            <div class="mb-2">Envoyer une notification à :</div>
            <div class="custom-control custom-radio">
                <input class="custom-control-input" type="radio" id="informAll" name="notify_users" value="all">
                <label for="informAll" class="custom-control-label">tous les utilisateurs :</label>
                <p class="notice">Les emails seront envoyés à tous les utilisateurs</p>
            </div>
            <div class="custom-control custom-radio">
                <input class="custom-control-input" type="radio" id="customRadio2" name="notify_users" value="newsletter">
                <label for="customRadio2" class="custom-control-label">que la newsletter :</label>
                <p class="notice">Les emails seront envoyés qu'aux abonnés à la newsletter</p>
            </div>
            <div class="custom-control custom-radio">
                <input class="custom-control-input" type="radio" id="customRadio3" name="notify_users" value="suscribers">
                <label for="customRadio3" class="custom-control-label">que les souscrivants :</label>
                <p class="notice">Les emails seront envoyés qu'à ceux qui sont abonnés à une
                    formation ou à une étape</p>
            </div>
        </div>
HTML;
    }

    /**
     * Retourne une balise HTML label
     * 
     * @param string $for   [[Description]]
     * @param string $label [[Description]]
     * @param string $class
     * 
     * @author Joel
     * @return string
     */
    public static function label(string $for = null, string $label = null, string $class = null) : string
    {
        return <<<HTML
		<label for="{$for}" class="{$class}">{$label}</label>
HTML;
    }

    /**
     * Retourne un bouton de submit.
     * 
     * @param string $name  [[Description]]
     * @param string $text  [[Description]]
     * @param string $class [[Description]]
     * 
     * @return string
     */
    public static function submitButton(string $name = null,  string $text = null, string $class = null)
    {
        return self::button("submit", $name, $text, "btn-primary");
    }

    /**
     * Retourne le formulaire.
     * 
     * @param string $form_content 
     * 
     * @return string
     */
    private static function returnForm($form_content)
    {
        $submitButton = self::submitButton('enregistrement', 'Enregistrer');
        if ($form_content) {
            return <<<HTML
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="myForm" method="post" enctype="multipart/form-data"
                             action="{$_SERVER['REQUEST_URI']}">
                                {$form_content}
                                <div class="row">
                                    <div class="col-12">
                                        {$submitButton}
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
HTML;
        }
    }

    /**
     * Affiche la liste des items parents pour en choisir un comme parent de
     * l'item enfant que l'utlisateur veut créer.
     * 
     * @param string $categorie 
     * 
     * @return string
     */
    private static function parentList($categorie = null)
    {
        $options = null;
        $bddManager = Entity::bddManager();
        $items = $bddManager->get("code", ItemParent::TABLE_NAME);
        foreach ($items as $item) {
            $item = Entity::createObjectByCategorieAndCode($categorie, $item["code"]);
            $options .= '<option value="'. $item->getID() . '">';
            $options .= ucfirst($item->getTitle());
            $options .= ' - ';
            $options .= '<span class="italic">'. ucfirst($item->getCategorie()) . '</span>';
            $options .= '</option>';
        }
        return $options;
    }

    /**
     * Retourne un input pour le formulaire de type file
     * 
     * @param string $name 
     * @param string $id 
     * @param string $class  
     * 
     * @return string
     */
    private static function FileInput(string $name = null, string $id = null, string $class = null)
    {
        $labelAndInput = 
            self::input("file", $name, $id, null, null, "custom-file-input") .
            self::label("customFile", "Importer", "custom-file-label");

        return <<<HTML
        <div class="{$class}">
            <div class="custom-file">
                {$labelAndInput}
            </div>
        </div>
HTML;
    }
    
    /**
     * Retourne une balise input pour le texte.
     * 
     * @param string $name        [[Description]]
     * @param string $id          [[Description]]
     * @param string $value       [[Description]] 
     * @param string $placeholder [[Description]] 
     * @param string $class 
     * 
     * @return string
     */
    private static function text(
        string $name = null, 
        string $id = null, 
        string $value = null, 
        string $placeholder = null,
        string $class = null
    ) {
        return self::input("text", $name, $id, $value, $placeholder, $class);
    }
    
    /**
     * Retourne une balise input pour saisir un mot de passe.
     * 
     * @param string $name        [[Description]]
     * @param string $id          [[Description]]
     * @param string $value       [[Description]] 
     * @param string $placeholder [[Description]]
     * @param string $class 
     * 
     * @return string
     */
    private static function password(
        string $name = null, 
        string $id = null, 
        string $value = null, 
        string $placeholder = null, 
        string $class = null
    ) {
        return self::input("password", $name, $id, $value, $placeholder, $class);
    }

    /**
     * Retourne une balise HTML input pour saisir un email
     * 
     * @param string $name        [[Description]]
     * @param string $id          [[Description]]
     * @param string $value       [[Description]] 
     * @param string $placeholder [[Description]]
     * @param string $class 
     * 
     * @author Joel
     * @return string [[Description]]
     */
    private static function email(
        string $name = null, 
        string $id = null,
        string $value = null, 
        string $placeholder = null,
        string $class = null
    ) {
        return self::input("email", $name, $id, $value, $placeholder, $class);
    }

    /**
     * Retourne une balise de bouton radio.
     * 
     * @param string $name  Nom de la balise dans la variable superglobale $_POST.
     * @param string $value La valeur que doit contenir la balise.
     * @param string $text  Texte à afficher.
     * @param string $class 
     * 
     * @return string
     */
    private static function radio(
        string $name = null, 
        string $value = null,
        string $text = null, 
        string $class = null
    ) {
        return <<<HTML
        <label>
            <input type="radio" name="{$name}" id="" value="{$value}" class="{$class}"> {$text}
        </label>
HTML;
    }

    /**
     * Retourne une balise HTML input pour de type number.
     * 
     * @param string $name        [[Description]]
     * @param string $id          [[Description]]
     * @param string $value       [[Description]] 
     * @param string $placeholder [[Description]] 
     * @param string $class    
     * @param int    $min         
     * @param int    $max       
     * 
     * @author Joel
     * @return string [[Description]]
     */
    private static function number(
        string $name = null,
        string $id = null,
        string $value = null,
        string $placeholder = null,
        string $class = null,
        int    $min = null,
        int    $max = null
    ) {
        return self::input("number", $name, $id, $value, $placeholder, $class, $min, $max);
    }

    /**
     * Retourne un input.
     * 
     * @param string $type        
     * @param string $name        [[Description]]
     * @param string $id          [[Description]]
     * @param string $value       [[Description]] 
     * @param string $placeholder [[Description]] 
     * @param string $class       
     * @param int    $min         
     * @param int    $max  
     * 
     * @return string
     */
    public static function input(
        string $type = null,
        string $name = null,
        string $id = null,
        string $value = null,
        string $placeholder = null,
        string $class = null,
        int    $min = null,
        int    $max = null
    ) {
        return <<<HTML
        <input type="{$type}" name="{$name}" id="{$id}" value="{$value}"
            placeholder="{$placeholder}" min="{$min}" max="{$max}" class="{$class}"/>
HTML;
    }

    /**
     * Retourne une balise HTML textarea.
     * 
     * @param string $name        
     * @param string $id          
     * @param string $placeholder 
     * @param string $value 
     * @param string $class   
     * @param string $rows        
     * 
     * @author Joel
     * @return string 
     */
    private static function textarea(
        string $name = null,
        string $id = null,
        string $placeholder = null,
        string $value = null,
        string $class = null,
        string $rows = null
    ) {
        return <<<HTML
        <textarea name="{$name}" id="{$id}" rows="{$rows}" placeholder="{$placeholder}" class="col-12 {$class}">{$value}</textarea>
HTML;
    }
    
    /**
     * Retourne une balise HTML button
     * 
     * @param string $type 
     * @param string $name  
     * @param string $text  
     * @param string $class  
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public static function button(string $type = null, string $name = null,  string $text = null, string $class = null)
    {
        return <<<HTML
		<button type="{$type}" name="{$name}" class="btn {$class}">{$text}</button>
HTML;
    }

}