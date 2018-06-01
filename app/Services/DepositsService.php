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
use App\Models\Deposits;
use App\Models\DailyStockReadings;
use App\Models\DailyTotalizerReadings;
class DepositsService
{
    private $database;
    private $pump_repository;

    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }
    public function create(array $data) {
        $this->database->beginTransaction();
        try{
           // return $data;
            $data['teller_date']= date_format(date_create($data['payment_date']),"Y-m-d");
            $data['reading_date']= date_format(date_create($data['reading_date']),"Y-m-d");
            //date_format(date_create($params['selected_date']),"Y-m-d")
            $deposits = Deposits::create($data);
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
         return Deposits::where('id', $deposits['id'])->with('creator')->with('approver')->get()->first();
    }
     public function update($deposit_id, array $data)
    {
        $deposit = Deposits::where('id',$deposit_id);
        $this->database->beginTransaction();
        try {
            Deposits::where('id', $deposit['id'])->update($data);
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return Deposits::where('id', $deposit_id)->with('creator')->with('approver')->get()->first();
    }

    public function get_all(array $options = []){
        return Deposits::all();
    }
    public function get_by_id($user_id, array $options = [])
    {
        return get_requested_deposit($user_id);
    }
      public function get_by_station_id($station_id)
    {
       return Deposits::where('station_id',$station_id)->get();
    }
    private function get_requested_deposit($id, array $options = [])
    {
     return Deposits::where('id', $id)->with('creator')->with('approver')->get()->first();
    }
     public function validate_amount($params)
    {   

      $result = DailyTotalizerReadings::where('station_id',$params['station_id']);
       //return date_format(date_create($params['date']),"Y-m-d");
       $result->where('reading_date', 'LIKE', date_format(date_create($params['selected_date']),"Y-m-d").'%');
      $pump_data = $result->get();
      $total_amount=0;
      foreach ($pump_data as $key => $value) {
          $total_amount = $total_amount + $value['shift_1_cash_collected'] 
          + $value['shift_2_cash_collected'] + $value['cash_collected'];
      }
       return $total_amount;
    }
}