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

namespace App\BackEnd\APIs\Files;

use Exception;
use Intervention\Image\ImageManager;

/**
 * Gère les fichiers image.
 * 
 * @category Category
 * @package  App\BackEnd
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class Image extends File
{
    /**
     * Date à la quelle l'image a été enregistrée sur l'application.
     * 
     * @var string
     */
    private $_date_creation;

    /**
     * Permet de sauvegarder l'image dans les fichiers du serveur dans le dossier des
     * images et des miniatures.
     * 
     * @param string $image_name Le nom de l'image.
     * 
     * @return bool
     */
    public function saveImages(string $image_name)
    {
        $this->save($image_name, THUMBS_PATH, 1280, 720);
        $this->save($image_name, ORIGINALS_IMAGES_PATH);
        return true;
    }

    /**
     * Créer une miniature et la sauvegarde.
     * 
     * @param $avatar_name Le nom du fichier
     * 
     * @return void
     */
    public function saveAvatar($avatar_name)
    {
        $this->save($avatar_name, AVATARS_PATH, 150, 150);
    }

    /**
     * Enregistre une image en prenant en paramètre le nom et le dossier de
     * sauvegarde.
     * 
     * @param string $image_name 
     * @param string $dir_path     Le dossier où on doit déposer l'image.
     * @param int    $image_width 
     * @param int    $image_height 
     * 
     * @return bool
     */
    private function save(string $image_name, string $dir_path, int $image_width = null, int $image_height = null)
    {
        if (!file_exists($dir_path)) {
            mkdir($dir_path);
        }
        $manager = new ImageManager();
        $manager = $manager->make($_FILES['image_uploaded']['tmp_name']);
        if (null !== $image_width && null !== $image_height){
            $manager->fit($image_width, $image_height);
        }
        $manager->save($dir_path . $image_name . IMAGES_EXTENSION);
        return true;
    }

    /**
     * Renomme l'image de couverture et l'image miniature d'un item.
     * 
     * @param string $old_name L'ancien nom de l'image.
     * @param string $new_name Le nouveau nom de l'image.
     * 
     * @return bool
     */
    public function renameImages($old_name, $new_name)
    {
        $old_thumbs = THUMBS_PATH . $old_name;
        $new_thumbs = THUMBS_PATH . $new_name . IMAGES_EXTENSION;

        if (rename($old_thumbs, $new_thumbs)) {
            return true;
        } else {
            throw new Exception("Echec du renommage de l'image de couverture.");
        }
    }

    /**
     * Supprime les images de couverture et miniatures
     * 
     * @param string $image_name Le nom de l'image.
     * 
     * @return bool
     */
    public function deleteImages($image_name)
    {
        $old_thumbs_path = THUMBS_PATH . $image_name;
        if (file_exists($old_thumbs_path)) {
            unlink($old_thumbs_path);
        }
        $old_original_image_path = ORIGINALS_IMAGES_PATH . $image_name;
        if (file_exists($old_original_image_path)) {
            unlink($old_original_image_path);
        }
    }

}
