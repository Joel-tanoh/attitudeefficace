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
            <div>
                <i class="far fa-clock mr-2"></i><span>Ajoutée le {$date_created} </span>
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

}