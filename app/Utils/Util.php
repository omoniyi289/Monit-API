<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/18/18
 * Time: 12:48 AM
 */

namespace App;
use JWTAuth;

class Util
{
    public static function get_token (){
        return JWTAuth::parseToken()->authenticate();
    }
    public static function get_user_details_from_token($value){
        $token_details = self::get_token();
        if (is_array($value)){
            foreach ($value as $val => $item){
                $token_details[$item];
            }
        }
        return $token_details[$value];
    }
}