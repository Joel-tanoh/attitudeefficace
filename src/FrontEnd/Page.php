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

use App\FrontEnd\View\Layout;
use App\FrontEnd\View\View;

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
    private $description;
    private $view;

    /**
     * Permet de créer une page.
     * 
     * @param string $meta_title  Le titre qui sera affiché dans la page.
     * @param string $view        Le contenu de la page qui sera affiché dans
     *                            la page.
     * @param string $description La description de la page.
     */
    public function __construct(string $meta_title = null, string $view = null, string $description = null)
    {
        $this->meta_title = $meta_title;
        $this->description = $description;
        $this->view = $view;
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
        <body>
            {$this->view}
            {$this->appJs()}
        </body>
        </html>
HTML;
    }

    /**
     * Affiche la page d'administration.
     * 
     * @return string
     **/
    public function adminPage()
    {
        $layout = new Layout();
        $view = new View();

        return <<<HTML
        {$this->debutDePage("fr")}
        <head>
            {$this->metaData()}
            {$this->adminCss()}  
        </head>
        <body id="adminPart">
            {$layout->fixedNavbarAndFixedSidebarAndContainer( $view->navbar("admin"), $view->adminSidebar(), $this->view ) }
            {$this->adminJs()}
        </body>
        </html>
HTML;
    }

    /**
     * Page de connexion.
     * 
     * @return string
     */
    public function connexionPage()
    {
        return <<<HTML
        {$this->debutDePage("fr")}
        <head>
            {$this->metaData()}
            {$this->adminCss()}  
        </head>
        <body id="adminPart">
            {$this->view}
            {$this->adminJs()}
        </body>
        </html>
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
     * Retourne les balises meta
     * 
     * @return string
     */
    private function metaData()
    {
        return <<<HTML
        <meta charset="utf-8">
        <meta name="description" content="{$this->description}">
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
     * Retourne les fichiers Css de la partie administration.
     * 
     * @return string
     */
    private function adminCss()
    {
        $theme = "default";
        return <<<HTML
        {$this->appCss()}
        {$this->callCssFile("app/admin/" . $theme . "/css/connexion.css")}
        {$this->callCssFile("app/admin/" . $theme . "/css/navbar.css")}
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
        <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
         rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
         crossorigin="anonymous"> -->
        {$this->callCssFile("vendor/bootstrap/css/bootstrap.min.css")}
        {$this->callCssFile("vendor/bootstrap/css/icheck-bootstrap.min.css")}
        <!-- <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
         rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN"
         crossorigin="anonymous"> -->
        {$this->callCssFile("vendor/fontawesome/css/fontawesome.min.css")}
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
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script> -->
        {$this->callJsFile("vendor/fontawesome/js/all.min.js")}
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

}
