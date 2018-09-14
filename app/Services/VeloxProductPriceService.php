<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;


use App\Reposities\CompanyRepository;
use App\Reposities\ProductPriceRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use App\ProductPrices;
use App\ProductChangeLogs;


class VeloxProductPriceService
{
    private $database;
    private $dispatcher;
    private $product_price_repository;

    public function __construct(DatabaseManager $database,Dispatcher $dispatcher,
                                ProductPriceRepository $product_price_repository)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
        $this->product_price_repository = $product_price_repository;
    }

    public function get_by_params($request)
    {
        $result = ProductPrices::where('id', '>', 0);
        //return $request;
     //   if(isset($request['station_id'])){
            $station_id = $request['station_id'];
            $result = $result->where('station_id', $station_id);

      //  }  

      //  if(isset($request['product'])){
            $product = $request['product'];
            $result = $result->where('product', $product);
      ///  }          
        return $result->get(['id','new_price_tag', 'product']);
    }
}