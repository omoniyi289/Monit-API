<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:04 AM
 */

namespace App\Requests;


use Core\Requests\APIRequest;

class ApiItemRequest extends APIRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'item' => 'array|required',
            'item.name' => 'required|string',
            'item.category' => 'required|string',
            //'company.country' => 'required|string',
            'item.brand' => 'required|string',
            'item.uom' => 'required|string',
            'item.parentsku' => 'required|string',
        ];
    }
    public function attributes()
    {
        return [
            'item.name' => 'the item\'s name'
        ];
    }
}