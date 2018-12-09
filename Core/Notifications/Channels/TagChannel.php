<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/25/18
 * Time: 1:57 PM
 */

namespace Core\Notifications\Channels;

use Core\Notifications\TargetListener;

class TagChannel implements TargetListener
{
    protected $tag_channel;

    public function __construct($tag_channel)
    {
        $this->tag_channel = $tag_channel;
    }

    public function get_target()
    {
        return  ['channel_tag' => $this->tag_channel];
    }
}