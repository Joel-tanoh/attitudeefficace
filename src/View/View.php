<?php

/**
 * Fichier de classe.
 * 
 * @author Joel Tanoh <joel.developpeur@gmail.com>
 */

namespace App\View;

use App\Router;
use App\BackEnd\Bdd\BddManager;
use App\BackEnd\Models\Model;
use App\View\Notification;
use App\View\Form;
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
        $admin_url = ADMIN_URL;
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
                        <a href="{$admin_url}/password-forgotten">Mot de passe oublié ?</a>
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
    public static function adminDashboardView()
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
     * @param array $class_name La classe PHP ou la catégorie des items qu'on veut
     *                          lister qui permettrat d'instancier des objets.
     * 
     * @return string Code HTML de la page qui liste les items.
     */
    public static function listItemsView(array $items, string $class_name)
    {
        $title = ucfirst(Model::getCategorieFormated(Router::getUrlAsArray()[0], "pluriel"));
        $number_of_items = BddManager::countTableItems(
            Model::getTableNameFrom(Router::getUrlAsArray()[0]),
            "categorie",
            Router::getUrlAsArray()[0]
        );
        $listItemsContentHeader = Snippet::listItemsContentHeader($title, $number_of_items);

        if (empty($items)) {
            $notification = new Notification();
            $content = '<div class="col-12">'. $notification->info($notification->noItems($class_name)) .'</div>';
        } else {
            $list = "";
            foreach ($items as $item) {
                $object = Model::returnObject($class_name, $item["code"]);
                $list .= Card::card($object->get("thumbs_src"), $object->get("title"), $object->get("admin_url"), $object->get("day_creation"));
            }
            $content = $list;
        }

        return <<<HTML
        <div class="">
            {$listItemsContentHeader}
        </div>
        <section class="row">
            {$content}
        </section>
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
        $number_of_videos = BddManager::countTableItems("item_childs", "categorie", "videos");
        if (empty($videos)) {
            $videos_list = null;
        } else {
            $videos_list = "";
            foreach($videos as $video) {
                $video = Model::returnObject("videos", $video["code"]);
                $videos_list .= Card::card($video->get("thumbs_src"), $video->get("title"), $video->get("admin_url"), $video->get("day_creation"));
            }
        }
        $listItemsContentHeader = Snippet::listItemsContentHeader("Motivation +", $number_of_videos);

        return <<<HTML
        {$listItemsContentHeader}
        <section class="row">
            {$videos_list}
        </section>
HTML;
    }

    /**
     * Page de listing des comptes administrateurs et utilisateurs.
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
            $admin_template = new AdministrateurView();
            $to_return = $admin_template->listAccounts($accounts);
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
        $title = ucfirst(Model::getCategorieFormated(Router::getUrlAsArray()[0], "pluriel")) . " &#8250 Ajouter";
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
            $parent_view = new ParentView($item);
            return $parent_view->readParent();
        } elseif ($item->isChild()) { 
            $child_view = new ChildView($item);
            return $child_view->readChild();
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

        $title = $item->get('title') . " &#8250 éditer";
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
     * @param Model  $items     La liste des items qu'on veut supprimer.
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
            $notification = $notifier->info( $notifier->nothingToDelete( Model::getCategorieFormated($categorie, "pluriel") ) );
        } else {
            $list = Snippet::deleteItemsTable($items, $categorie);
        }

        $error = null !== $error ? $notifier->error($error) : null;
        $title = Model::getCategorieFormated($categorie, "puriel");
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
     * Page 404 de la partie publique.
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
                Nous n'avons pas retrouvé la page que vous cherchez. Elle n'a peut être pas encore été développée.
                Retour à la <a href="{$public_url}">page d'acceuil</a>.
            </p>
        </section>
HTML;
    }

    /**
     * Page 404 de la partie administration.
     * 
     * @return string
     */
    public static function adminError404View()
    {
        $admin_url = ADMIN_URL;

        return <<<HTML
        <section class="text-center">
            <h2 class="text-warning"> 404</h2>
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page non trouvée.</h3>
            <p>
                Nous n'avons pas retrouvé la page que vous cherchez. Elle n'a peut être pas encore été développée.
                Retour au <a href="{$admin_url}">Tableau de bord</a>.
            </p>
        </section>
HTML;
    }

}