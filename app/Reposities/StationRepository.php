<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/15/18
 * Time: 10:58 AM
 */

namespace App\Reposities;
use App\Station;
use Core\Repository\BaseRepository;

class StationRepository extends BaseRepository
{

    public function get_model()
    {
        return new Station();
    }

    public function create(array $data){
        $station = $this->get_model();
        $station->fill($data);
        $station->save();
        return $station;
    }

    public function update(Station $station, array $data){
        $station->fill($data);
        $station->save();
        return $station;
    }


}