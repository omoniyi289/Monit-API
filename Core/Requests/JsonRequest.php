<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/16/18
 * Time: 2:32 PM
 */

namespace Core\Requests;


use Illuminate\Foundation\Http\FormRequest;

class JsonRequest extends FormRequest
{
    protected function get_validator_instance(){
        $factory = $this->container->make('Illuminate\Validation\Factory');
        if (method_exists($this,'validator')){
            return $this->container->call([$this,'validator'],compact('factory'));
        }
        return $factory->make($this->json()->all(),$this->container->call([$this,'rules']),
            $this->messages(),$this->attributes());
    }
}