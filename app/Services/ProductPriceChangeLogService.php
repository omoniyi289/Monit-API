<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;


use App\Reposities\CompanyRepository;
use App\Reposities\ProductPricesLogsRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use App\ProductChangeLogs;
use App\ProductPrices;
use App\Mail\PriceChangeMail;
use Mail;
use App\Station;
use App\Products;
use App\User;
use App\Services\ProductPriceService;
use App\Models\CompanyUserRole;
use App\Events\PriceChangeApprovalGenerated;


class ProductPriceChangeLogService
{
    private $database;
    private $dispatcher;
    private $product_price_change_log_repository;

    public function __construct(DatabaseManager $database,Dispatcher $dispatcher,
                                ProductPricesLogsRepository $product_price_change_log_repository)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
        $this->product_price_change_log_repository = $product_price_change_log_repository;
    }

    public function create_default(array $data){
        $this->database->beginTransaction();
        try{

            if($data['mode']=='create'){
            $exist = $this->get_by_station_and_product_id($data['station_id'], $data['product_id']);
            if (count($exist) > 0) {
                return 'ERROR 400';
            }else{   
                 $data['new_price_tag'] = $data['requested_price_tag'];  
                 $product= Products::where('id', $data['product_id'])->first();
                 $data['product'] = $product['code'];    
                 $product_price = ProductPrices::create($data);
                }
            }
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return ProductPrices::where('id', $product_price['id'])->with('station:id,name,company_id')->with('product:id,code')->get()->first();
    }

    public function get_company_by_name($name){
        return $this->product_price_change_log_repository->get_where('name',$name);
    }

    public function get_all(array $options = []){
        return $this->product_price_change_log_repository->get($options);
    }
    public function get_by_id($user_id, array $options = [])
    {
        return $this->get_requested_product_price_change($user_id);
    }
    public function create_new_request($new_data)
    {
                   
                    $station = Station::with('station_users.user.role.role_permissions.permission')->where('id', $new_data['station_id'])->get()->first();  
                    /*get users with privilege to this station with permission to approve price change(APCR)*/
                   $product= Products::where('id', $new_data['product_id'])->first();
                   $new_data['product'] = $product['code'];   
                   if(isset($new_data['v1_id'])){
                      unset($new_data['v1_id']);
                    }
                    $data = ProductChangeLogs::create($new_data);
                   
                    $data['product_name'] = $product['name'];
                    //set the approval level to level 1
                    $data['is_approved_type'] = 'is_approved';
                    $data['approval_level_indicator_string'] = 'Requested By';
                    $station_users =  $station->station_users;
                     $is_email_sent = false;
                    foreach ($station_users as $key => $value) {
                        $user =  $value->user;
                        if($user->role !== null ){

                        $role_permissions = $user->role->role_permissions;
                        foreach ($role_permissions as $key => $value) {
                                    $permission = $value->permission;
                                if($permission['UI_slug'] == "APCR"){
                                        $is_email_sent = true;
                                      //  Mail::to($user['email'])->send(new PriceChangeMail($station,$user,$new_data['creator_name'], $data ));

                                        $mail_data = ['station'=> $station, 'user'=>$user, 'last_updated_by' => $new_data['creator_name'],'product_change_result' => $data];
                                           event(new PriceChangeApprovalGenerated($mail_data));
                                   }                            
                            }    
                            }                                       
                    }

                    ///first try send to users with level 2 having ensured there is a second or third level
                     
                      if( $station['pc_approval_levels'] >= 2 ){
                      foreach ($station_users as $key => $value) {
                          $user =  $value->user;
                          if($user->role !== null ){
                          $role_permissions = $user->role->role_permissions;
                          foreach ($role_permissions as $key => $value) {
                                      $permission = $value->permission;
                                  if($permission['UI_slug'] == "APCRL2"){
                                         
                                            $is_email_sent = true;
                                           //Mail::to($user['email'])->send(new PriceChangeMail($station,$user,$new_data['creator_name'], $data ));

                                           $mail_data = ['station'=> $station, 'user'=>$user, 'last_updated_by' => $new_data['creator_name'],'product_change_result' => $data];
                                           event(new PriceChangeApprovalGenerated($mail_data));
                                      }
                                  }
                              }    
                        }
                      }
                        ///if no level 2, send to level 3 having ensured there is a second or third level
                      if(!$is_email_sent){
                        if( $station['pc_approval_levels'] >= 2 ){
                          foreach ($station_users as $key => $value) {
                          $user =  $value->user;
                          if($user->role !== null ){
                          $role_permissions = $user->role->role_permissions;
                          foreach ($role_permissions as $key => $value) {
                                      $permission = $value->permission;
                                  if($permission['UI_slug'] == "APCRL3"){
      
                                           $is_email_sent = true;
                                           //Mail::to($user['email'])->send(new PriceChangeMail($station,$user,$new_data['creator_name'], $data ));

                                            $mail_data = ['station'=> $station, 'user'=>$user, 'last_updated_by' => $new_data['creator_name'],'product_change_result' => $data];
                                           event(new PriceChangeApprovalGenerated($mail_data));
                                      }
                                        }
                                    }    
                              }
                            }
                          }



return  ProductChangeLogs::where("id", $data['id'])->with('product:id,code')->with('approver:id,fullname,email')->with('station:id,name,company_id')->get()->first();
    }
    public function get_by_station_id($station_ids, array $options = [])
    {
        $station_ids = explode(",", $station_ids);

        return ProductChangeLogs::whereIn("station_id", $station_ids)->with('product:id,code')->with('approver:id,email,fullname')->with('station:id,name,company_id')->get();
    }
  public function verify_approval($data)
    {
        $output = array();
        $user_details = User::where('id', $data['user_id'])->get()->first();
        ///track user's permissions
        $user_perms= array();
        //track other users' permissions
        $other_user_perms = array();
        $other_user_perms['APCR'] = 0;
        $other_user_perms['APCRL2'] = 0;
        $other_user_perms['APCRL3'] = 0;
        $other_user_perms['EPCR'] = 0;
        $log_status = '';
        $prd = ProductChangeLogs::where('id', $data['request_id'])->get()->first();
        $station = Station::with('station_users.user.role.role_permissions.permission')->where('id', $prd['station_id'])->get()->first();
           //send mail to executors for this stastion with EPCR permision
                   $product= Products::where('id', $prd['product_id'])->first();
                    $prd['product_name'] = $product['name'];
                    if(count($station) > 0 and $station->station_users !== null ){
                    $station_users =  $station->station_users;
                    foreach ($station_users as $key => $value) {
                        $user =  $value->user;
                        if( $user->role !== null ){
                        $role_permissions = $user->role->role_permissions;
                        foreach ($role_permissions as $key => $value) {
                                    $permission = $value->permission;
                                if($permission['UI_slug'] == "APCR" or $permission['UI_slug'] == "APCRL2" or $permission['UI_slug'] == "APCRL3"or $permission['UI_slug'] == "EPCR"){
                                        if($user->id == $user_details['id'] ){
                                        array_push($user_perms, $permission['UI_slug']);
                                            }else{
                                                $other_user_perms[$permission['UI_slug']]++;
                                            }


                                    }
                                }
                            }    
                      }
                    }

        $output['statusCode'] = 0;
        $output['current_approval_level'] = 0 ;
        
        if($prd['is_executed'] == 1){
           $output['message'] = 'Oops! Request already executed';
        }else if( $prd['is_approved'] === 0 or $prd['is_approved_level_2'] === 0 
            or $prd['is_approved_level_3'] === 0){
           $output['message'] = 'Oops! Request already disapproved';
        }
         else if($prd['is_approved_level_3'] == 1){
            //all approval stages complete for stations with 3 levels
             $output['current_approval_level'] = 3;
         }
        else if($prd['is_approved_level_2'] == 1){
            $output['current_approval_level'] = 2;
            if( in_array("APCRL3", $user_perms)) {
                $output['statusCode'] = 1;
                $output['message'] = "eligible";
             }else{
                $output['message'] = "You do not have the permission to further approval at this level";
            }
        }else if($prd['is_approved'] == 1){
            $output['current_approval_level'] = 1;
            //user has level 2 permission or level 3 permission with nobodu given permission for level 2
            if( in_array("APCRL2", $user_perms) or (in_array("APCRL3", $user_perms) and $other_user_perms['APCRL2'] ==0 )){
                $output['statusCode'] = 1;
                $output['message'] = "eligible";
             }else{
                $output['message'] = "You do not have the permission to further approval at this level";
            }
        }else if($prd['is_approved'] == null){
            $output['current_approval_level'] = 0;
            //user has level 1 permission or level 2 permission with nobodu given permission for level 1 or level 3 permission with nobodu given permission for level 2 and 1 
            if(in_array("APCR", $user_perms) or (in_array("APCRL2", $user_perms) and $other_user_perms['APCR'] ==0 ) or (in_array("APCRL3", $user_perms) and $other_user_perms['APCRL2'] == 0 and $other_user_perms['APCR'])){
                $output['statusCode'] = 1;
                $output['message'] = "eligible";
             }else{
                $output['message'] = "You do not have the permission to further approval at this level";
            }
        }

        ///finally check if all stages of approval is complete, verify_exectuable is for execution permsiion check
         $output['pc_approval_levels'] = $station['pc_approval_levels'];
        if(!isset($data['verify_executable']) and $output['current_approval_level'] >= $output['pc_approval_levels']){
            $output['statusCode'] = 0;
            $output['message'] = 'Oops! all approval stages completed, awaiting execution';
        }else if(isset($data['verify_executable']) and $output['current_approval_level'] >= $output['pc_approval_levels']){

            if(in_array("EPCR", $user_perms)) {
                $output['statusCode'] = 1;
                $output['message'] = "eligible";
             }else{
                $output['statusCode'] = 0;
                $output['message'] = "You do not have the permission to execute request";
            }
        }

        //exempt super users of all
        if($user_details['role_id'] == "master" or  $user_details['role_id'] == "super"){
                $output['statusCode'] = 1;
                $output['message'] = "eligible";
                if(!isset($data['verify_executable']) and $output['current_approval_level'] >= $output['pc_approval_levels']){
                    $output['statusCode'] = 0;
                    $output['message'] = 'Oops! all approval stages completed, awaiting execution';
                }
            }
        
        return $output;
    }
    public function get_by_station_and_product_id($station_id, $product_id, array $options = [])
    {
        return ProductPrices::where("station_id", $station_id)->where("product_id", $product_id)->get();
    }
    private function get_requested_product_price_change($user_id, array $options = [])
    {
        return $this->product_price_change_log_repository->get_by_id($user_id, $options);
    }
}