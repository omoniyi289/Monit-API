<?php
namespace App\Http\Controllers;

use App\Requests\ApiPermissonRequest;
use App\Services\NotificationModulesService;
use Core\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Exception;

class NotificationController extends BaseController
{
    private $notification_service;
    public function __construct(NotificationModulesService $notification_service)
    {
        $this->notification_service = $notification_service;
    }


    public function get_by_id($role_id){
        $resource_options = $this->parse_resource_options();
        $data = $this->notification_service->get_id($role_id,$resource_options);
        return $this->response(1, 8000, "notification details", $data);
    }
  

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->notification_service->get_all($resource_options);
        return $this->response(1, 8000, "all notifications", $data);
    }
   
}