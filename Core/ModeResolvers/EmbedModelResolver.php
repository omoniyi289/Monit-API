<?php
/**
 * Created by PhpStorm.
 * User: funmi ayinde
 * Date: 1/9/18
 * Time: 12:21 PM
 */
namespace Core\ModeResolvers;

class EmbedModelResolver implements ModeResolverInterface
{
    public function resolve($prop, &$object, &$root, $full_property_path)
    {
        return $object;
    }
}