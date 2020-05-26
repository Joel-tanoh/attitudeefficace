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

namespace App\FrontEnd\View\Html;

use App\BackEnd\APIs\Bdd;
use App\BackEnd\Models\Model;
use App\BackEnd\Utils\Utils;
use App\Router;

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
class Form
{
    /**
     * Retourne un formulaire en fonction de la catégorie passée en paramètre.
     * 
     * @param string $categorie La catégorie.
     * @param mixed  $item 
     * 
     * @return string Le contenu du formulaire.
     */
    public function getForm($categorie, $item = null)
    {
        if ($categorie === "administrateurs") return $this->addAdminUserForm($item);
        elseif (Model::isParentCategorie($categorie)) $form = $this->parentForm($item, $categorie);
        elseif ($categorie === "videos") $form = $this->addVideoForm($item);
        elseif ($categorie === "minis-services") $form = $this->miniServiceForm($item, $categorie);
        elseif (Model::isChildCategorie($categorie)) $form = $this->childForm($item, $categorie);
        else Utils::header(ADMIN_URL);

        return $this->returnForm($form);
    }

    /**
     * Retourne le formulaire pour administrateur.
     * 
     * @param $admin 
     * 
     * @return string
     */
    public function addAdminUserForm($admin = null)
    {
        $form_content = $this->loginInput($admin, "col-12 form-control");
        $form_content .= $this->passwordInput("col-12 form-control");
        $form_content .= $this->confirmPasswordInput("col-12 form-control");
        $form_content .= $this->emailInput($admin, "col-12 form-control");
        $form_content .= $this->chooseAdminAccountType();
        $form_content .= $this->avatarInput();

        return <<<HTML
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form id="myForm" method="post" enctype="multipart/form-data" action="{$_SERVER['REQUEST_URI']}">
                            {$form_content}
                            {$this->submitButton('enregistrement', 'Enregistrer')}
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
    public function parentForm($item = null, string $categorie)
    {
        $prix_label = <<<HTML
        Prix :
        <p class="notice">Ce sera la somme que les utilisateurs devront payer pour
        accéder à cet élément</p>
HTML;
        return $this->commonItemsInformations($item, $prix_label, $categorie);
    }
 
    /**
     * Formulaire d'un item enfant.
     * 
     * @param mixed  $item 
     * @param string $categorie
     * 
     * @return string Le formulaire.
     */
    public function childForm($item = null, string $categorie = null)
    {
        $uploadPdf = $categorie === "ebooks" ? true : false;

        $prix_label = <<<HTML
        Prix :
        <p class="notice">Ce sera la somme que les utilisateurs devront payer pour
        avoir accès à cet élément</p>
HTML;

        $formContent = $this->commonItemsInformations($item, $prix_label, $categorie);
        $formContent .= $this->articleContentTextarea($item, $categorie);
        $formContent .= $this->pdfFileInput($uploadPdf);

        return $formContent;
    }

    /**
     * Retourne le formulaire pour ajouter une formation.
     * 
     * @param $item
     * 
     * @return string
     */
    public function addFormationForm($item = null)
    {
        $prix_label = <<<HTML
        Prix :
        <p class="notice">Ce sera la somme que les utilisateurs devront payer pour
        avoir accès à cet élément</p>
HTML;
        $form_content = <<<HTML
        <div class="row mb-2">
            <div class="col-md-6">
                {$this->titleInput($item)}
                {$this->descriptionTextarea($item)}
            </div>
            <div class="col-md-6">
                {$this->prixInput($item, $prix_label)}
                {$this->rangInput($item, "formations")}
                {$this->videoInput($item)}
                {$this->imageInput()}
                {$this->notifyUsersBox()}
            </div>
        </div>
HTML;

        return $form_content;
    }

    /**
     * Formumaire d'ajout d'une vidéo.
     * 
     * @param mixed $item Dans le cas ou le formulaire charge pour une modification.
     * 
     * @return string
     */
    public function addVideoForm($item = null)
    {
        $prix_label = <<<HTML
        Prix :
        <p class="notice">Ce sera la somme que les utilisateurs devront payer pour
        avoir accès à cet élément</p>
HTML;
        return <<<HTML
        <div class="col-md-7">
            {$this->selectParent("videos")}
            {$this->titleInput($item)}
            {$this->descriptionTextarea($item)}
            {$this->videoInput($item)}
        </div>
        <div class="col-md-5">
            {$this->prixInput($item, $prix_label)}
            {$this->rangInput($item, "videos")}
            {$this->imageInput()}
            {$this->notifyUsersBox()}
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
    public function miniServiceForm($item = null, string $categorie = null)
    {
        $mini_service_label = <<<HTML
        Prix :
        <p class="notice">
            Cette somme sera affichée aux utilisateurs qui voudront ce service
        </p>
HTML;
        $form_content = $this->commonItemsInformations($item, $mini_service_label, $categorie);
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
    public function commonItemsInformations($item = null, $prix_label = null, $categorie = null)
    {
        return <<<HTML
        <div class="row mb-2">
            <div class="col-md-7">
                {$this->selectParent($categorie)}
                {$this->titleInput($item)}
                {$this->descriptionTextarea($item)}
            </div>
            <div class="col-md-5">
                {$this->prixInput($item, $prix_label)}
                {$this->videoInput($item)}
                {$this->rangInput($item, $categorie)}
                {$this->imageInput()}
                {$this->notifyUsersBox()}
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
    public function loginInput($user = null, string $class = null)
    {
        $login = !is_null($user) ? $user->get("login") : "";
        extract($_POST);
        return <<<HTML
        <div class="form-group">
            {$this->label("login", "Login")}
            {$this->inputText('login', 'login', $login, "Login", $class)}
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
    public function passwordInput(string $class = null)
    {
        $password = "";
        extract($_POST);
        return <<<HTML
        <div class="form-group">
            {$this->label("password", "Entrez le mot de passe")}
            {$this->inputPassword('password', 'password', $password, "Saisir le mot de passe", $class)}
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
    public function emailInput($user = null, string $class = null)
    {
        $email = !is_null($user) ? $user->get("email") : "";
        extract($_POST);
        return <<<HTML
        <div class="form-group">
            {$this->label("email", "Adresse email")}
            {$this->email('email', 'email', $email, "johny@mail.com", $class)}
        </div>
HTML;
    }

    /**
     * Retourne deux boutons radios pour choisir le type de compte.
     * 
     * @return string
     */
    public function chooseAdminAccountType()
    {
        return <<<HTML
        {$this->label("", "Type de compte :")}
        <div class="row mb-2">
            <div class="col-6">
                {$this->inputRadio("account_type", "administrateur", "Administrateur")}
            </div>
            <div>
                {$this->inputRadio("account_type", "utilisateur", "Utilisateur")}
            </div>
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
    public function confirmPasswordInput(string $class = null)
    {
        $confirminputPassword = "";
        extract($_POST);
        return <<<HTML
        <div class="form-group">
            {$this->label("confirmPassword", "Confirmez le mot de passe")}
            {$this->inputPassword("confirminputPassword", "confirmPassword", $confirminputPassword, "Confirmer le mot de passe", $class)}
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
    public function selectParent(string $categorie = null)
    {
        if (null !== $categorie && Model::isChildCategorie($categorie) && $categorie !== "minis-services") {
            return <<<HTML
            <div id="chooseParentBox" class="mb-2">
                {$this->label("selectParentList", "Choisir le parent :")}
                <select name="parent_id" id="selectParentList" class="select2 col-12 form-control">
                    <option value="0">-- Sans parent --</option>
                    <option value="-1">Motivation plus</option>
                    {$this->parentList("themes", "Thèmes")}
                    {$this->parentList("etapes", "Etapes")}
                    {$this->parentList("formations", "Formations")}
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
    public function titleInput($item = null)
    {
        $title = !is_null($item) ? $item->get("title") : "";
        extract($_POST);
        return <<<HTML
        <div class="form-group">
            {$this->label("title", "Titre")}
            {$this->inputText('title', 'title', $title, "Saisir le titre", "col-12 form-control")}
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
    public function descriptionTextarea($item)
    {
        $description = !is_null($item) ? $item->get("description") : "";
        extract($_POST);
        return <<<HTML
        <div class="form-group">
            {$this->label("descriptionTextarea", "Description")}
            {$this->inputTextarea('description', 'descriptionTextarea', "Saisir la description...", $description, "form-control", "10")}
        </div>
HTML;
    }
    
    /**
     * Retourne un champ de type textarea pour écrire le contenu d'un article.
     * 
     * @param string $item      A passer dans le cas ou on veut modifier un item.
     * @param string $categorie 
     * 
     * @return string Le code HTML pour le champ du contenu de l'article.
     */
    public function articleContentTextarea($item = null, string $categorie = null)
    {
        $article_content = null !== $item ? $item->get("article_content") : $categorie === "articles" ? "" : null;

        extract($_POST);

        if (null !== $article_content) {
            return <<<HTML
            <div class="form-group mt-2">
                {$this->inputTextarea('article_content', "summernote", null, $article_content, null)}
            </div>
HTML;
        }
    }

    /**
     * Retourne un champ dans le formulaire pour le prix.
     * 
     * @param mixed  $item 
     * @param string $label 
     * 
     * @return string Le code HTML pour le champ.
     */
    public function prixInput($item = null, $label = null)
    {
        $prix =  !is_null($item) ? $item->get("price") : "";
        extract($_POST);

        return <<<HTML
        <div class="form-group">
            {$this->label("Prix", $label)}
            {$this->inputNumber('prix', 'Prix', $prix, "Prix", "col-12 form-control", 0)}
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
    public function rangInput($item = null, string $categorie = null)
    {
        if (null !== $item) {
            $rang = $item->get("rang");
            $rang_actuel = ($rang == "1") ? $rang . "er" : $rang . " eme";
            $label = <<<HTML
            Donnez un rang à cet élément :
            <p class="notice"> Cet élément apparaîtra : {$rang_actuel}</p>
HTML;
        } else {
            $rang = Bdd::getMaxValueOf( "rang",
                Model::getTableNameFrom( $categorie ),
                "categorie",
                "categorie",
                $categorie
            ) + 1;
            $rang_actuel = ($rang == "1") ? $rang . "er" : $rang . " eme";
            $label = <<<HTML
            Donnez un rang à cet élément :
            <p class="notice"> Cet élément apparaîtra : {$rang_actuel} par défaut</p>
HTML;
        }

        extract($_POST);

        return <<<HTML
        <div class="form-group">
            {$this->label("rang", $label)}
            {$this->inputNumber('rang', 'Rang', $rang, "Rang", "col-12 form-control", 0)}
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
    public function videoInput($item = null)
    {
        $video_link = !is_null($item) ? $item->get("video_link") : "";
        $label = <<<HTML
        Coller l'id de la vidéo de Youtube :
        <p class="notice">Cette vidéo peut être une vidéo de description</p>
HTML;
        extract($_POST);
        
        return <<<HTML
        <div class="form-group">
            {$this->label("videoLink", $label)}
            {$this->inputText('video_link', 'videoLink', $video_link, 'www.youtube.com?v=...', "col-12 form-control")}
        </div>
HTML;
    }
       
    /**
     * Retourne un champ de type file pour pouvoir uploader un fichier image.
     * 
     * @return string Le code HTML pour le champ.
     */
    public function avatarInput()
    {
        return <<<HTML
        <div class="form-group">
            {$this->label("avatarUploaded", "Importer un avatar :")}
            {$this->inputFile("avatar_uploaded", "avatarUploaded")}
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
    public function imageInput(bool $image_uploaded = null)
    {
        return <<<HTML
        <div class="form-group">
            {$this->label("imageUploaded", "Importer une image de couverture :")}
            {$this->inputFile("image_uploaded", "imageUploaded")}
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
    public function pdfFileInput(bool $pdf_uploaded = null)
    {
        if ($pdf_uploaded) {
            return <<<HTML
            <div class="form-group">
                {$this->label("pdfUploaded", "Importer un fichier PDF :")}
                {$this->inputFile("pdf_uploaded", "pdfUploaded", "col-md-5")}
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
    public function notifyUsersBox()
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
    public function label(string $for = null, string $label = null, string $class = null) : string
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
    public function submitButton(string $name = null,  string $text = null, string $class = null)
    {
        return $this->button("submit", $name, $text, "btn-primary");
    }

    /**
     * Retourne le formulaire.
     * 
     * @param string $form_content 
     * 
     * @return string
     */
    private function returnForm($form_content)
    {
        if ($form_content) {
            return <<<HTML
            <div class="card">
                <div class="card-body">
                    <form id="myForm" method="post" enctype="multipart/form-data" action="{$_SERVER['REQUEST_URI']}">
                        {$form_content}
                        {$this->submitButton('enregistrement', 'Enregistrer')}
                    </form>
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
     * @param string $label  
     * 
     * @return string
     */
    private function parentList($categorie = null, $label = null)
    {
        $options = null;
        $items = Bdd::getAllFrom(Model::getTableNameFrom($categorie), $categorie);
        foreach ($items as $i) {
            $item = Model::returnObject($categorie, $i["code"]);
            $options .= '<option value="'. $item->get("id") . '">';
            $options .= '<span class="text-small">'. ucfirst($item->get('categorie')) . '</span>';
            $options .= ' - ';
            $options .= ucfirst($item->get("title"));
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
    private function inputFile(string $name = null, string $id = null, string $class = null)
    {
        return <<<HTML
        <div class="row">
            <div class="col-12 {$class}">
                <div class="custom-file">
                    {$this->input("file", $name, $id, null, null, "custom-file-input")}
                    {$this->label("customFile", "Importer", "custom-file-label")}
                </div>
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
    private function inputText(
        string $name = null, 
        string $id = null, 
        string $value = null, 
        string $placeholder = null,
        string $class = null
    ) {
        return $this->input("text", $name, $id, $value, $placeholder, $class);
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
    private function inputPassword(
        string $name = null, 
        string $id = null, 
        string $value = null, 
        string $placeholder = null, 
        string $class = null
    ) {
        return$this->input("password", $name, $id, $value, $placeholder, $class);
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
    private function email(
        string $name = null, 
        string $id = null,
        string $value = null, 
        string $placeholder = null,
        string $class = null
    ) {
        return $this->input("email", $name, $id, $value, $placeholder, $class);
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
    private function inputRadio(
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
    private function inputNumber(
        string $name = null,
        string $id = null,
        string $value = null,
        string $placeholder = null,
        string $class = null,
        int $min = null,
        int $max = null
    ) {
        return $this->input(
            "number", $name, $id, $value, $placeholder, $class, $min, $max
        );
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
    public function input(
        string $type = null,
        string $name = null,
        string $id = null,
        string $value = null,
        string $placeholder = null,
        string $class = null,
        int $min = null,
        int $max = null
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
    private function inputTextarea(
        string $name = null,
        string $id = null,
        string $placeholder = null,
        string $value = null,
        string $class = null,
        string $rows = null
    ) {
        return <<<HTML
        <textarea name="{$name}" id="{$id}" rows="{$rows}" placeholder="{$placeholder}"
            class="col-12 {$class}">{$value}</textarea>
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
    public function button(string $type = null, string $name = null,  string $text = null, string $class = null)
    {
        return <<<HTML
		<button type="{$type}" name="{$name}" class="btn {$class}">
			{$text}
		</button>
HTML;
    }

}