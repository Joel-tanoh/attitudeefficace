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
     * Extensions des fichiers pdf.
     * 
     * @var string
     */
    const EXTENSION = ".pdf";

    /**
     * Vérifie que c'est un fichier PDF.
     * 
     * @author Joel
     * @return bool
     */
    public function isPDF()
    {
        return $this->extension === 'pdf';
    }

    /**
     * Permet de sauvegarder un fichier pdf qui vient d'être uploadé.
     * 
     * @param string $pdfName 
     * 
     * @return true
     */
    public function savePdfFile($pdfName)
    {
        $destination = PDF_PATH . $pdfName . Pdf::EXTENSION;
        if (!empty($_FILES["pdf_uploaded"]["name"])) {
            if (!move_uploaded_file($_FILES["pdf_uploaded"]["tmp_name"], $destination)) {
                throw new Exception("Echec de l'enregistrement du fichier Pdf");
            }
        }
    }

}

