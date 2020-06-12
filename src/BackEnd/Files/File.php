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

/**
 * Gère une Les fichiers.
 * 
 * @category Category
 * @package  App\BackEnd
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class File
{
    /**
     * Nom du fichier.
     * 
     * @var string
     */
    protected $name;

    /**
     * Le nom du dossier.
     * 
     * @var string
     */
    protected $dirName;

    /**
     * La taille du fichier uploadé
     * 
     * @var int
     */
    protected $size;
    
    /**
     * Extension du fichier uploadé.
     * 
     * @var string
     */
    protected $extension;

    /**
     * Date du fichier.
     * 
     * @var string
     */
    protected $date;

    /**
     * Constructeur d'un fichier.
     * 
     * @param $path Tableau $_FILES qui contient les informations relatives
     *                      à l'image.
     */
    public function __construct(array $path = null)
    {
        if (null !== $path) {
            $fileInfos = pathinfo($path);

            $this->name = $fileInfos['filename'];
            $this->dirName = $fileInfos["dirname"];
            $this->extension = $fileInfos['extension'];
            $this->size = $fileInfos['size'];
        }
        
    }

    /**
     * Retourne le nom du fichier.
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Retourne le nom du dossier.
     * 
     * @return string
     */
    public function getDirName()
    {
        return $this->dirName;
    }

    /**
     * Retourne la taille du fichier.
     * 
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }
    
    /**
     * Retourne l'extension du fichier uploadé.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function getExtension()
    {
        return '.' . mb_strtolower($this->extension);
    }

    /**
     * Retourne la date.
     * 
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }
    
}
