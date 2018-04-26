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
use App\TankGroups;
use App\PumpGroups;
use App\PumpGroupToTankGroup;
use App\Models\DailyStockReadings;
use App\Models\DailyTotalizerReadings;

ini_set('max_execution_time', 80000); 
class MigrationService
{
    private $database;
    private $dispatcher;
    private $servername = "185.130.207.215";
    private $username = "samuel.j";
    private $password = "Tr-3re@Aza4r";
    private $dbname = "station_manager_1";
    private $conn ='';
    
    //$sql = "SELECT id, firstname, lastname FROM MyGuests";
    //$result = $conn->query($sql);

    public function __construct(DatabaseManager $database,Dispatcher $dispatcher)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
       // $this->company_repository = $company_repository;
        // Create connection
        $this->conn =   mysqli_connect($this->servername, $this->username, $this->password, $this->dbname);
        // Check connection
       if (!$this->conn) {
                 return ("Connection failed: " . mysqli_connect_error());
}
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
            
            $sql = "SELECT Email, Firstname, Lastname, UserId, LastActivityDate FROM aspnetusers where company_manager = '".$id."'";
             
            $result = mysqli_query($this->conn,$sql);
            
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                    //array_push($arr, $row);
                    $date_array = explode(".", $row['LastActivityDate']);
                    $row['LastActivityDate'] = $date_array[0];
                    $auth_key = str_random(6);
                    $password = bcrypt("123456");
                    $user=  User::where('email', $row['Email'])->get()->first();
                    if(count($user) == 0){
                    //doesn't exist
                    $counter++;
                    User::create(['fullname' => $row['Firstname'].' '.$row['Lastname'], 'v1_id'=> $row['UserId'], 'email'=> $row['Email'] , 'username' => $row['Email'], 'created_at' => $row ['LastActivityDate'], 'company_id'=>$value['id'], 'phone_number'=> ' ', 'is_company_set_up'=> 1 ,'is_verified'=> 1, 'auth_key'=> $auth_key, 'password'=> $password ]);  
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
    
      public function pump_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{


           /*$station = Station::where('v1_id', '!=',NULL)->get();
           //return $company['v1_id'];
            foreach ($station as $key => $value) {
              $id= $value['v1_id'];
              ///track last update
            $exist= Pumps::where('station_id', $value['id'])->orderBy('created_at', 'DESC')->get()->first();

            if(count($exist) > 0){
                ///compute max milli second
             $date_time = explode(".", ((array)$exist['created_at'])['date'])[0].'.999';
             $sql = "SELECT * FROM pumps where stationid = '".$id."' AND datecreated > '".$date_time."'";
                }else {*/
                 $sql = "SELECT * FROM pumps";
            //    }

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

                     Tanks::where('code', $row['tankcode'])->update(['tank_group_id'=> $new_tg['id']]);

                    }else{
                        Tanks::where('code', $row['tankcode'])->update(['tank_group_id'=> $exist2['id']]);
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

                     Pumps::where('pump_nozzle_code', $row['pumpcode'])->update(['pump_group_id'=> $new_tg['id']]);

                    }else{
                        Pumps::where('pump_nozzle_code', $row['pumpcode'])->update(['pump_group_id'=> $exist2['id']]);
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

    public function preadings_migrate(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{


           $pump = Pumps::where('v1_id', '!=',NULL)->get();
           $sql = "SELECT * FROM daily_totalizer_reading";
           $result = mysqli_query($this->conn,$sql);
           return $row =mysqli_num_rows($result);
           //return $company['v1_id'];
            foreach ($pump as $key => $value) {
              $id= $value['v1_id'];
              ///track last update
            
                 $sql = "SELECT * FROM daily_totalizer_reading where pumpid = '".$id."'";
            
            $result = mysqli_query($this->conn,$sql);
            //return mysqli_num_rows($result);
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                    //array_push($arr, $row);
                    $date_array = explode(".", $row['datecreated']);
                    $row['datecreated'] = $date_array[0];
                    $user = User::where('v1_id' , $row['createdby'])->get()->first();
                   
                     $counter++;

                    $new_tg = DailyTotalizerReadings::create(['company_id'=> $value['company_id'], 'v1_id'=> $row['iDailyTotalizerReadingId'], 'station_id' => $value ['station_id'],'pump_id'=>$value['id'], 'status'=>  $row['status'], 
                    'nozzle_code' => $value ['pump_nozzle_code'],'open_shift_totalizer_reading'=> $value['opening_shift_totalizer_reading'], 'shift_1_totalizer_reading'=> $row['shift_1_totalizer_reading'], 'shift_2_totalizer_reading' => $row ['shift_2_totalizer_reading'],'close_shift_totalizer_reading'=>$row['closing_shift_totalizer_reading'], 'shift_1_cash_collected'=>  $row['shift_1_cash_collected'],'created_by' => $user['id'], 
                    'shift_2_cash_collected' => $row ['shift_2_cash_collected'],'cash_collected'=>$row['cash_collected'], 'ppv'=>  $row['PPV'], 'created_at'=>  $row['datecreated'], 
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


           $pump = Tanks::where('v1_id', '!=',NULL)->get();
           //$pump = Pumps::where('v1_id', '!=',NULL)->get();
           $sql = "SELECT * FROM daily_stock_readings";
           $result = mysqli_query($this->conn,$sql);
           return $row =mysqli_num_rows($result);
           //return $company['v1_id'];
            foreach ($pump as $key => $value) {
              $id= $value['v1_id'];
              ///track last update
            
                 $sql = "SELECT * FROM daily_stock_readings where tankid = '".$id."'";
            
            $result = mysqli_query($this->conn,$sql);
            //return mysqli_num_rows($result);
            if (mysqli_num_rows($result) > 0 and $result !=false ) {
                // output data of each row
                while($row =mysqli_fetch_assoc($result) ){
                    //array_push($arr, $row);
                    $date_array = explode(".", $row['datecreated']);
                    $row['datecreated'] = $date_array[0];
                   $user = User::where('v1_id' , $row['createdby'])->get()->first();
                     $counter++;

                    $new_tg = DailyStockReadings::create(['company_id'=> $value['company_id'], 'v1_id'=> $row['iDailyStockReadingsId'], 'station_id' => $value ['station_id'],'tank_id'=>$value['id'], 'status'=>  $row['status'], 
                    'tank_code' => $value ['tank_code'], 'created_by'=> $user['id'], 'created_at'=>  $row['datecreated'], 'phy_shift_end_volume_reading' => $row['phy_shift_end_volume_reading'],'phy_shift_start_volume_reading' => $row['phy_shift_start_volume_reading'],'return_to_tank'=>$row['return_to_tank'],
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
       
}