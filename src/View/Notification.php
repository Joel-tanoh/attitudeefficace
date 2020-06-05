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

/**
 * Permet de gérer toutes les notifications d'error, de succès et d'informations
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @link     Link
 */
class Notification
{    
    /**
     * Permet d'afficher un message d'exception.
     * 
     * @param string $message Message d'erreur à afficher pour les alert-danger
     * 
     * @return string
     */
    public function exception(string $message) : string
    {
        return <<<HTML
        <div class="container">
            <div class="alert alert-danger">
                {$message}
            </div>
        </div>
HTML;
    }

    /**
     * Retourne la liste des erreurs lors de l'exécution de la validation des
     * données issues d'un formulaire.
     * 
     * @param $errors Liste des erreurs.
     * 
     * @return string Liste des erreurs bien formatée en balise ul.
     */
    public function errors($errors)
    {
        $error = '';
        foreach ($errors as $err) {
            $error .= $err;
            $error .= "<br/>";
        }
        return $this->error($error);
    }

    /**
     * Permet d'afficher un message d'erreur.
     * 
     * @param string $message Message d'erreur à afficher pour les alert-danger
     * 
     * @return string
     */
    public function error(string $message) : string
    {
        return <<<HTML
        <div class="alert app-alert-danger d-flex align-items-center">
            <i class="fas fa-exclamation-triangle text-danger mr-3"></i>
            <div>{$message}</div>
        </div>
HTML;
    }

    /**
     * Permet d'afficher un message de succès.
     * 
     * @param string $success_message Message de succès à afficher
     *                                pour les alert-success
     * 
     * @return string
     */
    public function success(string $success_message) : string
    {
        return <<<HTML
        <div class="col-12 alert app-alert-success">
            {$success_message}
        </div>
HTML;
    }

    /**
     * Permet d'afficher un message de type information.
     * 
     * @param string $info Information à afficher.
     * 
     * @return string
     */
    public function info(string $info) : string
    {
        return <<<HTML
        <div class="col-12 alert app-alert-info">
            {$info}
        </div>
HTML;
    }

    /**
     * Retourne "les ... que vous voulez supprimer seront affichés ici".
     * 
     * @param string $title 
     * 
     * @return string
     */
    public function nothingToDelete($title)
    {
        return "Les $title que vous voulez supprimer seront affiché(e)s ici.";
    }

    /**
     * Retourne une chaine de caractère 'Ajout éffectué avec succès'
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function addSuccess() : string
    {
        return "Ajout éffectué avec succès !";
    }

    /**
     * Retourne une chaine de 'le nom ou le titre est invalide'.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function titleIsEmpty() : string
    {
        return "Veuillez insérer un titre.";
    }
    
    /**
     * Retourne une chaine de 'le nom ou le titre est invalide'.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function titleContainsHTML() : string
    {
        return "Veuillez vérifier que le titre ne contient pas de code HTML.";
    }

    /**
     * Retourne une chaîne de caractère "Veuillez insérer une description".
     * 
     * @return string
     */
    public function descriptionIsEmpty()
    {
        return "Veuillez insérer une description.";
    }
   
    /**
     * Retourne une chaine de caractère.
     * 
     * @return string
     */
    public function descriptionContainsHTML()
    {
        return "Veuillez vérifier que la description ne contient pas de code HTML.";
    }

    /**
     * Retourne le login passé en paramètre est déjà dans la base de données.
     *
     * @author Joel
     * @return string
     */
    public function loginIsUsed() : string
    {
        return "Ce login est déjà utilisé.";
    }

    /**
     * Le login ne doit pas contenir de code HTML
     * 
     * @return string
     */
    public function loginContainsHTML()
    {
        return "Le login ne doit pas contenir de code HTML";
    }

    /**
     * Retourne une chaine de carctère, veuillez saisir un login
     * 
     * @return string
     */
    public function loginIsEmpty()
    {
        return "Veuillez saisir un login.";
    }

