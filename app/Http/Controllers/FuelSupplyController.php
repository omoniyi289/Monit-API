<?php
namespace App\Http\Controllers;

use App\Requests\ApiCompanyRequest;
use App\Requests\ApiFuelSupplyRequest;
use App\Services\CompanyService;
use App\Services\FuelSupplyService;
use App\Services\StockReceivedService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;

class FuelSupplyController extends BaseController
{
    private $fuel_supply_service;
    private $stock_received_service;
    public function __construct(FuelSupplyService $fuel_supply_service, StockReceivedService $stock_received_service)
    {
        $this->fuel_supply_service = $fuel_supply_service;
        $this->stock_received_service = $stock_received_service;
    }

    public function create(ApiFuelSupplyRequest $request) {

        $totalizer_request = $request->get('fuel_request',[]);
        $data = $this->fuel_supply_service->create($totalizer_request);
        return $this->response(1, 8000, "request successfully created", $data);
    }
      public function update(Request $request)
    {
        $stock_update_request = $request->get('fuel_request', []);
        if($stock_update_request['status'] == 'Goods in Transit' and ($stock_update_request['driver_name'] == '' or $stock_update_request['quantity_loaded'] == ''or $stock_update_request['truck_reg_number'] == '')){
            return $this->response(0, 8000, "error!, please fill in all necessary fields", null, 400);
        }

        else if(isset($stock_update_request['compartments']) and isset($stock_update_request['first_seal_numbers']) and count($stock_update_request['first_seal_numbers']) > 0){
            if(($stock_update_request['compartments'] != count($stock_update_request['first_seal_numbers'])) or ($stock_update_request['compartments'] != count($stock_update_request['first_seal_quantities'])) ){
           return $this->response(0, 8000, "error!, please fill in all seals numbers and quantities, input 0 where not applicable", null, 400); 
                }

               foreach ($stock_update_request['first_seal_numbers'] as $value) {
                    if($value == null){
                        return $this->response(0, 8000, "error!, please fill in all seals numbers, input 0 where not applicable", null, 400); 
                        }           
                    }
                foreach ($stock_update_request['first_seal_quantities'] as $value) {
                    if($value == null){
                        return $this->response(0, 8000, "error!, please fill in all seals quantities, input 0 where not applicable", null, 400); 
                        }           
                    }

        }
         // return $stock_update_request['first_seal_numbers'];   

        $data = $this->fuel_supply_service->update($stock_update_request);

        if($data == 'invalid code'){        
            return $this->response(0, 8000, "invalid code supplied for this station", null, 400);
            }else{
            return $this->response(1, 8000, "request successfully updated", $data);     
            }
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->fuel_supply_service->get_all($resource_options);
        return $this->response(1, 8000, "fuel requests", $data);
    }

    public function get_by_id($stock_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->fuel_supply_service->get_by_id($stock_id,$resource_options);
        return $this->response(1, 8000, "request details", $data);
    }
    public function get_by_request_code(Request $request) {
        $stock_update_request = $request->all();
        $resource_options = $this->parse_resource_options();
        $exist_code_at_fuel_supplied=$this->fuel_supply_service->verify_request_credentials($stock_update_request);
        $exist_code_at_stock_received=$this->stock_received_service->verify_request_credentials($stock_update_request);
        //return $exist_code;
       // $exist_code = $this->station_service->get_station_by_name($station_req['name']);
        if (count($exist_code_at_fuel_supplied) == 0){
               return $this->response(0, 8000, "error! invalid request code supplied for this station", $exist_code_at_fuel_supplied, 400);
            }
        if (count($exist_code_at_stock_received) == 1 and $exist_code_at_stock_received[0]['quantity_supplied'] !=null){
               return $this->response(0, 8000, "stock already received at the station", $exist_code_at_stock_received, 400);
            }
        $data = $this->fuel_supply_service->get_request_details($stock_update_request['code'],$resource_options);
        return $this->response(1, 8000, "request details", $data);
    }
   
    public function get_by_params(Request $request) {
        $stock_update_request = $request->all();
        $resource_options = $this->parse_resource_options();
        $data = $this->fuel_supply_service->get_by_params($stock_update_request);
        return $this->response(1, 8000, "requested fuel supplies", $data);
    }


}