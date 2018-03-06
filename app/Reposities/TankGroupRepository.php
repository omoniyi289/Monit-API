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
        //update the tanks
        foreach ($data['selected_tanks'] as  $value) {
             Tanks::where('id', $value)->update(['tank_group_id'=> $tank_group['id']]);
            }
        return $tank_group;
    }


}