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
use App\BackEnd\Data\Data;
use App\BackEnd\Utils\Utils;

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
        switch ($categorie) {
        
        case "administrateurs" :
            $form = $this->adminForm($item);
            break;

        case Data::isParentCategorie($categorie) :
            $form = $this->parentForm($item);
            break;

        case Data::isChildCategorie($categorie) :
            $form = $this->childForm($item, $categorie);
            break;

        case "minis-services" :
            $form = $this->miniServiceForm($item);
            break;

        default :
            Utils::header(ADMIN_URL);
        }
        return $form;
    }

    /**
     * Retourne le formulaire pour administrateur.
     * 
     * @param $admin 
     * 
     * @return string
     */
    public function adminForm($admin = null)
    {
        $form_content = "";
        $form_content .= $this->loginInput($admin, "col-12 app-form");
        $form_content .= $this->passwordInput("col-12 app-form");
        $form_content .= $this->confirmPasswordInput("col-12 app-form");
        $form_content .= $this->emailInput($admin, "col-12 app-form");
        $form_content .= $this->chooseAdminAccountType();
        $form_content .= $this->avatarInput();

        return <<<HTML
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form id="myForm" method="post" enctype="multipart/form-data"
                        action="{$_SERVER['REQUEST_URI']}">
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
     * 
     * @return string Le formulaire.
     */
    public function parentForm($item = null)
    {
        $prix_label = <<<HTML
        Prix :
        <p class="notice">Ce sera la somme que les utilisateurs devront payer pour
        accéder à cet élément</p>
HTML;
        $form_content = "";
        $form_content .= $this->firstBox($item, $prix_label);
        $form_content .= $this->imageInput();
        return $this->_returnForm($form_content);
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
        $pdf_uploaded = $categorie == "ebooks" ? true : false;
        $prix_label = <<<HTML
        Prix :
        <p class="notice">Ce sera la somme que les utilisateurs devront payer pour
        accéder à cet élément</p>
HTML;
        $form_content = "";
        $form_content .= $this->firstBox($item, $prix_label);
        $form_content .= $this->articleContentTextarea($item);
        $form_content .= $this->imageInput();
        $form_content .= $this->pdfFileInput($pdf_uploaded);
        return $this->_returnForm($form_content);
    }

    /**
     * Formulaire pour ajouter un mini service.
     * 
     * @param $item A passer dans le cas ou on veut modifier un miniservice.
     * 
     * @return string
     */
    public function miniServiceForm($item = null)
    {
        $mini_service_label = <<<HTML
        Prix :
        <p class="notice">
            Cette somme sera affichée aux utilisateurs qui voudront ce service.
        </p>
HTML;
        $form_content = "";
        $form_content .= $this->firstBox($item, $mini_service_label);
        return $this->_returnForm($form_content);
    }

    /**
     * Retourne un bloc qui affiche le titre et la description sur la même
     * ligne.
     * 
     * @param mixed  $item       L'item à passer en paramètre si c'est dans le
     *                           cas de la modification d'un item.
     * @param string $prix_label 
     * 
     * @return string
     */
    public function firstBox($item = null, $prix_label = null)
    {
        return <<<HTML
        <div class="row">
            <div class="col-12 col-md-5">
                {$this->selectParent()}
                {$this->titleInput($item)}
                {$this->prixInput($item, $prix_label)}
                {$this->rangInput($item)}
                {$this->videoInput($item)}
            </div>
            <div class="col-12 col-md-7">
                {$this->descriptionInput($item)}
                {$this->informUsersInput()}
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
            {$this->_text('login', 'login', $login, "Login", $class)}
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
            {$this->_password('password', 'password', $password, "Saisir le mot de passe", $class)}
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
            {$this->_email('email', 'email', $email, "johny@mail.com", $class)}
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
                {$this->_radio("account_type", "administrateur", "Administrateur")}
            </div>
            <div>
                {$this->_radio("account_type", "utilisateur", "Utilisateur")}
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
        $confirm_password = "";
        extract($_POST);
        return <<<HTML
        <div class="form-group">
            {$this->label("confirmPassword", "Confirmez le mot de passe")}
            {$this->_password("confirm_password", "confirmPassword", $confirm_password, "Confirmer le mot de passe", $class)}
        </div>
HTML;
    }

    /**
     * Retourne une liste de bouton radio pour choisir le type de l'item parent
     * et une liste dans laquelle sera affiché les items dont le type a été choisi
     * dans la liste.
     * 
     * @param bool $choose_parent 
     * 
     * @return string
     */
    public function selectParent(bool $choose_parent = null)
    {
        global $url;
        $choose_parent = $url[0] != "minis-services"
            && Data::isChildCategorie($url[0])
            ? true
            : false;

        if ($choose_parent) {
            return <<<HTML
            <div id="chooseParentBox" class="mb-3">
                {$this->label("categorieId", "Choisir le parent :")}
                <select name="parent_id" id="categorieId" class="col-12 select2">
                    <option value="0">-- Sans parent --</option>
                    <option value="-1">Motivation plus</option>
                    {$this->_parentList("themes", "Thèmes")}
                    {$this->_parentList("etapes", "Etapes")}
                    {$this->_parentList("formations", "Formations")}
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
        return <<<HTML
        <div class="form-group">
            {$this->label("title", "Titre")}
            {$this->_text('title', 'title', $title, "Saisir le titre", "col-12 app-form")}
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
    public function descriptionInput($item)
    {
        $description = !is_null($item) ? $item->get("description") : "";
        return <<<HTML
        <div class="form-group">
            {$this->label("description", "Description")}
            {$this->_textarea('description', 'description', "Saisir la description", $description, null, "7")}
        </div>
HTML;
    }
    
    /**
     * Retourne un champ de type textarea pour écrire le contenu d'un article.
     * 
     * @param string $item A passer dans le cas ou on veut modifier un item.
     * 
     * @return string Le code HTML pour le champ du contenu de l'article.
     */
    public function articleContentTextarea($item = null)
    {
        global $url;
        $article_content = !is_null($item) ? $item->get("article_content")
            : $url[0] == "articles"
                ? ""
                : null;
        if (!is_null($article_content)) {
            return <<<HTML
            <div class="form-group">
                {$this->label("editor", "Contenu de l'article")}
                {$this->_textarea('article_content', 'editor', "Commencez à écrire...", $article_content)}
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
        $prix =  !is_null($item) ? $item->get("prix") : "";
        return <<<HTML
        <div class="form-group">
            {$this->label("Prix", $label)}
            {$this->_number('prix', 'Prix', $prix, "Prix", "col-12 app-form", 0)}
        </div>
HTML;
    }

    /**
     * Retourne un champ dans le formulaire pour le rang.
     * 
     * @param mixed $item 
     * 
     * @return string Le code HTML pour le champ.
     */
    public function rangInput($item = null)
    {
        global $url;
        $rang = !is_null($item)
            ? $item->get("rang")
            : Bdd::getMaxValueOf(
                "rang",
                Data::getTableNameFrom($url[0]),
                "categorie",
                "categorie",
                $url[0]
            ) + 1;
        $rang_actuel = ($rang == "1") ? $rang . "er" : $rang . " eme";

        extract($_POST);

        $label = <<<HTML
        Donnez un rang à cet élément :
        <p class="notice"> Classement actuelle : {$rang_actuel}</p>
HTML;
        return <<<HTML
        <div class="form-group">
            {$this->label("rang", $label)}
            {$this->_number('rang', 'Rang', $rang, "Rang", "col-12 app-form", 0)}
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
        return <<<HTML
        <div class="form-group">
            {$this->label("videoLink", $label)}
            {$this->_text('video_link', 'videoLink', $video_link, 'www.youtube.com?v={ID}', "col-12 app-form")}
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
            {$this->_file("avatar_uploaded", "avatarUploaded")}
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
            {$this->_file("image_uploaded", "imageUploaded", "col-md-5")}
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
                {$this->_file("pdf_uploaded", "pdfUploaded", "col-md-5")}
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
    public function informUsersInput()
    {
        return <<<HTML
        <div class="p-3 bg-cloud">
            <div class="mb-2">Notifier :</div>
            <div class="custom-control custom-radio">
                <input class="custom-control-input" type="radio" id="informAll" name="notify_users" value="all">
                <label for="informAll" class="custom-control-label">Tous les utilisateurs :</label>
                <p class="notice">Les emails seront envoyés à tous les utilisateurs</p>
            </div>
            <div class="custom-control custom-radio">
                <input class="custom-control-input" type="radio" id="customRadio2" name="notify_users" value="newsletter">
                <label for="customRadio2" class="custom-control-label">Que la newsletter :</label>
                <p class="notice">Les emails seront envoyés qu'aux abonnés à la newsletter</p>
            </div>
            <div class="custom-control custom-radio">
                <input class="custom-control-input" type="radio" id="customRadio3" name="notify_users" value="learners">
                <label for="customRadio3" class="custom-control-label">Que les étudiants :</label>
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
     * 
     * @author Joel
     * @return string
     */
    public function label(string $for = null, string $label = null) : string
    {
        return <<<HTML
		<label for="{$for}">{$label}</label>
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
    public function submitButton(
        string $name = null, 
        string $text = null,
        string $class = null
    ) {
        return $this->_button("submit", $name, $text, $class);
    }

    /**
     * Retourne le formulaire.
     * 
     * @param string $form_content 
     * 
     * @return string
     */
    private function _returnForm($form_content)
    {
        if ($form_content) {
            return <<<HTML
            <div class="card">
                <div class="card-body">
                    <form id="myForm" method="post" enctype="multipart/form-data"
                        action="{$_SERVER['REQUEST_URI']}"/>
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
    private function _parentList($categorie = null, $label = null)
    {
        $options = null;
        $items = Bdd::getAllFrom(Data::getTableNameFrom($categorie), $categorie);
        foreach ($items as $i) {
            $item = Data::returnObject($categorie, $i["code"]);
            $options .= '<option value="'. $item->get("id") . '">';
            $options .= '<span>'. ucfirst($item->get('categorie')) . '</span>';
            $options .= ' - ';
            $options .= ucfirst($item->get("title"));
            $options .= '</option>';
        }
        return <<<HTML
        {$options}
HTML;
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
    private function _file(string $name = null, string $id = null, string $class = null)
    {
        return <<<HTML
        <div class="row">
            <div class="col-12 {$class}">
                <div class="custom-file">
                    {$this->_input("file", $name, $id, null, null, "custom-file-input")}
                    <label class="custom-file-label" for="customFile">
                        Importer</label>
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
    private function _text(
        string $name = null, 
        string $id = null, 
        string $value = null, 
        string $placeholder = null,
        string $class = null
    ) {
        return $this->_input("text", $name, $id, $value, $placeholder, $class);
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
    private function _password(
        string $name = null, 
        string $id = null, 
        string $value = null, 
        string $placeholder = null, 
        string $class = null
    ) {
        return$this->_input("password", $name, $id, $value, $placeholder, $class);
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
    private function _email(
        string $name = null, 
        string $id = null,
        string $value = null, 
        string $placeholder = null,
        string $class = null
    ) {
        return $this->_input("email", $name, $id, $value, $placeholder, $class);
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
    private function _radio(
        string $name = null, 
        string $value = null,
        string $text = null, 
        string $class = null
    ) {
        return <<<HTML
        <label>
            <input type="radio" name="{$name}" id="" value="{$value}"
                class="{$class}"> {$text}
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
    private function _number(
        string $name = null,
        string $id = null,
        string $value = null,
        string $placeholder = null,
        string $class = null,
        int $min = null,
        int $max = null
    ) {
        return $this->_input(
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
    private function _input(
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
    private function _textarea(
        string $name = null,
        string $id = null,
        string $placeholder = null,
        string $value = null,
        string $class = null,
        string $rows = null
    ) {
        return <<<HTML
        <textarea name="{$name}" id="{$id}" rows="{$rows}"
			placeholder="{$placeholder}" class="col-12 app-form {$class}">{$value}</textarea>
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
    private function _button(
        string $type = null,
        string $name = null, 
        string $text = null,
        string $class = null
    ) {
        return <<<HTML
		<button type="{$type}" name="{$name}" class="app-btn bg-primary {$class}">
			{$text}
		</button>
HTML;
    }

}
