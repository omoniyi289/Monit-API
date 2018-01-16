<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:04 AM
 */

namespace App\Requests;


use Core\Requests\APIRequest;

class ApiProductsRequest extends APIRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'product' => 'array|required',
            'product.name' => 'required|string',
            'product.code' => 'required|string',
        ];
    }
    public function attributes()
    {
        return [
            'product.name' => 'the name\'s name'
        ];
    }
}