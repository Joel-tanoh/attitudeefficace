<?php

/**
 * Fichier de classe.
 * 
 * @author Joel Tanoh <joel.developpeur@gmail.com>
 */

namespace App\FrontEnd\View;

use App\BackEnd\APIs\Bdd;
use App\BackEnd\Models\Model;
use App\BackEnd\Utils\Notification;
use App\FrontEnd\View\Html\Form;
use App\FrontEnd\View\ModelsView\AdministrateurView;
use App\FrontEnd\View\ModelsView\ParentView;
use App\FrontEnd\View\ModelsView\ChildView;
use App\Router;

/**
 * Une vue est un bloc ou un ensemble de bloc de code HTML qui a une fonctionnalité
 * bien précise : elle peut être une navbar, une sidebar, une banière Bootstrap, une liste à
 * puce etc.
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
    public function connexionFormView($admin_login, $admin_password, $error = null)
    {
        $form = new Form();
        $logo_dir = LOGOS_DIR;
        $admin_url = ADMIN_URL;
        $notificateur = new Notification();
        $error = null !== $error ? $notificateur->error($error) : null;
        
        return <<<HTML
        <div id="connexion">
            <div id="container" class="container-fluid">
                <div class="mb-2 d-flex flex-column align-items-center">
                    <img class="img-fluid rounded mb-3" src="{$logo_dir}/logo_1.png" alt="Attitude efficace" width="75rem">
                    <div class="h5">Attitude efficace</div>
                </div>
                <div class="error-box">
                    {$error}
                </div>
                <form method="post" action="{$_SERVER['PHP_SELF']}">
                    <header class="text-white">Connexion</header>
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
                            {$this->activateSessionButton()}
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
     * Retourne une barre de navigation en fonction de la partie passée en
     * paramètre.
     * 
     * @param string $appPart 
     * 
     * @return Navbar
     */
    public function navbar(string $appPart)
    {
        $navbar = new Navbar();
        if ($appPart == "public") return $navbar->publicNavbar();
        elseif ($appPart == "administration" || $appPart == "admin") return $navbar->adminNavbar();
    }

    /**
     * Retourne la sidebar.
     * 
     * @return Sidebar.
     */
    public function adminSidebar()
    {
        $sidebar = new Sidebar();
        return $sidebar->adminSidebar();
    }
       
    /**
     * Affiche l'avatar d'un utilisateur.
     * 
     * @param string $avatar_src
     * @param string $alt_information
     * 
     * @return string
     */
    public function showAvatar(string $avatar_src, string $alt_information = null)
    {
        return <<<HTML
        <div>
            <img src="{$avatar_src}" alt="{$alt_information}" class="user-avatar img-fluid"/>
        </div>
HTML;
    }

    /**
     * Retourne l'image miniature de l'utilisateur connecté dans la navbar.
     * 
     * @param string $avatar_src
     * @param string $alt_information
     * 
     * @param $user 
     * 
     * @return string
     */
    public function navbarUserAvatar(string $avatar_src, string $alt_information = null)
    {
        return <<<HTML
        <div>
            <img src="{$avatar_src}" alt="{$alt_information}" class="navbar-user-avatar img-circle shdw mr-2"/>
        </div>
HTML;
    }

    /**
     * Peremet d'afficher l'avatar de l'utilisateur dans la sidebar.
     * 
     * @param string $avatar_src
     * @param string $alt_information
     * 
     * @return string
     */
    public function sidebarUserAvatar(string $avatar_src, string $alt_information = null)
    {
        return <<<HTML
        <div class="text-center my-2">
            <img src="{$avatar_src}" alt="{$alt_information}" class="sidebar-user-avatar img-circle img-fluid"/>
        </div>
HTML;
    }

    /**
     * Permet d'afficher le logo dans la navbar.
     * 
     * @param string $brand_src        Le lien vers l'image.
     * @param bool   $set_it_clickable Permet de rendre le logo clickable.
     * @param string $click_direction  L'url exécuté lors du click sur le logo.
     * 
     * @return string
     */
    public function navbarBrand(string $brand_src, bool $set_it_clickable = false, string $click_direction = null)
    {
        if ($set_it_clickable) {
            return <<<HTML
            <a class="brand" href="{$click_direction}">
                <img src="{$brand_src}" alt="Attitude efficace" class="brand navbar-brand mb-2">
            </a>
HTML;
        } else {
            return <<<HTML
            <img src="{$brand_src}" alt="Attitude efficace" class="brand navbar-brand mb-2">
HTML;
        }
    }

    /**
     * Affiche le logo dans la sidebar.
     *
     * @param string $brand_src        Le lien vers l'image.
     * @param bool   $set_it_clickable Permet de rendre le logo clickable.
     * @param string $click_direction  L'url exécuté lors du click sur le logo.
     * 
     * @return string
     */
    public function sidebarBrand(string $brand_src, bool $set_it_clickable = false, string $click_direction = null) : string
    {
        if ($set_it_clickable) {
            return <<<HTML
            <a class="brand" href="{$click_direction}">
                <img src="{$brand_src}" alt="Attitude efficace" class="brand sidebar-brand mb-2">
            </a>
HTML;
        } else {
            return <<<HTML
            <img src="{$brand_src}" alt="Attitude efficace" class="brand sidebar-brand mb-2">
HTML;
        }
    }

    /**
     * Tableau de bord (Tableau de bord de la partie administration).
     * 
     * @return string
     */
    public function adminDashboard()
    {
        return <<<HTML

HTML;
    }

    /**
     * Retourne la vue de la page d'acceuil de la partie publique.
     * 
     * @return string
     */
    public function publicAccueil()
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
    public function listItems(array $items, string $class_name)
    {
        $title = ucfirst(Model::getCategorieFormated(Router::getUrlAsArray()[0], "pluriel"));
        if (empty($items)) {
            $notification = new Notification();
            $to_show = '<div class="col-12">'. $notification->info($notification->noItems($class_name)) .'</div>';
        } else {
            $list = "";
            foreach ($items as $item) {
                $object = Model::returnObject($class_name, $item["code"]);
                $list .= Card::card($object->get("thumbs_src"), $object->get("title"), $object->get("admin_url"), $object->get("day_creation"));
            }
            $to_show = $list;
        }

        return <<<HTML
        <div class="mb-3">
            <div class="mb-4">
                {$this->crumbs($title)}
            </div>
            <section class="row">
                {$to_show}
            </section>
        </div>
HTML;
    }

    /**
     * Affiche la vue qui permet de lister les vidéos de motivation plus.
     * 
     * @param array $videos
     * 
     * @return string
     */
    public function listMotivationPlusVideos(array $videos)
    {
        $number_of_videos = Bdd::countTableItems("item_childs", "categorie", "videos");
        if (empty($videos)) {
            $videos_list = null;
        } else {
            $videos_list = "";
            foreach($videos as $video) {
                $video = Model::returnObject("videos", $video["code"]);
                $videos_list .= Card::card($video->get("thumbs_src"), $video->get("title"), $video->get("admin_url"), $video->get("day_creation"));
            }
        }
        
        return <<<HTML
        <div class="mb-3">
            <h1 class="mb-3">Bienvenue dans votre rubrique Motivation +</h1>
            <section class="d-flex align-items-center mb-4">
                <div class="mr-2">Vous avez actuellement {$number_of_videos} vidéos.</div>
                {$this->contextMenu()}
            </section>
            <section class="row">
                {$videos_list}
            </section>
        </div>
HTML;
    }

    /**
     * Retourne une vue qui affiche les utilisateurs qui suivent l'item
     * passé en paramètre.
     * 
     * @param $item 
     * 
     * @return string
     */
    public function listItemsuscribers($item)
    {

    }

    /**
     * Page de listing des comptes administrateurs et utilisateurs.
     * 
     * @param $accounts Un tableau qui contient les variables qui viennent de la base
     *                  de données.
     * 
     * @return string
     */
    public function listAccounts($accounts)
    {
        if (empty($accounts)) {
            $notification = new Notification();
            $to_return = $notification->info( $notification->noAccounts() );
        } else {
            $admin_layout = new AdministrateurView();
            $to_return = $admin_layout->listAccounts($accounts);
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
    public function createItem(string $categorie = null, $errors = null)
    {
        $form = new Form();
        $notification = new Notification();
        $formContent = $form->getForm($categorie);
        $error = !empty($errors) ? $notification->errors($errors) : null;
        $title = ucfirst(Model::getCategorieFormated(Router::getUrlAsArray()[0], "pluriel")) . " &#8250 Ajouter";

        return <<<HTML
        <div class="mb-3">
            {$this->crumbs($title)}
            {$error}
            {$formContent}
        </div>
HTML;
    }

    /**
     * Retourne la vue pour ajouter une vidéo de motivation plus.
     * 
     * @param string $errors S'il y'a des erreurs à afficher.
     * 
     * @return string
     */
    public function createMotivationPlusVideo($errors = null)
    {
        $form = new Form();
        $notification = new Notification();
        $formContent = $form->getForm("videos");
        $error = !empty($errors) ? $notification->errors($errors) : null;
        $title = "Motivation + &#8250 nouvelle vidéo";

        return <<<HTML
        <div class="mb-3">
            {$this->crumbs($title)}
            {$error}
            {$formContent}
        </div>
HTML;
    }

    /**
     * Retourne la vue pour lire un item.
     * 
     * @param $item Objet
     * 
     * @return string
     */
    public function readItem($item)
    {
        if ($item->isParent()) {
            $parent_view = new ParentView();
            return $parent_view->readParent($item);
        } elseif ($item->isChild()) { 
            $child_view = new ChildView();
            return $child_view->readChild($item);
        }
    }

    /**
     * Retourne la page de modification d'un item.
     * 
     * @param string $item      L'item qu'on veut modifier.
     * @param string $categorie La catégorie ou la table de l'item qu'on veut00
     *                          modifier.
     * @param array  $errors    Les erreurs à afficher dans le cas où la validation
     *                          des données retourne des erreurs.
     * 
     * @return string
     */
    public function editItem($item, $categorie, $errors = null)
    {
        $form = new Form();
        $notification = new Notification();
        $form = $form->getForm($categorie, $item);
        $error = !empty($errors) ? $notification->errors($errors) : null;
        $title = $item->get('title') . " &#8250 éditer";

        return <<<HTML
        <div class="mb-3">
            {$this->crumbs($title)}
            {$error}
            {$form}
        </div>
HTML;
    }

    /**
     * Retourne la page de suppression de plusieurs items selon la catégorie.
     * 
     * @param Model   $items     La liste des items qu'on veut supprimer.
     * @param string $categorie La catégorie des items à supprimer.
     * @param string $error     Au cas où il y'a une erreur à afficher.
     * 
     * @return string Code de la page.
     */
    public function deleteItems($items, $categorie, $error = null)
    {
        $notifier = new Notification();
        $notification = null;
        $list = "";

        if (empty($items)) {
            $notification = $notifier->info( $notifier->nothingToDelete( Model::getCategorieFormated($categorie, "pluriel") ) );
        } else {
            $list = $this->deleteItemsTable($items, $categorie);
        }

        $error = null !== $error ? $notifier->error($error) : null;
        $title = Model::getCategorieFormated($categorie, "puriel");

        return <<<HTML
        <div class="mb-3">
            {$this->crumbs($title)}
            {$error}
            {$notification}
            {$list}
        </div>
HTML;
    }

    /**
     * Page 404 de la partie publique.
     * 
     * @return string
     */
    public function publicError404()
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
    public function adminError404()
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

    /**
     * Retourne les boutons pour publier, supprimer ou modifier l'instance.
     * 
     * @param $item          L'objet pour lequel on doit afficher le bouton.
     * @param bool $edit_button   
     * @param bool $post_button   
     * @param bool $share_button  
     * @param bool $delete_button 
     * 
     * @return string
     */
    public function manageButtons($item)
    {
        $buttons = '';
        $buttons .= $this->button($item->get("edit_url"), "Editer", "btn-primary mr-1", "far fa-edit fa-lg");
        $buttons .= $this->button($item->get("post_url"), "Poster", "btn-success mr-1", "fas fa-reply fa-lg");
        $buttons .= $this->button($item->get("share_url"), "Partager", "btn-success mr-1", "fas fa-share fa-lg");
        $buttons .= $this->button($item->get("delete_url"), "Supprimer", "btn-danger mr-1", "far fa-trash-alt fa-lg");
        
        return <<<HTML
        <div class="mb-4">
            {$buttons}
        </div>
HTML;
    }

    /**
     * Affiche la vidéo de description de l'instance passé en paramètre.
     * 
     * @param string $video_link L'identifiant de la vidéos sur Youtube.
     * 
     * @return string
     */
    public function showYoutubeVideo(string $video_link = null)
    {
        if (null === $video_link) {
            $result = $this->noVideoBox();
        } else {
            $result = <<<HTML
            <iframe src="https://www.youtube.com/embed/{$video_link}"
                allow="accelerometer; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen class="w-100 video" style="height:30rem"></iframe>
HTML;
        }

        return <<<HTML
        <div class="card mb-3">
            <div class="card-header">Vidéo</div>
            {$result}
        </div>
HTML;
    }

    /**
     * Retourne une liste "voir aussi" pour afficher les autres items de la même
     * catégorie que l'item courant en excluant l'item courant.
     * 
     * @param string $exclu Le titre de la méthode qu'on ne veut pas
     *                      afficher. 
     * 
     * @return $array
     */
    public function voirAussi($exclu)
    {
        $table = Model::getTableNameFrom($exclu->get("categorie"));
        $items = Bdd::getAllFromTableWithout($table, $exclu->get("id"), $exclu->get("categorie"));
        $list = '';
        foreach ($items as $item) {
            $item = Model::returnObject($exclu->get("categorie"), $item["code"]);
            $list .= $this->voirAussiRow($item);
        }
        if (empty($list)) $list = '<div>Vide</div>';

        return <<<HTML
        <div class="col-md-3 mb-3">
            <div class="card">
                <h6 class="card-header bg-white">Voir aussi</h6>
                <div class="card-body">
                    {$list}
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Affiche les données.
     * 
     * @param $item L'item dont on affiche les données.
     * 
     * @return string
     */
    public function showData($item)
    {
        return <<<HTML
        <div class="row mb-3">
            <div class="col-12 col-md-6 mb-3">
                {$this->data($item)}
            </div>
            <div class="col-12 col-md-6">
                {$this->showThumbs($item)}
            </div>
        </div>
        {$this->showYoutubeVideo($item->get("video_link"))}
HTML;
    }

    /**
     * Retourne un crumbs.
     * 
     * @param string $title
     * 
     * @return string
     */
    public function crumbs(string $title = null)
    {
        $title = ucfirst($title);
        return <<<HTML
        <div class="d-flex align-items-center mb-3">
            <div class="h4 mr-3">{$title}</div>
            {$this->contextMenu()}
        </div>
HTML;
    }

    /**
     * Retourne le contextMenu.
     * 
     * @return string
     */
    public function contextMenu()
    {
        return <<<HTML
        <div>
            {$this->button(Model::getCategorieUrl(Router::getUrlAsArray()[0], ADMIN_URL)."/create", "Ajouter", "btn-success",  "fas fa-plus")}
            {$this->button(Model::getCategorieUrl(Router::getUrlAsArray()[0], ADMIN_URL)."/delete", "Supprimer", "btn-danger", "fas fa-trash-alt")}
        </div>
HTML;
    }

    /**
     * Retourne une vue pour une barre de recherche.
     * 
     * @return string
     */
    public function searchBar()
    {
        $form = new Form();

        return <<<HTML
        <div class="app-search-bar m-3">
            <form action="" method="post">
                {$form->input("search", "recherche", "rechercheInput", null, "Rechercher", "app-search-bar-input p-1")}
                <button type="submit" class="app-search-bar-button">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
HTML;
    }

    /**
     * Affiche une ligne d'une liste.
     * 
     * @param $item L'objet dont on affiche les données.
     *
     * @return string
     */
    private function rowOfListingItems($item)
    {
        $title = ucfirst($item->get("title"));
        $childrenNumber = $item->isParent() ? ParentView::itemchildrenNumber($item) . " | " : null;

        return <<<HTML
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="mb-2">{$title}</h5>
                <div>
                    Créé le {$item->get("day_creation")} |
                    Visité {$item->get("views")} fois |
                    {$childrenNumber}
                    {$item->get("classement")}
                </div>
                <div>
                    <a href="{$item->get('url')}" class="text-success">Voir plus</a>
                    <a href="{$item->get('edit_url')}" class="text-blue">Editer</a>
                    <a href="{$item->get('delete_url')}" class="text-danger">Supprimer</a>
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Retourne une vue qui affiche l'ensemble des données principales
     * pour l'item passé en paramètre.
     * 
     * @param $item 
     * 
     * @return string
     */
    private function data($item)
    {
        return <<<HTML
        <div class="card">
            <div class="card-header">Données</div>
            <div class="card-body">
                <div>Description : {$item->get("description")}</div>
                <div>Prix : {$item->get("prix")}</div>
                <div>Date de création : {$item->get("date_creation")}</div>
                <div>Date de mise à jour : {$item->get("date_modification")}</div>
            </div>
        </div>
HTML;
    }

    /**
     * Retourne une checkbox pour activer les variables cookies. Si l'utilisateur
     * coche cette checkbox, les cookies sont activées.
     * 
     * @return string
     */
    private function activateSessionButton()
    {
        return <<<HTML
        <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox" name="activate_cookie" id="customCheckbox1" value="oui">
            <label for="customCheckbox1" class="custom-control-label">Se souvenir de moi</label>
        </div>
HTML;
    }

    /**
     * Retourne une vue pour permmetre à l'utilisateur de se connecter
     * par les réseaux sociaux.
     * 
     * @return string
     */
    private function connectBySocialsNetworks()
    {
        return <<<HTML
        <div class="text-center text-muted h5 mb-3">-- OU --</div>
        <div class="mb-3">
            <div class="mb-2">{$this->connexionFormFacebookButton()}</div>
            <div>{$this->connexionFormGoogleButton()}</div>
        </div>
HTML;
    }

    /**
     * Retourne un bouton qui dirige vers la page pour se connecter grâce
     * à Facebook.
     * 
     * @return string Code du bouton.
     */
    private function connexionFormFacebookButton()
    {
        return <<<HTML
        <a href="" class="d-block text-center bg-facebook text-white rounded p-2">
            Se connecter avec Facebook
        </a>
HTML;
    }

    /**
     * Retourne un bouton qui dirige vers la page pour se connecter grâce
     * à Google.
     * 
     * @return string Code du bouton.
     */
    private function connexionFormGoogleButton()
    {
        return <<<HTML
        <a href="" class="d-block text-center bg-danger text-white rounded p-2">
            Se connecter avec Google
        </a>
HTML;
    }

    /**
     * Retourne un lien du contextMenu.
     * 
     * @param string $href 
     * @param string $text 
     * @param string $btn_class 
     * @param string $fa_icon_class 
     *
     * @return string
     */
    private function button(string $href, string $text, string $btn_class = null, string $fa_icon_class = null)
    {
        if (null !== $fa_icon_class) {
            $fa_icon_class = '<i class="' . $fa_icon_class. '"></i>';
        }
        return <<<HTML
        <a class="btn {$btn_class}" href="{$href}">
            {$fa_icon_class}
            <span>{$text}</span>
        </a>
HTML;
    }

    /**
     * Retourne l'image de l'item passé en paramètre.
     * 
     * @param $item 
     * 
     * @return string
     */
    private function showThumbs($item)
    {
        $content = null !== $item->get("thumbs_src") ? $this->thumbs($item) : $this->noThumbsBox();

        return <<<HTML
        <div class="card">
            <div class="card-header">Image de couverture</div>
            {$content}
        </div>
HTML;
}

    /**
     * Retourne l'image de couverture de l'item passé en paramètre.
     * 
     * @param mixed $item
     * 
     * @return string
     */
    private function thumbs($item)
    {
        return <<<HTML
        <img src="{$item->get('thumbs_src')}" alt="{$item->get('image_name')}" class="img-fluid"/>
HTML;
    }

    /**
     * Retourne qu'il n'y pas d'image.
     * 
     * @return string
     */
    private function noThumbsBox()
    {
        return <<<HTML
        <div>Aucune image.</div>
HTML;
    }
   
    /**
     * Ce bloc est le bloc qui sera affiché si
     * l'instance concernée n'a pas de vidéo de description
     * 
     * @return string
     */
    private function noVideoBox()
    {
        return <<<HTML
        <div>Aucune vidéo.</div>
HTML;
    }

    /**
     * Retourne une vue pour afficher les autres items de même type
     * que celui passé en paramètre.
     *  
     * @param $item La catégorie qu'on veut afficher.
     * 
     * @return string
     */
    private function voirAussiRow($item)
    {
        $title = ucfirst($item->get("title"));
        $thumbs_src = $item->get("thumbs_src");
        return <<<HTML
        <div class="d-flex p-2">
            <div class="mr-2" style="width:5rem">
                <img src="{$thumbs_src}" alt="{$item->get('slug')}" class="img-fluid">
            </div>
            <div>
                <h5><a href="{$item->get('public_url')}">{$title}</a></h5>
                <span class="text-muted float-right text-small">{$item->get("day_creation")}</span>
            </div>
        </div>
HTML;
    }

    /**
     * Retourne un tableau sur la page de suppression d'items.
     * 
     * @param mixed $items
     * @param string $categorie
     * 
     * @return string
     */
    private function deleteItemsTable($items, string $categorie)
    {
        $rows = '';
        $form = new Form();
        foreach($items as $item) {
            $item = Model::returnObject($categorie, $item["code"]);
            $rows .= $this->deleteItemsTableRow($item);
        }
        return <<<HTML
        <form id="myForm" method="post" enctype="multipart/form-data" action="{$_SERVER['REQUEST_URI']}">
            <table class='mb-3'>
                <thead>
                    <th><input type="checkbox" id="checkAllItemsForDelete"></th>
                    <th>Titre</th>
                </thead>
                {$rows}
            </table>
            {$form->submitButton("suppression", "Supprimer")}
        </form>
HTML;
    }

    /**
     * Retourne une ligne dans le tableau de suppression des éléments.
     * 
     * @param $item
     * 
     * @return string
     */
    private function deleteItemsTableRow($item)
    {
        return <<<HTML
        <tr>
            <td><input type="checkbox" name="codes[]" id="{$item->get('slug')}" value="{$item->get('code')}"></td>
            <td><label for="{$item->get('slug')}">{$item->get("title")}</label></td>
        </tr>
HTML;
    }

}