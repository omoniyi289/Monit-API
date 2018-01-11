<?php
/**
 * Created by PhpStorm.
 * User: e360
 * Date: 1/10/18
 * Time: 11:15 AM
 */

namespace App\Resposities;

use App\User;
use Core\Repository\BaseRepository;


class UserRepository extends BaseRepository
{
    function getModel()
    {
        return new User();
    }

    public function create(array $data){
        $user = $this->getModel();
        $user->fill($data);
        $user->save();
        return $user;
    }

    public function update(User $user, array $data){
        $user->fill($data);
        $user->save();
        return $user;
    }
}