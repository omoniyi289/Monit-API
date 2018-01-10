<?php
/**
 * Created by PhpStorm.
 * User: funmi ayinde
 * Date: 1/9/18
 * Time: 12:24 PM
 */
namespace Core\ModeResolvers;

interface ModeResolverInterface {
    public function resolve($prop,&$object,&$root,$full_property_path);
}