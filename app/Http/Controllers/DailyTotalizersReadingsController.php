<?php
namespace App\Http\Controllers;

use App\Requests\ApiCompanyRequest;
use App\Requests\ApiDailyTotalizersReadingsRequest;
use App\Services\CompanyService;
use App\Services\DailyTotalizersReadingsService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;

class DailyTotalizersReadingsController extends BaseController
{
    private $daily_totalizers_readings_service;
    
    public function __construct(DailyTotalizersReadingsService $daily_totalizers_readings_service)
    {
        $this->daily_totalizers_readings_service = $daily_totalizers_readings_service;
    }

    public function create(ApiDailyTotalizersReadingsRequest $request) {

        $totalizer_request = $request->get('pumps',[]);
        $data = $this->daily_totalizers_readings_service->create($totalizer_request);
        return $this->response(1, 8000, "stock successfully created", $data);
    }
      public function update(Request $request)
    {
        $totalizer_update_request = $request->get('pumps', []);
        $data = $this->daily_totalizers_readings_service->update($totalizer_update_request);
        return $this->response(1, 8000, "stock successfully updated", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->daily_totalizers_readings_service->get_all($resource_options);
        return $this->response(1, 8000, "totalizers", $data);
    }

    public function get_by_id($stock_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->daily_totalizers_readings_service->get_by_id($stock_id,$resource_options);
        return $this->response(1, 8000, "totalizers details", $data);
    }
   
    public function get_by_params(Request $request) {
        $stock_update_request = $request->all();
        $resource_options = $this->parse_resource_options();
        $data = $this->daily_totalizers_readings_service->get_by_params($stock_update_request);
        return $this->response(1, 8000, "requestedd totalizers", $data);
    }

}