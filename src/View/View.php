<?php

/**
 * Fichier de classe.
 * 
 * @author Joel Tanoh <joel.developpeur@gmail.com>
 */

namespace App\View;

use App\Router;
use App\BackEnd\Models\Entity;
use App\BackEnd\Models\Items\ItemChild;
use App\View\Notification;
use App\View\Form;
use App\View\ModelsView\UserView;
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
    public static function administrattionDashboard()
    {
        return <<<HTML

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
     * @param array $items      La liste des items à lister.
     * @param array $className La classe PHP ou la catégorie des items qu'on veut
     *                          lister qui permettrat d'instancier des objets.
     * 
     * @return string Code HTML de la page qui liste les items.
     */
    public static function listItemsView(array $items, string $className)
    {
        $bddManager = Entity::bddManager();
        $title = ucfirst(Entity::getCategorieFormated(Router::getUrlAsArray()[0], "pluriel"));

        $itemsNumber = $bddManager->count(
            "id",
            Entity::getTableName(Router::getUrlAsArray()[0]),
            "categorie",
            Router::getUrlAsArray()[0]
        );

        $contentHeader = Snippet::listItemsContentHeader($title, $itemsNumber);

        if (empty($items)) {
            $notification = new Notification();
            $content =
                '<div class="row">'
                    . '<div class="col-12">'
                        . $notification->info($notification->noItems($className))
                    .'</div>'
                .'</div>'
            ;
        } else {
            $content = Template::gridOfCards($items, $className);
        }

        return <<<HTML
        {$contentHeader}
        {$content}
HTML;
    }

    /**
     * Affiche la vue qui permet de lister les vidéos de motivation plus.
     * 
     * @param array $videos
     * 
     * @return string
     */
    public static function listMotivationPlusVideosView(array $videos)
    {
        $bddManager = Entity::bddManager();
        $videosNumber = $bddManager->count("id", ItemChild::TABLE_NAME, "categorie", "videos");

        if (empty($videos)) {
            $notification = new Notification();
            $content = 
            '<div class="row">'
                . '<div class="col-12">'
                    . $notification->info($notification->noItems("motivation-plus"))
                .'</div>'
            .'</div>'
        ;
        } else {
            $content = Template::gridOfCards($videos, "videos");
        }

        $listItemsContentHeader = Snippet::listItemsContentHeader("Motivation +", $videosNumber);

        return <<<HTML
        {$listItemsContentHeader}
        {$content}
HTML;
    }

    /**
     * La vue qui liste les minis services et affiche le résumé des commandes de minis
     * service.
     * 
     * @param array $items      La liste des items à lister.
     * 
     * @return string Code HTML de la page qui liste les mini services.
     */
    public static function listMiniservicesView(array $items)
    {
        $bddManager = Entity::bddManager();
        $title = ucfirst(Entity::getCategorieFormated(Router::getUrlAsArray()[0], "pluriel"));
        $itemsNumber = $bddManager->count(
            "id"
            , Entity::getTableName(Router::getUrlAsArray()[0])
            , "categorie"
            , "mini-services"
        );

        $listItemsContentHeader = Snippet::listItemsContentHeader($title, $itemsNumber);
        $miniServiceCommandsResume = Snippet::miniServicesCommandsResume();

        if (empty($items)) {
            $notification = new Notification();
            $content = '<div>'. $notification->info($notification->noItems("mini-services")) .'</div>';
        } else {
            $content = Template::gridOfCards($items, "mini-services", "px-2");
        }

        return <<<HTML
        {$listItemsContentHeader}
        <section class="row mb-3">
            <section class="col-12 col-md-9 mb-3">
                {$content}
            </section>
            <section class="col-12 col-md-3">
                {$miniServiceCommandsResume}
            </section>
        </section>
HTML;
    }

    /**
     * Vue qui affiche toutes les commandes.
     * 
     * @return string
     */
    public static function listMiniservicesCommandsView($commands = null)
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
     * @param $accounts Un tableau qui contient les variables qui viennent de la base
     *                  de données.
     * 
     * @return string
     */
    public static function listAccountsView($accounts)
    {
        if (empty($accounts)) {
            $notification = new Notification();
            $to_return = $notification->info( $notification->noAccounts() );
        } else {
            $adminTemplate = new UserView();
            $to_return = $adminTemplate->listUsers($accounts);
        }

        return $to_return;
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
    public static function createItemView(string $categorie = null, $errors = null)
    {
        $notification = new Notification();
        $formContent = Form::getForm($categorie);
        $error = !empty($errors) ? $notification->errors($errors) : null;
        $title = ucfirst(Entity::getCategorieFormated(Router::getUrlAsArray()[0], "pluriel")) . " &#8250 Ajouter";
        $listItemsContentHeader = Snippet::listItemsContentHeader($title);

        return <<<HTML
        {$listItemsContentHeader}
        {$error}
        {$formContent}
HTML;
    }

    /**
     * Retourne la vue pour ajouter une vidéo de motivation plus.
     * 
     * @param string $errors S'il y'a des erreurs à afficher.
     * 
     * @return string
     */
    public static function createMotivationPlusVideoView($errors = null)
    {
        $notification = new Notification();
        $formContent = Form::getForm("videos");
        $error = !empty($errors) ? $notification->errors($errors) : null;
        $contentHeader = Snippet::listItemsContentHeader("Motivation + &#8250 nouvelle vidéo");

        return <<<HTML
        {$contentHeader}
        {$error}
        {$formContent}
HTML;
    }

    /**
     * Retourne la vue pour lire un item.
     * 
     * @param $item Objet
     * 
     * @return string
     */
    public static function readItemView($item)
    {
        if ($item->isParent()) {
            $parentView = new ParentView($item);
            return $parentView->readParent();
        } elseif ($item->isChild() || $item->getCategorie() === "motivation-plus") {
            $childView = new ChildView($item);
            return $childView->readChild();
        }
    }

    /**
     * Retourne la page de modification d'un item.
     * 
     * @param $item      L'item qu'on veut modifier.
     * @param string $categorie La catégorie ou la table de l'item qu'on veut00
     *                          modifier.
     * @param array  $errors    Les erreurs à afficher dans le cas où la validation
     *                          des données retourne des erreurs.
     * 
     * @return string
     */
    public static function editItemView($item, $categorie, $errors = null)
    {
        $form = Form::getForm($categorie, $item);

        $notification = new Notification();
        $error = !empty($errors) ? $notification->errors($errors) : null;

        $title = $item->getTitle() . " &#8250 éditer";
        $listItemsContentHeader = Snippet::listItemsContentHeader($title);

        return <<<HTML
        {$listItemsContentHeader}
        {$error}
        {$form}
HTML;
    }

    /**
     * Retourne la page de suppression de plusieurs items selon la catégorie.
     * 
     * @param Entity  $items     La liste des items qu'on veut supprimer.
     * @param string $categorie La catégorie des items à supprimer.
     * @param string $error     Au cas où il y'a une erreur à afficher.
     * 
     * @return string Code de la page.
     */
    public static function deleteItemsView($items, $categorie, $error = null)
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
        $listItemsContentHeader = Snippet::listItemsContentHeader($title);

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