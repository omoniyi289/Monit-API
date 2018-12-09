<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/25/18
 * Time: 1:52 PM
 */

namespace Core\Notifications\Channels;


use Core\Notifications\TargetListener;

class DeviceChannel implements TargetListener
{

    protected $device_id;

    public function __construct($device_id)
    {
        $this->device_id = $device_id;
    }

    public function get_target()
    {
        return ['device_iden' => $this->device_id];
    }


}