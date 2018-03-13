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

        $stock_request = $request->get('stock',[]);
        $data = $this->daily_stock_readings_service->create($stock_request);
        return $this->response(1, 8000, "stock successfully created", $data);
    }
      public function update($stock_id, Request $request)
    {
        $stock_update_request = $request->get('stock_reading', []);
        $data = $this->daily_stock_readings_service->update($stock_id, $stock_update_request);
        return $this->response(1, 8000, "stock successfully updated", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->daily_stock_readings_service->get_all($resource_options);
        return $this->response(1, 8000, "stocks", $data);
    }

    
     public function get_by_params(Request $request) {
        $stock_update_request = $request->all();
        $resource_options = $this->parse_resource_options();
        $data = $this->daily_stock_readings_service->get_by_params($stock_update_request);
        return $this->response(1, 8000, "requestedd stocks", $data);
    }

}