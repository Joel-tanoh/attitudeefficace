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
 * @version  GIT: Joel_tanoh
 * @link     Link
 */

namespace App\FrontEnd;

use App\BackEnd\Data\Data;
use App\BackEnd\Utils\Notification;
use App\FrontEnd\Layout\AdministrateurLayout;
use App\FrontEnd\Layout\Layout;
use App\FrontEnd\Layout\TopBar;
use App\FrontEnd\Layout\SideBar;
use App\FrontEnd\Layout\ParentLayout;
use App\FrontEnd\Layout\ChildLayout;
use App\FrontEnd\Layout\Html\Form;

/**
 * Classe qui gère tout ce qui est en rapport à une page.
 *  
 * @category Category
 * @package  App\FrontEnd
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  Release: 1
 * @link     Link
 */
class Page
{
    private $meta_title;
    private $page_title;
    private $_page_description;
    private $page_content;

    /**
     * Permet de créer une page.
     * 
     * @param string $page_title       Le titre qui sera affiché dans la page.
     * @param string $page_content     Le contenu de la page qui sera affiché dans
     *                                 la page.
     * @param string $page_description La description de la page.
     */
    public function __construct(string $page_title = null, string $page_content = null, string $page_description = null)
    {
        $this->meta_title = APP_NAME . " | " . $page_title;
        $this->page_title = $page_title;
        $this->_page_description = $page_description;
        $this->page_content = $page_content;
        $this->notificateur = new Notification();
    }

    /**
     * Affiche le code pour l'index de la partie publique
     * 
     * @return string
     **/
    public function publicPage()
    {
        return <<<HTML
        {$this->debutDePage("fr")}
        <head>
        {$this->metaData()}
        {$this->publicCss()}
        </head>
        {$this->publicBody()}
HTML;
    }

    /**
     * Affiche la page d'administration.
     * 
     * @return string
     **/
    public function adminPage()
    {
        return <<<HTML
        {$this->adminHeads()}
        {$this->adminBody()}
HTML;
    }

    /**
     * Retourne la page de connexion.
     * 
     * @param string $admin_login    Variable login qui contient le login de la
     *                               la personne qui veut se connecter.
     * @param string $admin_password Mot de passe de la personne qui veut se
     *                               se connecter.
     * @param string $error          
     * 
     * @return string
     */
    public function connexionPage($admin_login, $admin_password, $error = null)
    {
        return <<<HTML
        {$this->adminHeads()}
        {$this->connexionPageContent($admin_login, $admin_password, $error)}
HTML;
    }

    /**
     * Page de dashboard (Tableau de bord de la partie administration).
     * 
     * @return string
     */
    public function dashboard()
    {
        return <<<HTML

HTML;
    }

