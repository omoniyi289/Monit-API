<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 5:23 PM
 */

namespace App\Http\Controllers;
use App\Requests\ApiCompanyRequest;
use App\Requests\ApiTankGroupRequest;
use App\Requests\ApiTankRequest;
use App\Services\CompanyService;
use App\Services\TankGroupService;
use App\Services\TankService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;


class TanksController extends BaseController
{
    private $tank_service;

    public function __construct(TankService $tank_service)
    {
        $this->tank_service = $tank_service;
    }

    public function create(ApiTankRequest $request){
        $tank_request = $request->get('tank',[]);
        $exist_name = $this->tank_service->get_tank_by_code($tank_request['code'], $tank_request['station_id']);
        if (count($exist_name) == 1){
               return $this->response(0, 8000, "error! tank with this same code already exist", $exist_name, 400);
            }
        $data = $this->tank_service->create($tank_request);
        return $this->response(1, 8000, "tank successfully created", $data);
    }
     public function update($tank_id, Request $request)
    {
        $tank_update_request = $request->get('tank', []);
        $data = $this->tank_service->update($tank_id, $tank_update_request);
        return $this->response(1, 8000, "tank successfully updated", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->tank_service->get_all($resource_options);
        return $this->response(1, 8000, "all tanks", $data);
    }
    public function get_by_id($tank_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->tank_service->get_by_id($tank_id,$resource_options);
        return $this->response(1, 8000, "tank details", $data);
    }
    public function get_tanks_by_station_id($station_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->tank_service->get_by_station_id($station_id,$resource_options);
        return $this->response(1, 8000, "requested tanks", $data);
    }
    public function delete($tank_id) {
            try {
                $resource_options = $this->parse_resource_options();
                $data = $this->tank_service->delete($tank_id, $resource_options);
                return $this->response(1, 8000, "tank deleted", $data);
            }catch (Exception $exception){
                return $this->response(0, 8000, $exception->getMessage(), null,500);
            }
        }

}