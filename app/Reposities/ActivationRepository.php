<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 5:24 PM
 */

namespace App\Reposities;

use App\Activations;
use App\Company;
use Core\Repository\BaseRepository;

class ActivationRepository extends BaseRepository
{

    public function get_model()
    {
        return new Activations();
    }
    public function create(array $data){
        $activations = $this->get_model();
        $activations->fill($data);
        $activations->save();
        return $activations;
    }


}