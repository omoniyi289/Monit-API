<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;
use App\Reposities\CompanyRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use App\Company;
use App\Station;
use App\Pumps;
use App\Tanks;
use App\Role;
use App\User;
use App\Permission;
use App\Models\StationUsers;
use App\Models\NotificationModules;
use App\Models\UserNotifications;
use App\RolePermission;
use App\TankGroups;
use App\PumpGroups;
use App\PumpGroupToTankGroup;
use App\Models\DailyStockReadings;
use App\Models\DailyTotalizerReadings;
use App\Models\Deposits;
use App\ProductPrices;
use App\ProductChangeLogs;
use App\Models\ExpenseHeader;
use App\Models\ExpenseItems;

ini_set('max_execution_time', 80000); 
class MigrationService
{
    private $database;
    private $dispatcher;
    private $servername = "185.130.207.215";
    private $username = "samuel.j";
    private $password = "Tr-3re@Aza4r";
    private $dbname = "station_manager";
    private $conn ='';

    private $local_servername = "127.0.0.1";
    private $local_username = "newroot";
    private $local_password = "some_password";
    private $local_dbname = "station_manager";
    private $local_conn ='';

    private $staging_servername = "185.130.207.215";
    private $staging_username = "samuel.j";
    private $staging_password = "Tr-3re@Aza4r";
    private $staging_dbname = "station_manager_demo";
    private $staging_conn ='';

    private $prod_servername = "34.246.63.12";
    private $prod_username = "niyio";
    private $prod_password = "tu@r7r7brA+a";
    private $prod_dbname = "station_manager";
    private $prod_conn ='';




    private $ms_servername = "185.173.25.163";
    private $ms_username = "samuel.j";
    private $ms_password = "P@ssw0rd%%";
    private $ms_dbname = "station_manager";
    private $ms_conn ='';
    
    //$sql = "SELECT id, firstname, lastname FROM MyGuests";
    //$result = $conn->query($sql);

    public function __construct(DatabaseManager $database,Dispatcher $dispatcher)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
  
        $this->conn =   mysqli_connect($this->servername, $this->username, $this->password, $this->dbname);
        $this->local_conn =   mysqli_connect($this->local_servername, $this->local_username, $this->local_password, $this->local_dbname);

        $this->staging_conn =   mysqli_connect($this->staging_servername, $this->staging_username, $this->staging_password, $this->staging_dbname);
      
      $this->prod_conn =   mysqli_connect($this->prod_servername, $this->prod_username, $this->prod_password, $this->prod_dbname);
      
