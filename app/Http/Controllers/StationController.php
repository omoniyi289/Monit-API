<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/15/18
 * Time: 8:58 AM
 */

namespace App\Http\Controllers;
use App\interfaces\GenericInterface;
use App\Requests\ApiStationRequest;
use App\Services\ActivationService;
use App\Services\CompanyService;
use App\Services\StationService;
use App\Services\UserService;
use App\Util;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;

class StationController extends BaseController implements GenericInterface
{
    private $station_service;
    private $company_service;
    private $user_service;
    private $activation_service;

    public function __construct(StationService $station_service,
                                CompanyService $company_service,
                                UserService $user_service, ActivationService $activation_service)
    {
        $this->station_service = $station_service;
        $this->company_service = $company_service;
        $this->user_service = $user_service;
        $this->activation_service = $activation_service;
    }

    public function create(ApiStationRequest $request){
        $station_req = $request->get('station',[]);
        $user_id = $this->get_user();
        $company_details = $this->company_service->get_company_by_user_id($user_id)->first();
        $exist_name = $this->station_service->get_station_by_name($station_req['name']);
        if (count($exist_name) == 1){
               return $this->response(0, 8000, "error! station with this same name already exist", $exist_name, 400);
            }

        //$station_req['company_id'] = $company_details['id'];
        $station_req['station_user_id'] = $user_id;
        $data = $this->station_service->create($station_req);
        $activation_code = $this->activation_code(6);
         $this->activation_service->create([
            "activation_code" => $activation_code,
            "license_type" => $station_req["license_type"],
            "activation_date" => date('Y-m-d'),
            "station_id" => $data["id"],
            'is_activated' => false,
        ]);
        return $this->response(1, 8000, "station successfully created", $data);
    }

    public function get_by_id($station_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->station_service->get_by_id($station_id,$resource_options);
        return $this->response(1, 8000, "station details", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->station_service->get_all($resource_options);
        return $this->response(1, 8000, "all stations", $data);
    }

    public function get_station_by_company(){
        $company = $this->get_company();
        $company_stations = $this->station_service->get_station_by_company($company);
        return $this->response(1, 8000, "station with company details", $company_stations);
    }
    //niyi
    public function get_stations_by_company_id($company_id){
        $company_stations = $this->station_service->get_station_by_company_id($company_id);
        return $this->response(1, 8000, "registered stations", $company_stations);
    }
    public function get_station_by_state($state){
        $company_stations = $this->station_service->get_station_by_state($state);
        return $this->response(1, 8000, "registered stations", $company_stations);
    }

     public function get_stations_by_user_id($company_id){
        $company_stations = $this->station_service->get_stations_by_user_id($company_id);
        return $this->response(1, 8000, "registered stations", $company_stations);
    }

    public function get_user()
    {
        return  Util::get_user_details_from_token('id');
    }

    public function get_company()
    {
        $user_id = Util::get_user_details_from_token('id');
        return $this->company_service->get_company_by_user_id($user_id)->first();
    }

    public function update($station_id, Request $request)
    {
        $station_update_request = $request->get('station', []);
        $data = $this->station_service->update($station_id, $station_update_request);
        return $this->response(1, 8000, "station successfully updated", $data);
    }
     public function delete($station_id) {
            try {
                $resource_options = $this->parse_resource_options();
                $data = $this->station_service->delete($station_id, $resource_options);
                return $this->response(1, 8000, "station deleted", $data);
            }catch (Exception $exception){
                return $this->response(0, 8000, $exception->getMessage(), null,500);
            }
        }

    public function activation_code($count){
        $arr = array();
        for ($i= 0; $i <= $count; $i++){
            $arr[] = mt_rand();
        }
    }
}