<?php
/**
 * Created by PhpStorm.
 * User: omoniyiomotoso
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;

use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use App\User;
use App\Models\VeloxCustomerVendor;
use App\Models\VeloxCustomerWallet;
use App\Models\VeloxVendor;
use App\Models\VeloxPaymentHistory;
use App\Reposities\VeloxPaymentRepository;
 
class VeloxPaymentService
{
    private $database;
    private $vp_repository;

    public function __construct(DatabaseManager $database, VeloxPaymentRepository $vp_repository)
    {
        $this->database = $database;
        $this->vp_repository = $vp_repository;
    }
   public function create(array $data){
    //return $data;
    $this->database->beginTransaction();
    try{
        //get current wallet details
        $customer_details = $data['selected_customer'];
        $customer_vendor_wallet_id = $customer_details['customer_vendor_wallet_id'];
        $current_wallet_details= $this->get_current_wallet_details($customer_vendor_wallet_id);
        //do necessary additions
        $amount_paid = $data ['amount_paid'];
        $current_balance = $current_wallet_details ['current_balance'];
        $new_balance = $current_balance + $amount_paid;

        //update customer's wallet
        VeloxCustomerWallet::where('id', $customer_vendor_wallet_id)->update(['current_balance'=> $new_balance ]);
        //create history
        $user= User::where('id', $data['uploaded_by'])->get(['fullname'])->first();

        $company_vendor= VeloxPaymentHistory::create(['vendor_id' => $customer_details['vendor_id'], 'company_id' => $customer_details['company_id'], 'amount_paid' => $amount_paid, 'start_company_balance' => $current_balance, 'end_company_balance' => $new_balance, 'current_credit_limit'=> $current_wallet_details['current_credit_limit'], 'payment_made_by' => $customer_details['name'], 'payment_uploaded_by' => $user['fullname'], 'payment_date' => date_format(date_create($data['payment_date']) ,"Y-m-d") ]);
            
        }
    catch (Exception $exception){
        $this->database->rollBack();
        throw $exception;
        }

        $this->database->commit();
        return $company_vendor;
    }
    
     public function get_company_id_on_velox($company_id)
    {
        return VeloxVendor::where('sm_company_id', $company_id)->get()->first();

    }
      public function get_current_wallet_details($customer_vendor_wallet_id)
    {
        return VeloxCustomerWallet::where('id', $customer_vendor_wallet_id)->get()->first();

    }

     public function get_by_params($request)
    {
        $result = VeloxPaymentHistory::where('id', '>', 0);
        if(isset($request['customer_id'])){
            $customer_id = $request['customer_id'];
            $result = $result->where('company_id', $customer_id);

        }  

        if(isset($request['vendor_id'])){
            $company_id = $request['vendor_id'];
            $velox_company_detials = $this->get_company_id_on_velox($company_id);
            $result = $result->where('vendor_id', $velox_company_detials['id']);

        }          
        return $result->get();
    }
  
   
}