        // Check connection
       if (!$this->conn) {
                 return ("Connection failed: " . mysqli_connect_error());
          }
     //      if (!$this->ms_conn) {
     //            return ("Microsoft Connection failed: ");
     //     }
    }

    public function company_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{
        $exist= Company::where('v1_id', '!=',NULL)->orderBy('created_at', 'DESC')->get()->first();

            if(count($exist) > 0){
                ///compute max milli second
            $date_time = explode(".", ((array)$exist['created_at'])['date'])[0].'.999';
            $sql = "SELECT * FROM companies where datecreated > '".$date_time."'";
                }else {
                  $sql = "SELECT * FROM companies";   # code...
                }
            $result = mysqli_query($this->conn,$sql);
           // return mysqli_fetch_assoc($result);
            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                    //array_push($arr, $row);
                    $date_array = explode(".", $row['datecreated']);
                    $row['datecreated'] = $date_array[0];
                    $counter++;
                    Company::create(['name'=> $row['Companyname'], 'registration_number'=> $row['registrationNo'], 'address'=> $row['HQAddress'], 'city'=> $row['City'] , 'state' => $row['State'], 'logo' => $row['logo'], 'created_at' => $row ['datecreated'], 'updated_at'=> $row['datemodified'], 'v1_user_id'=>strtoupper($row['createdby']), 'company_type' => $row['CompanyType'], 'v1_id'=>strtoupper($row['companyid']), 'country'=>'Nigeria' ]);
                }
            }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
         return $counter;
    }
     public function station_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{
           
           
                 $sql = "SELECT * FROM gas_stations ";
             
           $result = mysqli_query($this->conn,$sql);
            //return mysqli_num_rows($result);
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                    //array_push($arr, $row);
                    $date_array = explode(".", $row['datecreated']);
                    $row['datecreated'] = $date_array[0];
                
            $exist= Station::where('v1_id', $row['stationid'])->get()->first();

            if(count($exist) == 0){
              
                     $station = Company::where('v1_id',$row['companyid'])->get()->first();
              
                    $sql2= "SELECT * FROM company_staff where staffid = '".$row['station_manager']."'";
                    $result2 = mysqli_query($this->conn,$sql2);
                    $result2 = mysqli_fetch_assoc($result2);
                    $counter++;
                    Station::create(['name'=> $row['station_name'], 'v1_id'=> $row['stationid'], 'address'=> $row['address'], 'city'=> $row['city'] , 'state' => $row['state'], 'created_at' => $row ['datecreated'], 'company_id'=>$station['id'], 'is_station_enabled'=>  1, 'manager_name'=>$result2['fullname'] , 'manager_phone'=>$result2['phoneno'],'show_atg_dpk'=> $row['show_atg_dpk'],'show_atg_ago'=> $row['show_atg_ago'],'show_atg_pms'=> $row['show_atg_pms'],'show_fcc_dpk'=> $row['show_fcc_dpk'],'show_fcc_ago'=> $row['show_fcc_ago'],'show_fcc_pms'=> $row['show_fcc_pms'],'show_atg_data'=> $row['show_atg_data'],'show_fcc_data'=> $row['show_fcc_data'],'hasFCC'=> $row['hasFCC'],'hasATG'=> $row['hasATG'],'regionid'=> $row['regionid'],'fcc_oem'=> $row['fcc_oem'],'atg_oem'=> $row['atg_oem'],'daily_pms_target'=> $row['daily_pms_target'],'daily_ago_target'=> $row['daily_ago_target'],'daily_dpk_target'=> $row['daily_dpk_target'],'daystodelivery'=> $row['daystodelivery'],'oem_stationid'=> $row['oem_stationid']]);        
                }
            }
                 # code...
            }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
         return $counter;
    }

      public function role_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{
         
                 $sql = "SELECT * FROM aspnet_Roles";
             
            $result = mysqli_query($this->conn,$sql);
            //return $result;
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                   
                    $company = Company::where('v1_id', '!=',NULL)->get();
                    foreach ($company as $key => $value) {
                    $id= $value['v1_id'];
                    
                    $role = Role::where('company_id', $value['id'])->where('name', $row['RoleName'])->get()->first();
                    if(count($role) == 0){
                        $counter++;
                        //role doesnt exist for company
                    Role::create(['name'=> $row['RoleName'], 'v1_id'=> strtoupper($row['RoleId']),'company_id'=>$value['id'], 'description'=> $row['RoleName']]);    
                        }    
                }
            }
                 # code...
            }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
         return $counter;
    }

     public function user_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{
           $company = Company::where('v1_id', '!=',NULL)->get();
           //return $company['v1_id'];
            foreach ($company as $key => $value) {
            $id= $value['v1_id'];
            
            $sql = "SELECT Email,status, Firstname, Lastname, UserId, LastActivityDate, status FROM aspnetusers where company_manager = '".$id."'";
           
            $result = mysqli_query($this->conn,$sql);
            
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                    //array_push($arr, $row);
                    if($row['UserId'] == null ){
                      $sql3= "SELECT UserId from aspnet_Membership where email = '".$row['Email']."'";
                      $result3 = mysqli_query($this->conn,$sql3);
                      $row3 =mysqli_fetch_assoc($result3);
                      $row['UserId'] = $row3['UserId'];
                    }
                    $date_array = explode(".", $row['LastActivityDate']);
                    $row['LastActivityDate'] = $date_array[0];
                    $auth_key = str_random(6);
                    $password = bcrypt("123456");
                    $user=  User::where('email', $row['Email'])->get()->first();
                    if(count($user) == 0){
                    //doesn't exist
                    $counter++;
                    User::create(['fullname' => $row['Firstname'].' '.$row['Lastname'], 'v1_id'=> $row['UserId'], 'email'=> $row['Email'] , 'username' => $row['Email'], 'created_at' => $row ['LastActivityDate'], 'company_id'=>$value['id'], 'phone_number'=> ' ', 'is_company_set_up'=> 1 ,'is_verified'=> 1, 'auth_key'=> $auth_key, 'status'=> $row['status'], 'password'=> $password ]);  
                        }      
                }
            }
               //return mysqli_num_rows($result);
            
            }
            
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
         return $counter;
    }

     public function user_role_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{
            
                $user = User::where('v1_id', '!=',NULL)->get();
           //return $company['v1_id'];
            foreach ($user as $key => $value) {
            $id= $value['v1_id'];
            
            $sql = "SELECT DISTINCT roleid FROM user_privileges where userid = '".$id."'";
             
            $result = mysqli_query($this->conn,$sql);
           // if(mysqli_num_rows($result) > 1 ){
           //     throw new \Exception("Error double ROle spotted", 1);
                
           // }
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                   
                    $role = Role::where('v1_id', $row['roleid'])->where('company_id', $value['company_id'])->get()->first();
                    if(count($role) > 0 and isset($role['id'])){
                        $counter++;
                        User::where('id', $value['id'])->update(['role_id'=> $role['id']]);    
                        }    
                }
            }
                 # code...
            }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
         return $counter;
    }
