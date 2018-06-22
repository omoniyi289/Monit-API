<?php
namespace App\Http\Controllers;

use App\Requests\ApiCompanyRequest;
use App\Requests\ApiDailyTotalizersReadingsRequest;
use App\Services\CompanyService;
use App\Services\DailyTotalizersReadingsService;
use App\Services\DailyStockReadingsService;
use App\Services\FGDemoService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Pumps;
use App\Tanks;
use App\Models\FGDemoCompany;
use App\Models\FGDemoStation;
use App\Models\StationUsers;
use App\Models\FGDemoDailyStockReadings;
use App\Models\FGDemoDailyTotalizerReadings;

class FGDemoController extends BaseController
{
    private $daily_totalizers_readings_service;
    private $fg_demo_service;

    public function __construct(FGDemoService  $fg_demo_service, DailyTotalizersReadingsService $daily_totalizers_readings_service, DailyStockReadingsService $daily_stock_readings_service)
    {
        $this->daily_totalizers_readings_service = $daily_totalizers_readings_service;
        $this->daily_stock_readings_service = $daily_stock_readings_service;
        $this->fg_demo_service = $fg_demo_service;
        ini_set('memory_limit', '2048M');
    }


    public function get_dashboard_kpis(Request $request){
        $resource_options = $this->parse_resource_options();
        $data = $this->fg_demo_service->get_dashboard_kpis($request);
        return $this->response(1, 8000, "data", $data);
    }

    public function get_by_id($stock_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->daily_totalizers_readings_service->get_by_id($stock_id,$resource_options);
        return $this->response(1, 8000, "totalizers details", $data);
    }

     public function get_demo_station_replenishment_plan(Request $request) {
        $resource_options = $this->parse_resource_options();
        $data = $this->fg_demo_service->get_replenishment_plan($request);
        return $this->response(1, 8000, "data", $data);
    }

   

   public function add_station_delivery(Request $request) {
        $resource_options = $this->parse_resource_options();
        $data = $this->fg_demo_service->add_station_delivery($request);
        return $this->response(1, 8000, "data", $data);
    }

}