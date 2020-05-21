<?php

/**
 * Fichier de configuration général de l'application ou du site.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <tanohbassapatrick@gmail.com>
 * @license  url.com license_name
 * @version  GIT: Joel_tanoh
 * @link     Link
 */

/**
 * Constantes des bases de données
 */
define("APP_NAME", "Attitude efficace");
define("DB_NAME", "Attitudeefficace");
define("DB_ADDRESS", "localhost");
define("DB_LOGIN", "root");
define("DB_PASSWORD", "Joel1997@admin");

/**
 * Constantes des chemins des dossiers
 */
define("ROOT_PATH", dirname(__DIR__) . DIRECTORY_SEPARATOR);
define("PUBLIC_PATH", ROOT_PATH . "public" . DIRECTORY_SEPARATOR);
define("ADMIN_PATH", PUBLIC_PATH . "administration" . DIRECTORY_SEPARATOR);
define("ASSETS_PATH", PUBLIC_PATH . "assets" . DIRECTORY_SEPARATOR);
define("FILES_PATH", PUBLIC_PATH . "files" . DIRECTORY_SEPARATOR);
define("PDF_PATH", FILES_PATH . "pdf" . DIRECTORY_SEPARATOR);
define("IMAGES_PATH", FILES_PATH . "images" . DIRECTORY_SEPARATOR);
define("ORIGINALS_IMAGES_PATH", IMAGES_PATH . "originals" . DIRECTORY_SEPARATOR);
define("THUMBS_PATH", IMAGES_PATH . "thumbs" . DIRECTORY_SEPARATOR);
define("AVATARS_PATH", IMAGES_PATH . "avatars" . DIRECTORY_SEPARATOR);
define("LOGOS_PATH", IMAGES_PATH . "logos" . DIRECTORY_SEPARATOR);

/**
 * Constantes Urls des dossiers
 */
define("PUBLIC_URL", "http://attitudeefficace.com");
define("ADMIN_URL", "http://attitudeefficace.com/administration");
define("ASSETS_DIR", PUBLIC_URL . "/assets");
define("FILES_DIR", PUBLIC_URL . "/files");
define("PDF_DIR", FILES_DIR . "/pdf");
define("IMAGES_DIR", FILES_DIR . "/images");
define("ORIGINALS_IMAGES_DIR", IMAGES_DIR . "/orgininals");
define("THUMBS_DIR", IMAGES_DIR . "/thumbs");
define("AVATARS_DIR", IMAGES_DIR . "/avatars");
define("LOGOS_DIR", IMAGES_DIR . "/logos");

/**
 * Constances des fichiers
 */
define("MAX_IMAGE_UPLOADED_SIZE", 2097152);
define("VALID_IMAGE_EXTENSIONS", ['png', 'jpg', 'jpeg', 'gif']);
define("PDF_EXTENSION", ".pdf");
define("IMAGES_EXTENSION", ".png");
define("DEFAULT_COVER", IMAGES_DIR . "/default-cover" . IMAGES_EXTENSION);
define("DEFAULT_THUMBS", THUMBS_DIR . "/default-thumbs" . IMAGES_EXTENSION);
define("DEFAULT_AVATAR", AVATARS_DIR . "/default-avatar" . IMAGES_EXTENSION);


/**
 * Vérifie si l'utilisateur est connecté ou s'est déjà connecté.
 * 
 * @return bool True si le cookie['admin_login'] n'est pas vide ou si la
 *              session['admin_login'] n'est pas vide.
 */
function someoneIsConnected()
{
    return !empty($_COOKIE['admin_login']) || !empty($_SESSION['admin_login']) ? true : false;
}

/**
 * Permet de dumper une variable.
 * 
 * @param mixed $var 
 * 
 * @return string
 */
function dump($var)
{
    echo '<pre class="dumper">';
    var_dump($var);
    echo '</pre>';
}