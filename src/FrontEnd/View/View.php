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
use App\FrontEnd\View\Layout;

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
     * @param string $adminLogin    Variable login qui contient le login de la
     *                              la personne qui veut se connecter.
     * @param string $adminPassword Mot de passe de la personne qui veut se
     *                              se connecter.
     * @param string $error          
     * 
     * @return string
     */
    public function connexionFormView($adminLogin, $adminPassword, $error = null)
    {
        $form = new Form();
        $logoDir = LOGOS_DIR;
        $adminUrl = ADMIN_URL;
        $error = null !== $error ? $this->notificateur->error($error) : null;
        
        return <<<HTML
        <div id="connexion">
            <div id="container" class="container-fluid">
                <div class="mb-2 d-flex flex-column align-items-center">
                    <img class="img-fluid rounded mb-3" src="{$logoDir}/logo_1.png" alt="Attitude efficace" width="100rem">
                    <div class="h3">Attitude efficace</div>
                </div>
                <div class="error-box">
                    {$error}
                </div>
                <form method="post" action="{$_SERVER['PHP_SELF']}">
                    <header class="text-white">Connexion</header>
                    <div class="content">
                        <div>
                            <input placeholder="Login" type="text" name="admin_login"
                                id="adminLogin" value="{$adminLogin}" autofocus/>
                        </div>
                        <div>
                            <input placeholder="Mot de passe" type="password"
                                name="admin_password" id="adminPassword" value="{$adminPassword}"/>
                        </div>
                        <div>
                        <div class="d-flex justify-content-between mb-2">     
                            {$this->activateSessionButton()}
                            {$form->submitButton("connexion", "Connexion")}
                        </div>
                        <div class="text-center text-muted h5">-- OU --</div>
                        <div class="mb-3">
                            <div class="mb-2">{$this->connexionFormFacebookButton()}</div>
                            <div>{$this->connexionFormGoogleButton()}</div>
                        </div>
                    </div>
                    <footer>
                        <a href="{$adminUrl}/password-forgotten">Mot de passe oublié ?</a>
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
     * @param Personne $person Celui dont on doit afficher l'avatar.
     * 
     * @return string
     */
    public function showAvatar($person)
    {
        return <<<HTML
        <img src="{$person->get('avatar_src')}" alt="{$person->get('login')}" class="img-fluid"/>
HTML;
    }

    /**
     * Retourne le menu.
     * 
     * @return string
     */
    public function menu()
    {
        global $url;
        $link = "/" . $url[0];

        return <<<HTML
        <div>
            {$this->menuLink($link."/create", "fas fa-plus", "Ajouter")}
            {$this->menuLink($link."/delete", "fas fa-trash-alt", "Supprimer")}
        </div>
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
        $buttons .= $this->button($item, "edit_url", "bg-blue mr-1", "far fa-edit fa-lg", "Editer");
        $buttons .= $this->button($item, "post_url", "bg-success mr-1", "fas fa-reply fa-lg", "Poster");
        $buttons .= $this->button($item, "share_url", "bg-success mr-1", "fas fa-share fa-lg", "Partager");
        $buttons .= $this->button($item, "delete_url", "bg-danger mr-1", "far fa-trash-alt fa-lg", "Supprimer");
        
        return <<<HTML
        <div class="mb-4">
            {$buttons}
        </div>
HTML;
    }

    /**
     * Retourne une petite carte pour afficher un item.
     * 
     * @param $item 
     * 
     * @return string
     */
    public function smallCard($item)
    {
        $title = ucfirst($item->get("title"));
        
        return <<<HTML
        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <a href="{$item->get('url')}">
                <h5>{$title}</h4>
                <div class="mb-3">
                    <div>Crée le {$item->get("date_creation")}</div>
                    <div>Mis à jour {$item->get("date_modification")}</div>
                    <div>Posté : {$item->get("posted")}</div>
                </div>
            </a>
        </div>
HTML;
    }

    /**
     * Affiche la vidéo de description de l'instance passé en paramètre.
     * 
     * @param $item L'objet dont on affiche les données.
     * 
     * @return string
     */
    public function showVideo($item)
    {
        if (null === $item->get("video_link")) {
            $result = $this->noVideoBox();
        } else {
            $result = <<<HTML
            <iframe src="https://www.youtube.com/embed/{$item->get('video_link')}"
                allow="accelerometer; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen class="w-100 h-100 video"></iframe>
HTML;
        }

        return <<<HTML
        <div class="app-card mb-3">
            <div class="app-card-header">Vidéo</div>
            <div class="app-card-body">
                {$result}
            </div>
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
        {$this->showVideo($item)}
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
    public function listItemlearners($item)
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
        $to_return = "";
        if (empty($accounts)) {
            $notification = new Notification();
            $to_return .= $notification->info( $notification->noAccounts() );
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

        return <<<HTML
        <div class="mb-3">
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
        if ($item->isParent()) { return ParentView::readItem($item); }
        if ($item->isChild()) { return ChildView::readItem($item); }
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
        $layout = new Layout();
        $form = new Form();
        $notification = new Notification();
        $form = $form->getForm($categorie, $item);
        $error = !empty($errors) ? $notification->errors($errors) : null;

        return <<<HTML
        <div class="mb-3">
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
        $notification = new Notification();
        if (empty($items)) {
            $content = $notification->info( $notification->nothingToDelete( Model::getTypeFormated($categorie) ) );
        } else {
            $content = "Vous verez s'afficher un tableau avec les items à supprimer";
        }
        $error = !empty($error) ? $notification->error($error) : null;

        return <<<HTML
        <div class="mb-3">
            {$error}
            {$content}
        </div>
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
        if (empty($items)) {
            $notification = new Notification();
            $notification = $notification->info( $notification->noItems( $class_name ) );
    
            return <<<HTML
            <div class="mb-3">
                {$notification}
            </div>
HTML;
        } else {
            $list = "";
            foreach ($items as $item) {
                $object = Model::returnObject($class_name, $item["code"]);
                $list .= $this->rowOfListingItems($object);
            }

            return <<<HTML
            <div class="mb-3">
                {$list}
            </div>
HTML;
        }
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
                Nous n'avons pas retrouvé la page que vous cherchez.
                Retour au <a href="{$public_url}">tableau de bord</a>.
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
        $adminUrl = ADMIN_URL;

        return <<<HTML
        <section class="text-center">
            <h2 class="text-warning"> 404</h2>
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page non trouvée.</h3>
            <p>
                Nous n'avons pas retrouvé la page que vous cherchez.
                Retour au <a href="{$adminUrl}">Tableau de bord</a>.
            </p>
        </section>
HTML;
    }

    /**
     * Affiche le logo dans la sidebar
     * 
     * @return string
     */
    public function sidebarBrand() : string
    {
        $logos_dir = LOGOS_DIR;
        $admin_url = ADMIN_URL;
        return <<<HTML
        <a class="brand" href="{$admin_url}">
            <img src="{$logos_dir}/logo_3.png" alt="Attitude efficace" class="brand sidebar-brand mb-2">
        </a>
HTML;
    }

    /**
     * Permet d'afficher le logo dans la navbar.
     * 
     * @param string $logo_url L'url de
     * 
     * @return string
     */
    public function navbarBrand()
    {

    }

    /**
     * Affiche une ligne d'une liste.
     * 
     * @param $item L'objet dont on affiche les données.
     *
     * @return string
     */
    public function rowOfListingItems($item)
    {
        $title = ucfirst($item->get("title"));
        $childrenNumber = $item->isParent() ? ParentView::itemchildrenNumber($item) : null;

        return <<<HTML
        <div class="mb-3">
            <h5 class="mb-2">{$title}</h5>
            <div>
                Créé le {$item->get("day_creation")} |
                Visité {$item->get("views")} fois |
                {$childrenNumber} |
                {$item->get("classement")}
            </div>
            <div>
                <a href="{$item->get('url')}" class="text-success">Détails</a>
                <a href="{$item->get('editer')}" class="text-blue">Editer</a>
                <a href="{$item->get('delete_url')}" class="text-danger">Supprimer</a>
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
        <div class="app-card">
            <div class="app-card-header">Données</div>
            <div class="app-card-body">
                <div class="mb-3">Description : {$item->get("description")}</div>
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
        <div class="custom-control custom-checkbox form-group">
            <input class="custom-control-input" type="checkbox" name="activate_cookie" id="customCheckbox1" value="oui">
            <label for="customCheckbox1" class="custom-control-label">
                Se souvenir de moi
            </label>
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
     * Retourne un lien du menu.
     * 
     * @param string $href 
     * @param string $text 
     * @param string $class 
     *
     * @return string
     */
    private function menuLink(string $href, string $text, string $class = null)
    {
        $adminUrl = ADMIN_URL;

        return <<<HTML
        <a class="{$class}" href="{$adminUrl}{$href}">
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
        $boxContent = null !== $item->get("cover_src")
            ? $this->thumbs($item)
            : $this->noThumbsBox();

        return <<<HTML
        <div class="app-card">
            <div class="app-card-header">Image de couverture</div>
            <div class="app-card-body">
                {$boxContent}
            </div>
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
        <img src="{$item->get('cover_src')}" alt="{$item->get('image_name')}" class="img-fluid"/>
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
        <div class="">
            <div class="d-flex p-2">
                <div class="mr-2" style="width:5rem">
                    <img src="{$thumbs_src}" alt="{$item->get('slug')}" class="img-fluid">
                </div>
                <div>
                    <h5><a href="{$item->get('url')}">{$title}</a></h5>
                    <span class="text-muted float-right text-small">{$item->get("day_creation")}</span>
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Retourne le code pour un bouton dans le manageButtons.
     * 
     * @param $item     L'objet dont il faut afficher les liens dans les boutons.
     * @param string $link     Le lien url à afficher dans le bouton
     * @param string $class    La classe css pour le bouton (la balise <a>)
     * @param string $fa_class La classe fontawesome pour l'icone dans le bouton
     * @param string $text     Le texte à afficher dans le bouton
     * 
     * @return string
     */
    private function button($item = null, string $link = null, string $class = null, string $fa_class = null, string $text = null)
    {
        return <<<HTML
        <a class="app-btn {$class} pb-2" href="{$item->get($link)}">
            <i class="{$fa_class}"></i>{$text}
        </a>
HTML;
    }

}