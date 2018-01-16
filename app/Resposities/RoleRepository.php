<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/11/18
 * Time: 9:35 AM
 */

namespace App\Resposities;
use App\Role;
use Core\Repository\BaseRepository;

class RoleRepository extends BaseRepository
{
    public function get_model()
    {
        return new Role();
    }

    public function create(array $data){
        $role = $this->get_model();
        $role->fill($data);
        $role->save();
        return $role;
    }

    public function update(Role $role, array $data){
        $role->fill($data);
        $role->save();
        return $role;
    }
}