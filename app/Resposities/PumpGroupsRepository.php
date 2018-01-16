<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 5:24 PM
 */

namespace App\Resposities;

use App\PumpGroups;
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
        return $pump_groups;
    }


}