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

namespace App\BackEnd\Files;

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
     * Permet de sauvegarder l'image dans les fichiers du serveur dans le dossier des
     * images et des miniatures.
     * 
     * @param string $imageName Le nom de l'image.
     * 
     * @return bool
     */
    public function saveImages(string $imageName)
    {
        $this->save($imageName, THUMBS_PATH, 1280, 720);
        $this->save($imageName, ORIGINALS_THUMBS_PATH);
        return true;
    }

    /**
     * Créer une miniature et la sauvegarde.
     * 
     * @param $avatarName Le nom du fichier
     * 
     * @return void
     */
    public function saveAvatar($avatarName)
    {
        $this->save($avatarName, AVATARS_PATH, 150, 150);
        return true;
    }

    /**
     * Enregistre une image en prenant en paramètre le nom et le dossier de
     * sauvegarde.
     * 
     * @param string $imageName 
     * @param string $dirPath     Le dossier où on doit déposer l'image.
     * @param int    $imageWidth 
     * @param int    $imageHeight 
     * 
     * @return bool
     */
    private function save(string $imageName, string $dirPath, int $imageWidth = null, int $imageHeight = null)
    {
        if (!file_exists($dirPath)) {
            mkdir($dirPath);
        }
        $manager = new ImageManager();
        $manager = $manager->make($_FILES['image_uploaded']['tmp_name']);
        if (null !== $imageWidth && null !== $imageHeight){
            $manager->fit($imageWidth, $imageHeight);
        }
        $manager->save($dirPath . $imageName . IMAGES_EXTENSION);
        return true;
    }

    /**
     * Renomme l'image de couverture et l'image miniature d'un item.
     * 
     * @param string $oldName L'ancien nom de l'image.
     * @param string $newName Le nouveau nom de l'image.
     * 
     * @return bool
     */
    public function renameImages($oldName, $newName)
    {
        $oldThumbs = THUMBS_PATH . $oldName;
        $newThumbs = THUMBS_PATH . $newName . IMAGES_EXTENSION;

        if (rename($oldThumbs, $newThumbs)) {
            return true;
        } else {
            throw new Exception("Echec du renommage de l'image de couverture.");
        }
    }

    /**
     * Supprime les images de couverture et miniatures
     * 
     * @param string $imageName Le nom de l'image.
     * 
     * @return bool
     */
    public function deleteImages($imageName)
    {
        $oldThumbsPath = THUMBS_PATH . $imageName;
        if (file_exists($oldThumbsPath)) {
            unlink($oldThumbsPath);
        }
        
        $oldOrgImgPath = ORIGINALS_THUMBS_PATH . $imageName;
        if (file_exists($oldOrgImgPath)) {
            unlink($oldOrgImgPath);
        }
    }

}
