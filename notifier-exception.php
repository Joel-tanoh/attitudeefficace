<?php

/**
 * Fichier de notification.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  License.com license
 * @link     Link
 */

use App\BackEnd\Utils\Notification;

if (!empty($exception)) {
    $notification = new Notification();
    echo $notification->exception($exception);
}