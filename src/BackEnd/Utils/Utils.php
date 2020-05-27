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

namespace App\BackEnd\Utils;

use App\BackEnd\BddManager;
use Cocur\Slugify\Slugify;
use DateTime;

/**
 * Gère toutes les fonctions de l'application.
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class Utils
{
    /**
     * Génère et retourne un code aléatoire.
     * 
     * @author Joel
     * @return string
     */
    public static function generateCode()
    {
        $code = '';
        $string_array = explode(
            ',',
            '0,1,2,3,4,5,6,7,8,9,_,A,a,B,b,C,c,D,d,E,e,F,f,G,g,H,h,I,i,J,j,K,k,L,l,M,n,O,o,P,p,Q,q,R,r,S,s,T,t,U,u,V,v,W,w,X,x,Y,y,Z,z'
        );
        $code_length = random_int(5, 8);
        $string_array_length = count($string_array);
        for ($i = 0; $i <= $code_length; $i++) {
            $j = random_int(0, $string_array_length - 1);
            $code .= $string_array[$j];
        }
        return $code;
    }

    /**
     * Permet de faire une redirection vers l'url passé en paramètre.
     * 
     * @param string $url L'url sur lequel faire la redirection.
     * 
     * @return void
     */
    static function header(string $url = "")
    {
        header("location: " . $url);
        exit();
    }
    
    /**
     * Retourne un slugify qui peut être utilisé par toutes les classes.
     * 
     * @param string $string La chaîne de caractère qu'on veut sluguer.
     * 
     * @return string La chaîne de caractère slugué.
     */
    static function slugify($string)
    {
        $slugify = new Slugify(['rulesets' => ['default', 'turkish']]);
        return $slugify->slugify($string);
    }

    /**
     * Vérifie si c'est une nouvelle visite.
     * 
     * @return bool
     */
    public static function isNewVisit()
    {
        if (isset($_SERVER["HTTP_REFERER"]) && strchr($_SERVER["HTTP_REFERER"], $_SERVER["SERVER_NAME"])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Permet de mettre à jour le compteur de visite de l'app.
     * 
     * @return bool 
     */
    public static function setAppComputerVisite()
    {
        $year = date("Y");
        $month = date("m");
        $day = date("d");
        $visite = BddManager::verifyDateVisitIsset($year, $month, $day);

        if (self::isNewVisit()) {
            if ($visite["date_isset"]) {
                BddManager::incOrDecColValue("increment", "nombre_visite", "compteur_visites", $visite["id"]);
            } else {
                BddManager::insertNewVisit($year, $month, $day, 1);
            }
        }
    }

}
