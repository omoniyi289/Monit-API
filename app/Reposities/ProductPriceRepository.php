<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 5:24 PM
 */

namespace App\Reposities;

use App\Company;
use App\ProductPrices;
use Core\Repository\BaseRepository;

class ProductPriceRepository extends BaseRepository
{

    public function get_model()
    {
        return new ProductPrices();
    }
    public function create(array $data){
        $product_prices = $this->get_model();
        $product_prices->fill($data);
        $product_prices->save();
        return $product_prices;
    }
}