<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 5:23 PM
 */

namespace App\Http\Controllers;
use App\Requests\ApiCompanyRequest;
use App\Services\CompanyService;
use App\Services\EquipmentMaintenanceService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;

class EquipmentMaintenanceController extends BaseController
{
    private $equipment_maintenance_service;

    public function __construct(EquipmentMaintenanceService $equipment_maintenance_service)
    {
        $this->equipment_maintenance_service = $equipment_maintenance_service;
    }


         public function create_pump_maintenance_log(Request $request)
    {
       $totalizer_request = $request->get('pumps',[]);
        $data = $this->equipment_maintenance_service->create_pump_maintenance_log($totalizer_request);
        if($data == 'invalid_input'){        
            return $this->response(0, 8000, "no  reading supplied", null, 400);
            }else{
        return $this->response(1, 8000, "reading successfully created", $data);
        }
    }

     public function get_pump_maintenance_log(Request $request) {
        //return $request;
        $_request = $request->all();
        $resource_options = $this->parse_resource_options();
        $data = $this->equipment_maintenance_service->get_pump_maintenance_log($_request);
        return $this->response(1, 8000, "requested", $data);
    }

    public function get_pump_readings(Request $request)
    {
        $equipment_maintenance_update_request = $request->all();
        $data = $this->equipment_maintenance_service->get_pump_readings($equipment_maintenance_update_request);
        return $this->response(1, 8000, "requested", $data);
    }

}