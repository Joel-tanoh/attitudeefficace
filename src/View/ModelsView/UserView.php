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

namespace App\View\ModelsView;

use App\BackEnd\Models\Users\Administrateur;

/**
 * Gère toutes les vues concernant les comptes administrateurs.
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @link     link
 */
class UserView extends \App\View\View
{
    private $adminUser;

    public function __construct($adminUser = null)
    {  
       $this->adminUser = $adminUser;
    }

    /**
     * Liste tous les comptes administrateurs créées sur le site.
     * 
     * @param $users 
     * 
     * @return string
     */
    public function listAdmins($users)
    {
        $accounts_list = null;

        if (!empty($users)) {
            foreach ($users as $user) {
                $user = new Administrateur($user['code']);
                $accounts_list .= $this->listRow($user);
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
     * @param $user
     * 
     * @return string
     */
    private function listRow($user)
    {
        return <<<HTML
        <tr>
            <td>{$user->getLogin()}</td>
            <td>{$user->getRole()}</td>
            <td>{$user->getEmailAddress()}</td>
        </tr>
HTML;
    }

}