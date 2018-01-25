<?php
/**
 * Created by PhpStorm.
 * User: e360
 * Date: 1/25/18
 * Time: 4:24 PM
 */

namespace App\Providers;

use App\Notifications\RolesAssigned;
use Core\Events\EventServiceProvider;

class UserServiceProvider extends EventServiceProvider
{
    protected $listen = [
        RolesAssigned::class => [

        ]
    ];
}