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
use Core\Repository\BaseRepository;

class TankGroupRepository extends BaseRepository
{

    public function get_model()
    {
        return new TankGroups();
    }
    public function create(array $data){
        $tank_group = $this->get_model();
        $tank_group->fill($data);
        $tank_group->save();
        return $tank_group;
    }


}