    /**
     * Retourne "Veuillez saisir une valeur correcte pour dans le champ " suivi du
     * nom du champ.
     * 
     * @param string $name Le nom à afficher dans la notification.
     * 
     * @return string
     */
    public function isNotInt(string $name)
    {
        return "Veuillez saisir une valeur correcte pour dans le champ " . $name;
    }

    /**
     * Retourne que l'adresse email est vide.
     * 
     * @author Joel
     * @return string
     */
    public function emailIsEmpty() : string
    {
        return 'Veuillez saisir une adresse email!';
    }
     
    /**
     * Retourne que l'adresse email n'est pas valide.
     * 
     * @author Joel
     * @return string
     */
    public function emailIsInvalid() : string
    {
        return 'Veuillez entrer une adresse email valide !';
    }
    
    /**
     * Retourne que le login n'est pas valide.
     * 
     * @author Joel
     * @return string
     */
    public function loginIsInvalid() : string
    {
        return "Veuillez vérifier que la taille du login > 4 et qu'il ne contient aucun code HTML !";
    }

    /**
     * Retourne que le champ de mot de passe est vide.
     * 
     * @return string
     */
    public function passwordIsEmpty()
    {
        return "Veuillez saisir un mot de passe.";
    }

    /**
     * Retourne que le mot de passe saisi est invalide.
     * 
     * @return string
     */
    public function passwordLengthIsInvalid()
    {
        return "Veuillez saisir un mot de passe de plus de 8 caractères.";
    }

    /**
     * Veuillez confirmer le mot de passe.
     * 
     * @return string
     */
    public function confirmPasswordIsEmpty()
    {
        return "Veuillez confirmer le mot de passe.";
    }
    
    /**
     * Retourne que les mots de passes ne sont pas identiques.
     * 
     * @author Joel
     * @return string
     */
    public function passwordsNotIdentics() : string
    {
        return "Veuillez vérifier que les mots de passes sont identiques.";
    }
    
    /**
     * Retourne une chaîne "Veuillez remplir tous les champs".
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function inputsEmpty() : string
    {
        return 'Veuillez remplir les champs !';
    }
    
    /**
     * Retourne une chaîne de caractère 'Identifiants incorrects'.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function errorLogin() : string
    {
        return 'Vos identifiants sont incorrects, veuillez réessayer !';
    }
    
    /**
     * Retourne une chaine de caractère 'Modification effectuée avec succès'.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function modificationSucceed()
    {
        return 'Modification effectuée avec succès !';
    }
    
    /**
     * Retourne une chaîne de caractère 'Echec de la modification'.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function modificationFailed() : string
    {
        return "Echec de la modification !";
    }
   
    /**
     * Retourne une chaîne de caractères
     * 'La description ne doit pas excéder 250 caractères'.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function descriptionLengthIsInvalid() : string
    {
        return "La description ne doit pas excéder 400 caractères";
    }
    
    /**
     * Retourne une chaine de caractère 'Fichier non chargé,
     * modification possible ultérieurement'.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function fileNotUpload() : string
    {
        return "Le fichier chargé n'a pas été enrégistrée, vous pouvez modifier cela ultérieurement.";
    }
    
    /**
     * Retourne une chaîne de caractère 'Child inexistant'.
     * 
     * @author Joel 
     * @return string [[Description]]
     */
    public function itemNotExist() : string
    {
        return "Element introuvable !";
    }


    /**
     * Retourne 'Veuillez selectionner un élément'.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function nothingSelected() : string
    {
        return "Veuillez selectionner au moins un élément à supprimer !";
    }
    
    /**
     * Retourne qu'il n'y a pas de compte administrateurs dans la base de données.
     * 
     * @author Joel
     * @return string
     */
    public function noAccounts() : string
    {
        return "Les comptes utilisateurs et administrateurs s'afficheront ici.";
    }

