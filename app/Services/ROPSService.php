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
use App\Models\ROPS;

class ROPSService
{
    private $database;
    private $rops_repository;

    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }
    public function create(array $data) {
        $this->database->beginTransaction();
        try{
           // return $data;
            $data['survey_date']= date_format(date_create($data['survey_date']),"Y-m-d");
            //date_format(date_create($params['selected_date']),"Y-m-d")
            $survey = ROPS::create($data);
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
         return ROPS::where('id', $survey['id'])->get()->first();
    }

    public function get_by_date($date){
        return ROPS::where('survey_date',$date)->get();
    }
  
      public function get_by_params($params)
    {
       // return $params;
        if($params['station_id'] !=null && $params['survey_date'] != null){
            $survey_date = date_format(date_create($params['survey_date']),"Y-m-d");
            return ROPS::where('survey_date',$survey_date)->where('station_id',$params['station_id'])->get();
            }
    }
   
    
}