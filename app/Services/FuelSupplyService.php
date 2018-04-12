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
use App\Mail\GoodsInTransitMail;
use App\Mail\GoodsInTransitMailSealChange;
use App\Station;
use App\Products;
use App\User;
use App\Models\CompanyUsers;
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
                    
                    $approver_details = CompanyUsers::where('id', $data['approved_by'])->get()->first();
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
           //return $data['request_code'];
           if(count($req) > 0){
         if($data['status'] == 'Goods in Transit'){    
       
        $query = FuelSupply::where('request_code', $data['request_code'])->update(['status' => $data['status']]);
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
              }
              else{
                $query = FuelSupply::where('request_code', $data['request_code'])->update(['last_modified_by' => $data['last_modified_by'], 'status' =>$data['status']]);
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
   //   public function get_by_station_id($stock_id)
    //{
      // return DailyTotalizerReadings::where('station_id',$stock_id)->get();
    //}
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