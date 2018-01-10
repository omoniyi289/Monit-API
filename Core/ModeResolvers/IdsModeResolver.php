<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Created by PhpStorm.
 * User: funmi ayinde
 * Date: 1/9/18
 * Time: 12:31 PM
 */

namespace Core\ModeResolvers;

use Core\Utils\Utility;

class IdsModeResolver implements ModeResolverInterface {
    /*
     * Map through the collection and convert it to a collection of
     * IDs
     * **/
    public function resolve($prop, &$object, &$root, $full_property_path){
        if (is_array($object)){
            // determine if this is a singular relationship or
            // a collection of models
            $array_copy = $object;
            $first_elem = array_shift($array_copy);
            // the object was not a collection,and was rather
            // a single model, because the first item returned
            // was a property, therefore just return the single ID
            if (Utility::is_primitive($first_elem)){
                return (int) Utility::get_property($object,'id');
            }
            return array_map(function ($entry){
                return (int) Utility::get_property($entry,'id');
            },$object);
        }else if($object instanceof Collection){
            return $object->map(function ($entry){
                return (int) Utility::get_property($entry,'id');
            });
        }else if ($object instanceof Model){
            return $object->id;
        }
    }
}