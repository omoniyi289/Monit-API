<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 5:24 PM
 */

namespace App\Reposities;

use App\PumpGroups;
use App\Pumps;
use Core\Repository\BaseRepository;

class PumpGroupsRepository extends BaseRepository
{

    public function get_model()
    {
        return new PumpGroups();
    }
    public function create(array $data){
        $pump_groups = $this->get_model();
        $pump_groups->fill($data);
        $pump_groups->save();
        //update the pumps
        foreach ($data['selected_pumps'] as  $value) {
             Pumps::where('id', $value['id'])->update(['pump_group_id'=> $pump_groups['id']]);
            }
        return $pump_groups;
    }


}