<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 3:42 PM
 */

namespace App\Reposities;

use App\Permission;
use Core\Repository\BaseRepository;

class PermissionRepository extends BaseRepository
{

    public function get_model()
    {
        return new Permission();
    }

    public function create(array $data){
        $permission = $this->get_model();
        $permission->fill($data);
        $permission->save();
        return $permission;
    }
}