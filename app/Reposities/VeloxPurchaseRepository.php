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

class VeloxPurchaseRepository 
{

    // public function create(array $data){

    //     return DB::insert('insert into company_vendors (company_id, vendor_id, created_at, updated_at, status) values (?,?,?,?,?)',[ $data['company_id'], $data['vendor_id'], date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), 'Request Pending']);
    // }

    // public function update($data){
    //     return DB::update('update company_vendors set status = ? where id = ?', [$data['status'], $data['id']]);
    // }

    // public function get_by_params($vendor_id){

    //     return DB::connection('mysql2')->select('select p_history.* ,

    //          WHERE customer_vendor.vendor_id = ?' , [$vendor_id]);

    // }
    
    // public function delete($data){
    //     $company_user->fill($data);
    //     $company_user->save();
    //     return $company_user;
    // }
}