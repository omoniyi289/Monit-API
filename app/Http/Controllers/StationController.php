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
use App\Services\CompanyService;
use App\Services\StationService;
use App\Services\UserService;
use App\Util;
use Core\Controllers\BaseController;

class StationController extends BaseController implements GenericInterface
{
    private $station_service;
    private $company_service;
    private $user_service;

    public function __construct(StationService $station_service,
                                CompanyService $company_service,UserService $user_service)
    {
        $this->station_service = $station_service;
        $this->company_service = $company_service;
        $this->user_service = $user_service;
    }

    public function create(ApiStationRequest $request){
        $station_req = $request->get('station',[]);
        $user_id = $this->get_user();
        $company_details = $this->company_service->get_company_by_user_id($user_id)->first();
        $station_req['company_id'] = $company_details['id'];
        $station_req['station_user_id'] = $user_id;
        $data = $this->station_service->create($station_req);
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

    public function get_company_by_station(){
        $company = $this->get_company();
        $company_stations = $this->station_service->get_station_by_company($company);
        return $this->response(1, 8000, "station with company details", $company_stations);
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
}