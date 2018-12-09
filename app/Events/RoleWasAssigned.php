<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/25/18
 * Time: 4:08 PM
 */

namespace App\Events;

use App\Role;
use Core\Events\Event;

class RoleWasAssigned extends Event
{
    public $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }
}