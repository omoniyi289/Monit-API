<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/8/18
 * Time: 11:22 PM
 */
namespace Core\Constants;

class CodesConstants
{
    public function error_and_success_codes($success_message = null){
        $code_array = [
            8000 => $success_message,
            8001 => "We're currently undergoing maintenance, be back with you shortly",
            8002 => "Error! Invalid Request",
            8003 => 'Bad request or one of the fields empty',
            8004 => 'Filter group does not have \'filters\' key.',
            8005 => 'Cannot use page option without limit option',
            8006 => 'cannot be null when'. 'resources are transformed using sideload',
            8007 => 'Includes should be an array',
            8008 => 'Sort should be an array',
            8009 => 'username already exist',
            8010 => 'email already exist',
            8011 => 'password must be more than six characters',
            8012 => 'this role already exist',
            8013 => 'price already set, make a change request instead',
        ];
        return $code_array;
    }
}