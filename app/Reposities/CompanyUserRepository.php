<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 6:45 PM
 */

namespace App\Reposities;

use App\CpyUsers;
use App\CompanyUsers;
use Core\Repository\BaseRepository;

class CompanyUserRepository extends BaseRepository
{

    public function get_model()
    {
        return new CompanyUsers();
    }

    public function create(array $data){
        $company_user = $this->get_model();
        $company_user->fill($data);
        $company_user->save();
        return $company_user;
    }

    public function update(CompanyUsers $company_user, array $data){
        $company_user->fill($data);
        $company_user->save();
        return $company_user;
    }

    public function get_by_id($user_id, array $options = [])
    {
        return $this->get_requested_user($user_id);
    }
}