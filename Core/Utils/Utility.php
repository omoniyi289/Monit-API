<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/9/18
 * Time: 11:46 AM
 */
namespace Core\Utils;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
class Utility
{
    /*
     * Get the property of an array or object
     * */
    public static function get_property($obj_or_array,$prop){
        if (is_array($obj_or_array)){
            return $obj_or_array[$prop];
        }else{
            return $obj_or_array->{$prop};
        }
    }
    /*
     *  Set a property of an Eloquent model, normal obj or array
     * */
    public static function set_property(&$object_or_array,$prop,$val){
        if ($object_or_array instanceof Model){
            // Check if relation exists, if so
            if ($prop){
                if ($object_or_array->relationLoaded($prop) && !Utility::is_primitive($val)){
                    $object_or_array->setRelation($prop,$val);
                }else{
                    unset($object_or_array[$prop]);
                    $object_or_array->setAttribute($prop,$val);
                }
            }
        }else if (is_array($object_or_array)){
            $object_or_array[$prop] = $val;
        }else{
            $object_or_array->{$prop} = $val;
        }
    }
    /*
     * Is the variable a primitive type
     * **/
    public static function is_primitive($val){
        return !is_array($val) && !($val instanceof Model) && !($val instanceof Collection);
    }

    public static function is_collection($val){
        return is_array($val) || $val instanceof Collection;
    }
}