<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/25/18
 * Time: 12:57 PM
 */

namespace Core\Notifications\Channels;


use Core\Exceptions\NotificationException;
use Core\Notifications\TargetListener;

class EmailChannel implements TargetListener
{
    /*
     *  Recipient email
     * */
    protected $email;

    public function  __construct($email)
    {
        if (filter_var($email,FILTER_VALIDATE_EMAIL) === false){
            throw NotificationException::push_notification_email_is_invalid($email);
        }
        $this->email = $email;
    }

    public function get_target()
    {
        return ['email' => $this->email];
    }
}