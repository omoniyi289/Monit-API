<?php

namespace App\Initializers;
use App\Reposities\CompanyRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use App\User;
use App\Models\UserNotifications;

ini_set('max_execution_time', 80000); 
class UserNotfCompanyIdSetter
{

    public function __construct(DatabaseManager $database,Dispatcher $dispatcher)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
    }

     public function unc_setter(){
        $this->database->beginTransaction();
        $counter = 0;
        try{
          $user = User::get(['id', 'company_id']);  
          foreach ($user as $key => $value) {
              UserNotifications::where('company_user_id', $value['id'])->update([ 'company_id' => $value['company_id'] ]);
              $counter++;

          }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }
   
 
       
}