<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 6:45 PM
 */

namespace App\Resposities;

use App\StationUsers;
use Core\Repository\BaseRepository;

class StationUserRepository extends BaseRepository
{

    public function get_model()
    {
        return new StationUsers();
    }

    public function create(array $data){
        $station_user = $this->get_model();
        $station_user->fill($data);
        $station_user->save();
        return $station_user;
    }

    public function update(StationUsers $station_user, array $data){
        $station_user->fill($data);
        $station_user->save();
        return $station_user;
    }

    public function get_by_id($user_id, array $options = [])
    {
        return $this->get_requested_user($user_id);
    }
}