<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 5:24 PM
 */

namespace App\Reposities;

use App\PumpGroupToTankGroup;
use Core\Repository\BaseRepository;

class PumpGroupToTankGroupRepository extends BaseRepository
{

    public function get_model()
    {
        return new PumpGroupToTankGroup();
    }
    public function create(array $data){
        $pump_groups = $this->get_model();
        $pump_groups->fill($data);
        $pump_groups->save();
        return $pump_groups;
    }

     public function update(PumpGroupToTankGroup $tank, array $data){
        $tank->fill($data);
        $tank->save();
        return $tank;
    }
}