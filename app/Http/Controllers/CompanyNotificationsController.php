<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 4:23 PM
 */

namespace App\Http\Controllers;

use App\Services\CompanyNotificationsService;
use App\Services\CompanyService;
use App\Services\UserService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Mail\NewCompanyUserMail;
use Illuminate\Http\JsonResponse;
use Mail;
class CompanyNotificationsController extends BaseController
{
    private $company_notification_service;
    private $company_service;
    private $user_service;

    public function __construct(CompanyNotificationsService $company_notification_service,CompanyService $company_service, UserService $user_service)
    {
        $this->company_notification_service = $company_notification_service;
        $this->company_service = $company_service;
        $this->user_service = $user_service;
    }

    public function create(Request $request) {
        $pump_request = $request->get('notification_settings',[]);
        $data = $this->company_notification_service->create($pump_request);
        return $this->response(1, 8000, "pump successfully created", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->company_notification_service->get_all($resource_options);
        return $this->response(1, 8000, "all notifications", $data);
    }

    public function get_by_params(Request $request) {
        $resource_options = $this->parse_resource_options();
        $data = $this->company_notification_service->get_by_params($request->all());
        return $this->response(1, 8000, "requested notifications", $data);
    }
     public function get_by_id($id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->company_notification_service->get_by_id($id,$resource_options);
        return $this->response(1, 8000, "requested notifications", $data);
    }
    public function delete($id) {
            try {
                $resource_options = $this->parse_resource_options();
                $data = $this->company_notification_service->delete($id, $resource_options);
                return $this->response(1, 8000, "notification deleted", $data);
            }catch (Exception $exception){
                return $this->response(0, 8000, $exception->getMessage(), null,500);
            }
        }

}