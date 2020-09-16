<?php

/**
 * Fichier de classe.
 * 
 * @author Joel Tanoh <joel.developpeur@gmail.com>
 */

namespace App\View;

use App\Router;
use App\BackEnd\Models\Entity;
use App\BackEnd\Models\Items\Item;
use App\BackEnd\Models\Items\ItemChild;
use App\BackEnd\Models\Users\Administrator;
use App\View\Notification;
use App\View\Form;
use App\View\Models\Items\ItemChildView;
use App\View\Models\Items\ItemParentView;
use App\View\Models\Users\AdministratorView;
use App\View\ModelsView\AdministrateurView;
use App\View\ModelsView\ParentView;
use App\View\ModelsView\ChildView;

/**
 * Classe View. Regroupe toutes les vues de l'application.
 */
class View
{
    /**
     * Retourne la vue formulaire de connexion.
     * 
     * @param string $admin_login    Variable login qui contient le login de la
     *                               la personne qui veut se connecter.
     * @param string $admin_password Mot de passe de la personne qui veut se
     *                               se connecter.
     * @param string $error          
     * 
     * @return string
     */
    public static function connexionFormView($admin_login, $admin_password, $error = null)
    {
        $form = new Form();
        $logo_dir = LOGOS_DIR_URL;
        $adminurl = ADMIN_URL;
        $notificateur = new Notification();
        $error = null !== $error ? $notificateur->error($error) : null;
        $activateSessionCheckBox = Snippet::activateSessionButton();
        
        return <<<HTML
        <div id="connexion">
            <div id="container" class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-2 d-flex flex-column align-items-center">
                            <img class="img-fluid rounded mb-3" src="{$logo_dir}/logo_1.png"
                             alt="Attitude efficace" width="75rem">
                            <div class="h5">Attitude efficace</div>
                        </div>
                    </div>
                </div>
                <div class="error-box">
                    {$error}
                </div>
                <form method="post" action="{$_SERVER['PHP_SELF']}" class="rounded">
                    <header class="text-white rounded-top">Connexion</header>
                    <div class="content">
                        <div>
                            <input placeholder="Login" type="text" name="admin_login"
                                id="adminLogin" value="{$admin_login}" autofocus/>
                        </div>
                        <div>
                            <input placeholder="Mot de passe" type="password"
                                name="admin_password" id="adminPassword" value="{$admin_password}"/>
                        </div>
                        <div>
                        <div class="d-flex justify-content-between mb-2">
                            {$activateSessionCheckBox}
                            {$form->submitButton("connexion", "Connexion")}
                        </div>
                    </div>
                    <footer>
                        <a href="{$adminurl}/password-forgotten">Mot de passe oublié ?</a>
                    </footer>
                </form>
            </div>
        </div>
HTML;
    }

    /**
     * Tableau de bord (Tableau de bord de la partie administration).
     * 
     * @return string
     */
    public static function administrationDashboard()
    {
        $visitorsOnlineNumberSnippet = Snippet::showVisitorsOnlineNumber();

        return <<<HTML
        <div class="row mb-3">
            <div class="col-12">
                <h3>Tableau de bord</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-2">
                {$visitorsOnlineNumberSnippet}
            </div>
        </div>
HTML;
    }

    /**
     * Retourne la vue de la page d'acceuil de la partie publique.
     * 
     * @return string
     */
    public static function publicAccueilView()
    {
        return <<<HTML

HTML;
    }

    /**
     * Methode qui permet de lister les items.
     * 
     * @param array $items     La liste des items à lister.
     * @param array $categorie La classe PHP ou la catégorie des items qu'on veut
     *                         lister qui permettrat d'instancier des objets.
     * 
     * @return string Code HTML de la page qui liste les items.
     */
    public static function listItems(array $items, string $categorie)
    {
        if ($categorie === "motivation-plus") {
            $title = "Motivation +";
            $itemsNumber = Item::getMotivationPlusVideosNumber();
        } else {
            $title = ucfirst(Entity::getCategorieFormated(Router::getUrlAsArray()[0], "pluriel"));
            $itemsNumber = Item::countAllItems($categorie);
        }

        if (empty($items)) {
            $notification = new Notification();
            $content =
                '<div class="row">'
                    . '<div class="col-12">'
                        . $notification->info($notification->noItems($categorie))
                    .'</div>'
                .'</div>'
            ;
        } else {
            $content = Snippet::listingTable($items);
        }

        $contentHeader = Snippet::listItemsContentHeader($title, "Liste", $itemsNumber);

        return <<<HTML
        {$contentHeader}
        {$content}
HTML;
    }

    /**
     * Vue qui affiche toutes les commandes.
     * 
     * @return string
     */
    public static function listMiniservicesOrders($commands = null)
    {
        return <<<HTML
        <section>
            Commandes
        </section>
HTML;
    }

