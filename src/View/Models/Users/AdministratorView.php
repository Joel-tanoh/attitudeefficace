<?php

namespace App\View\Models\Users;

use App\View\View;

/**
 * Gère tous les affichages relative à l'administrateur.
 */
class AdministratorView extends View
{
    protected $admin;

    function __construct(\App\BackEnd\Models\Users\Administrator $admin = null)
    {
        $this->admin = $admin;
    }

        
    /**
     * Liste tous les comptes administrateurs créées sur le site.
     * 
     * @param array $admins 
     * 
     * @return string
     */
    public function list($admins)
    {
        $list = null;

        foreach ($admins as $admin) {
            $list .= $this->listRow($admin);
        }

        return <<<HTML
        <div class="row">
            <div class="col-12">
                <table class="table border bg-white">
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
        </div>
HTML;
    }

    /**
     * Unle ligne du tableau qui liste les comptes administrateurs.
     * 
     * @return string
     */
    private function listRow()
    {
        return <<<HTML
        <tr>
            <td>{$this->admin->getLogin()}</td>
            <td>{$this->admin->getRole()}</td>
            <td>{$this->admin->getEmailAddress()}</td>
        </tr>
HTML;
    }

}