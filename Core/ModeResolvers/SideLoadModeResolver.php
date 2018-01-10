<?php
namespace Core\ModeResolvers;

use Core\Utils\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Created by PhpStorm.
 * User: funmi
 * Date: 1/9/18
 * Time: 12:46 PM
 */

class SideLoadModeResolver implements ModeResolverInterface {
    private $id_resolver;

    public function __construct()
    {
        $this->id_resolver = new IdsModeResolver();
    }

    /*
     * Move all relational resources to the root element and
     * use id resolver to replace then with collections of
     * identifiers
     * **/
    public function resolve($prop, &$object, &$root, $full_property_path)
    {
       $this->add_collection_to_root($root,$object,$full_property_path);
       return $this->id_resolver->resolve($prop,$object,$root,$full_property_path);
    }

    /*
     * Add the collection to the root array
     * **/
    private function add_collection_to_root(&$root,&$object,$full_property_path){
        // let first determine if the object is a resource or a collection
        $is_it_resource = false;
        if (is_array($object)){
            $copy = $object;
            $values  =  array_values($copy);
            $first_prop_or_resource = array_shift($values);
            if (Utility::is_primitive($first_prop_or_resource)){
                $is_it_resource = true;
            }
        }elseif ($object instanceof Model){
           $is_it_resource = true;
        }
        $new_collection = $is_it_resource ? [$object] : $object;
        // does the existing collections use arrays or collections
        $copy = $root;
        $values = array_values($copy);
        $existing_root_collection = array_shift($values);
        $new_collection = $existing_root_collection instanceof Collection ?
            new Collection($new_collection) : $new_collection;
        if (!array_key_exists($full_property_path,$root)){
            $root[$full_property_path] = $new_collection;
        }else{
            $this->merge_root_collection($root[$full_property_path],$new_collection);
        }
    }
    /*
     * If a collection for this resource has already begun(i.e multiple
     * resources share this type of resource), then merge with the existing
     * collection
     * **/
    private function merge_root_collection(&$collection,$object){
        if (is_array($object)){
            foreach ($object as $resource){
                $this->add_resource_to_collection_if_non_exist($collection,$resource);
            }
        }elseif ($object instanceof Collection){
            $object->each(function ($resource) use (&$collection){
                $this->add_resource_to_collection_if_non_exist($collection,$resource);
            });
        }
    }

    /*
     * this to check if the resource already exists in the root collection by id
     * **/
    private function add_resource_to_collection_if_non_exist(&$collection,$resource){
        $identifier = Utility::get_property($resource,'id');
        $exists = false;
        $copy = $collection instanceof Collection ? $collection->toArray() : $collection;
        foreach ($copy as $root_resource){
            if ((int) Utility::get_property($root_resource,'id') === (int) $identifier){
                $exists = true;
                break;
            }
        }
        if ($exists === false){
            if (is_array($collection)){
                $collection[] = $resource;
            }else if ($collection instanceof Collection){
                $collection->push($resource);
            }
        }
    }
}