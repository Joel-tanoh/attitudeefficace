<?php

/**
 * Fichier de classe
 * 
 * PHP verison 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @version  "GIT: <Joel-tanoh>"
 * @link     Link
 */

namespace App\BackEnd\APIs;

use App\BackEnd\Data\Personnes\Learner;

/**
 * Gère les envois d'email.
 * 
 * PHP verison 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @version  "Release: <package_version>"
 * @link     Link
 */
class Email
{
    /**
     * La méthode qui envoie les mails aux destinataires.
     * 
     * @param array  $destinataires La liste des distinaires.
     * @param string $subject       Le sujet 
     * @param string $message       Le message à envoyer.
     * @param bool   $join_file     True si l'email doit contenir des pièces jointes.
     * 
     * @return bool
     */
    public function sendMail(
        array $destinataires,
        string $subject,
        string $message,
        bool $join_file = null
    ) {
        if (!empty($destinataires)) {
            $email_counter = 0;
            foreach ($destinataires as $destinataire) {
                mail($destinataire, $subject, $message, $this->_headers($join_file));
                $email_counter++;
            }
            if ($email_counter) return true;
        }
    }

    /**
     * Envoie un email de notification aux utilisateurs.
     * 
     * @return bool
     */
    public function notifyUsers()
    {
        if (null !== $_POST["notify_users"]) {
            if ($_POST["notify_users"] == "all") {
                $this->notifyAllUsers();
            } elseif ($_POST["notify_users"] == "newsletter") {
                $this->notifyNewsletter();
            } elseif ($_POST["notify_users"] == "learners") {
                $this->notifyLearners();
            }
        }
    }

    /**
     * Envoie un email à tous les utilisateurs (newsletter et abonnés à un item).
     * 
     * @return bool
     */
    public function notifyAllUsers()
    {
        if (!empty($this->getAllEmails())) {
            $this->sendMail(
                $this->getAllEmails(),
                $this->_notificationSubject(),
                $this->_notificationMessage()
            );
        }
        return true;
    }

    /**
     * Permet d'envoyer un email à toute la newsletter.
     * 
     * @param string $subject Le sujet du mail.
     * @param string $message Le contenu du mail.
     * 
     * @return bool
     */
    public function notifyNewsletter(string $subject = null, string $message = null)
    {
        $newsletters_mails = Bdd::select("adresse_email", "newsletters");
        if (!empty($newsletters_mails)) {
            $this->sendMail(
                $newsletters_mails,
                $this->_notificationSubject(),
                $this->_notificationMessage()
            );
        }
    }

    /**
     * Envoie un email à tous ceux qui sont abonnés à un item parent.
     * 
     * @return bool
     */
    public function notifyLearners()
    {
        $learners_mails = Bdd::select("adresse_email", Learner::TABLE_NAME);
        if (!empty($learners_mails)) {
            $this->sendMail(
                $learners_mails,
                $this->_notificationSubject(),
                $this->_notificationMessage()
            );
        }
        return true;
    }

    /**
     * Retourne la liste des emails en fonction de leur catégorie.
     * 
     * @param string $category 
     * 
     * @return array
     */
    public function getAllEmails(string $category = null)
    {
        return Bdd::getAllEmails();
    }

    /**
     * Retourne les headers.
     * 
     * @param bool $join_file Pour dire qu'on le mail contiendra des fichiers joints.
     * 
     * @return string
     */
    private function _headers(bool $join_file = null)
    {
        $separator = "\r\n";
        $headers = "MIME-Version: 1.0" . $separator;
        $headers .= "Content-type:text/html;charset=UTF-8" . $separator;
        $headers .= 'From: <joel.developpeur@gmail.com>' . $separator;
        if ($join_file) {

        }
        return $headers;
    }

    /**
     * Retourne le subject pour un email lorsqu'une notification doit être envoyée
     * aux utilisateurs.
     * 
     * @return string
     */
    private function _notificationSubject()
    {
        $subject = "Du nouveau sur votre plateforme " . APP_NAME;
        return $subject;
    }

    /**
     * Retourne le message lorsqu'une notification doit être envoyées aux
     * utilisateurs.
     * 
     * @return string Le code HTML du message.
     */
    private function _notificationMessage()
    {
        return <<<HTML
        <p>Un nouvel element vient d'être créé sur votre plateforme. Cet email
        doit contenir la catégorie du nouvel élément créé, le titre ou le nom, 
        la description et un lien vers cet élément</p>
HTML;
    }

}