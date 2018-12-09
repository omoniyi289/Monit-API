<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/26/18
 * Time: 3:51 PM
 */

namespace App\Http\Controllers;

use App\Services\ActivationService;
use Core\Controllers\BaseController;

class ActivationController  extends BaseController
{
    private $activation_service;

    public function __construct(ActivationService $activation_service)
    {
        $this->activation_service = $activation_service;
    }

    public function activation_code($count){
        $arr = array();
        for ($i= 0; $i <= $count; $i++){
            $arr[] = mt_rand();
        }
    }
}