    /**
     * Vue de listing des comptes administrateurs et utilisateurs.
     * 
     * @param $admins Un tableau qui contient les variables qui viennent de la base
     *                de données.
     * 
     * @return string
     */
    public static function listAdministrators($admins)
    {
        if (empty($admins)) {
            $notification = new Notification();
            $toReturn = $notification->info( $notification->noAdministrateurs() );
        } else {
            $toReturn = (new AdministratorView())->list($admins);
        }

        return $toReturn;
    }

    /**
     * Retourne la page pour ajouter un nouvel item.
     * 
     * @param string $categorie La catégorie de l'item qu'on veut créer.      
     * @param string $errors    Les erreurs à afficher s'il en existe après le
     *                          traitement du formulaire.
     * 
     * @return string
     */
    public static function createItem(string $categorie = null, $errors = null)
    {
        $notification = new Notification();

        if ($categorie === "motivation-plus") {
            $formContent = Form::getForm("videos");
            $title = "Motivation +";
        } else {
            $formContent = Form::getForm($categorie);
            $title = ucfirst(Entity::getCategorieFormated(Router::getUrlAsArray()[0], "pluriel"));
        }
        
        $error = !empty($errors) ? $notification->errors($errors) : null;
        $contentHeader = Snippet::listItemsContentHeader($title, "Ajouter");

        return <<<HTML
        {$contentHeader}
        {$error}
        {$formContent}
HTML;
    }

    /**
     * Retourne la vue pour lire un item.
     * 
     * @param \App\BackEnd\Models\Items\ItemParent|\App\BackEnd\Models\Items\ItemChild $item
     * 
     * @return string
     */
    public static function readItem($item)
    {
        if ($item->isParent()) {
            $itemParentView = new ItemParentView($item);
            return $itemParentView->readView();

        } elseif ($item->isChild() || $item->getCategorie() === "motivation-plus") {
            $itemChildView = new ItemChildView($item);
            return $itemChildView->readView();
        }
    }

    /**
     * Affiche une vue spéciale pour les vidéos.
     * 
     * @param \App\BackEnd\Models\Items\ItemChild $item
     */
    public static function readVideo($item)
    {
        return <<<HTML

HTML;
    }

    /**
     * Retourne la page de modification d'un item.
     * 
     * @param \App\BackEnd\Models\Items\ItemParent|\App\BackEnd\Models\Items\ItemChild $item
     * @param string $categorie La catégorie ou la table de l'item qu'on veut
     *                          modifier.
     * @param array  $errors    Les erreurs à afficher dans le cas où la validation
     *                          des données retourne des erreurs.
     * 
     * @return string
     */
    public static function updateItem($item, $categorie, $errors = null)
    {
        $form = Form::getForm($categorie, $item);

        $notification = new Notification();
        $error = !empty($errors) ? $notification->errors($errors) : null;

        $title = $item->getTitle();
        $listItemsContentHeader = Snippet::listItemsContentHeader($title, "Editer");

        return <<<HTML
        {$listItemsContentHeader}
        {$error}
        {$form}
HTML;
    }

    /**
     * Retourne la page de suppression de plusieurs items selon la catégorie.
     * 
     * @param array  $items     La liste des items qu'on veut supprimer.
     * @param string $categorie La catégorie des items à supprimer.
     * @param string $error     Au cas où il y'a une erreur à afficher.
     * 
     * @return string Code de la page.
     */
    public static function deleteItems($items, $categorie, $error = null)
    {
        $notifier = new Notification();
        $notification = null;
        $list = null;

        if (empty($items)) {
            $notification = $notifier->info( $notifier->nothingToDelete( Entity::getCategorieFormated($categorie, "pluriel") ) );
        } else {
            $list = Snippet::deleteItemsTable($items, $categorie);
        }

        $error = null !== $error ? $notifier->error($error) : null;
        $title = Entity::getCategorieFormated($categorie, "puriel");
        $listItemsContentHeader = Snippet::listItemsContentHeader($title, "Supprimer");

        return <<<HTML
        {$listItemsContentHeader}
        <div class="row">
            <div class="col-12">
                {$notification}
                {$error}
                {$list}
            </div>
        </div>
HTML;
    }

    /**
     * Vue 404 de la partie publique.
     * 
     * @return string
     */
    public static function publicError404View()
    {
        $public_url = PUBLIC_URL;

        return <<<HTML
        <section class="text-center">
            <h2 class="text-warning">404</h2>
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page non trouvée.</h3>
            <p>
                Nous n'avons pas retrouvé la page que vous cherchez.
                Retour à la <a href="{$public_url}">page d'acceuil</a>.
            </p>
        </section>
HTML;
    }

    /**
     * Vue 404 de la partie administration.
     * 
     * @return string
     */
    public static function adminError404View()
    {
        $adminurl = ADMIN_URL;

        return <<<HTML
        <section class="text-center">
            <h2 class="text-warning"> 404</h2>
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page non trouvée.</h3>
            <p>
                Nous n'avons pas retrouvé la page que vous cherchez. Elle n'a peut être pas encore été développée.
                Retour au <a href="{$adminurl}">Tableau de bord</a>.
            </p>
        </section>
HTML;
    }

}