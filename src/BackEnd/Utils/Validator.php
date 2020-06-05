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
use App\BackEnd\Models\Model;
use App\BackEnd\Models\Persons\Administrateur;
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
    const HTML_REGEX = "#<.*>#";
    const EMAIL_REGEX = "#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i";
    const PASSWORD_LENGTH = 8;
    private $errors = [];
    private $to_validate = [];

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
        $this->notificateur = new Notification();

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

        if (!empty($prix)) {
            $this->validatePrix($prix);
        }

        if (!empty($rang)) {
            $this->validateRang($rang);
        }

        if (!empty($video_link) ) {
            $this->validateVideoLink($video_link);
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
     * Valide le titre de l'item qu'on veut créer.
     * 
     * @param string $title Le titre de l'item à valider.
     * 
     * @return string
     */
    public function validateTitle(string $title = null)
    {
        $this->to_validate["title"] = $title;
        if (empty($title)) {
            $this->errors["title"] = $this->notificateur->titleIsEmpty();
        } elseif ($this->containsHTML($title)) {
            $this->errors["title"] = $this->notificateur->titleContainsHTML();
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
        $this->to_validate["description"] = $description;
        if (empty($description)) {
            $this->errors["description"] = $this->notificateur->descriptionIsEmpty();
        } elseif ($this->containsHTML($description)) {
            $this->errors["description"] = $this->notificateur->descriptionContainsHTML();
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
        $this->to_validate["article_content"] = $article_content;
        if (empty($article_content)) {
            $this->errors["article_content"] = $this->notificateur->articleContentIsEmpty();
        }
    }

    /**
     * Permet de vérifier que le prix saisi l'utilisateur est un entier.
     * 
     * @param string $prix Le prix saisi par l'utilisateur.
     * 
     * @return string Une notification si le prix n'est pas un entier.
     */
    public function validatePrix(string $prix = null)
    {
        $this->to_validate["prix"] = $prix;
        if (!is_int((int)$prix)) {
            $this->errors["prix"] = $this->notificateur->IsNotInt("prix");
        }
    }

    /**
     * Permet de vérifier que le rang saisi l'utilisateur est un entier.
     * 
     * @param string $rang Le rang saisi par l'utilisateur.
     * 
     * @return string Une notification si le rang n'est pas un entier.
     */
    public function validateRang(string $rang = null)
    {
        $rang = (int)$rang;
        $this->to_validate["rang"] = $rang;

        if (!is_int($rang)) {
            $this->errors["rang"] = $this->notificateur->IsNotInt("rang");
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
        $this->to_validate["login"] = $login;
        if (empty($login)) {
            $this->errors["login"] = $this->notificateur->loginIsEmpty();
        } elseif ($this->containsHTML($login)) {
            $this->errors["login"] = $this->notificateur->loginContainsHTML();
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
        $this->to_validate["password"] = $password;
        $password_length = strlen($password);
        if (empty($password)) {
            $this->errors["password"] = $this->notificateur->passwordIsEmpty();
        } elseif ($password_length < self::PASSWORD_LENGTH) {
            $this->errors["password"] = $this->notificateur->passwordLengthIsInvalid();
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
        $this->to_validate["confirm_password"] = $confirm_password;
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        if (empty($confirm_password)) {
            $this->errors["confirm_password"] = $this->notificateur->confirmPasswordIsEmpty();
        } elseif (!password_verify($confirm_password, $password_hashed)) {
            $this->errors["confirm_password"] = $this->notificateur->passwordsNotIdentics();
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
        $this->to_validate["image_uploaded"] = $_FILES["image_uploaded"];
        $image_uploaded = new FileUploaded($_FILES["image_uploaded"]);
        if (!$image_uploaded->isAnImageHasValidSizeAndNoError()) {
            $this->errors["image_uploaded"] = $this->notificateur->imageIsInvalid();
        }
    }

    /**
     * Permet de vérifier que le fichier pdf uplaodé est exactement un fichier PDF.
     * 
     * @return string
     */
    public function validatePdfFile()
    {
        $this->to_validate["pdf_uploaded"] = $_FILES["pdf_uploaded"];
        $pdf_uploaded = new FileUploaded($_FILES["pdf_uploaded"]);
        if (!$pdf_uploaded->isPdfFile()) {
            $this->errors["pdf_uploaded"] = $this->notificateur->isNotPdfFile();
        }
    }

    /**
     * Effectue les validations sur le lien de la vidéo.
     * 
     * @param $video_link Lien de la vidéo de description.
     * 
     * @return string|null
     */
    public function validateVideoLink(string $video_link = null)
    {
        $this->to_validate["video_link"] = $video_link;
        if ($this->containsHTML($video_link)) {
            $this->errors["video_link"] = $this->notificateur->videoLinkIsInvalid();
        }
    }
    
    /**
     * Effectue les validations sur un nom. Vérifie que le nom n'excède pas 250
     * caractères ou qu'il ne contient pas de code HTML.
     * 
     * @param string $nameto_validate Le nom qu'il faut valider.
     * @param string $post_name        La valeur de l'attribut name dans le
     *                                 le formulaire.
     * 
     * @return string|null
     */
    public function validateName(string $nameto_validate = null, string $post_name = null)
    {
        $this->to_validate[$post_name] = $nameto_validate;
        if (strlen($nameto_validate) > 250 ) {
            $this->errors[$post_name] = "Veuillez vérifier que le nom n'excède pas 250 caractères.";
        } elseif ($this->containsHTML($nameto_validate)) {
            $this->errors[$post_name] = "Veuillez vérifier que le nom ne contient pas de code HTML.";
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
        $this->to_validate["email"] = $email;
        if (!preg_match(self::EMAIL_REGEX, $email)) {
            $this->errors["email"] = $this->notificateur->emailIsInvalid();
        }
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
        $this->to_validate["parent_id"] = $parent_id;
        if (!is_int((int)$parent_id)) {
            $this->errors["parent_id"] = "Le choix de catégorie que vous avez fait est invalide.";
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
