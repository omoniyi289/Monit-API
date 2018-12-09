<?php
namespace App\Services;

use App\Notifications\RolesAssigned;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use Exception;
use App\Models\Region;
use App\Models\StationRegions;
use App\Station;

class RegionService
{
    private $database;
    private $dispatcher;

    public function __construct(DatabaseManager $database,Dispatcher $dispatcher)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
       }

    public function create(array $data){
        try {
            $this->database->beginTransaction();
            try {
                $region = Region::create($data);
                foreach ($data['selected_stations'] as $value) {
                    $permission= Station::where('id', $value)->get()->first();
                    StationRegions::create(['region_id' => $region['id'], 'station_id' => $value['id'], 'company_id' => $data['company_id']]);
                }
             
                

            } catch (Exception $exception) {
                $this->database->rollBack();
                throw $exception;
            }
            $this->database->commit();
             return Region::where("id",$region['id'])->with('region_stations.station')->get()->first();
        }catch (\Exception $exception){
            throw  $exception;
        }
    }

    public function get_region_by_name($name, $company_id){
        return Region::where("name",$name)->where("company_id",$company_id)->get();
    }
    public function get_id($region_id, array $options = []){
        return $this->get_requested_region($region_id);
    }

    private function get_requested_region($region_id, array $options = []){
        return Region::where('id',$region_id)->get()->first();
    }
     public function get_region_stations($region_id)
    {
        return Region::where("id",$region_id)->with('region_stations.station')->get()->first();     
    }
    public function update($region_id,array  $data){
       // return $data;
       $this->database->beginTransaction();
        try{
            Region::where('id', $region_id )->update(['name'=> $data['name'],'manager_name'=> $data['manager_name'],'manager_phone'=> $data['manager_phone'],'manager_email'=> $data['manager_email']]);
            StationRegions::where('region_id', $region_id)->delete();
            foreach ($data['selected_stations'] as $value) {
                    $permission= Station::where('id', $value)->get()->first();
                    StationRegions::create(['region_id' => $region_id, 'station_id' => $value['id'], 'company_id' => $data['company_id']]);
                }
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return Region::where("company_id",$data['company_id'])->with('region_stations.station')->get();
        
    }
      public function get_by_company_id($company_id)
    {
       return Region::where("company_id",$company_id)->with('region_stations.station')->get();
    }
    public function delete($region_id, array $options = [])
    {  
        StationRegions::where('region_id', $region_id)->delete();
        return  Region::where('id',$region_id)->delete();
    }

    public function get_all($options = []){
        return $this->Region::all();
    }
}