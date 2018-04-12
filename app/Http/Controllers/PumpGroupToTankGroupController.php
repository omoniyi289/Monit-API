<?php

namespace App\Http\Controllers;

use App\Requests\ApiPumpGroupToTankGroupRequest;
use App\Services\PumpGroupToTankGroupService;
use Core\Controllers\BaseController;
use App\Tanks;
use App\Pumps;
use App\PumpGroups;
use App\TankGroups;
use Illuminate\Http\Request;
use App\PumpGroupToTankGroup;

class PumpGroupToTankGroupController extends BaseController
{
    private $pump_group_to_tank_group_service;

    public function __construct(PumpGroupToTankGroupService $pump_group_to_tank_group_service)
    {
        $this->pump_group_to_tank_group_service = $pump_group_to_tank_group_service;
    }

    public function create(ApiPumpGroupToTankGroupRequest $request) {
        //return $request;
        $company_request = $request->get('p_t_group',[]);
        $data = $this->pump_group_to_tank_group_service->create($company_request);
        return $this->response(1, 8000, "Pump-tank group successfully created", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->pump_group_to_tank_group_service->get_all($resource_options);
        return $this->response(1, 8000, "all pump-tank groups", $data);
    }
    public function get_by_id($pump_group_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->pump_group_to_tank_group_service->get_by_id($pump_group_id,$resource_options);
        return $this->response(1, 8000, "pump-tank group details", $data);
    }

    public function get_by_station_id($station_id) {
        $pumps =  Pumps::where('station_id',$station_id)->with('product')->get();
        $tanks =  Tanks::where('station_id',$station_id)->with('product')->get();
        $pump_groups =  PumpGroups::where('station_id',$station_id)->with('pumps')->get();
        $tank_groups =  TankGroups::where('station_id',$station_id)->with('tanks')->get();
        $pump_group_to_tank_group =  PumpGroupToTankGroup::where('station_id',$station_id)->with('get_tank_group')->with('get_pump_group')->get();
        $data= array(['pump-tank' => 1, 'pumps' => $pumps, 'tanks' => $tanks,'tank_groups' => $tank_groups, 'pump_groups' => $pump_groups,'pump_groups_to_tank_groups' => $pump_group_to_tank_group]);

        return $this->response(1, 8000, "pump-tank group details", $data);
    }
     public function delete($id) {
            try {
                $resource_options = $this->parse_resource_options();
                $data = $this->pump_group_to_tank_group_service->delete($id, $resource_options);
                return $this->response(1, 8000, "map deleted", $data);
            }catch (Exception $exception){
                return $this->response(0, 8000, $exception->getMessage(), null,500);
            }
        }

}