/////////run just once
     public function role_perm_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{
            
                $role = Role::where('v1_id', '!=',NULL)->get();
           //return $company['v1_id'];
            foreach ($role as $key => $value) {
            $id= $value['name'];
            
            $sql = "SELECT DISTINCT privilegeName, id FROM role_privileges where roleName = '".$id."'";
             
            $result = mysqli_query($this->conn,$sql);
            // $privs = array();
            // while($row =mysqli_fetch_assoc($result) ){
            //       array_push($privs, $row);
            //   }
            //   return $privs;

            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                  $permission = $row['privilegeName'];
                  if($permission== 'Create User' or $permission== 'Modify User'){
                      $inhouse= 'Create/Modify Users';
                  }else if ($permission== 'Create Price' or $permission== 'Request Price Change'){
                    $inhouse = 'Create Price Change Request';
                  }
                  else if ($permission== 'Modify Price' or $permission== 'Approve Price Change'){
                    $inhouse = 'Approve Price Change Request';
                  }
                  else if ($permission== 'Modify Station'){
                    $inhouse = 'Create/Modify Stations';
                  }
                  else if ($permission== 'Capture Sales / Stock'){
                    $inhouse = 'Capture Sales and Stock';
                  }
                  else if ($permission== 'Modify Sales / Stock'){
                    $inhouse = 'Modify Sales and Stock';
                  }
                  else if ($permission== 'Create Bank Payment' or $permission== 'Modify Bank Payment'){
                    $inhouse = 'Add and Manage Payments';
                  }
                  else if ($permission== 'Create Expense' or $permission== 'Modify Expenses'){
                    $inhouse = 'Add and Manage Expenses';
                  }
                  else if ($permission== 'Analytics - Current Day Sales'){
                    $inhouse = 'Analytics - Current Day Sales';
                  }
                  else if ($permission== 'Analytics - Current Day Stock'){
                    $inhouse = 'Analytics - Current Day Stock';
                  }
                  else if ($permission== 'Analytics - Previous Day Stats'){
                    $inhouse = 'Analytics - Previous Day Stats';
                  }
                   else if ($permission== 'Analytics - Historical Data'){
                    $inhouse = 'Analytics - Historical Data';
                  }
                  else if ($permission== 'Analytics - Expenses'){
                    $inhouse = 'Analytics - Expenses';
                  }
                  else if ($permission== 'Analytics - Reconciliation'){
                    $inhouse = 'Analytics - Reconciliation';
                  }
                  else if ($permission== 'Analytics - Reports'){
                    $inhouse = 'Analytics - Reports';
                  }
                  else if ($permission== 'Analytics - Stock Data'){
                    $inhouse = 'Analytics - Stock Data';
                  }
                  else if ($permission== 'Store - Add Items' or $permission== 'Store - Modify Items'){
                    $inhouse = 'Store- Add/Modify Items';
                  }
                  else if ($permission== 'Store - Stock Manager'){
                    $inhouse = 'Store- Fill/Refill Stock';
                  }
                  

               
                   $perm =Permission::where('name', $inhouse)->get()->first();
                        $counter++;
                    RolePermission::create(['role_id'=> $value['id'], 'permission_id'=> $perm['id'], 'permission_name'=> $perm['name'], 'company_id'=> $value['company_id'], 'v1_id'=> $row['id'] ]);   

                  
                           
                }
                    $others = array();

                if($value['name'] == 'Administrator'){
                array_push($others, "Create/Modify Company");
                array_push($others, "Create/Modify Roles");
                array_push($others, "Setup Station Configuration");
                array_push($others, "Modify Station Configuration");
                array_push($others, "Request Fuel Supply");
                array_push($others, "Approve/Update Fuel Request");

                array_push($others, "Process Fuel Request");
                array_push($others, "Receive Stock");
                array_push($others, "Store- Transfer Stock");
                array_push($others, "Store- Receive Stock");
                array_push($others, "Store- Count Stock");
                }          
                foreach ($others as $key => $value2) {
                     $perm =Permission::where('name', $value2)->get()->first();
                        $counter++;
                    RolePermission::create(['role_id'=> $value['id'], 'permission_id'=> $perm['id'], 'permission_name'=> $perm['name'], 'company_id'=> $value['company_id'], 'v1_id'=> $row['id']]);   
                   } 
            }
                 # code...
            }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
         return $counter;
    }

   public function user_station_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{


                 $sql = "SELECT * FROM usersInstations";
                $result = mysqli_query($this->conn,$sql);
            //return mysqli_num_rows($result);
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                    //array_push($arr, $row);
                    $date_array = explode(".", $row['datecreated']);
                    $row['datecreated'] = $date_array[0];
                    
                     $station = Station::where('v1_id',$row['stationid'])->get()->first();
                 
                    $user= User::where('v1_id', $row['userid'])->get()->first();                     
                      //  return $row['tankgroupid'];
                     if(count($station) > 0 and count($user) > 0){
                      $counter++;
                    $new_pt = StationUsers::create([ 'v1_id'=> $row['id'], 'created_at' => $row ['datecreated'],'company_id'=>$station['company_id'], 'station_id'=>  $station['id'], 'has_access'=> $row['HasAccess'],
                    'company_user_id' => $user['id'] ]);

                }
            
             // return 1;   # code...
            }
            }
        }catch (Exception $exception){
            $this->database->rollBack();
            return $row['pumpgroupid'];
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }

    ////run just once
    public function user_notf_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{


                 $sql = "SELECT * FROM notification_config";
                $result = mysqli_query($this->conn,$sql);
            //return mysqli_num_rows($result);
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                    //array_push($arr, $row);
                    $date_array = explode(".", $row['datecreated']);
                    $row['datecreated'] = $date_array[0];
                    $module = $row['module'];
                  if($module== 'Sales & Stock'){
                      $inhouse= 'Daily Operations Report';
                  }
                  //else if ($module== 'Station League Table'){
                  //  $inhouse = 'Station League Table Report';
                //  }
              //    else if ($module== 'Bank Payments'){
            //        $inhouse = 'Bank Payments Report';
           //       }


                     $notf = NotificationModules::where('name',$inhouse)->get()->first();
                 
                    $user= User::where('v1_id', $row['userid'])->get()->first();                     
                      //  return $row['tankgroupid'];
                     if(count($notf) > 0 and count($user) > 0){
                      $counter++;
                    $new_pt = UserNotifications::create([ 'v1_id'=> $row['ID'], 'created_at' => $row ['datecreated'],'notification_id'=>$notf['id'], 'name'=>  $notf['name'], 'active'=> $row['Active'],
                    'company_user_id' => $user['id'] ]);

                }
            
             // return 1;   # code...
            }
            }
        }catch (Exception $exception){
            $this->database->rollBack();
            return $row['pumpgroupid'];
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }

    
      public function pump_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{


                 $sql = "SELECT * FROM pumps";
                  $result = mysqli_query($this->conn,$sql);
                  //return mysqli_num_rows($result);
                  if (mysqli_num_rows($result) > 0 and $result !=false ) {
                      // output data of each row
                      while($row =mysqli_fetch_assoc($result) ){
                          //array_push($arr, $row);
                          $date_array = explode(".", $row['datecreated']);
                          $row['datecreated'] = $date_array[0];
                          if($row['pump_product'] == 'PMS'){
                          $product = 1;
                           }else if($row['pump_product'] == 'DPK'){
                          $product = 2;
                           }else if($row['pump_product'] == 'AGO'){
                          $product = 3;
                           }

            $exist= Pumps::where('v1_id', $row['iPumpsId'])->get()->first();

            if(count($exist) == 0){
              
                     $station = Station::where('v1_id', $row['stationid'])->get()->first();
                    //$product 
                     if(count($station) > 0){
                     $counter++;
                    Pumps::create(['pump_nozzle_code'=> $row['pump_number'], 'v1_id'=> strtoupper($row['iPumpsId']), 'brand'=> $row['pump_brand'], 'created_at' => $row ['datecreated'], 'product_id' => $product, 'company_id'=>$station['company_id'], 'station_id'=>  $station['id'], 'serial_number'=> $row['serial_number'], 'fcc_pump_nozzle_id'=> $row['oem_pumpId'] ]); 
                  }

                }
            }
                 # code...
           }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }
   public function tank_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{
        $sql = "SELECT * FROM tanks";
            //    }

            $result = mysqli_query($this->conn,$sql);
            //return mysqli_num_rows($result);
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                    //array_push($arr, $row);
                    $date_array = explode(".", $row['datecreated']);
                    $row['datecreated'] = $date_array[0];
                    if($row['product'] == 'PMS'){
                    $product = 1;
                     }else if($row['product'] == 'DPK'){
                    $product = 2;
                     }else if($row['product'] == 'AGO'){
                    $product = 3;
                     }

            $exist= Tanks::where('v1_id', $row['iTanksId'])->get()->first();

            if(count($exist) == 0){
              
                     $station = Station::where('v1_id',$row['stationid'])->get()->first();
                    //$product 
                     $counter++;   
                      if(count($station) > 0){       
                    Tanks::create(['code'=> $row['tank_code'], 'v1_id'=> strtoupper($row['iTanksId']), 'created_at' => $row ['datecreated'], 'product_id' => $product, 'company_id'=>$station['company_id'], 'station_id'=>  $station['id'], 
        'capacity' => $row ['tankcapacity'], 'reorder_volume' => $row ['reordervolume'],'deadstock' => $row ['deadstock'], 'atg_tank_id' => $row ['oem_tankId'],
        'max_water_level' => $row ['maxwaterlevel'], 'type' => $row ['tanktype'] ]); 
                  }
            }
              //return 1;   # code...
           }
         }

            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }
    public function tankgroup_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{


                 $sql = "SELECT * FROM tankgroups";
             $result = mysqli_query($this->conn,$sql);
            //return mysqli_num_rows($result);
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                    //array_push($arr, $row);
                    $date_array = explode(".", $row['datecreated']);
                    $row['datecreated'] = $date_array[0];
                    
                     $station = Station::where('v1_id',$row['stationid'])->get()->first();
                   
                      if(count($station) > 0){
                    $exist2= TankGroups::where('name', $row['tankgroupname'])->where('station_id', $station['id'])->get()->first();                     
                    if(count($exist2) == 0){
                      $counter++;

                    $new_tg = TankGroups::create(['code'=> $row['tankgroupname'], 'v1_id'=> $row['tankgroupid'], 'created_at' => $row ['datecreated'],'company_id'=>$station['company_id'], 'station_id'=>  $station['id'], 
                    'name' => $row ['tankgroupname'] ]); 

                     Tanks::where('code', $row['tankcode'])->where('station_id', $station['id'])->update(['tank_group_id'=> $new_tg['id']]);

                    }else{
                        Tanks::where('code', $row['tankcode'])->where('station_id', $station['id'])->update(['tank_group_id'=> $exist2['id']]);
                    }

                }
          }
              //return 1;   # code...
            }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }

    public function pumpgroup_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{


          $sql = "SELECT * FROM pumpgroups";
            

           $result = mysqli_query($this->conn,$sql);
            //return mysqli_num_rows($result);
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                    //array_push($arr, $row);
                    $date_array = explode(".", $row['datecreated']);
                    $row['datecreated'] = $date_array[0];
                    
                     $station = Station::where('v1_id',$row['stationid'])->get()->first();
                    //$product 
                     if(count($station) > 0){ 
                    $exist2= PumpGroups::where('name', $row['pumpgroupname'])->where('station_id', $station['id'])->get()->first();                     
                    if(count($exist2) == 0){
                      $counter++;
                        //new tankgroup
                    $new_tg = PumpGroups::create(['code'=> $row['pumpgroupname'], 'v1_id'=> $row['pumpgroupid'], 'created_at' => $row ['datecreated'],'company_id'=>$station['company_id'], 'station_id'=>  $station['id'], 
                    'name' => $row ['pumpgroupname'] ]); 



                     Pumps::where('pump_nozzle_code', $row['pumpcode'])->where('station_id', $station['id'])->update(['pump_group_id'=> $new_tg['id']]);

                    }else{
                        Pumps::where('pump_nozzle_code', $row['pumpcode'])->where('station_id', $station['id'])->update(['pump_group_id'=> $exist2['id']]);
                    }

                }
              }
            }
              //return 1;   # code...
                     
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }

    public function p_t_map_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{


                 $sql = "SELECT * FROM Pump_to_Tank_Mapping";
                $result = mysqli_query($this->conn,$sql);
            //return mysqli_num_rows($result);
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                    //array_push($arr, $row);
                    $date_array = explode(".", $row['datecreated']);
                    $row['datecreated'] = $date_array[0];
                    
                     $station = Station::where('v1_id',$row['stationid'])->get()->first();
                    //$product 
                     if(count($station) > 0){ 
                    $exist2= PumpGroupToTankGroup::where('name', $row['ptgroupname'])->where('station_id', $station['id'])->get()->first();                     
                    if(count($exist2) == 0){
                   
                        $pgroup= PumpGroups::where('v1_id', $row['pumpgroupid'])->get()->first();

                     $tgroup= TankGroups::where('v1_id', $row['tankgroupid'])->get()->first();
                      //  return $row['tankgroupid'];
                     if(count($pgroup) > 0 and count($tgroup) > 0){
                      $counter++;
                    $new_pt = PumpGroupToTankGroup::create(['name'=> $row['ptgroupname'], 'v1_id'=> $row['ptgroupid'], 'created_at' => $row ['datecreated'],'company_id'=>$station['company_id'], 'station_id'=>  $station['id'], 
                    'pump_group_id' => $pgroup['id'] , 'tank_group_id' => $tgroup['id'] ]);
                         
}}}
                }
            
             // return 1;   # code...
            }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            return $row['pumpgroupid'];
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }

    public function preadings_update_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        //return $counter; 
        try{
            
                 $sql = "SELECT * FROM daily_totalizer_reading where reading_date >= '2018-05-31 00:00:00'";
            
            $result = mysqli_query($this->conn,$sql);
           // return mysqli_num_rows($result);
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                    //array_push($arr, $row);
                  $data_exist= DailyTotalizerReadings::where('v1_id', $row['iDailyTotalizerReadingId'])->get()->first();
                  if(count($data_exist) > 0){
                      if($data_exist['status']== 'Open' and $row['status'] == 'Closed'){
                        DailyTotalizerReadings::where('v1_id', $row['iDailyTotalizerReadingId'])->update([ 'status'=>  $row['status'], 'open_shift_totalizer_reading'=> $row['opening_shift_totalizer_reading'], 'shift_1_totalizer_reading'=> $row['shift_1_totalizer_reading'], 'shift_2_totalizer_reading' => $row ['shift_2_totalizer_reading'],'close_shift_totalizer_reading'=>$row['closing_shift_totalizer_reading'], 'shift_1_cash_collected'=>  $row['shift_1_cash_collected'], 
                    'shift_2_cash_collected' => $row ['shift_2_cash_collected'],'cash_collected'=>$row['cash_collected'], 'ppv'=>  $row['PPV'], 'reading_date'=>  $row['reading_date'], 
                    'shift_2_cash_collected' => $row ['shift_2_cash_collected'] ]);
                      }
                      continue;
                  }

                    $date_array = explode(".", $row['datecreated']);
                    $row['datecreated'] = $date_array[0];
                     $station = Pumps::where('v1_id',$row['pumpid'])->get()->first();
                   
                      if(count($station) > 0){
                    $user = User::where('v1_id' , $row['createdby'])->get()->first();
                   
                     $counter++;
                   

                    $new_tg = DailyTotalizerReadings::create(['company_id'=> $station['company_id'], 'v1_id'=> $row['iDailyTotalizerReadingId'], 'station_id' => $station ['station_id'],'pump_id'=>$station['id'], 'status'=>  $row['status'], 
                    'nozzle_code' => $station ['pump_nozzle_code'],'open_shift_totalizer_reading'=> $row['opening_shift_totalizer_reading'], 'shift_1_totalizer_reading'=> $row['shift_1_totalizer_reading'], 'shift_2_totalizer_reading' => $row ['shift_2_totalizer_reading'],'close_shift_totalizer_reading'=>$row['closing_shift_totalizer_reading'], 'shift_1_cash_collected'=>  $row['shift_1_cash_collected'],'created_by' => $user['id'], 
                    'shift_2_cash_collected' => $row ['shift_2_cash_collected'],'cash_collected'=>$row['cash_collected'], 'ppv'=>  $row['PPV'], 'created_at'=>  $row['datecreated'], 'reading_date'=>  $row['reading_date'], 
                    'shift_2_cash_collected' => $row ['shift_2_cash_collected'] ]); 
                  }

                
              
            }
              //return 1;   # code...
            }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }

     public function treadings_update_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{

      $sql = "SELECT * FROM daily_stock_readings where reading_date >= '2018-05-31 00:00:00' ";
            
            $result = mysqli_query($this->conn,$sql);
            //return mysqli_num_rows($result);
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                    //array_push($arr, $row);
                  $data_exist= DailyStockReadings::where('v1_id', $row['iDailyStockReadingsId'])->get()->first();
                  if(count($data_exist) > 0){
                      if($data_exist['status']== 'Open' and $row['status'] == 'Closed'){
                        DailyStockReadings::where('v1_id', $row['iDailyStockReadingsId'])->update([ 'v1_id'=> $row['iDailyStockReadingsId'], 'status'=>  $row['status'], 'reading_date'=>  $row['reading_date'],  'phy_shift_end_volume_reading' => $row['phy_shift_end_volume_reading'],'phy_shift_start_volume_reading' => $row['phy_shift_start_volume_reading'],'return_to_tank'=>$row['return_to_tank'],
                        'end_delivery'=>$row['end_delivery'] ]);
                      }
                      continue;
                  }
                    $date_array = explode(".", $row['datecreated']);
                    $row['datecreated'] = $date_array[0];
                     $station = Tanks::where('v1_id',$row['tankid'])->get()->first();
                   
                      if(count($station) > 0){
                   $user = User::where('v1_id' , $row['createdby'])->get()->first();
                     $counter++;

                    $new_tg = DailyStockReadings::create(['company_id'=> $station['company_id'], 'v1_id'=> $row['iDailyStockReadingsId'], 'station_id' => $station ['station_id'],'tank_id'=>$station['id'], 'status'=>  $row['status'], 
                    'tank_code' => $station ['code'], 'created_by'=> $user['id'], 'created_at'=>  $row['datecreated'], 'reading_date'=>  $row['reading_date'],  'phy_shift_end_volume_reading' => $row['phy_shift_end_volume_reading'],'phy_shift_start_volume_reading' => $row['phy_shift_start_volume_reading'],'return_to_tank'=>$row['return_to_tank'],
                        'end_delivery'=>$row['end_delivery'] ]); 
                }
            }
              //return 1;   # code...
            }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }
    public function preadings_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{
            
          $sql = "SELECT * FROM daily_totalizer_reading";
            
            $result = mysqli_query($this->conn,$sql);
            //return mysqli_num_rows($result);
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                    //array_push($arr, $row);
                    $date_array = explode(".", $row['datecreated']);
                    $row['datecreated'] = $date_array[0];
                     $station = Pumps::where('v1_id',$row['pumpid'])->get()->first();
                   
                      if(count($station) > 0){
                    $user = User::where('v1_id' , $row['createdby'])->get()->first();
                   
                     $counter++;

                    $new_tg = DailyTotalizerReadings::create(['company_id'=> $station['company_id'], 'v1_id'=> $row['iDailyTotalizerReadingId'], 'station_id' => $station ['station_id'],'pump_id'=>$station['id'], 'status'=>  $row['status'], 
                    'nozzle_code' => $station ['pump_nozzle_code'],'open_shift_totalizer_reading'=> $row['opening_shift_totalizer_reading'], 'shift_1_totalizer_reading'=> $row['shift_1_totalizer_reading'], 'shift_2_totalizer_reading' => $row ['shift_2_totalizer_reading'],'close_shift_totalizer_reading'=>$row['closing_shift_totalizer_reading'], 'shift_1_cash_collected'=>  $row['shift_1_cash_collected'],'created_by' => $user['id'], 
                    'shift_2_cash_collected' => $row ['shift_2_cash_collected'],'cash_collected'=>$row['cash_collected'], 'ppv'=>  $row['PPV'], 'created_at'=>  $row['datecreated'], 'reading_date'=>  $row['reading_date'], 
                    'shift_2_cash_collected' => $row ['shift_2_cash_collected'] ]); 
                  }

                
              
            }
              //return 1;   # code...
            }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }

     public function treadings_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{

      $sql = "SELECT * FROM daily_stock_readings";
            
            $result = mysqli_query($this->conn,$sql);
            //return mysqli_num_rows($result);
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                    //array_push($arr, $row);
                    $date_array = explode(".", $row['datecreated']);
                    $row['datecreated'] = $date_array[0];
                     $station = Tanks::where('v1_id',$row['tankid'])->get()->first();
                   
                      if(count($station) > 0){
                   $user = User::where('v1_id' , $row['createdby'])->get()->first();
                     $counter++;

                    $new_tg = DailyStockReadings::create(['company_id'=> $station['company_id'], 'v1_id'=> $row['iDailyStockReadingsId'], 'station_id' => $station ['station_id'],'tank_id'=>$station['id'], 'status'=>  $row['status'], 
                    'tank_code' => $station ['code'], 'created_by'=> $user['id'], 'created_at'=>  $row['datecreated'], 'reading_date'=>  $row['reading_date'],  'phy_shift_end_volume_reading' => $row['phy_shift_end_volume_reading'],'phy_shift_start_volume_reading' => $row['phy_shift_start_volume_reading'],'return_to_tank'=>$row['return_to_tank'],
                        'end_delivery'=>$row['end_delivery'], ]); 
                }
            }
              //return 1;   # code...
            }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }
     public function ptt_product_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{

      $sql2 = DailyStockReadings::with('tank.product')->where('product', null)->get(['id', 'tank_id']);   
          foreach ($sql2 as $key => $value) {
                 $product_code = $value['tank']['product']['code'];
              $new_tg = DailyStockReadings::where('id', $value['id'])->update(['product'=>$product_code]);
            }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }
    public function ptp_product_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{

      $sql = DailyTotalizerReadings::with('pump.product')->where('product', null)->get(['id', 'pump_id']);   
          foreach ($sql as $key => $value) {
                 $product_code = $value['pump']['product']['code'];
              $new_tg = DailyTotalizerReadings::where('id', $value['id'])->update(['product'=>$product_code]);
            }

            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }

    public function pp_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{
        $sql = "SELECT * FROM product_prices";
            //    }

            $result = mysqli_query($this->conn,$sql);
            //return mysqli_num_rows($result);
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                    //array_push($arr, $row);
                    $date_array = explode(".", $row['datecreated']);
                    $row['datecreated'] = $date_array[0];
                    if($row['product'] == 'PMS'){
                    $product = 1;
                     }else if($row['product'] == 'DPK'){
                    $product = 2;
                     }else if($row['product'] == 'AGO'){
                    $product = 3;
                     }

            $exist= ProductPrices::where('v1_id', $row['id'])->get()->first();

            if(count($exist) == 0){
              
                     $station = Station::where('v1_id',$row['stationid'])->get()->first();
                    //$product 
                     $counter++;   
                      if(count($station) > 0){       
                    ProductPrices::create(['new_price_tag'=> $row['new_ppv'], 'v1_id'=> strtoupper($row['id']), 'created_at' => $row ['datecreated'], 'product_id' => $product, 'company_id'=>$station['company_id'], 'station_id'=>  $station['id'], 'product' => $row ['product'] ]); 
                  }
            }
              //return 1;   # code...
           }
         }

            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }

       public function pplog_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{
        $sql = "SELECT * FROM price_change_log";
            //    }

            $result = mysqli_query($this->conn,$sql);
            //return mysqli_num_rows($result);
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                    //array_push($arr, $row);
                    $date_array = explode(".", $row['datecreated']);
                    $row['datecreated'] = $date_array[0];
                    if($row['product'] == 'PMS'){
                    $product = 1;
                     }else if($row['product'] == 'DPK'){
                    $product = 2;
                     }else if($row['product'] == 'AGO'){
                    $product = 3;
                     }

            $exist= ProductChangeLogs::where('v1_id', $row['id'])->get()->first();

            if(count($exist) == 0){
              
                     $station = Station::where('v1_id',$row['stationid'])->get()->first();
                     $user = User::where('v1_id' , $row['createdby'])->get()->first();
                    //$product 
                     $counter++;   
                      if(count($station) > 0){       
                    ProductChangeLogs::create(['requested_price_tag'=> $row['new_ppv'], 'current_price_tag'=> $row['old_ppv'], 'v1_id'=> strtoupper($row['id']), 'created_at' => $row ['datecreated'], 'product_id' => $product, 'company_id'=>$station['company_id'], 'station_id'=>  $station['id'], 'product' => $row ['product'], 'updated_by'=> $user['id'] ]); 
                  }
            }
              //return 1;   # code...
           }
         }

            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }

    public function deposits_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{


                 $sql = "SELECT * FROM cash_bank_deposits";
                  $result = mysqli_query($this->conn,$sql);
                  //return mysqli_num_rows($result);
                  if (mysqli_num_rows($result) > 0 and $result !=false ) {
                      // output data of each row
                      while($row =mysqli_fetch_assoc($result) ){
                          //array_push($arr, $row);
                          $date_array = explode(".", $row['datecreated']);
                          $row['datecreated'] = $date_array[0];
                         

            $exist= Deposits::where('v1_id', $row['ID'])->get()->first();

            if(count($exist) == 0){           
                     $station = Station::where('v1_id', $row['stationid'])->get()->first();
                     $user = User::where('v1_id' , $row['createdby'])->get()->first();
                    //$product 
                     if(count($station) > 0){
                     $counter++;
                    Deposits::create(['reading_date'=> $row['reading_date'], 'v1_id'=> strtoupper($row['ID']), 'teller_date'=> $row['teller_date'], 'created_at' => $row ['datecreated'], 'company_id'=>$station['company_id'], 'station_id'=>  $station['id'], 'teller_number'=> $row['bank_teller_no'], 'account_number'=> $row['bank_account_no'],'bank'=> $row['bank_name'], 'amount'=> $row['amount_deposited'], 'payment_type'=> $row['bank_deposit_type'] , 'pos_receipt_number'=> $row['pos_receipt_no'] , 'pos_receipt_range'=> $row['pos_receipt_range'], 'created_by'=> $user['id'] ]); 
                  }

                }
            }
                 # code...
           }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }

       public function expense_header_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{


                 $sql = "SELECT * FROM expense_header";
                  $result = mysqli_query($this->conn,$sql);
                  //return mysqli_num_rows($result);
                  if (mysqli_num_rows($result) > 0 and $result !=false ) {
                      // output data of each row
                      while($row =mysqli_fetch_assoc($result) ){
                          //array_push($arr, $row);
                          $date_array = explode(".", $row['datecreated']);
                          $row['datecreated'] = $date_array[0];
                         //return $row['datecreated'];

            $exist= ExpenseHeader::where('v1_id', $row['expenseId'])->get()->first();

            if(count($exist) == 0){
              
                     $station = Station::where('v1_id', $row['stationid'])->get()->first();
                     $user = User::where('v1_id' , $row['createdby'])->get()->first();
                    //$product 
                     if(count($station) > 0){
                     $counter++;
                    ExpenseHeader::create([
           'expense_code' => $row['expensecode'],
            'created_by' => $user['id'],
            'company_id'=> $station['company_id'],
            'station_id' => $station['id'],          
            'expense_date' => $row['expensedate'],
            'total_amount' => $row['totalexpenseamount'],
             'v1_id' => $row['expenseId'],
             'created_at'=> $row['datecreated'],
              ]); 
                  }

                }
            }
                 # code...
           }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }
        public function expense_items_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{


                 $sql = "SELECT * FROM expense_items";
                  $result = mysqli_query($this->conn,$sql);
                  //return mysqli_num_rows($result);
                  if (mysqli_num_rows($result) > 0 and $result !=false ) {
                      // output data of each row
                      while($row =mysqli_fetch_assoc($result) ){
                          //array_push($arr, $row);
                          $date_array = explode(".", $row['datecreated']);
                          $row['datecreated'] = $date_array[0];
                         

            $exist= ExpenseItems::where('v1_id', $row['itemid'])->get()->first();

            if(count($exist) == 0){
                    $header= ExpenseHeader::where('v1_id', $row['expenseid'])->get()->first();
                    $user = User::where('v1_id' , $row['createdby'])->get()->first();
                    //$product 
                     if(count($header) == 1){
                     $counter++;
                    ExpenseItems::create([
          'expense_id'=> $header['id'],'created_by'=> $user['id'],'unit_amount'=> $row['unitamount'],'total_amount'=> $row['totalamount'],'quantity'=> $row['quantity'],'expense_type'=> $row['expensetype'],'proof_of_payment'=> $row['proofofpayment'],'approved'=> $row['approved'],'item_code'=> $row['itemcode'],'item_description'=> $row['itemdescription'],'v1_id'=> $row['itemid'], 'created_at'=> $row['datecreated']
              ]); 
                  }

                }
            }
                 # code...
           }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }

      public function items_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{


                 $sql = "SELECT * FROM items";
                  $result = mssql_query($this->conn,$sql);
                  //return mysqli_num_rows($result);
                  if (mssql_num_rows($result) > 0 and $result !=false ) {
                      // output data of each row
                      while($row =mssql_fetch_assoc($result) ){
                          //array_push($arr, $row);
                          $date_array = explode(".", $row['datecreated']);
                          $row['datecreated'] = $date_array[0];
                         //return $row['datecreated'];

            $exist= Items::where('v1_id', $row['expenseId'])->get()->first();

            if(count($exist) == 0){
              
                     $station = Station::where('v1_id', $row['stationid'])->get()->first();
                     $user = User::where('v1_id' , $row['createdby'])->get()->first();
                    //$product 
                     if(count($station) > 0){
                     $counter++;
                    ExpenseHeader::create([
            'created_by' => $user['id'],
            'company_id'=> $station['company_id'],
            'station_id' => $station['id'],          
            
             'v1_id' => $row['expenseId'],
             'created_at'=> $row['datecreated'],
             'description'=> $row['description'],'created_by'=> $row['datecreated'],'parentsku'=> $row['parentsku'],'hasvariants'=> $row['hasvariants'], 'name'=> $row['itemname'], 'category'=> $row['category'], 'status'=> $row['status'], 'brand'=> $row['brand'], 'uom'=> $row['uom'], 'modified_by'=> $row['modifiedby'], 'active'=> $row['active'], 'v1_id'=> $row['itemid']
              ]); 
                  }

                }
            }
                 # code...
           }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }


