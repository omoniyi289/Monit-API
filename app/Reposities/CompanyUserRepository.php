<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 6:45 PM
 */

namespace App\Reposities;

use App\User;
use Core\Repository\BaseRepository;

class CompanyUserRepository extends BaseRepository
{

    public function get_model()
    {
        return new User();
    }

    public function create(array $data){
        $company_user = $this->get_model();
        $company_user->fill($data);
        $company_user->save();
        return $company_user;
    }

    public function update(User $company_user, array $data){
        $company_user->fill($data);
        $company_user->save();
        return $company_user;
    }
}