<?php

/**
 * Fichier de classe.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @link     link
 */

namespace App\FrontEnd\Layout;

use App\BackEnd\Data\Personnes\Administrateur;
use App\FrontEnd\Layout\Html\Form;

/**
 * Gère toutes les vues concernant les comptes administrateurs.
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @link     link
 */
class AdministrateurLayout
{
    /**
     * Liste tous les comptes administrateurs créées sur le site.
     * 
     * @param $accounts 
     * 
     * @return string
     */
    public function listAccounts($accounts)
    {
        $accounts_list = null;
        if (!empty($accounts)) {
            foreach ($accounts as $acc) {
                $account = new Administrateur($acc['code']);
                $accounts_list .= $this->_listRow($account);
            }
        }
        return <<<HTML
        <div>
            <table class="table table-responsive-md">
                <thead class="thead-light">
                    <th>Login</th>
                    <th>Statut</th>
                    <th>Adresse email</th>
                </thead>
                <tbody>
                    {$accounts_list}
                </tbody>
            </table>
        </div>
HTML;
    }

    /**
     * Unle ligne du tableau qui liste les comptes administrateurs.
     * 
     * @param $account Un Objet compte.
     * 
     * @return string
     */
    private function _listRow($account)
    {
        return <<<HTML
        <tr>
            <td>{$account->get("login")}</td>
            <td>{$account->get("statut")}</td>
            <td>{$account->get("email")}</td>
        </tr>
HTML;
    }

    /**
     * Retourne l'image miniature de l'utilisateur connecté.
     * 
     * @param $user 
     * 
     * @return string
     */
    public function userImage($user)
    {
        return <<<HTML
        <img src="{$user->get('avatar_src')}" class="user-image img-circle shdw mr-2"
            alt="User Image">
HTML;
    }

}