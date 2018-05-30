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
        ini_set('memory_limit', '2048M');
    }


    public function get_filtered(Request $request){
        $user = $request->get('user',[]);
        $user_id = $request->get('user_id',[]);
       
        $company_id=$request->get('company_id');
        $start_date=$request->get('start_date');
        $end_date=$request->get('end_date');
        

        //return (array)$pump_data[0];
        $merged_data_by_date= array();
        $final_submission = array();
        $company_count = 0;
        $station_count = 0;
        $tank_count = 0;
        $pump_count = 0;
        $pump_data = '';
        $tank_data = '';
        //pumps
        $pump_query = Pumps::with('product');
        $tank_query = Tanks::with('product');
        $back_day ='-3 days';
        //return $start_date.' '.$end_date;
        if($start_date == 'init'){
            $start_date = date('Y-m-d', strtotime($back_day));
        }else{
          $start_date = date_format(date_create($start_date),"Y-m-d")."00:00:00";  
        }
        if($end_date == 'init'){
            //$end_date = date('Y-m-d h:i:s');
            $end_date ='-1 day';
        }else{
            $end_date = date_format(date_create($end_date),"Y-m-d")."23:23:23";  
        }

        
        $pump_data = DailyTotalizerReadings::select('close_shift_totalizer_reading', 'open_shift_totalizer_reading', 'ppv', 'reading_date', 'pump_id', 'station_id', 'product')->where('reading_date','>=', $start_date)->where('reading_date','<=', $end_date)->orderBy('reading_date', 'ASC')->with(array('pump'=>function($query){
            $query->select('id','pump_nozzle_code');}))->with(array('station'=>function($query){
            $query->select('id','name');}));

        $tank_data = DailyStockReadings::select('phy_shift_start_volume_reading', 'phy_shift_end_volume_reading', 'return_to_tank', 'reading_date', 'tank_id', 'station_id', 'product', 'end_delivery', 'start_delivery')->where('reading_date','>=', $start_date)->where('reading_date','<=', $end_date)->orderBy('reading_date', 'ASC')->with(array('tank'=>function($query){
            $query->select('id','code');}))->with(array('station'=>function($query){
            $query->select('id','name');}));


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

                    $pump_data = $pump_data->orWhere(function($query)use($value, $start_date, $end_date){
                        $query->where('station_id', $value['station_id'])->where('reading_date','>=', $start_date)->where('reading_date','<=', $end_date);
                    });
                    $tank_data = $tank_data->orWhere(function($query)use($value, $start_date, $end_date){
                        $query->where('station_id', $value['station_id'])->where('reading_date','>=', $start_date)->where('reading_date','<=', $end_date);
                    });
                    
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
         //return $pump_data;
        
        //$final_submission['total_companies'] = c;

        
        $total_tank_sales = 0;
        $total_pump_sales = 0;
        $total_vol_supplied = 0;
        foreach ($pump_data as $key => $value) {
            if($value['close_shift_totalizer_reading'] != 0 and $value['open_shift_totalizer_reading'] != 0){
            $total_pump_sales = $total_pump_sales + $value['close_shift_totalizer_reading'] - $value['open_shift_totalizer_reading'];
        }
             $time_stamp = date("M d Y",strtotime($value['reading_date']));
             $product_stamp = $value['product'];
             $station = $value['station']['name'];
             //return $station;
             if(!isset($merged_data_by_date[$time_stamp][$station][$product_stamp]['pump_data'])){
                $merged_data_by_date[$time_stamp][$station][$product_stamp]['pump_data'] = array();
                 array_push($merged_data_by_date[$time_stamp][$station][$product_stamp]['pump_data'], $value);
                }
            else{
              array_push($merged_data_by_date[$time_stamp][$station][$product_stamp]['pump_data'], $value);  
            }
        }
        //return $total_pump_sales;

        foreach ($tank_data as $key => $value) {
            if($value['phy_shift_start_volume_reading'] != 0 and $value['phy_shift_end_volume_reading'] != 0){
            $total_tank_sales = $total_tank_sales + ($value['phy_shift_start_volume_reading'] - $value['phy_shift_end_volume_reading']) + ($value['start_delivery'] + $value['end_delivery']);
            }
            
            $total_vol_supplied = $total_vol_supplied + ($value['start_delivery'] + $value['end_delivery']);
             $time_stamp = date("M d Y",strtotime($value['reading_date']));
             $product_stamp = $value['product'];
             $station = $value['station']['name'];
             if(!isset($merged_data_by_date[$time_stamp][$station][$product_stamp]['tank_data'])){
                $merged_data_by_date[$time_stamp][$station][$product_stamp]['tank_data'] = array();
                 array_push($merged_data_by_date[$time_stamp][$station][$product_stamp]['tank_data'], $value);
                }
            else{
              array_push($merged_data_by_date[$time_stamp][$station][$product_stamp]['tank_data'], $value);  
            }
        }

       $final_submission['total_pumps'] = $station_count > 0 ? count($pump_query) : 0;
       $final_submission['total_tanks'] = $station_count > 0 ? count($tank_query) : 0;
       $final_submission['total_stations'] = $station_count;
       $final_submission['total_pump_sales'] = $station_count > 0 ? $total_pump_sales:0;    
       $final_submission['total_tank_sales'] = $station_count > 0 ? $total_tank_sales:0;
       $final_submission['total_vol_supplied'] = $station_count > 0 ? $total_vol_supplied
       :0;
       $final_submission['merged_data_by_date'] = $station_count > 0 ? $merged_data_by_date:0;

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