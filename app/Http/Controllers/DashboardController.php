<?php
namespace App\Http\Controllers;

use App\Requests\ApiCompanyRequest;
use App\Requests\ApiDailyTotalizersReadingsRequest;
use App\Services\CompanyService;
use App\Services\DailyTotalizersReadingsService;
use App\Services\DailyStockReadingsService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Pumps;
use App\Tanks;
use App\Company;
use App\Station;
use App\Models\StationUsers;
use App\Models\DailyStockReadings;
use App\Models\DailyTotalizerReadings;

class DashboardController extends BaseController
{
    private $daily_totalizers_readings_service;

    public function __construct(DailyTotalizersReadingsService $daily_totalizers_readings_service, DailyStockReadingsService $daily_stock_readings_service)
    {
        $this->daily_totalizers_readings_service = $daily_totalizers_readings_service;
        $this->daily_stock_readings_service = $daily_stock_readings_service;
    }


    public function get_filtered(Request $request){
        $user = $request->get('user',[]);
        $user_id = $request->get('user_id',[]);
       
        $company_id=$request->get('company_id');
            

        //return (array)$pump_data[0];
        $merged_data_by_date= array();
        $final_submission = array();
        $company_count = 0;
        $station_count = 0;
        $tank_count = 0;
        $pump_count = 0;
        $pump_data = array();
        $tank_data = array();
        //pumps
        $pump_query = Pumps::with('product');
        $tank_query = Tanks::with('product');
        $pump_data = DailyTotalizerReadings::with('pump.product')->with('station');
        $tank_data = DailyStockReadings::with('tank.product')->with('station');

        if($user == 'first_company_user'){
            $company = Company::where('user_id', $user_id)->get()->first();
           
            $stations=Station::where('company_id', $company['id'])->get();
            $station_count = count($stations);
            $loop_counter = 1;
            foreach ($stations as $value) {
                
                if($loop_counter == 1){
                    $pump_query = $pump_query->where('station_id', $value['id']);
                    $tank_query = $tank_query->where('station_id', $value['id']);

                    $pump_data = $pump_data->where('station_id', $value['id']);
                    $tank_data = $tank_data->where('station_id', $value['id']);
                    $loop_counter ++;

                }else{
                    $pump_query = $pump_query->orWhere('station_id', $value['id']);
                     $tank_query = $tank_query->orWhere('station_id', $value['id']);

                    $pump_data = $pump_data->orWhere('station_id', $value['id']);
                    $tank_data = $tank_data->orWhere('station_id', $value['id']);
                }
            }

            

        }else if($user == 'company_regular_user'){
            $stations=StationUsers::where('company_user_id', $user_id)->get();
            $station_count = count($stations);
            $loop_counter = 1;

            foreach ($stations as $value) {
                if($loop_counter == 1){
                    $pump_query = $pump_query->where('station_id', $value['station_id']);
                    $tank_query = $tank_query->where('station_id', $value['station_id']);

                    $pump_data = $pump_data->where('station_id', $value['station_id']);
                    $tank_data = $tank_data->where('station_id', $value['station_id']);
                    $loop_counter ++;
                    }
                else{
                    $pump_query = $pump_query->orWhere('station_id', $value['station_id']);
                    $tank_query = $tank_query->orWhere('station_id', $value['station_id']);

                    $pump_data = $pump_data->orWhere('station_id', $value['station_id']);
                    $tank_data = $tank_data->orWhere('station_id', $value['station_id']); 
                }
            }
        }else if($user == 'e360_super_user'){
            $query = Station::with('companies')->get();
            $station_count = count($query);
        }

        $pump_query= $pump_query->get();
        $tank_query= $tank_query->get();

        $pump_data= $pump_data->get();
        $tank_data= $tank_data->get();
        //return $tank_query;
        $final_submission['total_pumps'] = count($pump_query);
        $final_submission['total_tanks'] = count($tank_query);
        $final_submission['total_stations'] = $station_count;
        //$final_submission['total_companies'] = c;

        
        $total_tank_sales = 0;
        $total_pump_sales = 0;
        $total_vol_supplied = 0;
        foreach ($pump_data as $key => $value) {
            $total_pump_sales = $total_pump_sales + ($value['close_shift_totalizer_reading'] - $value['open_shift_totalizer_reading']);
             $time_stamp = date("M d Y",strtotime($value['created_at']));
             $product_stamp = $value['pump']['product']['code'];
             if(!isset($merged_data_by_date[$time_stamp][$product_stamp]['pump_data'])){
                $merged_data_by_date[$time_stamp][$product_stamp]['pump_data'] = array();
                 array_push($merged_data_by_date[$time_stamp][$product_stamp]['pump_data'], $value);
                }
            else{
              array_push($merged_data_by_date[$time_stamp][$product_stamp]['pump_data'], $value);  
            }
        }

        foreach ($tank_data as $key => $value) {
            $total_tank_sales = $total_tank_sales + ($value['phy_shift_start_volume_reading'] - $value['phy_shift_end_volume_reading']);
            $total_vol_supplied = $total_vol_supplied + ($value['start_delivery'] + $value['end_delivery']);
             $time_stamp = date("M d Y",strtotime($value['created_at']));
             $product_stamp = $value['tank']['product']['code'];
             if(!isset($merged_data_by_date[$time_stamp][$product_stamp]['tank_data'])){
                $merged_data_by_date[$time_stamp][$product_stamp]['tank_data'] = array();
                 array_push($merged_data_by_date[$time_stamp][$product_stamp]['tank_data'], $value);
                }
            else{
              array_push($merged_data_by_date[$time_stamp][$product_stamp]['tank_data'], $value);  
            }
        }

       $final_submission['total_pump_sales'] = $total_pump_sales;    
       $final_submission['total_tank_sales'] = $total_tank_sales;
       $final_submission['total_vol_supplied'] = $total_vol_supplied;
       $final_submission['merged_data_by_date'] = $merged_data_by_date;
        return $this->response(1, 8000, "dashboard data", $final_submission);
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