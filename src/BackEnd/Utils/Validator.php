<?php

/**
 * Fichier de classe
 * 
 * PHP verison 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @version  "GIT: <Joel-tanoh>"
 * @link     Link
 */

namespace App\BackEnd\Utils;

use App\BackEnd\Files\FileUploaded;
use App\View\Notification;

/**
 * Permet de faire toutes les vérifications sur les données entrées dans les
 * formulaires.
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @link     Link
 */
class Validator
{
    /**
     * RegEx pour les comparaison HTML
     * 
     * @var string
     */
    const HTML_REGEX = "#<.*>#";

    /**
     * RegEx pour comparer aux adresses emails.
     * 
     * @var string
     */
    const EMAIL_REGEX = "#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i";

    /**
     * La longeur minimal des mots de passes.
     * 
     * @var string
     */
    const PASSWORD_LENGTH = 8;

    /**
     * Tableau contenant les erreurs après validation de variables.
     * 
     * @var array
     */
    private $errors = [];

    /**
     * Tableau contenant les variables à valider.
     * 
     * @var array
     */
    private $toValidate = [];

    /**
     * Instancie un objet pour la validation.
     * 
     * @param $data Les données qu'on veut valider. Très souvent ces données
     *              proviennent du formulaire donc de la variable superglobale
     *              $_POST. Ils peuvent aussi provenir de GET.
     * 
     * @author Joel 
     */
    public function __construct(array $data = null)
    {
        $this->notifier = new Notification();

        extract($data);

        if (isset($login)) {
            $this->validateLogin($login);
        }

        if (isset($password)) {
            $this->validatePassword($password);
        }

        if (isset($confirm_password)) {
            $this->validatePasswords($password, $confirm_password);
        }

        if (!empty($email)) {
            $this->validateEmail($email);
        }
        
        if (isset($title)) {
            $this->validateTitle($title);
        }

        if (isset($description)) {
            $this->validateDescription($description);
        }

        if (isset($article_content)) {
            $this->validateArticleContent($article_content);
        }

        if (!empty($price)) {
            $this->validatePrix($price);
        }

        if (!empty($rank)) {
            $this->validateRang($rank);
        }

        if (!empty($youtube_video_link) ) {
            $this->validateVideoLink($youtube_video_link);
        }

        if (!empty($_FILES["image_uploaded"]["name"])) {
            $this->validateImage();
        }

        if (!empty($_FILES["pdf_uploaded"]["name"])) {
            $this->validatePdfFile();
        }

        if (!empty($parent_id)) {
            $this->validateParentId($parent_id);
        }
        
    }

    /**
     * Retourne les erreurs à l'issu de la validation des données. Chaque champ
     * du tableau a pour nom le nom issu du POST ou du GET.
     * 
     * @return array Le tableau contenant les erreurs.
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Effectue les validations sur un id d'item parent.
     * 
     * @param string $parent_id Id provenant du formulaire
     * 
     * @return string|null
     */
    public function validateParentId(string $parent_id = null)
    {
        $this->toValidate["parent_id"] = $parent_id;
        if (!is_int((int)$parent_id)) {
            $this->errors["parent_id"] = "Le parent que vous avez fait est invalide.";
        }
    }
    
    /**
     * Valide le titre de l'item qu'on veut créer.
     * 
     * @param string $title Le titre de l'item à valider.
     * 
     * @return string
     */
    public function validateTitle(string $title = null)
    {
        $this->toValidate["title"] = $title;
        if (empty($title)) {
            $this->errors["title"] = $this->notifier->titleIsEmpty();
        } elseif ($this->containsHTML($title)) {
            $this->errors["title"] = $this->notifier->titleContainsHTML();
        }
    }

    /**
     * Valide la description, retourne une chaine de caractère si la description
     * est invalide.
     * 
     * @param string $description La description à valider.
     * 
     * @return string
     */
    public function validateDescription(string $description = null)
    {
        $this->toValidate["description"] = $description;
        if (empty($description)) {
            $this->errors["description"] = $this->notifier->descriptionIsEmpty();
        } elseif ($this->containsHTML($description)) {
            $this->errors["description"] = $this->notifier->descriptionContainsHTML();
        }
    }

    /**
     * Permet de vérifier que l'article a un contenu.
     * 
     * @param string $article_content Le contenu de l'article.
     * 
     * @return string Une notification si l'article est vide.
     */
    public function validateArticleContent(string $article_content = null)
    {
        $this->toValidate["article_content"] = $article_content;
        if (empty($article_content)) {
            $this->errors["article_content"] = $this->notifier->articleContentIsEmpty();
        }
    }

    /**
     * Permet de vérifier que le price saisi l'utilisateur est un entier.
     * 
     * @param string $price Le price saisi par l'utilisateur.
     * 
     * @return string Une notification si le price n'est pas un entier.
     */
    public function validatePrix(string $price = null)
    {
        $this->toValidate["price"] = $price;
        if (!is_int((int)$price)) {
            $this->errors["price"] = $this->notifier->IsNotInt("price");
        }
    }

    /**
     * Permet de vérifier que le rang saisi l'utilisateur est un entier.
     * 
     * @param string $rang Le rang saisi par l'utilisateur.
     * 
     * @return string Une notification si le rang n'est pas un entier.
     */
    public function validateRang(string $rank = null)
    {
        $rank = (int)$rank;
        $this->toValidate["rank"] = $rank;

        if (!is_int($rank)) {
            $this->errors["rank"] = $this->notifier->IsNotInt("rank");
        }
    }

