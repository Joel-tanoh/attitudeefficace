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
        $this->_save($image_name, COVERS_PATH, 1280, 720);
        $this->_save($image_name, THUMBS_PATH, 320, 180);
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
        $this->_save($avatar_name, AVATARS_PATH, 150, 150);
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
    private function _save(string $image_name, string $dir_path, int $image_width, int $image_height)
    {
        if (!file_exists($dir_path)) {
            mkdir($dir_path);
        }
        $manager = new ImageManager();
        $manager->make($_FILES['image_uploaded']['tmp_name'])
            ->fit($image_width, $image_height)
            ->save($dir_path . $image_name . IMAGES_EXTENSION);
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
        $old_cover = COVERS_PATH . $old_name;

        if (file_exists($old_cover)) {
            $new_cover = COVERS_PATH . $new_name . IMAGES_EXTENSION;

            if (rename($old_cover, $new_cover)) {
                $old_thumbs = THUMBS_PATH . $old_name;
                $new_thumbs = THUMBS_PATH . $new_name . IMAGES_EXTENSION;

                if (rename($old_thumbs, $new_thumbs)) {
                    return true;
                } else {
                    throw new Exception("Echec du renommage de l'image miniature.");
                }
            } else {
                throw new Exception("Echec du renommage de l'image de couverture.");
            }
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
        $old_covers_path = COVERS_PATH . $image_name;
        if (file_exists($old_covers_path)) {
            unlink($old_covers_path);
            $old_thumbs_path = THUMBS_PATH . $image_name;
            unlink($old_thumbs_path);
        }
    }

}
