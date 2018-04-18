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

    public function create(array $data){
        $this->database->beginTransaction();
        try{

            if($data['mode']=='create'){
            $exist = $this->get_by_station_and_product_id($data['station_id'], $data['product_id']);
            if (count($exist) > 0) {
                return 'ERROR 400';
            }else{   
             $data['new_price_tag'] = $data['requested_price_tag'];      
             $product_price = ProductPrices::create($data);
                }
            }
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $product_price;
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
    public function create_new_log($new_data)
    {
                $data = ProductChangeLogs::create($new_data);
              // return $product_price;
                    $sss= Products::where('id', $data['product_id'])->first();
                    $data['product_name'] = $sss['name'];
                    $station = Station::where('id', $data['station_id'])->get()->first();
                    
                    $approver_details = User::where('id', $data['approved_by'])->get()->first();
                    Mail::to($approver_details['email'])->send(new PriceChangeMail($station,$approver_details,$new_data['creator_name'], $data ));
                  return $data;
    }
    public function get_by_station_id($station_id, array $options = [])
    {
        return ProductChangeLogs::where("station_id", $station_id)->with('product')->with('approver')->get();
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