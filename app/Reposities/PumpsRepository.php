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

class PumpsRepository extends BaseRepository
{

    public function get_model()
    {
        return new Pumps();
    }
    public function create(array $data){
        $pumps = $this->get_model();
        $pumps->fill($data);
        $pumps->save();
        return $pumps;
    }


}