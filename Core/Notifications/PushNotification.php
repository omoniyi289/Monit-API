<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/25/18
 * Time: 2:10 PM
 */

namespace Core\Notifications;

use Core\Exceptions\NotificationException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Exception;
class PushNotification
{
    protected $token;
    protected $http_client;

    public function __construct($token, Client $http_client)
    {
        $this->token= $token;
        $this->http_client = $http_client;
    }

    protected function push_notification_url(){
        return 'https://api.pushbullet.com/v2/pushes';
    }

    protected function get_headers(){
        return [
            'Access-Token' => $this->token,
        ];
    }

    /*
     * This send request to the
     * Pushbullet API
     * */
    public function send($params){
        $url = $this->push_notification_url();
        try{
            return $this->http_client->post($url,[
                'json' => $params,
                'headers' => $this->get_headers(),
            ]);
        }catch (ClientException $exception){
            throw NotificationException::push_notification_error($exception);
        }catch (Exception $exception){
            throw NotificationException::push_notification_could_not_communicate();
        }
    }
}