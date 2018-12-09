<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/10/18
 * Time: 11:46 AM
 */

namespace App\Requests;

use Core\Requests\APIRequest;

class ApiRegionRequest extends APIRequest
{
    public function authorize(){
        return true;
    }
    public function rules(){
        return [
            'region' => 'array|required',
            'region.name' => 'required|string'
           
        ];
    }

    public function attributes()
    {
        return [
            'region.name' => 'the region\'s name'
        ];
    }
}