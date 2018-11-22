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
use App\Models\COPS;
use App\Events\COPSGenerated;
use App\Models\UsersInStations_Details;
use App\Station;
class COPSService
{
    private $database;
    private $COPS_repository;

    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }
    public function create(array $data) {
        $this->database->beginTransaction();
        $inserted_COPS = '';
        try{
               // return $data;
                $survey_date = date_format(date_create($data['survey_date']),"Y-m-d");
                $company_id = $data['company_id'];
                $station_id = $data['station_id'];
                //date_format(date_create($params['selected_date']),"Y-m-d")

                $uploaded_by = $data['uploaded_by'];

                for($i= 0; $i < $data['competitor_frequency']; $i++) {

               isset($data['competitor_name'][$i]) ? $competitor_name = $data['competitor_name'][$i] : $competitor_name = '';

               isset($data['competitor_omp_pms'][$i]) ? $competitor_omp_pms = $data['competitor_omp_pms'][$i] : $competitor_omp_pms = '';

               isset($data['competitor_omp_ago'][$i]) ? $competitor_omp_ago = $data['competitor_omp_ago'][$i] : $competitor_omp_ago = '';

               isset($data['competitor_omp_dpk'][$i]) ? $competitor_omp_dpk = $data['competitor_omp_dpk'][$i] : $competitor_omp_dpk = '';

               isset($data['competitor_omp_lube'][$i]) ? $competitor_omp_lube = $data['competitor_omp_lube'][$i] : $competitor_omp_lube = '';

               isset($data['competitor_omp_lpg'][$i]) ? $competitor_omp_lpg = $data['competitor_omp_lpg'][$i] : $competitor_omp_lpg = '';


               isset($data['competitor_company_pms'][$i]) ? $competitor_company_pms = $data['competitor_company_pms'][$i] : $competitor_company_pms = '';

               isset($data['competitor_company_ago'][$i]) ? $competitor_company_ago = $data['competitor_company_ago'][$i] : $competitor_company_ago = '';

               isset($data['competitor_company_dpk'][$i]) ? $competitor_company_dpk = $data['competitor_company_dpk'][$i] : $competitor_company_dpk = '';

               isset($data['competitor_company_lube'][$i]) ? $competitor_company_lube = $data['competitor_company_lube'][$i] : $competitor_company_lube = '';

               isset($data['competitor_company_lpg'][$i]) ? $competitor_company_lpg = $data['competitor_company_lpg'][$i] : $competitor_company_lpg = '';
               if($competitor_name != ''){
               COPS::create(['company_id' => $company_id, 'station_id' => $station_id, 'uploaded_by' => $uploaded_by, 'survey_date' => $survey_date, 'competitor' => $competitor_name, 'omp_pms' => $competitor_omp_pms , 'company_pms' => $competitor_company_pms , 'omp_ago' => $competitor_omp_ago , 'company_ago' => $competitor_company_ago, 'omp_dpk' => $competitor_omp_dpk, 'company_dpk' => $competitor_company_dpk, 'omp_lube' => $competitor_omp_lube , 'company_lube' => $competitor_company_lube, 'omp_lpg' => $competitor_omp_lpg, 'company_lpg' => $competitor_company_lpg]);
                }

             }

             for($i= 0; $i < $data['d2d_frequency']; $i++) {
               isset($data['d2d_name'][$i]) ? $d2d_name = $data['d2d_name'][$i] : $d2d_name = '';

               isset($data['d2d_omp_pms'][$i]) ? $d2d_omp_pms = $data['d2d_omp_pms'][$i] : $d2d_omp_pms = '';

               isset($data['d2d_omp_ago'][$i]) ? $d2d_omp_ago = $data['d2d_omp_ago'][$i] : $d2d_omp_ago = '';

               isset($data['d2d_omp_dpk'][$i]) ? $d2d_omp_dpk = $data['d2d_omp_dpk'][$i] : $d2d_omp_dpk = '';

               isset($data['d2d_omp_lube'][$i]) ? $d2d_omp_lube = $data['d2d_omp_lube'][$i] : $d2d_omp_lube = '';

               isset($data['d2d_omp_lpg'][$i]) ? $d2d_omp_lpg = $data['d2d_omp_lpg'][$i] : $d2d_omp_lpg = '';


               isset($data['d2d_company_pms'][$i]) ? $d2d_company_pms = $data['d2d_company_pms'][$i] : $d2d_company_pms = '';

               isset($data['d2d_company_ago'][$i]) ? $d2d_company_ago = $data['d2d_company_ago'][$i] : $d2d_company_ago = '';

               isset($data['d2d_company_dpk'][$i]) ? $d2d_company_dpk = $data['d2d_company_dpk'][$i] : $d2d_company_dpk = '';

               isset($data['d2d_company_lube'][$i]) ? $d2d_company_lube = $data['d2d_company_lube'][$i] : $d2d_company_lube = '';

               isset($data['d2d_company_lpg'][$i]) ? $d2d_company_lpg = $data['d2d_company_lpg'][$i] : $d2d_company_lpg = '';
                if($d2d_name != ''){
               COPS::create(['company_id' => $company_id, 'station_id' => $station_id, 'uploaded_by' => $uploaded_by, 'survey_date' => $survey_date, 'd2d' => $d2d_name, 'omp_pms' => $d2d_omp_pms , 'company_pms' => $d2d_company_pms , 'omp_ago' => $d2d_omp_ago , 'company_ago' => $d2d_company_ago, 'omp_dpk' => $d2d_omp_dpk, 'company_dpk' => $d2d_company_dpk, 'omp_lube' => $d2d_omp_lube , 'company_lube' => $d2d_company_lube, 'omp_lpg' => $d2d_omp_lpg, 'company_lpg' => $d2d_company_lpg]);
                }
             }

             for($i= 0; $i < $data['location_frequency']; $i++) {
               isset($data['location_name'][$i]) ? $location_name = $data['location_name'][$i] : $location_name = '';

               isset($data['location_omp_pms'][$i]) ? $location_omp_pms = $data['location_omp_pms'][$i] : $location_omp_pms = '';

               isset($data['location_omp_ago'][$i]) ? $location_omp_ago = $data['location_omp_ago'][$i] : $location_omp_ago = '';

               isset($data['location_omp_dpk'][$i]) ? $location_omp_dpk = $data['location_omp_dpk'][$i] : $location_omp_dpk = '';

               isset($data['location_omp_lube'][$i]) ? $location_omp_lube = $data['location_omp_lube'][$i] : $location_omp_lube = '';

               isset($data['location_omp_lpg'][$i]) ? $location_omp_lpg = $data['location_omp_lpg'][$i] : $location_omp_lpg = '';


               isset($data['location_company_pms'][$i]) ? $location_company_pms = $data['location_company_pms'][$i] : $location_company_pms = '';

               isset($data['location_company_ago'][$i]) ? $location_company_ago = $data['location_company_ago'][$i] : $location_company_ago = '';

               isset($data['location_company_dpk'][$i]) ? $location_company_dpk = $data['location_company_dpk'][$i] : $location_company_dpk = '';

               isset($data['location_company_lube'][$i]) ? $location_company_lube = $data['location_company_lube'][$i] : $location_company_lube = '';

               isset($data['location_company_lpg'][$i]) ? $location_company_lpg = $data['location_company_lpg'][$i] : $location_company_lpg = '';
                if($location_name != ''){
               COPS::create(['company_id' => $company_id, 'station_id' => $station_id, 'uploaded_by' => $uploaded_by, 'survey_date' => $survey_date, 'location' => $location_name, 'omp_pms' => $location_omp_pms , 'company_pms' => $location_company_pms , 'omp_ago' => $location_omp_ago , 'company_ago' => $location_company_ago, 'omp_dpk' => $location_omp_dpk, 'company_dpk' => $location_company_dpk, 'omp_lube' => $location_omp_lube , 'company_lube' => $location_company_lube, 'omp_lpg' => $location_omp_lpg, 'company_lpg' => $location_company_lpg]);
                }
             }


                $survey =  COPS::whereDate('survey_date', $survey_date)->where('station_id', $station_id)->with('uploader:id,fullname')->with('station:id,name')->get();
                ///generate pdf and send report
                if( count($survey) > 0 ){
                $mail_data = [
                   'receiver_data'=> $survey,
                    'date' => $survey_date
                ];

                event(new COPSGenerated($mail_data));
                  }
              }

        catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $inserted_COPS;
    }

    public function get_by_date($date){
        return COPS::whereDate('survey_date',$date)->get();
    }

   public function get_by_params($params)
    {
       // return $params;
        if($params['station_id'] !=null && isset($params['survey_date'])  && $params['survey_date'] != null){
            $survey_date = date_format(date_create($params['survey_date']),"Y-m-d");
            return COPS::whereDate('survey_date',$survey_date)->where('station_id',$params['station_id'])->get();
            }
        else if($params['station_id'] !=null && isset($params['request_type']) && $params['request_type'] == 'summary'){
          
            return COPS::where('station_id',$params['station_id'])->distinct()->select('survey_date', 'created_at', 'uploaded_by')->with('uploader:id,fullname')->get();
        }
    }


 
    
}