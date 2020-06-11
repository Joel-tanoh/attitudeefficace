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

/*** Constantes des bases de données ***/
define("APP_NAME", "Attitude efficace");
define("DB_NAME", "Attitudeefficace");
define("DB_ADDRESS", "localhost");
define("DB_LOGIN", "root");
define("DB_PASSWORD", "Joel1997@admin");

/*** Constances des fichiers (tailles maximales, extensions autorisées) ***/
define("MAX_IMAGE_UPLOADED_SIZE", 2097152);
define("VALID_IMAGE_EXTENSIONS", ['png', 'jpg', 'jpeg', 'gif']);
define("PDF_EXTENSION", ".pdf");
define("IMAGES_EXTENSION", ".png");

/*** Constantes des chemins des dossiers et des fichiers ***/
define("ROOT_PATH", dirname(__DIR__) . DIRECTORY_SEPARATOR);
define("PUBLIC_PATH", ROOT_PATH . "public" . DIRECTORY_SEPARATOR);
define("ADMIN_PATH", PUBLIC_PATH . "administration" . DIRECTORY_SEPARATOR);
define("ASSETS_PATH", PUBLIC_PATH . "assets" . DIRECTORY_SEPARATOR);
define("FILES_PATH", PUBLIC_PATH . "files" . DIRECTORY_SEPARATOR);
define("PDF_PATH", FILES_PATH . "pdf" . DIRECTORY_SEPARATOR);
define("IMAGES_PATH", FILES_PATH . "images" . DIRECTORY_SEPARATOR);
define("ORIGINALS_THUMBS_PATH", IMAGES_PATH . "originals" . DIRECTORY_SEPARATOR);
define("THUMBS_PATH", IMAGES_PATH . "thumbs" . DIRECTORY_SEPARATOR);
define("AVATARS_PATH", IMAGES_PATH . "avatars" . DIRECTORY_SEPARATOR);
define("LOGOS_PATH", IMAGES_PATH . "logos" . DIRECTORY_SEPARATOR);

/*** Constantes Urls des dossiers ***/
define("PUBLIC_URL", "http://attitudeefficace.com");
define("ADMIN_URL", "http://attitudeefficace.com/administration");
define("ASSETS_DIR", PUBLIC_URL . "/assets");
define("FILES_DIR_URL", PUBLIC_URL . "/files");
define("PDF_DIR", FILES_DIR_URL . "/pdf");
define("IMAGES_DIR_URL", FILES_DIR_URL . "/images");
define("ORIGINALS_THUMBS_DIR", IMAGES_DIR_URL . "/orgininals");
define("THUMBS_DIR_URL", IMAGES_DIR_URL . "/thumbs");
define("AVATARS_DIR_URL", IMAGES_DIR_URL . "/avatars");
define("LOGOS_DIR_URL", IMAGES_DIR_URL . "/logos");
define("DEFAULT_THUMBS", THUMBS_DIR_URL . "/default-thumbs" . IMAGES_EXTENSION);
define("DEFAULT_AVATAR", AVATARS_DIR_URL . "/default-avatar" . IMAGES_EXTENSION);
