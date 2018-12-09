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
use Illuminate\Support\Facades\DB;


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

    public function update(Company $pump, array $data){
        $pump->fill($data);
        $pump->save();
        return $pump;
    }

    // public function get_stations_all(){
    //     DB::select(
    //         "select id,name from companies "
    //     );
    // }

}