    /**
     * Retourne le contenu de la page d'acceuil de la partie publique.
     * 
     * @return string
     */
    public function publicAccueilPage()
    {
        return <<<HTML

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
    public function listAccounts($accounts)
    {
        $to_return = "";
        if (empty($accounts)) {
            $notification = new Notification();
            $to_return .= $notification->info( $notification->noAccounts() );
        } else {
            $admin_layout = new AdministrateurLayout();
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
    public function create(string $categorie = null, $errors = null)
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
    public function read($item)
    {
        if ($item->isParent()) { return ParentLayout::read($item); }
        if ($item->isChild()) { return ChildLayout::read($item); }
    }

    /**
     * Retourne la page de modification d'un item.
     * 
     * @param string $item      L'item qu'on veut modifier.
     * @param string $categorie La catégorie ou la table de l'item qu'on veut
     *                          modifier.
     * @param array  $errors    Les erreurs à afficher dans le cas où la validation
     *                          des données retourne des erreurs.
     * 
     * @return string
     */
    public function edit($item, $categorie, $errors = null)
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
     * Retourne la page de suppression d'un item.
     * 
     * @param Data   $items     La liste des items qu'on veut supprimer.
     * @param string $categorie La catégorie des items à supprimer.
     * @param string $error     Au cas où il y'a une erreur à afficher.
     * 
     * @return string Code de la page.
     */
    public function delete($items, $categorie, $error = null)
    {
        $notification = new Notification();
        $layout = new Layout();
        if (empty($items)) {
            $content = $notification->info( $notification->nothingToDelete( Data::getTypeFormated($categorie) ) );
        } else {
            $content = "";
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
     * Methode qui permet d'afficher les items.
     * 
     * @param array $items      La liste des items à lister.
     * @param array $class_name La classe ou la catégorie des items qu'on veut
     *                          lister.
     * 
     * @return string Code HTML de la page qui liste les items.
     */
    public function listItems(array $items, string $class_name)
    {
        $layout = new Layout();
    
        if (empty($items)) {
            $notification = new Notification();
            $notification = $notification->info($notification->noItems($class_name));
    
            return <<<HTML
            <div class="mb-3">
                {$notification}
            </div>
HTML;
        } else {
            $list = "";
            foreach ($items as $item) {
                $object = Data::returnObject($class_name, $item["code"]);
                $list .= $layout->listRow($object);
            }

            return <<<HTML
            <div class="row mb-3">
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
        $admin_url = ADMIN_URL;
        return <<<HTML
        <section class="text-center">
            <h2 class="text-warning"> 404</h2>
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page non trouvée.</h3>
            <p>
                Nous n'avons pas retrouvé la page que vous cherchez.
                Retour au <a href="{$admin_url}">Tableau de bord</a>.
            </p>
        </section>
HTML;
    }

    /**
     * Code du début de la page.
     * 
     * @param string $html_lang
     * 
     * @return string
     */
    private function debutDePage($html_lang = "fr")
    {
        return <<<HTML
        <!DOCTYPE html>
        <html lang="{$html_lang}">
HTML;
    }

    /**
     * Retourne l'entête d'une page HTML
     * 
     * @param string $html_lang
     * 
     * @return string
     */
    private function adminHeads($html_lang= "fr")
    {
        return <<<HTML
        {$this->debutDePage($html_lang)}
        <head>
            {$this->metaData()}
            {$this->adminCss()}   
        </head>
HTML;
    }

    /**
     * Retourne les balises meta
     * 
     * @return string
     */
    private function metaData()
    {
        return <<<HTML
        <meta charset="utf-8">
        <meta name="description" content="{$this->_page_description}">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- <meta http-equiv="refresh" content="300"> -->
        <title>{$this->meta_title}</title>
        {$this->appIcon()}
HTML;
    }
    
    /**
     * Retourne le code pour les icones.
     * 
     * @return string
     */
    private function appIcon()
    {
        $logos_dir = LOGOS_DIR;
        return <<<HTML
        <link rel="icon" href="{$logos_dir}/favicon.ico" type="image/x-icon">
        <link rel="shortcut icon" href="{$logos_dir}/favicon.ico" type="image/x-icon">
HTML;
    }

    /**
     * Affiche l'interface de l'administration.
     * 
     * @return string
     */
    private function adminBody()
    {
        $layout = new Layout();
        return <<<HTML
        <body class="admin">
        <div class="container-fluid">
            {$layout->navbar("administration")}
            {$layout->adminSidebar()}
            {$this->pageContent()}
            {$this->adminJs()}
        </div>
        {$this->finDePage()}
HTML;
    }

    /**
     * Retourne le corps de la page sur la partie publique.
     * 
     * @return string
     */
    private function publicBody()
    {
        return <<<HTML
        <body id="public">
        {$this->pageContent()}
        {$this->appJs()}
        {$this->finDePage()}
HTML;
    }

    /**
     * Retourne le contenu de la page.
     * 
     * @return string
     */
    private function pageContent()
    {
        return <<<HTML
        <div id="content">
            {$this->page_content}
        </div>
HTML;
    }

    /**
     * Retourne le titre de la page.
     * 
     * @return string
     */
    private function pageTitle()
    {
        return <<<HTML
        <h3 class="mb-3">{$this->page_title}</h3>
HTML;
    }

    /**
     * Retourne le formulaire pour se connecter.
     * 
     * @param string $admin_login    Login
     * @param string $admin_password Mot de passe.
     * @param string $error          Erreur à afficher en cas d'erreur lors de
     *                               la connexion à la base de données.
     * 
     * @return string
     */
    private function connexionPageContent(string $admin_login, string $admin_password, string $error = null)
    {
        $form = new Form();
        $logos_dir = LOGOS_DIR;
        $admin_url = ADMIN_URL;
        $error = null !== $error ? $this->notificateur->error($error) : null;
        
        return <<<HTML
        <body id="connexion">
            <div id="container" class="container-fluid">
                <div class="mb-2 d-flex flex-column align-items-center">
                    <img class="img-fluid rounded mb-3" src="{$logos_dir}/logo_1.png"
                        alt="Attitude efficace" width="100rem">
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
                                id="adminLogin" value="{$admin_login}" autofocus/>
                        </div>
                        <div>
                            <input placeholder="Mot de passe" type="password"
                                name="admin_password" id="adminPassword" value="{$admin_password}"/>
                        </div>
                        <div>
                        <div class="d-flex justify-content-between mb-2">     
                            {$this->_activateSession()}
                            {$form->submitButton("connexion", "Connexion")}
                        </div>
                        <div class="text-center text-muted h5">-- OU --</div>
                        <div class="mb-3">
                            <div class="mb-2">{$this->_facebookButton()}</div>
                            <div>{$this->_googleButton()}</div>
                        </div>
                    </div>
                    <footer>
                        <a href="{$admin_url}/forgot-password.php">
                            Mot de passe oublié ?
                        </a>
                    </footer>
                </form>
            </div>
        {$this->findePage()}
HTML;
    }

    /**
     * Retourne une checkbox pour activer les variables cookies. Si l'utilisateur
     * coche cette checkbox, les cookies sont activées.
     * 
     * @return string
     */
    private function _activateSession()
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
    private function _facebookButton()
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
    private function _googleButton()
    {
        return <<<HTML
        <a href="" class="d-block text-center bg-danger text-white rounded p-2">
            Se connecter avec Google
        </a>
HTML;
    }

    /**
     * Retourne les fichiers css selon le thème passé en paramètre.
     *  
     * @return string
     */
    private function appCss()
    {
        return <<<HTML
        {$this->vendorCss()}
        {$this->callCssFile("app/main.css")}
HTML;
    }

    /**
     * Retourne les fichiers JS appelés.
     * 
     * @return string
     */
    private function appJs()
    {
        return <<<HTML
        {$this->vendorJs()}
        {$this->callJsFile("app/main.js")}
HTML;
    }

    /**
     * Retourne les fichiers Css de la partie administration.
     * 
     * @return string
     */
    private function adminCss()
    {
        $theme = "default";
        return <<<HTML
        {$this->appCss()}
        {$this->callCssFile("app/admin/" . $theme . "/css/admin.css")}
        {$this->callCssFile("app/admin/" . $theme . "/css/connexion.css")}
        {$this->callCssFile("app/admin/" . $theme . "/css/topbar.css")}
        {$this->callCssFile("app/admin/" . $theme . "/css/sidebar.css")}
HTML;
    }

    /**
     * Retourne les fichiers Css de la partie publique.
     * 
     * @return string
     */
    private function publicCss()
    {
        return $this->appCss();
    }

    /**
     * Retourne les fichiers Js de la partie administration.
     * 
     * @return string
     */
    private function adminJs()
    {
        $theme = "default";
        return <<<HTML
        {$this->vendorJs()}
        {$this->callJsFile("app/admin/" . $theme . "/js/admin.js")}
HTML;
    }

    /**
     * Retourne eles fichiers CSS utilisés sur toutes les pages.
     * 
     * @return string
     */
    private function vendorCss()
    {
        return <<<HTML
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
         rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
         crossorigin="anonymous">
        <!-- {$this->callCssFile("vendor/bootstrap/css/bootstrap.min.css")} -->
        {$this->callCssFile("vendor/bootstrap/css/icheck-bootstrap.min.css")}
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
         rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN"
         crossorigin="anonymous">
        <!-- {$this->callCssFile("vendor/fontawesome/css/fontawesome.min.css")} -->
        {$this->callCssFile("vendor/select2/css/select2.min.css")}
HTML;
    }

    /**
     * Retourne les fichiers JS appelés sur toutes les pages.
     * 
     * @return string
     */
    private function vendorJs()
    {
        return <<<HTML
        {$this->callJsFile("vendor/jquery/jquery.min.js")}
        {$this->callJsFile("vendor/bootstrap/js/bootstrap.bundle.min.js")}
        {$this->callJsFile("vendor/bootstrap/js/bs-custom-file-input.min.js")}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
        <!-- {$this->callJsFile("vendor/fontawesome/js/all.min.js")} -->
        {$this->callJsFile("vendor/select2/js/select2.full.min.js")}
        {$this->callJsFile("vendor/ckeditor/ckeditor.js")}
HTML;
    }

    /**
     * Retourne une balise link pour le fichiers css.
     * 
     * @param string $css_file_name Nom du fichier css.
     * 
     * @return string
     */
    private function callCssFile($css_file_name)
    {
        $assets_dir = PUBLIC_URL . "/assets";
        return <<<HTML
        <link rel="stylesheet" type="text/css" href="{$assets_dir}/{$css_file_name}">
HTML;
    }

    /**
     * Retourne une balise script pour appeler le fichier javascript passé
     * en paramètre.
     * 
     * @param string $js_file_name Nom du fichier javascript.
     * 
     * @return string
     */
    private function callJsFile($js_file_name)
    {
        $assets_dir = PUBLIC_URL . "/assets";
        return <<<HTML
        <script src="{$assets_dir}/{$js_file_name}"></script>
HTML;
    }

    /**
     * Retourne les balise HTML de fin de page
     * 
     * @return string
     **/
    private function finDePage()
    {
        return <<<HTML
        </body>
    </html>
HTML;
    }

}
