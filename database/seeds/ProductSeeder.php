<?php

use App\Products;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach (config('products') as $key => $product_category) {        
            foreach ($product_category as $inner_key => $product) {
            $perm = Products::where('code', $product['code'])->get()->first();      
                 if(count($perm) == 0){           
                 Products::create([
                  "name" => $product['name'],
                  "code" => $product['code'],
                    ]);
             }
            }
        }
    }
}