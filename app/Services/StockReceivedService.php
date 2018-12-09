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
use App\Models\StockReceived;
use App\Models\FuelSupply;
use App\Products;

class StockReceivedService
{
    private $database;

    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }
    public function create(array $data) {
        $this->database->beginTransaction();
        try{
            $stock = StockReceived::where('request_code', $data['request_code'])->get();
          if(count($stock) == 0){
          ////no waybill processed
                    $pump = StockReceived::create(['company_id' => $data['company_id'], 'station_id' => $data['station_id'], 'quantity_requested' => $data['quantity_requested'], 'quantity_supplied' => $data['quantity_supplied'],'driver_name' => $data['driver_name'], 'request_code' => $data['request_code'], 'truck_reg_number' => $data['truck_reg_number'],'arrival_time' => $data['arrival_time'], 'quantity_before_discharge' => $data['quantity_before_discharge'],'quantity_after_discharge' => $data['quantity_after_discharge'], 'stock_received_by' => $data['stock_received_by'], 'waybill_number' =>$data['waybill_number']]);
                  }

                  
              else{
                ///waybill was gotten
                $pump = StockReceived::where('request_code', $data['request_code'])->update(['quantity_supplied' => $data['quantity_supplied'],'driver_name' => $data['driver_name'], 'arrival_time' => $data['arrival_time'], 'quantity_before_discharge' => $data['quantity_before_discharge'],'quantity_after_discharge' => $data['quantity_after_discharge'], 'stock_received_by' => $data['stock_received_by'], 'waybill_number' =>$data['waybill_number']]);
              }
              $query = FuelSupply::where('request_code', $data['request_code'])->update(['status' => 'Delivery Completed']);
         
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
       // return $stock = StockReceived::where('request_code', $data['request_code'])->get()->first();
        return $result = StockReceived::where('station_id',$data['station_id'])->with('product')->orderBy('created_at', 'DESC')->get();
    }
     public function update(array $data)
    {
        
        $this->database->beginTransaction();
        try {
           $req = $this->get_requested_stock($data['request_code']);
           if(count($req) > 0){
                $req = StockReceived::where('request_code', $data['request_code'])->update(['last_modified_by' => $data['last_modified_by'], 'status' =>$data['status']]);
              } 
            else{
              return 'invalid code';
            } 

            // DailyTotalizerReadings::update($stock, $data);
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $result = StockReceived::where('station_id',$data['station_id'])->with('product')->orderBy('created_at', 'DESC')->get();
    }

    public function get_all(array $options = []){
        return StockReceived::all();
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

       $result = StockReceived::where('station_id',$params['station_id']);
    
       return $result->get();
    }
      public function get_delivery_pdf($params)
    {   
       $stock = StockReceived::where('id',$params['id'])->with('station')->with('fuelsupply'); 
       $stock= $stock->get()->first();
       //$driver= User::where('id', $stock['']);
       $product = Products::where('id', $stock['fuelsupply']['product_id'])->get()->first();
  //return response()->download($final_pdf);
       $stock['product'] = $product;
      return $stock;

    }

    public function get_waybill_pdf($params)
    {   
       $stock = StockReceived::where('request_code',$params['request_code'])->with('station')->with('fuelsupply')->with('stock_seal_numbers'); 
       $stock= $stock->get()->first();
       //$driver= User::where('id', $stock['']);
       $product = Products::where('id', $stock['fuelsupply']['product_id'])->get()->first();
  //return response()->download($final_pdf);
       $stock['product'] = $product;
      return $stock;

    }      

     public function get_by_request_code($req_code)
    {   

       return StockReceived::where('request_code', $req_code)->with('product')->get();
    
    }
       public function verify_request_credentials($req)
    {   

       return StockReceived::where('request_code', $req['code'])->where('station_id', $req['station_id'])->where('company_id', $req['company_id'])->with('product')->get();
    
    }
    private function get_requested_stock($stock_id, array $options = [])
    {
        return StockReceived::where('id', $stock_id)->with('product')->get();
    }
}