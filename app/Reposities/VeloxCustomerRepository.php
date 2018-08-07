<?php
/**
 * Created by PhpStorm.
 * User: omoniyiomotoso
 * Date: 1/12/18
 * Time: 6:45 PM
 */

namespace App\Reposities;
use Illuminate\Support\Facades\DB;
use App\User;
use Core\Repository\BaseRepository;

class VeloxCustomerRepository 
{

  
    public function get_by_vendor_id($vendor_id){

        return DB::connection('mysql2')->select('select  customer_vendor.id AS customer_vendor_id, customer_vendor.vendor_id,customer_vendor.company_id,customer.id, customer.name, customer.address, customer.city, customer.state, customer.email, customer_vendor_wallet.id AS customer_vendor_wallet_id,  customer_vendor_wallet.current_balance, customer_vendor_wallet.current_credit_limit, customer_vendor.status from company_vendors customer_vendor 
            INNER JOIN company_wallet customer_vendor_wallet  ON customer_vendor_wallet.company_id = customer_vendor.company_id AND customer_vendor_wallet.vendor_id = customer_vendor.vendor_id 
            INNER JOIN companies customer  ON customer.id = customer_vendor.company_id

             WHERE (customer_vendor.vendor_id = ?)' , [$vendor_id]);
    }

    public function get_by_status($vendor_id, $status){

        return DB::connection('mysql2')->select('select  customer_vendor.id AS customer_vendor_id, customer_vendor.vendor_id,customer_vendor.company_id,customer.id, customer.name, customer.address, customer.city, customer.state, customer.email, customer_vendor_wallet.id AS customer_vendor_wallet_id,  customer_vendor_wallet.current_balance, customer_vendor_wallet.current_credit_limit, customer_vendor.status from company_vendors customer_vendor 
            INNER JOIN company_wallet customer_vendor_wallet  ON customer_vendor_wallet.company_id = customer_vendor.company_id AND customer_vendor_wallet.vendor_id = customer_vendor.vendor_id 
            INNER JOIN companies customer  ON customer.id = customer_vendor.company_id

             WHERE (customer_vendor.vendor_id = ? AND customer_vendor.status = ?)' , [$vendor_id, $status]);
    }
    
    public function delete($data){
        $company_user->fill($data);
        $company_user->save();
        return $company_user;
    }
}