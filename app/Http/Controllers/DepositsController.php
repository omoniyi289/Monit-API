<?php
namespace App\Http\Controllers;

use App\Requests\ApiDepositsRequest;
use App\Services\DepositsService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;

class DepositsController extends BaseController
{
    private $deposits_service;

    public function __construct(DepositsService $deposits_service)
    {
        $this->deposits_service = $deposits_service;
    }

    public function create(ApiDepositsRequest $request) {

        $deposit_update_request = $request->get('deposits',[]);
        if($deposit_update_request['payment_type'] == 'POS' and (!isset($deposit_update_request['pos_receipt_number']) or !isset($deposit_update_request['pos_receipt_range']))){
            return $this->response(0, 8000, "error, missing parameter(s)", null, 400);
        }else if($deposit_update_request['payment_type'] == 'Cash Deposit' and (!isset($deposit_update_request['bank_name']) or !isset($deposit_update_request['account_number']) or !isset($deposit_update_request['teller_number']))){
            return $this->response(0, 8000, "error, missing parameter(s)", null, 400);
        }
        $data = $this->deposits_service->create($deposit_update_request);
        return $this->response(1, 8000, "deposits successfully created", $data);
    }
      public function update(Request $request)
    {
        $deposit_update_request = $request->get('deposits', []);
        $data = $this->deposits_service->update($deposit_update_request);
        if($data == 'invalid code'){        
            return $this->response(0, 8000, "invalid code supplied", null, 400);
            }else{
            return $this->response(1, 8000, "request successfully updated", $data);     
            }
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->deposits_service->get_all($resource_options);
        return $this->response(1, 8000, "deposits requests", $data);
    }

    public function get_by_id($stock_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->deposits_service->get_by_id($stock_id,$resource_options);
        return $this->response(1, 8000, "request details", $data);
    }
   
    public function get_by_station_id($station_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->deposits_service->get_by_station_id($station_id,$resource_options);
        return $this->response(1, 8000, "requested deposits", $data);
    }


}