<?php
/**
 * Created by PhpStorm.
 * User: funmi ayinde
 * Date: 1/9/18
 * Time: 11:42 AM
 */

/*
 *  A library for creating advanced structures of related entities
 * **/
namespace Core\Lib;
use Core\Constants\StatusConstant;
use Core\Responses\StatusResponse;
use Core\Utils\Utility;
use Illuminate\Support\Collection;

class Libtect
{
    private $mode_resolvers = [];

    public function parse_data($data, array $modes, $key = null){
        $return = [];
        uksort($modes,function ($x,$y){
            // Count the number of substring occurrences
            return substr_count($y,'.')-substr_count($x,'.');
        });
        if (Utility::is_collection($data)){
            $parsed = $this->parse_collection($modes,$data,$return);
        }else{
            $parsed = $this->parse_resource($modes,$data,$return);
        }
        if ($key !== null){
            $return[$key] = $parsed;
        }else{
            if (in_array('sideload',$modes)){
                $response = new StatusResponse();
                return $response->state_output_format(0,8006,null,400,
                    StatusConstant::JSON_MEDIA_TYPE);
            }
            $return = $parsed;
        }
        return $return;
    }
    /*
     * Parse a collection using given modes
     * **/
    private function parse_collection(array $modes,$collection,&$root,$full_prop_path = ''){
        if (is_array($collection)){
            foreach ($collection as $i => $resource){
                $collection[$i] = $this->parse_resource($modes,$resource,$root,$full_prop_path);
            }
        }else if($collection instanceof Collection){
            $collection = $collection->map(function ($resource) use ($modes,&$root,$full_prop_path){
                return $this->parse_resource($modes,$resource,$root,$full_prop_path);
            });
        }
        return $collection;
    }
    /*
     * Parse a single resource using given modes
     * **/
    private function parse_resource(array $modes, &$resource, &$root, $full_prop_path = ''){
        foreach ($modes as $relation => $mode){
            $mode_resolver =  $this->resolve_mode($modes);
            $steps = explode('.',$relation);
            $prop = array_shift($steps);
            if (is_array($resource)){
                if ($resource[$prop] === null){
                    continue;
                }
                $object = &$resource[$prop];
            }else{
                if ($resource->{$prop} === null){
                    continue;
                }
                $object = &$resource->{$prop};
            }
            if (empty($steps)){
                $full_prop_path .= $relation;
                $object = $this->mode_resolvers[$mode]->resolve($relation,$object,$root,$full_prop_path);
            }else{
                $path = implode('.',$steps);
                $modes = [$path=> $mode];
                $full_prop_path .= $prop . '.';
                if (Utility::is_collection($object)){
                    $object = $this->parse_collection($modes,$object,$root,$full_prop_path);
                }else {
                    $object =  $this->parse_resource($modes,$object,$root,$full_prop_path);
                }
            }
            $full_prop_path = '';
            Utility::set_property($resource,$prop,$object);
        }
        return $resource;
    }

    /*
     * Resolve a mode resolver class if it has not been resolved before
     * **/
    private function resolve_mode($mode){
        if (!isset($this->model_resolvers[$mode])){
            $this->mode_resolvers[$mode] = $this->create_mode_resolver($mode);
        }
        return $this->mode_resolvers[$mode];
    }
    /*
     * Instantiate a mode resolver class
     * **/
    private function create_mode_resolver($mode){
        $class = 'Core\ModeResolvers\\';
        switch ($mode){
            default:
            case 'embed':
                $class .= 'EmbedModelResolver';
                break;
            case 'ids':
                $class .= 'IdsModeResolver';
                break;
            case 'sideload':
                $class .= 'SideLoadModeResolver';
                break;
        }
        return new $class;
    }

}