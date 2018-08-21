<?php
namespace App\Http\Controllers;

use App\Requests\ApiCompanyRequest;
use App\Requests\ApiStockReceivedRequest;
use App\Services\CompanyService;
use App\Services\StockReceivedService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use PDF;

class StockReceivedController extends BaseController
{
    private $stock_received_service;

    public function __construct(StockReceivedService $stock_received_service)
    {
        $this->stock_received_service = $stock_received_service;
    }

    public function create(ApiStockReceivedRequest $request) {

        $totalizer_request = $request->get('stock_received',[]);
        $data = $this->stock_received_service->create($totalizer_request);
        return $this->response(1, 8000, "request successfully created", $data);
    }
      public function update(Request $request)
    {
        $totalizer_update_request = $request->get('stock_received', []);
        $data = $this->stock_received_service->update($totalizer_update_request);
        if($data == 'invalid code'){        
            return $this->response(0, 8000, "invalid code supplied", null, 400);
            }else{
            return $this->response(1, 8000, "request successfully updated", $data);     
            }
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->stock_received_service->get_all($resource_options);
        return $this->response(1, 8000, "fuel requests", $data);
    }

    public function get_delivery_pdf(Request $request){
        $stock_update_request = $request->all();
        $resource_options = $this->parse_resource_options();
        $data = $this->stock_received_service->get_delivery_pdf($stock_update_request);
        $pdf = PDF::setPaper('a4', 'portrait');
              $pdf = $pdf->loadView('delivery-note', compact('data'))->setPaper('a4', 'portrait');
           return   $final_pdf = $pdf->download('good.pdf');
     //$headers = array('Content-Type: application/pdf');
    }

    public function get_waybill_pdf(Request $request){
        $stock_update_request = $request->all();
        $resource_options = $this->parse_resource_options();
        $data = $this->stock_received_service->get_waybill_pdf($stock_update_request);
        $pdf = PDF::setPaper('a4', 'portrait');
              $pdf = $pdf->loadView('waybill-note', compact('data'))->setPaper('a4', 'portrait');
           return   $final_pdf = $pdf->download('better.pdf');
    }

    public function get_by_id($stock_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->stock_received_service->get_by_id($stock_id,$resource_options);
        return $this->response(1, 8000, "request details", $data);
    }
   
    public function get_by_params(Request $request) {
        $stock_update_request = $request->all();
        $resource_options = $this->parse_resource_options();
        $data = $this->stock_received_service->get_by_params($stock_update_request);
        return $this->response(1, 8000, "requested fuel supplies", $data);
    }

}