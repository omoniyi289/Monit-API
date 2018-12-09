<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;

use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use App\Models\FuelSupply;
use App\Models\StockReceived;
use App\Models\StockSealNumbers;
use App\Mail\FuelSupplyMail;
use Mail;
use App\Mail\SupplyAcknowledgementMail;
use App\Mail\GoodsInTransitMail;
use App\Mail\GoodsInTransitMailSealChange;
use App\Station;
use App\Products;
use App\Models\DailyStockReadings;
use App\User;
class FuelSupplyService
{
    private $database;
    private $query='';
    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }
    public function create(array $data) {
        $this->database->beginTransaction();
        try{
          
                    $query = FuelSupply::create(['company_id' => $data['company_id'], 'station_id' => $data['station_id'], 'quantity_requested' => $data['quantity_requested'],'product_id' => $data['product_id'], 'request_code' => $request_code = strtoupper(uniqid('FS')),'created_by' => $data['created_by'],'approved_by' => $data['approved_by'], 'status' =>$data['status']]);
                    $sss= Products::where('id', $data['product_id'])->first();
                    $data['product_name'] = $sss['name'];
                    $station = Station::where('id', $data['station_id'])->get()->first();
                    
                    $approver_details = User::where('id', $data['approved_by'])->get()->first();
                    Mail::to($approver_details['email'])->send(new FuelSupplyMail($station,$approver_details,$data['creator_name'], $data , $request_code));
                     //Mail::to("abayomi.e@e360africa.com")->send(new FuelSupplyMail($station,$user, $data , $request_code));
                      //Mail::to("abayomi.e@e360africa.com")->send(new ReportMail($mail_data, $value ));
         
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
       return  FuelSupply::where('id',$query['id'])->with('product')->with('approver')->with('stock_seal_numbers')->get()->first();
       // return $query;
    }



     public function update(array $data)
    {
        
        $this->database->beginTransaction();
        try {
           $req = $this->verify_request_update_credentials($data);
           $query = FuelSupply::where('request_code', $data['request_code'])->update(['status' => $data['status']]);
           if(count($req) > 0){
            if($data['status'] == 'Goods in Transit'){    
         
              $stock = StockReceived::where('request_code', $data['request_code'])->with('product')->get();
           
              if(count($stock) == 0){
                ///stock not yet created
                  $data['truck_departure_time']= date("Y-m-d H:i:s");

                  $data['waybill_number'] = strtoupper(uniqid('WB'));

                  $stock_received = StockReceived::create(['company_id' => $data['company_id'], 'station_id' => $data['station_id'], 'quantity_requested' => $req[0]['quantity_requested'], 'quantity_loaded' => $data['quantity_loaded'],'driver_name' => $data['driver_name'], 'request_code' => $data['request_code'], 'truck_reg_number' => $data['truck_reg_number'],'truck_departure_time' => $data['truck_departure_time'], 'waybill_printed_by' => $data['waybill_printed_by'], 'waybill_number' =>$data['waybill_number']]);

                    foreach ($data['first_seal_numbers'] as $key => $value) {
                    StockSealNumbers::create(['latest_seal_number'=>$value,'latest_seal_quantity'=>$data['first_seal_quantities'][$key], 'stock_received_id' => $stock_received['id'], 'compartment_number' => $key+1, 'request_code'=> $data['request_code']]);
                   } 

                   $sss= Products::where('id', $data['product_id'])->first();
                   $data['product_name'] = $sss['name'];
                   $station = Station::where('id', $data['station_id'])->get()->first();
                   
                   Mail::to($station['manager_email'])->send(new GoodsInTransitMail($station, $data));

                  }else{
                  
                  $data['truck_departure_time']= date("Y-m-d H:i:s");
                  $data['waybill_number'] = strtoupper(uniqid('WB'));
                   StockReceived::where('request_code', $data['request_code'])->update(['company_id' => $data['company_id'], 'station_id' => $data['station_id'], 'quantity_requested' => $req[0]['quantity_requested'], 'quantity_loaded' => $data['quantity_loaded'],'driver_name' => $data['driver_name'], 'request_code' => $data['request_code'], 'truck_reg_number' => $data['truck_reg_number'],'truck_departure_time' => $data['truck_departure_time'], 'waybill_printed_by' => $data['waybill_printed_by'], 'waybill_number' =>$data['waybill_number']]);
                  //return $data['latest_seal_numbers'];
                  StockSealNumbers::where('request_code', $data['request_code'])->delete();
                  //return $data['new_seal_numbers'];
                  foreach ($data['latest_seal_numbers'] as $key => $value) {
                    if(!isset($data['new_seal_numbers'][$key]) or $data['new_seal_numbers'][$key] == null){
                      $data['new_seal_numbers'][$key] = $data['latest_seal_numbers'][$key];
                      $data['new_seal_quantities'][$key] = $data['latest_seal_quantities'][$key];
                    }
                    StockSealNumbers::create(
                      ['previous_seal_number'=>$data['latest_seal_numbers'][$key],'latest_seal_number'=>$data['new_seal_numbers'][$key],
                      'previous_seal_quantity'=>$data['latest_seal_quantities'][$key],'latest_seal_quantity'=>$data['new_seal_quantities'][$key], 'stock_received_id' => $data['stock_received_id'], 'compartment_number' => $key+1, 'request_code'=> $data['request_code']]);
                   } 
                   $sss= Products::where('id', $data['product_id'])->first();
                   $data['product_name'] = $sss['name'];
                   $station = Station::where('id', $data['station_id'])->get()->first();
                   
                   Mail::to($station['manager_email'])->send(new GoodsInTransitMailSealChange($station, $data));

                  }
                    }else if($data['status'] == 'Approved'){
                  $req = FuelSupply::where('request_code', $data['request_code'])->get()->first();
                 $station = Station::with('station_users.user.role.role_permissions.permission')->where('id', $req['station_id'])->get()->first();
                 //send mail to executors for this station with PFRe permision
                         $product= Products::where('id', $req['product_id'])->first();
                          $req['product_name'] = $product['name'];
                          if(count($station) > 0 and $station->station_users !== null ){
                          $station_users =  $station->station_users;
                          foreach ($station_users as $key => $value) {
                              $user =  $value->user;
                              if($user->role !== null ){
                              $role_permissions = $user->role->role_permissions;
                              foreach ($role_permissions as $key => $value) {
                                          $permission = $value->permission;
                                      if($permission['UI_slug'] == "PFRe"){
                                                 Mail::to($user['email'])->send(new SupplyAcknowledgementMail($station,$user, $req , $req['request_code']));

                                          }
                                      }
                                  }    
                            }
                    
            
           $reply_id = 1;
            }
            }
            
            }else{
              return 'invalid code';
            } 

            // DailyTotalizerReadings::update($stock, $data);
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $result = FuelSupply::where('station_id',$data['station_id'])->with('product')->with('stock_received')->with('stock_seal_numbers')->orderBy('created_at', 'DESC')->get();
    }

    public function get_all(array $options = []){
        return FuelSupply::all();
    }
    public function get_by_id($stock_id, array $options = [])
    {
        return $this->get_requested_stock($stock_id);
    }
     public function autorequest()
         {

             //$stations= Station::all();
             $stations= Station::where('id', 13)->get();
             $runrate = array();
             
            foreach ($stations as $value) {
             ///calculate run rate for 30 days per station per product
                $station_runrate = array();
                $station_runrate['PMS'] = 0;
                $station_runrate['DPK'] = 0;
                $station_runrate['AGO'] = 0;

                $tank_data_for_30_days = DailyStockReadings::select('phy_shift_start_volume_reading', 'phy_shift_end_volume_reading', 'return_to_tank', 'reading_date', 'tank_id', 'station_id', 'product', 'end_delivery', 'start_delivery')->where('reading_date','>=', date('Y-m-d', strtotime('-30 days')))->where('reading_date','<=', date('Y-m-d h:i:s'))->where('station_id', $value['id'])->orderBy('reading_date', 'ASC')->with(array('tank'=>function($query){
                $query->select('id','code');}))->with(array('station'=>function($query){
                $query->select('id','name', 'city', 'state');}))->get();

                foreach ($tank_data_for_30_days as $key => $tank_reading) {

                    if(!isset($runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']])){
                    $runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']]=0;
                     }
                    $runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']] =$runrate[$tank_reading['station']['state']][$tank_reading['station']['city']][$tank_reading['station']['name']][$tank_reading['product']]
                     + (($tank_reading['phy_shift_start_volume_reading'] - $tank_reading['phy_shift_end_volume_reading']) + ($tank_reading['start_delivery'] + $tank_reading['end_delivery']))/count($tank_data_for_30_days);

                    $station_runrate[$tank_reading['product']] =$station_runrate[$tank_reading['product']]
                     + (($tank_reading['phy_shift_start_volume_reading'] - $tank_reading['phy_shift_end_volume_reading']) + ($tank_reading['start_delivery'] + $tank_reading['end_delivery']))/count($tank_data_for_30_days);
                }
                //calculate current tank vol. usiing the day before
              $readings = DailyStockReadings::with(array('tank'=>function($query){
                $query->select('id', 'reorder_volume');}))->where('reading_date','LIKE', '%'.date('Y-m-d', strtotime('-1 day')).'%')->where('station_id', $value['id'])->get();
                $station_current_tank_volume = array();
                $station_current_tank_volume['PMS'] = 0;
                $station_current_tank_volume['DPK'] = 0;
                $station_current_tank_volume['AGO'] = 0;

                $highest_reorder_level = array();
                $highest_reorder_level['PMS'] = 0;
                $highest_reorder_level['DPK'] = 0;
                $highest_reorder_level['AGO'] = 0;
                $counter=0;

                foreach ($readings as $key => $reading) {
                   //cal. the current tank vol. in station    
                  $station_current_tank_volume[$reading['product']] =  $station_current_tank_volume[$reading['product']] + $reading['phy_shift_end_volume_reading'];                 
               
                  ///determine hight reorder volume per station per product
                  $highest_reorder_level[$reading['product']] = $highest_reorder_level[$reading['product']] < $reading['tank']['reorder_volume'] ? $reading['tank']['reorder_volume'] : $highest_reorder_level[$reading['product']];
              }
              ////days to reorder
              $days_to_deorder = array();
              $days_to_deorder['PMS'] = 0;
              $days_to_deorder['DPK'] = 0;
              $days_to_deorder['AGO'] = 0;
             // return $station_runrate;
              if($station_runrate['PMS']> 0){
                  $days_to_deorder['PMS'] =  (int)(($station_current_tank_volume['PMS'] - $highest_reorder_level['PMS']) / $station_runrate['PMS']);
                    if($days_to_deorder <= 4){
                        $this->raise_supply_request();
                     }
                    }
              if($station_runrate['AGO']> 0){
                  $days_to_deorder['AGO'] =  (int)(($station_current_tank_volume['AGO'] - $highest_reorder_level['AGO']) / $station_runrate['AGO']);
                     }
              if($station_runrate['DPK']> 0){
                  $days_to_deorder['DPK'] =  (int)(($station_current_tank_volume['DPK'] - $highest_reorder_level['DPK']) / $station_runrate['DPK']); 
                     }

              
              return (array( 'station_current_tank_volume'=> $station_current_tank_volume, 'reorder_level' => $highest_reorder_level, 'station_runrate'=> $station_runrate,'days_to_deorder'=>$days_to_deorder));           
          }
       //  return $station_runrate;
    }
   
      public function get_by_params($params)
    {   

       $result = FuelSupply::where('station_id',$params['station_id'])->with('product')->with('stock_received')->with('approver')->with('stock_seal_numbers');
    
       return $result->get();
    }
      public function get_request_details($req_code)
    {   

       return FuelSupply::where('request_code', $req_code)->with('product')->with('stock_received')->get()->first();
    
    }
       public function verify_request_credentials($req)
    {   

       return FuelSupply::where('request_code', $req['code'])->where('station_id', $req['station_id'])->where('company_id', $req['company_id'])->with('product')->get();
    
    }
        public function verify_request_update_credentials($req)
    {   

       return FuelSupply::where('request_code', $req['request_code'])->where('station_id', $req['station_id'])->where('company_id', $req['company_id'])->with('product')->get();
    
    }
    private function get_requested_stock($stock_id, array $options = [])
    {
        return FuelSupply::where('id', $stock_id)->with('product')->get();
    }
}