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

use App\View\View;

/**
 * Gère toutes les vues concernant les comptes administrateurs.
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @link     link
 */
class AdministrateurView extends View
{
    private $adminUser;

    /**
     * Constructeur
     * 
     * @param \App\BackEnd\Models\Users\Administrateur
     * 
     * @return void
     */
    public function __construct($adminUser = null)
    {  
       $this->adminUser = $adminUser;
    }

    /**
     * Liste tous les comptes administrateurs créées sur le site.
     * 
     * @param array $admins 
     * 
     * @return string
     */
    public function listAdmins($admins)
    {
        $list = null;

        foreach ($admins as $admin) {
            $list .= $this->listRow($admin);
        }

        return <<<HTML
        <div>
            <table class="table table-responsive-md border">
                <thead class="thead-light">
                    <th>Login</th>
                    <th>Role</th>
                    <th>Adresse email</th>
                </thead>
                <tbody>
                    {$list}
                </tbody>
            </table>
        </div>
HTML;
    }

    /**
     * Unle ligne du tableau qui liste les comptes administrateurs.
     * 
     * @param \App\BackEnd\Models\Users\Administrateur $admin
     * 
     * @return string
     */
    private function listRow(\App\BackEnd\Models\Users\Administrateur $admin)
    {
        return <<<HTML
        <tr>
            <td>{$admin->getLogin()}</td>
            <td>{$admin->getRole()}</td>
            <td>{$admin->getEmailAddress()}</td>
        </tr>
HTML;
    }

}