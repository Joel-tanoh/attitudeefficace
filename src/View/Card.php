<?php

/**
 * Fichier de classe.
 * 
 * @author Joel Tanoh <joel.developpeur@gmail.com>
 */

namespace App\View;

/**
 * Gère toutes les vues de type cartes.
 * 
 * @author Joel Tanoh <joel.developpeur@gmail.com>
 */
class Card extends View
{
    /**
     * Une carte ressemblant à celle de Youtube pour les vidéos.
     * 
     * @param string $img_src      La source de l'image à afficher dans la carte.
     * @param string $title        Le titre de la carte.
     * @param string $href         Le lien ou on va en cliquant sur la carte.
     * @param string $date_created La date de création de l'élément.
     * 
     * @return string
     */
    public static function card(
        string $img_src = null,
        string $title = null,
        string $href = null,
        string $date_created = null
    ) {
        $img = null;
        if (null !== $img_src) {
            $img = <<<HTML
            <img src="{$img_src}" alt="une photo de {$title}" class="img-fluid">
HTML;
        }

        if (null !== $date_created) {
            $date_created = <<<HTML
            <div class="text-small">
                <i class="far fa-clock"></i> <span>Ajoutée le {$date_created} </span>
            </div>
HTML;
        }

        return <<<HTML
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <a href="{$href}" class="text-black">
                <div class="border">
                    {$img}
                    <div class="p-3 bg-white">
                        <h6>{$title}</h6>
                        {$date_created}
                    </div>
                </div>
            </a>
        </div>
HTML;
    }

    /**
     * Retourne une box pour les informations de types chillres avec un
     * petit texte.
     * 
     * @param mixed  $number     Le chiffre à afficher. Peut être une chaine de
     *                           caractère ou un nombre.
     * @param string $small_text Le petit texte à afficher en dessous du chiffre.
     * @param string $href       Le lien vers lequel l'on est dirigé en cliquant sur
     *                           la box.
     * 
     * @return string
     */
    public static function boxInfo($number, string $small_text, string $href = null)
    {
        $href = null !== $href 
            ? '<a href="' . $href. '" class="small-box-footer">Plus d\'info <i class="fas fa-arrow-circle-right"></i></a>'
            : null
        ;
        return <<<HTML
        <div class="small-box bg-info">
            <div class="inner">
            <h3>{$number}</h3>

            <p>{$small_text}</p>
            </div>
            <div class="icon">
            <i class="ion ion-bag"></i>
            </div>
            {$href}
        </div>
        </div>
HTML;
    }

}