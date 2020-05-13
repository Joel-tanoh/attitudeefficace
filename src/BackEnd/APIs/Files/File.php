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
     * La taille du fichier uploadé
     * 
     * @var int
     */
    protected $size;
    
    /**
     * Extension du fichier uploadé
     */
    protected $extension;

    /**
     * Date du fichier
     */
    protected $date;

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
     * Retourne la taille du fichier.
     * 
     * @author Joel
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
    
}
