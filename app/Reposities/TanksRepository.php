<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 5:24 PM
 */

namespace App\Reposities;

use App\Company;
use App\TankGroups;
use App\Tanks;
use Core\Repository\BaseRepository;

class TanksRepository extends BaseRepository
{

    public function get_model()
    {
        return new Tanks();
    }
    public function create(array $data){
        $tanks = $this->get_model();
        $tanks->fill($data);
        $tanks->save();
        return $tanks;
    }

}