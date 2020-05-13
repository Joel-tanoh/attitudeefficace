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

/**
 * Gère les fichiers PDF.
 * 
 * @category Category
 * @package  App\BackEnd
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class Pdf extends File
{
    /**
     * Vérifie que c'est un fichier PDF.
     * 
     * @author Joel
     * @return bool
     */
    public function isPDF()
    {
        return $this->extension == 'pdf';
    }

    /**
     * Permet de sauvegarder un fichier pdf qui vient d'être uploadé.
     * 
     * @param string $pdf_name 
     * 
     * @return true
     */
    public function savePdfFile($pdf_name)
    {
        $destination = PDF_PATH . $pdf_name . PDF_EXTENSION;
        if (!empty($_FILES["pdf_uploaded"]["name"])) {
            if (!move_uploaded_file($_FILES["pdf_uploaded"]["tmp_name"], $destination)) {
                throw new Exception("Echec de l'enregistrement du fichier Pdf");
            }
        } 
    }

}