    /**
     * Permet de valider le login saisi par l'utilisateur.
     * 
     * @param string $login Le login saisi par l'utilisateur.
     * 
     * @return string Une notification si le login est invalide.
     */
    public function validateLogin(string $login = null)
    {
        $this->toValidate["login"] = $login;
        if (empty($login)) {
            $this->errors["login"] = $this->notifier->loginIsEmpty();
        } elseif ($this->containsHTML($login)) {
            $this->errors["login"] = $this->notifier->loginContainsHTML();
        }
    }
   
    /**
     * Effectue les validations sur le mot de passe.
     * 
     * @param string $password Mot de passe dont il faut vérifier la longueur.
     * 
     * @return string
     */
    public function validatePassword(string $password = null)
    {
        $this->toValidate["password"] = $password;
        $password_length = strlen($password);
        if (empty($password)) {
            $this->errors["password"] = $this->notifier->passwordIsEmpty();
        } elseif ($password_length < self::PASSWORD_LENGTH) {
            $this->errors["password"] = $this->notifier->passwordLengthIsInvalid();
        }
    }

    /**
     * Compare les deux mots de passe passé en paramètre
     * 
     * @param string $password         Le premier mot de passe.
     * @param string $confirm_password Le second mot de passe.
     * 
     * @author Joel
     * @return bool
     */
    public function validatePasswords(string $password = null, string $confirm_password = null)
    {
        $this->toValidate["confirm_password"] = $confirm_password;
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        if (empty($confirm_password)) {
            $this->errors["confirm_password"] = $this->notifier->confirmPasswordIsEmpty();
        } elseif (!password_verify($confirm_password, $password_hashed)) {
            $this->errors["confirm_password"] = $this->notifier->passwordsNotIdentics();
        }        
    }
   
    /**
     * Permet de valider que le fichier uploadé dans le champ image est une image
     * et qu'elle respecte les conditions de poids, d'extension et d'erreur.
     * 
     * @return string
     */
    public function validateImage()
    {  
        $this->toValidate["image_uploaded"] = $_FILES["image_uploaded"];
        $image_uploaded = new FileUploaded($_FILES["image_uploaded"]);
        if (!$image_uploaded->isAnImageHasValidSizeAndNoError()) {
            $this->errors["image_uploaded"] = $this->notifier->imageIsInvalid();
        }
    }

    /**
     * Permet de vérifier que le fichier pdf uplaodé est exactement un fichier PDF.
     * 
     * @return string
     */
    public function validatePdfFile()
    {
        $this->toValidate["pdf_uploaded"] = $_FILES["pdf_uploaded"];
        $pdf_uploaded = new FileUploaded($_FILES["pdf_uploaded"]);
        if (!$pdf_uploaded->isPdfFile()) {
            $this->errors["pdf_uploaded"] = $this->notifier->isNotPdfFile();
        }
    }

    /**
     * Effectue les validations sur le lien de la vidéo.
     * 
     * @param $youtube_video_link Lien de la vidéo de description.
     * 
     * @return string|null
     */
    public function validateVideoLink(string $youtube_video_link = null)
    {
        $this->toValidate["youtube_video_link"] = $youtube_video_link;
        if ($this->containsHTML($youtube_video_link)) {
            $this->errors["youtube_video_link"] = $this->notifier->videoLinkIsInvalid();
        }
    }
    
    /**
     * Effectue les validations sur un nom. Vérifie que le nom n'excède pas 250
     * caractères ou qu'il ne contient pas de code HTML.
     * 
     * @param string $nameToValidate Le nom qu'il faut valider.
     * @param string $postName        La valeur de l'attribut name dans le
     *                                 le formulaire.
     * 
     * @return string|null
     */
    public function validateName(string $nameToValidate = null, string $postName = null)
    {
        $this->toValidate[$postName] = $nameToValidate;
        if (strlen($nameToValidate) > 250 ) {
            $this->errors[$postName] = "Veuillez vérifier que le nom n'excède pas 250 caractères.";
        } elseif ($this->containsHTML($nameToValidate)) {
            $this->errors[$postName] = "Veuillez vérifier que le nom ne contient pas de code HTML.";
        }
    }
  
    /**
     * Effectue les validations sur un email.
     * 
     * @param string $email Email à vérifier.
     * 
     * @return string|null
     */
    public function validateEmail(string $email = null)
    {
        $this->toValidate["email"] = $email;
        if (!preg_match(self::EMAIL_REGEX, $email)) {
            $this->errors["email"] = $this->notifier->emailIsInvalid();
        }
    }

    /**
     * Permet de valider une variable.
     * 
     * @param mixed  $var               La variable à valider.
     * @param int    $lvl               Le niveau de validation à appliquer.
     * @param string $errorsTabKey      La clé identifiant le tableau des erreurs concernant la
     *                                  variable checkée. 
     * @param string $nameInErrorString Le nom identifiant la varibale et qui sera affiché dans la
     *                                  chaîne de caractère retournée lorsqu'il y'a erreur.
     * 
     * @return void
     */
    public function validate($var, int $lvl, string $errorsTabKey, string $nameInErrorString)
    {
        if ($lvl === 1) {
            if (empty($var)) $this->errors[$errorsTabKey][] = $nameInErrorString . " ne doit pas être vide";
        }

        if ($lvl === 2) {

        }

        if ($lvl === 3) {
            if ($this->containsHTML($var)) $this->errors[$errorsTabKey][] = $nameInErrorString . " ne doit pas pas contenir du HTML";
        }

        if ($lvl === 4) {

        }
        
    }

    /**
     * Retourne true si la chaîne de caractère passée en paramètre contient du code
     * HTML.
     * 
     * @param string $string La chaîne dont il faut faire la vérification.
     * 
     * @return bool
     */
    private function containsHTML(string $string) : bool
    {
        return preg_match(self::HTML_REGEX, $string);
    }
    
}
