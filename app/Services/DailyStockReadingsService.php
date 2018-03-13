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
use App\Models\DailyStockReadings;
class DailyStockReadingsService
{
    private $database;

    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }
    public function create(array $data) {
        $this->database->beginTransaction();
        try{
            foreach ($data['readings'] as $value) {
                    $stock = DailyStockReadings::create(['company_id' => $data['company_id'], 'station_id' => $data['station_id'], 'tank_id' => $value['tank_id'],'tank_code' => $value['tank_code'], 'phy_shift_start_volume_reading' => $value['opening_reading'],'created_by' => $data['created_by'], 'status' =>'Opened']);
                }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $stock;
    }
     public function update($stock_id, array $data)
    {
        $stock = $this->get_requested_stock($stock_id);
        $this->database->beginTransaction();
        try {
            DailyStockReadings::update($stock, $data);
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $stock;
    }

    public function get_all(array $options = []){
        return DailyStockReadings::all();
    }
    public function get_by_id($stock_id, array $options = [])
    {
        return $this->get_requested_stock($stock_id);
    }
      public function get_by_params($params)
    {   

       $result = DailyStockReadings::where('station_id',$params['station_id']);
       if(isset($params['date'])){
            $result->where('created_at', 'LIKE', $params['date'].'%');
       }
       else{
        $result->where('created_at', 'LIKE', date('Y-m-d').'%');
       }
       return $result->get();
    }
    private function get_requested_stock($stock_id, array $options = [])
    {
        return DailyStockReadings::where('id', $stock_id)->get();
    }
}