<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/15/18
 * Time: 10:57 AM
 */

namespace App\Services;

use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use App\Models\StationUsers;
use App\Pumps;
use App\Tanks;
use App\Models\FGDemoCompany;
use App\Models\FGDemoStation;
use App\Models\FGDemoDailyStockReadings;
use App\Models\FGDemoDailyTotalizerReadings;
class FGDemoService
{
    private $database;
    private $station_repository;
    private $dispatcher;

    public function __construct(DatabaseManager $database, Dispatcher $dispatcher)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
    }
    
    public function get_all(array $options = []){
        return FGDemoStation::all();
    }

    public function get_station_by_company_id($company_id){
        return FGDemoStation::where("company_id",$company_id)->get();
    }

     public function add_station_delivery($request){
        //return $request;
        $station_id = $request->get('station_id');
        $delivery_vol = $request->get('delivery_volume');
        $product = $request->get('product');
        $reading_date = $request->get('reading_date');
        $reading_date =  date_format(date_create($reading_date),"Y-m-d").' 00:00:00';

        $station = FGDemoStation::where('id', $station_id)->get()->first();

        $new_tg = FGDemoDailyStockReadings::create(['company_id'=> $station['company_id'], 'station_id' => $station ['id'], 'reading_date'=>  $reading_date,'product' => $product, 'end_delivery'=>$delivery_vol, ]);
      return $new_tg;
    }


    public function get_replenishment_plan($request){
        $products = $request->get('products');
        $stations = $request->get('stations');
        $regions = $request->get('regions');
        $states = $request->get('states');
        $cities = $request->get('cities');
        $companies = $request->get('companies');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
    
        $merged_data_by_date= array();
        $final_submission = array();
        $company_count = 0;
        $station_count = 0;
        $tank_count = 0;
        $tank_data = '';
    
        $tank_query = Tanks::with('product');
        $back_day ='-12 days';
        $replenishment_plan=array();
        $runrate=array();
        $runrate_interval = date('Y-m-d', strtotime('2018-05-14 -30 days'));
        $today = "2018-05-14"; 
        //return $start_date.' '.$end_date;
        if($start_date == 'init'){
            $start_date = date('Y-m-d', strtotime($back_day));
        }else{
          $start_date = date_format(date_create($start_date),"Y-m-d")." 00:00:00";  
        }
        if($end_date == 'init'){
            $end_date = date('Y-m-d h:i:s');
        }else{
            $end_date = date_format(date_create($end_date),"Y-m-d")." 23:59:59";  
        }
        //return $start_date." ".$end_date;
     
        $tank_data = FGDemoDailyStockReadings::select('phy_shift_start_volume_reading', 'phy_shift_end_volume_reading', 'return_to_tank', 'reading_date', 'tank_id', 'station_id', 'product', 'end_delivery', 'start_delivery')->where('reading_date','>=', $start_date)->where('reading_date','<=', $end_date)->orderBy('reading_date', 'ASC')->with(array('tank'=>function($query){
            $query->select('id','code', 'reorder_volume', 'deadstock');}))->with(array('station'=>function($query){
            $query->select('id','name', 'state', 'city');}))->with(array('company'=>function($query){
            $query->select('id','name');}));

//stock_At_hand, replenishment plan, sales made, cosumption of people, deliveries
    if(count($stations) > 0){
            //$stations=StationUsers::where('company_user_id', $user_id)->get();
            $station_count = count($stations);
            $loop_counter = 1;

            foreach ($stations as $value) {
             ///calculate run rate for 30 days per station per product
                $station= FGDemoStation::where('id', $value)->get()->first();
            
                 $tank_data_for_30_days = FGDemoDailyStockReadings::select('phy_shift_start_volume_reading', 'phy_shift_end_volume_reading', 'return_to_tank', 'reading_date', 'tank_id', 'station_id', 'product', 'end_delivery', 'start_delivery')->where('reading_date','>=', $runrate_interval)->where('reading_date','<=', $today)->where('station_id', $value)->orderBy('reading_date', 'ASC')->with(array('tank'=>function($query){
            $query->select('id','code');}))->with(array('station'=>function($query){
            $query->select('id','name', 'city', 'state');}))->get();

            foreach ($tank_data_for_30_days as $key => $tank_reading) {

                if(!isset($runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']])){
                $runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']]=0;
                 }
                $runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']] =$runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']]
                 + (($tank_reading['phy_shift_start_volume_reading'] - $tank_reading['phy_shift_end_volume_reading']) + ($tank_reading['start_delivery'] + $tank_reading['end_delivery']))/count($tank_data_for_30_days);
            }

            //return $runrate;

                if($loop_counter == 1){
                    $tank_query = $tank_query->where('station_id', $value);

                    $tank_data = $tank_data->where('station_id', $value);
                    $loop_counter ++;
                    }
                else{
                    $tank_query = $tank_query->orWhere('station_id', $value);

                    $tank_data = $tank_data->orWhere(function($query)use($value, $start_date, $end_date){
                        $query->where('station_id', $value)->where('reading_date','>=', $start_date)->where('reading_date','<=', $end_date);
                    });
                    
                }
            }
        }else if(count($stations)== 0  and count($companies) > 0){
            $loop_counter = 1;

            foreach ($companies as $value) {
                $stations=FGDemoStation::where('company_id', $value)->get();
                $station_count = $station_count + count($stations);
                foreach ($station as  $inner_value) {
                       ///calculate run rate for 30 days per station per product
              
            $tank_data_for_30_days = FGDemoDailyStockReadings::select('phy_shift_start_volume_reading', 'phy_shift_end_volume_reading', 'return_to_tank', 'reading_date', 'tank_id', 'station_id', 'product', 'end_delivery', 'start_delivery')->where('reading_date','>=', $runrate_interval)->where('reading_date','<=', $today)->where('station_id', $inner_value->id)->orderBy('reading_date', 'ASC')->with(array('tank'=>function($query){
            $query->select('id','code');}))->with(array('station'=>function($query){
            $query->select('id','name', 'city', 'state');}))->get();

            foreach ($tank_data_for_30_days as $key => $tank_reading) {

                if(!isset($runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']])){
                $runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']]=0;
                 }
                $runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']] =$runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']]
                 + (($tank_reading['phy_shift_start_volume_reading'] - $tank_reading['phy_shift_end_volume_reading']) + ($tank_reading['start_delivery'] + $tank_reading['end_delivery']))/count($tank_data_for_30_days);
            }
                }

                if($loop_counter == 1){
                    $tank_query = $tank_query->where('company_id', $value);

                    $tank_data = $tank_data->where('company_id', $value);
                    $loop_counter ++;
                    }
                else{
                    $tank_query = $tank_query->orWhere('company_id', $value);
                    $tank_data = $tank_data->orWhere(function($query)use($value, $start_date, $end_date){
                        $query->where('company_id', $value)->where('reading_date','>=', $start_date)->where('reading_date','<=', $end_date);
                    });
                    
                }
            }
        }
        else if(count($companies)== 0 and count($stations)== 0 and count($cities) > 0){
            $loop_counter = 1;

            foreach ($cities as $value) {
                $stations=FGDemoStation::where('city', $value)->get();
                //return $stations;
                $station_count = $station_count + count($stations);
            foreach ($stations as $inner_value) {
                   ///calculate run rate for 30 days per station per product
              $tank_data_for_30_days = FGDemoDailyStockReadings::select('phy_shift_start_volume_reading', 'phy_shift_end_volume_reading', 'return_to_tank', 'reading_date', 'tank_id', 'station_id', 'product', 'end_delivery', 'start_delivery')->where('reading_date','>=', $runrate_interval)->where('reading_date','<=', $today)->where('station_id', $inner_value->id)->orderBy('reading_date', 'ASC')->with(array('tank'=>function($query){
            $query->select('id','code');}))->with(array('station'=>function($query){
            $query->select('id','name', 'city', 'state');}))->get();

            foreach ($tank_data_for_30_days as $key => $tank_reading) {

                if(!isset($runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']])){
                $runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']] =0;
                 }
                $runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']] =$runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']]
                 + (($tank_reading['phy_shift_start_volume_reading'] - $tank_reading['phy_shift_end_volume_reading']) + ($tank_reading['start_delivery'] + $tank_reading['end_delivery']))/count($tank_data_for_30_days);
            }

            //return $runrate;

                if($loop_counter == 1){
                    $tank_query = $tank_query->where('station_id', $inner_value->id);
                    $tank_data = $tank_data->where('station_id', $inner_value->id);
                    $loop_counter ++;
                    }
                else{
                    $tank_query = $tank_query->orWhere('station_id', $inner_value->id);
                    $tank_data = $tank_data->orWhere(function($query)use($inner_value, $start_date, $end_date){
                        $query->where('station_id', $inner_value->id)->where('reading_date','>=', $start_date)->where('reading_date','<=', $end_date);
                    });
                    
                }
            }
        }
        }
        else if(count($companies)== 0 and count($stations)== 0 and count($cities)== 0  and count($states) > 0){
            $loop_counter = 1;

            foreach ($states as $state) {

                $stations=FGDemoStation::where('state', $state)->get();
                $station_count = $station_count + count($stations);
            foreach ($stations as $inner_value) {
            //calculate run rate for 30 days per station per product
          $tank_data_for_30_days = FGDemoDailyStockReadings::select('phy_shift_start_volume_reading', 'phy_shift_end_volume_reading', 'return_to_tank', 'reading_date', 'tank_id', 'station_id', 'product', 'end_delivery', 'start_delivery')->where('reading_date','>=', $runrate_interval)->where('reading_date','<=', $today)->where('station_id', $inner_value->id)->orderBy('reading_date', 'ASC')->with(array('tank'=>function($query){
            $query->select('id','code');}))->with(array('station'=>function($query){
            $query->select('id','name', 'city', 'state');}))->get();
       
           foreach ($tank_data_for_30_days as $key => $tank_reading) {

                if(!isset($runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']])){
                $runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']]=0;
                 }
                $runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']] = $runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']]
                 + (($tank_reading['phy_shift_start_volume_reading'] - $tank_reading['phy_shift_end_volume_reading']) + ($tank_reading['start_delivery'] + $tank_reading['end_delivery']))/count($tank_data_for_30_days);
            }

            

                if($loop_counter == 1){
                    $tank_query = $tank_query->where('station_id', $inner_value->id);
                    $tank_data = $tank_data->where('station_id', $inner_value->id);
                    $loop_counter ++;
                    }
                else{
                    $tank_query = $tank_query->orWhere('station_id', $inner_value->id);

                  
                    $tank_data = $tank_data->orWhere(function($query)use($inner_value, $start_date, $end_date){
                        $query->where('station_id', $inner_value->id)->where('reading_date','>=', $start_date)->where('reading_date','<=', $end_date);
                    });
                    
                }
            }
        }
        }else{
            $query = FGDemoStation::with('companies')->get();
            $station_count = count($query);
            foreach ($query as $inner_value) {
                ///calculate run rate for 30 days per station per product
            $tank_data_for_30_days = FGDemoDailyStockReadings::select('phy_shift_start_volume_reading', 'phy_shift_end_volume_reading', 'return_to_tank', 'reading_date', 'tank_id', 'station_id', 'product', 'end_delivery', 'start_delivery')->where('reading_date','>=',$runrate_interval)->where('reading_date','<=', $today)->where('station_id', $inner_value->id)->orderBy('reading_date', 'ASC')->with(array('tank'=>function($query){
            $query->select('id','code');}))->with(array('station'=>function($query){
            $query->select('id','name', 'city', 'state');}))->get();

            foreach ($tank_data_for_30_days as $key => $tank_reading) {

                if(!isset($runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']])){
                $runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']]=0;
                 }
                $runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']] = $runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']]
                 + (($tank_reading['phy_shift_start_volume_reading'] - $tank_reading['phy_shift_end_volume_reading']) + ($tank_reading['start_delivery'] + $tank_reading['end_delivery']))/count($tank_data_for_30_days);
            }

            }
        }

        $tank_query= $tank_query->get();

        $tank_data= $tank_data->get();
       
        
     

        $total_current_tank_vol = array();
        $highest_reorder_level = array();
        $highest_deadstock_level = array();
        

        foreach ($tank_data as $key => $value) {
           
            $time_stamp = date("M d Y",strtotime($value['reading_date']));
             $product_stamp = $value['product'];
             $station = $value['station']['name'];
            
        ///replenishement plan stage 2
         if(!isset($total_current_tank_vol[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp])){
              $total_current_tank_vol[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp] = 0;
            }
            ///calc current tank vol .per station per product
            $total_current_tank_vol[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp] = $total_current_tank_vol[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp] + $value['phy_shift_end_volume_reading'];

         if(!isset($highest_deadstock_level[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp])){
              $highest_deadstock_level[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp] = 0;
            }
            ///determine hight deadstock value per station per product
            $highest_deadstock_level[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp] = $highest_deadstock_level[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp] < $value['tank']['deadstock'] ? $value['tank']['deadstock'] : $highest_deadstock_level[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp];

            if(!isset($highest_reorder_level[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp])){
              $highest_reorder_level[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp] = 0;
            }
            ///determine hight reorder value per station per product
            $highest_reorder_level[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp] = $highest_reorder_level[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp] < $value['tank']['reorder_volume'] ? $value['tank']['reorder_volume'] : $highest_reorder_level[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp];
            
         
        }
         
     
       $final_submission['total_current_tank_vol'] = $station_count > 0 ? $total_current_tank_vol:0;
       $final_submission['highest_reorder_level'] = $station_count > 0 ? $highest_reorder_level:0;
       $final_submission['highest_deadstock_level'] = $station_count > 0 ? $highest_deadstock_level : 0;
       $final_submission['runrate'] = $station_count > 0 ? $runrate : 0;
       
       $final_submission['merged_data_by_date'] = $station_count > 0 ? $merged_data_by_date:0;

       return $final_submission;
    }
  


   public function get_dashboard_kpis($request){
        $products = $request->get('products');
        $stations = $request->get('stations');
        $regions = $request->get('regions');
        $states = $request->get('states');
        $cities = $request->get('cities');
        $companies = $request->get('companies');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
       // return $request->all();

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
        $back_day ='-12 days';
        $replenishment_plan=array();
        $runrate=array();
        $runrate_interval = date('Y-m-d', strtotime('2018-05-14 -30 days'));
        $today = "2018-05-14"; 
        //return $start_date.' '.$end_date;
        if($start_date == 'init'){
            $start_date = date('Y-m-d', strtotime($back_day));
        }else{
          $start_date = date_format(date_create($start_date),"Y-m-d")." 00:00:00";  
        }
        if($end_date == 'init'){
            $end_date = date('Y-m-d h:i:s');
        }else{
            $end_date = date_format(date_create($end_date),"Y-m-d")." 23:59:59";  
        }
        //return $start_date." ".$end_date;
        
        $pump_data = FGDemoDailyTotalizerReadings::select('close_shift_totalizer_reading', 'open_shift_totalizer_reading', 'ppv', 'reading_date', 'pump_id', 'station_id', 'product')->where('reading_date','>=', $start_date)->where('reading_date','<=', $end_date)->orderBy('reading_date', 'ASC')->with(array('pump'=>function($query){
            $query->select('id','pump_nozzle_code');}))->with(array('station'=>function($query){
            $query->select('id','name', 'state', 'city');}))->with(array('company'=>function($query){
            $query->select('id','name');}));

        $tank_data = FGDemoDailyStockReadings::select('phy_shift_start_volume_reading', 'phy_shift_end_volume_reading', 'return_to_tank', 'reading_date', 'tank_id', 'station_id', 'product', 'end_delivery', 'start_delivery')->where('reading_date','>=', $start_date)->where('reading_date','<=', $end_date)->orderBy('reading_date', 'ASC')->with(array('tank'=>function($query){
            $query->select('id','code', 'reorder_volume', 'deadstock');}))->with(array('station'=>function($query){
            $query->select('id','name', 'state', 'city');}))->with(array('company'=>function($query){
            $query->select('id','name');}));

//stock_At_hand, replenishment plan, sales made, cosumption of people, deliveries
    if(count($stations) > 0){
            //$stations=StationUsers::where('company_user_id', $user_id)->get();
            $station_count = count($stations);
            $loop_counter = 1;

            foreach ($stations as $value) {
        

                if($loop_counter == 1){
                    $pump_query = $pump_query->where('station_id', $value);
                    $tank_query = $tank_query->where('station_id', $value);

                    $pump_data = $pump_data->where('station_id', $value);
                    $tank_data = $tank_data->where('station_id', $value);
                    $loop_counter ++;
                    }
                else{
                    $pump_query = $pump_query->orWhere('station_id', $value);
                    $tank_query = $tank_query->orWhere('station_id', $value);

                    $pump_data = $pump_data->orWhere(function($query)use($value, $start_date, $end_date){
                        $query->where('station_id', $value)->where('reading_date','>=', $start_date)->where('reading_date','<=', $end_date);
                    });
                    $tank_data = $tank_data->orWhere(function($query)use($value, $start_date, $end_date){
                        $query->where('station_id', $value)->where('reading_date','>=', $start_date)->where('reading_date','<=', $end_date);
                    });
                    
                }
            }
        }else if(count($stations)== 0  and count($companies) > 0){
            $loop_counter = 1;

            foreach ($companies as $value) {
                $stations=FGDemoStation::where('company_id', $value)->get();
                $station_count = $station_count + count($stations);
            

                if($loop_counter == 1){
                    $pump_query = $pump_query->where('company_id', $value);
                    $tank_query = $tank_query->where('company_id', $value);

                    $pump_data = $pump_data->where('company_id', $value);
                    $tank_data = $tank_data->where('company_id', $value);
                    $loop_counter ++;
                    }
                else{
                    $pump_query = $pump_query->orWhere('company_id', $value);
                    $tank_query = $tank_query->orWhere('company_id', $value);

                    $pump_data = $pump_data->orWhere(function($query)use($value, $start_date, $end_date){
                        $query->where('company_id', $value)->where('reading_date','>=', $start_date)->where('reading_date','<=', $end_date);
                    });
                    $tank_data = $tank_data->orWhere(function($query)use($value, $start_date, $end_date){
                        $query->where('company_id', $value)->where('reading_date','>=', $start_date)->where('reading_date','<=', $end_date);
                    });
                    
                }
            }
        }
        else if(count($companies)== 0 and count($stations)== 0 and count($cities) > 0){
            $loop_counter = 1;

            foreach ($cities as $value) {
                $stations=FGDemoStation::where('city', $value)->get();
                //return $stations;
                $station_count = $station_count + count($stations);
            foreach ($stations as $inner_value) {

                if($loop_counter == 1){
                    $pump_query = $pump_query->where('station_id', $inner_value->id);
                    $tank_query = $tank_query->where('station_id', $inner_value->id);

                    $pump_data = $pump_data->where('station_id', $inner_value->id);
                    $tank_data = $tank_data->where('station_id', $inner_value->id);
                    $loop_counter ++;
                    }
                else{
                    $pump_query = $pump_query->orWhere('station_id', $inner_value->id);
                    $tank_query = $tank_query->orWhere('station_id', $inner_value->id);

                    $pump_data = $pump_data->orWhere(function($query)use($inner_value, $start_date, $end_date){
                        $query->where('station_id', $inner_value->id)->where('reading_date','>=', $start_date)->where('reading_date','<=', $end_date);
                    });
                    $tank_data = $tank_data->orWhere(function($query)use($inner_value, $start_date, $end_date){
                        $query->where('station_id', $inner_value->id)->where('reading_date','>=', $start_date)->where('reading_date','<=', $end_date);
                    });
                    
                }
            }
        }
        }
        else if(count($companies)== 0 and count($stations)== 0 and count($cities)== 0  and count($states) > 0){
            $loop_counter = 1;

            foreach ($states as $state) {

                $stations=FGDemoStation::where('state', $state)->get();
                $station_count = $station_count + count($stations);
            foreach ($stations as $inner_value) {
      

                if($loop_counter == 1){
                    $pump_query = $pump_query->where('station_id', $inner_value->id);
                    $tank_query = $tank_query->where('station_id', $inner_value->id);

                    $pump_data = $pump_data->where('station_id', $inner_value->id);
                    $tank_data = $tank_data->where('station_id', $inner_value->id);
                    $loop_counter ++;
                    }
                else{
                    $pump_query = $pump_query->orWhere('station_id', $inner_value->id);
                    $tank_query = $tank_query->orWhere('station_id', $inner_value->id);

                    $pump_data = $pump_data->orWhere(function($query)use($inner_value, $start_date, $end_date){
                        $query->where('station_id', $inner_value->id)->where('reading_date','>=', $start_date)->where('reading_date','<=', $end_date);
                    });
                    $tank_data = $tank_data->orWhere(function($query)use($inner_value, $start_date, $end_date){
                        $query->where('station_id', $inner_value->id)->where('reading_date','>=', $start_date)->where('reading_date','<=', $end_date);
                    });
                    
                }
            }
        }
        }else{
            $query = FGDemoStation::with('companies')->get();
            $station_count = count($query);
            
        }

        $pump_query= $pump_query->get();
        $tank_query= $tank_query->get();

        $pump_data= $pump_data->get();
        $tank_data= $tank_data->get();
       
        $total_tank_sales = 0;
        $total_pump_sales = 0;
        $total_deliveries = array();
        $con_deliveries= array();
        $con_stocks_at_hand= array();
        $con_consumption_of_people= array();
        $total_deliveries['PMS']=0;
        $total_deliveries['AGO']=0;
        $total_deliveries['DPK']=0;

        $total_consumptions = array();
        $total_consumptions['PMS']=0;
        $total_consumptions['AGO']=0;
        $total_consumptions['DPK']=0;

        $stocks_at_hand = array();
        $stocks_at_hand['PMS']=0;
        $stocks_at_hand['AGO']=0;
        $stocks_at_hand['DPK']=0;

        $total_vol_supplied = 0;

        foreach ($pump_data as $key => $value) {
            if($value['close_shift_totalizer_reading'] != 0 and $value['open_shift_totalizer_reading'] != 0){
            $total_pump_sales = $total_pump_sales + $value['close_shift_totalizer_reading'] - $value['open_shift_totalizer_reading'];

            $total_consumptions[$value['product']] = $total_consumptions[$value['product']] + $value['close_shift_totalizer_reading'] - $value['open_shift_totalizer_reading'];
            ///state->city->company->station->product
            if(!isset($con_consumption_of_people[$value['station']['state']][$value['station']['city']][$value['station']['name']][$value['product']])){
                $con_consumption_of_people[$value['station']['state']][$value['station']['city']][$value['station']['name']][$value['product']]=0;
                 }
                 $con_consumption_of_people[$value['station']['state']][$value['station']['city']][$value['station']['name']][$value['product']]=$con_consumption_of_people[$value['station']['state']][$value['station']['city']][$value['station']['name']][$value['product']] + $value['close_shift_totalizer_reading'] - $value['open_shift_totalizer_reading'];

        }
             
        }
       

        $total_current_tank_vol = array();
        

        foreach ($tank_data as $key => $value) {
           
            $time_stamp = date("M d Y",strtotime($value['reading_date']));
             $product_stamp = $value['product'];
             $station = $value['station']['name'];
            
            $total_vol_supplied = $total_vol_supplied + ($value['start_delivery'] + $value['end_delivery']);

            $total_deliveries[$value['product']] = $total_deliveries[$value['product']] + ($value['start_delivery'] + $value['end_delivery']);
            ///state->city->company->station->product
            if(!isset($con_deliveries[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp])){
                $con_deliveries[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp]=0;
                 }
                 $con_deliveries[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp]=$con_deliveries[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp]   + ($value['start_delivery'] + $value['end_delivery']);


     
            $total_tank_sales = $total_tank_sales + ($value['phy_shift_start_volume_reading'] - $value['phy_shift_end_volume_reading']) + ($value['start_delivery'] + $value['end_delivery']);
            $stocks_at_hand[$value['product']]  = $stocks_at_hand[$value['product']]  + $value['phy_shift_end_volume_reading'];
             ///state->city->company->station->product
            if(!isset($con_stocks_at_hand[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp])){
                $con_stocks_at_hand[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp]=0;
                 }
                 $con_stocks_at_hand[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp]=$con_stocks_at_hand[$value['station']['state']][$value['station']['city']][$value['station']['name']][$product_stamp] + $value['phy_shift_end_volume_reading'];
        

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
       $final_submission['total_current_tank_vol'] = $station_count > 0 ? $total_current_tank_vol:0;
       
       $final_submission['total_deliveries'] = $station_count > 0 ? $total_deliveries
       :0;
       $final_submission['total_consumptions'] = $station_count > 0 ? $total_consumptions
       :0;
        $final_submission['stocks_at_hand'] = $station_count > 0 ? $stocks_at_hand
       :0;
       $final_submission['con_deliveries'] = $station_count > 0 ? $con_deliveries
       :0;
       $final_submission['con_stocks_at_hand'] = $station_count > 0 ? $con_stocks_at_hand
       :0;
        $final_submission['con_consumption_of_people'] = $station_count > 0 ? $con_consumption_of_people
       :0;
       $final_submission['merged_data_by_date'] = $station_count > 0 ? $merged_data_by_date:0;

       return $final_submission;
    }


    public function get_stations($params)
    {
        if(isset($params['state'])){
           return $state_name = $params['state'];
            return FGDemoStation::where('state', $state_name)->get();
            }
    }

}