    /**
     * Retourne une chaîne de caractères : "Les $categories que vous créerer s'afficheront
     * ici".
     * 
     * @param string categorie La catégorie à afficher dans la chaîne de caractère.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function noItems(string $categorie) : string
    {
        if ($categorie == "themes")  $categorie = "thèmes";
        if ($categorie == "motivation-plus") $categorie = "vidéos de motivation +";
        if ($categorie == "etapes")  $categorie = "étapes";
        if ($categorie == "videos") $categorie = "vidéos";

        return "Les " . $categorie . " que vous créerez seront affiché(e)s ici.";
    }
  
    /**
     * Retourne 'Une formation possède déjà ce titre'.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function issetFormation() : string
    {
        return "Une formation porte ce titre.";
    }

    /**
     * Retourne string 'Rang déjà occupé'.
     * 
     * @author Joel
     * @return [[Type]] [[Description]]
     */
    public function rangIsUsed() : string
    {
        return "Une étape occupe déjà le rang que vous avez donné à celle-ci ! Nous n'avons pas ajouté ce rang, vous pourrez le modifier plus tard.";
    }

    /**
     * Retourne une chaîne de caractère
     * 'Veuillez entrer une valeur correcte pour le nombre de page !'.
     * 
     * @return string
     */
    public function nombrePageIsInvalid() : string
    {
        return "Veuillez entrer une valeur correcte pour le nombre de page.";
    }

    /**
     * Retourne une chaîne de caractère
     * 'Veuillez saisir une date correcte pour l\'annéee de parution !'.
     * 
     * @return string
     */
    public function anneeParutionIsInvalid() : string
    {
        return "Veuillez saisir une date correcte pour l'annéee de parution !";
    }

    /**
     * Retourne une chaîne de caractère
     * 'Veuillez saisir le nom de l\'auteur et
     * verifier qu\'il n\'excède pas 250 caractères !''.
     * 
     * @return string
     */
    public function auteurNameIsInvalid() : string
    {
        return "Veuillez saisir le nom de l'auteur et verifier qu'il n'excède pas 250 caractères.";
    }

    /**
     * Retourne une chaîne de caractère
     * 'Veuillez saisir le nom de la maison d\'edition et
     * verifier qu\'elle n\'excède pas 250 caractères !'.
     * 
     * @return string
     */
    public function maisonEditionNameIsInvalid() : string
    {
        return "Veuillez saisir le nom de la maison d'edition et verifier qu'elle n'excède pas 250 caractères.";
    }

    /**
     * Retourne une chaîne de caractère "le lien de la vidéo est invalide".
     * 
     * @return string
     */
    public function videoLinkIsInvalid()
    {
        return "Veuillez vérifier le lien de la vidéo de description.";
    }

    /**
     * Retourne une chaine de caractère "Veuillez vérifier que vous avez bien
     * charger une image".
     * 
     * @return string
     */
    public function imageIsInvalid()
    {
        return "Veuillez charger une image de taille inférieure à 2 Mo.";
    }

    /**
     * Retourne "Veuillez charger un fichier PDF."
     * 
     * @return string
     */
    public function isNotPdfFile()
    {
        return "Veuillez charger un fichier PDF.";
    }

    /**
     * Retourne une chaine de caractère 'opération effectée avec succès'.
     * 
     * @return string
     */
    public function succeed()
    {
        return "Enregistrement effectué avec succès !";
    }
    
    /**
     * Retourne une chaine de caractère 'échec de l'enregistrement avec succès'.
     * 
     * @return string
     */
    public function failed()
    {
        return "Echec de l'enregistrement !";
    }

    /**
     * Retourne une chaîne de caractère "ce compte administrateur n'existe pas".
     *
     * @return string
     */
    public function adminNotExist()
    {
        return "Ce compte n'existe pas !";
    }

    /**
     * Retourne une chaine de caractère pour dire que le contenu de l'article
     * est vide.
     * 
     * @return string
     */
    public function articleContentIsEmpty()
    {
        return "Veuillez saisir le contenu de l'article.";
    }

}