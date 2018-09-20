<?php
namespace App\Http\Controllers;

use App\Requests\ApiCompanyRequest;
use App\Requests\ApiDailyStockReadingsRequest;
use App\Services\CompanyService;
use App\Services\DailyStockReadingsService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;

class DailyStockReadingsController extends BaseController
{
    private $daily_stock_readings_service;

    public function __construct(DailyStockReadingsService $daily_stock_readings_service)
    {
        $this->daily_stock_readings_service = $daily_stock_readings_service;
    }

    public function create(ApiDailyStockReadingsRequest $request) {

        $stock_request = $request->get('stocks',[]);
        $data = $this->daily_stock_readings_service->create($stock_request);
        if($data == 'invalid_input'){        
            return $this->response(0, 8000, "no stock reading supplied", null, 400);
            }else{
            return $this->response(1, 8000, "stock successfully created", $data);
        }
    }
      public function update(Request $request)
    {
        $stock_update_request = $request->get('stocks', []);
        $data = $this->daily_stock_readings_service->update($stock_update_request);
        return $this->response(1, 8000, "stock successfully updated", $data);
    }
    
       public function file_upload(Request $request)
    {
        $stock_update_request = $request;
        $data = $this->daily_stock_readings_service->handle_file_upload($stock_update_request);
        return $this->response(1, 8000, "stock file loaded", $data);
    }
        public function delete_by_params(Request $request)
    {
        $stock_update_request = $request->all();
        $data = $this->daily_stock_readings_service->delete_by_params($stock_update_request);
        return $this->response(1, 8000, "stock deleted", $data);
    }

        public function bovas_file_upload(Request $request)
    {
        $stock_update_request = $request;
        $data = $this->daily_stock_readings_service->bovas_handle_file_upload($stock_update_request);
        return $this->response(1, 8000, "stock file loaded", $data);
    }
   
       public function parsed_csv_data(Request $request)
    {
        $stock_update_request = $request->get('stocks', []);
        $data = $this->daily_stock_readings_service->upload_parsed_csv_data($stock_update_request);
        return $this->response(1, 8000, "stock successfully uploaded", $data);
    }

        public function bovas_parsed_csv_data(Request $request)
    {
        $stock_update_request = $request->get('stocks', []);
        $data = $this->daily_stock_readings_service->bovas_upload_parsed_csv_data($stock_update_request);
        return $this->response(1, 8000, "stock successfully uploaded", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->daily_stock_readings_service->get_all($resource_options);
        return $this->response(1, 8000, "stocks", $data);
    }

    
     public function get_by_params(Request $request) {
        //return $request;
        $stock_update_request = $request->all();
        $resource_options = $this->parse_resource_options();
        $data = $this->daily_stock_readings_service->get_by_params($stock_update_request);
        return $this->response(1, 8000, "requestedd stocks", $data);
    }

}