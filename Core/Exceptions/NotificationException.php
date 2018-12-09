<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/25/18
 * Time: 12:46 PM
 */

namespace Core\Exceptions;

use GuzzleHttp\Exception\ClientException;
use RuntimeException;

class NotificationException extends RuntimeException
{
    public static function push_notification_error(ClientException $exception)
    {
        $code = $exception->getResponse()->getStatusCode();
        $message = $exception->getResponse()->getBody();
        return new static("Notification Error  `{$code}` - `{$message}`");
    }

    public static function push_notification_email_is_invalid($email)
    {
        return new static("Provided email `{$email}` of `notifiable` is not valid");
    }

    public static function push_notification_with_out_receipient()
    {
        return new static("Neither device id nor email pf receipent was not provided");
    }

    public static function push_notification_could_not_communicate()
    {
        return new static("Couldn't connect to Push notification API");
    }

}