public function get_up_to_date_readings_of_a_reliable_station_for_demo(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{
        $sql = "SELECT * FROM daily_stock_readings where station_id = 13";    
        $result = mysqli_query($this->prod_conn,$sql);
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){    
                      if(true){
                     $counter++;         
                $insert = "INSERT INTO daily_stock_readings ('company_id', 'station_id', 'tank_id', 'status', 'tank_code', 'created_at', 'reading_date', 'phy_shift_end_volume_reading' ,
                                 'phy_shift_start_volume_reading', 'product', 'return_to_tank', 'end_delivery' ) VALUES ( 1, 1, '".$row['tank_id']."', '".$row['status']."', '".$row['tank_code']."', '".$row['created_at']."', '".$row['reading_date']."', '".$row['phy_shift_end_volume_reading']."', '".$row['phy_shift_start_volume_reading']."', '".$row['product']."', '".$row['return_to_tank']."', '".$row['end_delivery']."' ) ";

                  $result2 = mysqli_query($this->prod_conn,$insert);
                }
            }
           }

           $sql = "SELECT * FROM daily_totalizer_readings where station_id = 13";    
        $result = mysqli_query($this->prod_conn,$sql);
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){    
                      if(true){
                     $counter++;         
                $insert = "INSERT INTO daily_totalizer_readings ('company_id', 'station_id', 'pump_id', 'status', 'nozzle_code', 'created_at', 'reading_date', 'open_shift_totalizer_reading' ,
                                 'close_shift_totalizer_reading', 'product', 'ppv', 'cash_collected' ) VALUES ( 1, 1, '".$row['pump_id']."', '".$row['status']."', '".$row['nozzle_code']."', '".$row['created_at']."', '".$row['reading_date']."', '".$row['open_shift_totalizer_reading']."', '".$row['close_shift_totalizer_reading']."', '".$row['product']."', '".$row['ppv']."', '".$row['cash_collected']."' ) ";

                  $result2 = mysqli_query($this->prod_conn,$insert);
                }
            }
           }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }
   
 


   
public function get_up_to_sales_stock_for_demo_station(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{
        $sql = "SELECT * FROM physical_ee_sales_stock where stationid = 188 and transactiondate > '2018-09-01'";    
        $result = mysqli_query($this->prod_conn,$sql);
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){    
                      if(true){
                     $counter++;         
                // $insert = "INSERT INTO daily_stock_readings ('company_id', 'station_id', 'tank_id', 'status', 'tank_code', 'created_at', 'reading_date', 'phy_shift_end_volume_reading' ,
                //                  'phy_shift_start_volume_reading', 'product', 'return_to_tank', 'end_delivery' ) VALUES ( 1, 1, '".$row['tank_id']."', '".$row['status']."', '".$row['tank_code']."', '".$row['created_at']."', '".$row['reading_date']."', '".$row['phy_shift_end_volume_reading']."', '".$row['phy_shift_start_volume_reading']."', '".$row['product']."', '".$row['return_to_tank']."', '".$row['end_delivery']."' ) ";

                //   $result2 = mysqli_query($this->prod_conn,$insert);
                }
            }
           }

      
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }
   
       
}