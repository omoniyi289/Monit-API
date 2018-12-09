<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/25/18
 * Time: 2:33 PM
 */

namespace Core\Notifications;


use Core\Notifications\Channels\DeviceChannel;
use Core\Notifications\Channels\EmailChannel;
use Illuminate\Notifications\Notification;

class PushNotificationMessage
{

    protected $push_notification;

    public function __construct(PushNotification $push_notification)
    {
        $this->push_notification = $push_notification;
    }

    protected function send($notifiable, Notification $notification){
        if (!$target = $this->get_target($notifiable)){
            return;
        }
        $message = $notification->toPushNotification($notifiable)->target($target);
        $this->push_notification->send($message->toArray());

    }

    protected function get_target($notifiable){
        if (!$target = $notifiable->routeNotificationError('push_notification')){
            return;
        }
        if ($target instanceof TargetListener){
            return $target;
        }
        $target = (string) $target;
        if (filter_var($target,FILTER_VALIDATE_EMAIL) !== false){
            return new EmailChannel($target);
        }
        return new DeviceChannel($target);
    }
}