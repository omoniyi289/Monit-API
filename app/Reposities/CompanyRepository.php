<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 5:24 PM
 */

namespace App\Reposities;

use App\Company;
use Core\Repository\BaseRepository;

class CompanyRepository extends BaseRepository
{

    public function get_model()
    {
        return new Company();
    }
    public function create(array $data){
        $company = $this->get_model();
        $company->fill($data);
        $company->save();
        return $company;
    }


}