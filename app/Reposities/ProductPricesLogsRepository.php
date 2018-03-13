<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 5:24 PM
 */

namespace App\Reposities;

use App\Company;
use App\ProductChangeLogs;
use App\ProductPrices;
use Core\Repository\BaseRepository;

class ProductPricesLogsRepository extends BaseRepository
{

    public function get_model()
    {
        return new ProductChangeLogs();
    }
    public function create(array $data){
        $product_prices_change_logs = $this->get_model();
        $product_prices_change_logs->fill($data);
        $product_prices_change_logs->save();
        return $product_prices_change_logs;
    }
    public function update(Tanks $tank, array $data){
        $tank->fill($data);
        $tank->save();
        return $tank;
    }
}