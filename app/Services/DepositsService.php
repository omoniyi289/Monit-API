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
use App\Models\CompanyBankAccount;
use App\Services\StationService;
use App\Company;
use App\Station;
use App\Models\DailyStockReadings;
use App\Models\DailyTotalizerReadings;
use Maatwebsite\Excel\Facades\Excel;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
class DepositsService
{
    private $database;
    private $pump_repository;

    public function __construct(DatabaseManager $database, StationService $station_service)
    {
        $this->database = $database;
        $this->company_details = array();
        $this->csv_success_rows = array();
        $this->csv_error_log = array();
        $this->current_user = array();
        $this->user_station_ids = array();
        $this->station_service = $station_service;



    }
    public function create(array $data) {
        $this->database->beginTransaction();
        try{
          
            $data['teller_date']= date_format(date_create($data['payment_date']),"Y-m-d");
            $data['reading_date']= date_format(date_create($data['reading_date']),"Y-m-d");
            //date_format(date_create($params['selected_date']),"Y-m-d")
             $data['upload_type'] = 'Single';
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

    public function upload_parsed_csv_data(array $data) {
        $this->database->beginTransaction();
    //    return $data;
        try{
                foreach ($data['readings'] as $value) {
                    $company_id = $value['company_id'];
                    $station_id = $value['station_id'];
                    $created_by = $data['created_by'];
                    $pos_receipt_number = $value['pos_receipt_number'];
                    $payment_type = $value['payment_type'];
                    $account_number = $value['account_number'];
                    $bank = $value['bank'];
                    $amount = $value['amount'];
                    $teller_number = $value['teller_number'];
                    $pos_receipt_range = $value['pos_receipt_range'];

                    $reading_date = $value['actual_transaction_date'];
                    $teller_date = $value['teller_date'];
                    //else continue insert
                   

                        $stock = Deposits::create(['company_id' => $company_id,'station_id' => $station_id, 'account_number' => $account_number, 'payment_type' => $payment_type,'pos_receipt_number' => $pos_receipt_number, 'bank' => $bank, 'amount' => $amount,'created_by' => $created_by,'reading_date' => date_format(date_create($reading_date),"Y-m-d").' 00:00:00', 'pos_receipt_range' =>$pos_receipt_range, 'teller_date'=> $teller_date,'teller_number'=>$teller_number, 'upload_type'=>'Bulk']);
                    }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $stock;
    }

    public function handle_file_upload($request)
    {   
        $this->current_user = JWTAuth::parseToken()->authenticate();
        $user_id = $this->current_user->id;
        $company_id = $this->current_user->company_id;
        if($request->hasFile('file')) {
            $fileItself = $request->file('file');
            $rows = array();
            $load = Excel::load($fileItself, function($reader) {})->get();
            $row = $load[0];
          //  return $load[0];
           if(!isset($row->station_code)){
                array_push($this->csv_error_log , ["message" => "Station Code column not specified"]);
            }
            else if(!isset($row->bank)){
                array_push($this->csv_error_log , ["message" => "Bank column not specified"]);
            }
            else if(!isset($row->payment_type)){
                array_push($this->csv_error_log , ["message" => "Payment Type column not specified"]);
            }
            else if(!isset($row->teller_date)){
                array_push($this->csv_error_log , ["message" => "Teller Date column not specified"]);
            }else if(!isset($row->amount)){
                array_push($this->csv_error_log , ["message" => "Amount column not specified"]);
            }else{
                //to verify if user has access to upload for that company
              // $this->company_details = Company::where('id', $company_id)->get()->first();
                //to verify if user has access to upload for that station
               $user_stations_details = $this->station_service->get_stations_by_user_id($user_id);
               foreach ($user_stations_details as $key => $value) {
                  array_push($this->user_station_ids, $value['station_id']);
               }
              
                foreach($load as $key => $row) {
                $this->validate_company_and_bank($key, $row, $company_id);
                }
            }
        }
        return  array(['error' => $this->csv_error_log, 'success' => $this->csv_success_rows]);
    }

     public function validate_amount($params)
    {   

      $result = DailyTotalizerReadings::where('station_id',$params['station_id']);
       //return date_format(date_create($params['date']),"Y-m-d");
       $result->where('reading_date', 'LIKE', date_format(date_create($params['selected_date']),"Y-m-d").'%');
      $pump_data = $result->get();
      $total_amount=0;
      foreach ($pump_data as $key => $value) {
          $total_amount = $total_amount + ( ($value['close_shift_totalizer_reading'] 
          - $value['open_shift_totalizer_reading']) * $value['ppv']);
      }
       return $total_amount;
    }


    private function validate_company_and_bank($key, $row, $company_id){

          if($company_id != 'master'){
            $station_details  = Station::where('code', $row['station_code'])->where('company_id', $company_id)->get(['id', 'company_id', 'name'])->first();
          }

          else{
            $station_details  = Station::where('code', $row['station_code'])->get(['id', 'company_id', 'name'])->first();
          }

              $real_key = (int)$key+1;
              $row['company_id'] = $station_details['company_id'];
              $row['station_id'] = $station_details['id'];
              if(count($station_details) == 0){
                  array_push( $this->csv_error_log, ["message" => "Station with code ". $row['station_code']. " on row ".$real_key." not found, please confirm station code (check spelling)" ] );
              }else if($company_id != 'master' and !in_array($station_details['id'], $this->user_station_ids)){
                  array_push($this->csv_error_log, ["message" => "You are not permitted to upload readings for ". $row['station_code']. " on row ".$real_key ]);
              }else{
               
                // $account_details  = CompanyBankAccount::where('company_name', $row['company_name'])->where('bank', $row['bank'])->get(['id'])->first();
                // if(count($account_details) == 0){
                //     array_push($this->csv_error_log , ["message" => "Bank ". $row['bank']. " not saved for the company on row ".$real_key." (check spelling)"]);
                // }else{
                   array_push($this->csv_success_rows, $row);
               // } 
            }
        }
    
}