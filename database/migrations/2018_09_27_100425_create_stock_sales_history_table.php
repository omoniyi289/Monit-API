<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockSalesHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_sales_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable();    
            $table->integer('station_id')->nullable();
            $table->unsignedInteger('item_id')->nullable();    
            $table->string('compositesku')->nullable();    
            $table->integer('qty_sold')->nullable();
            $table->integer('supply_price')->nullable();
            $table->integer('qty_in_stock')->nullable();
            $table->integer('retail_price')->nullable();
            $table->integer('cash_collected')->nullable();
            $table->string('sold_by')->nullable();               
            $table->string('note')->nullable();               
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_sales_history');
    }
}
