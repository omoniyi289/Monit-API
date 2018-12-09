<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id')->nullable();    
            $table->foreign('item_id')->references('id')->on('items')->onUpdate('cascade');
            $table->string('compositesku')->nullable();    
            $table->integer('company_id')->nullable();    
            $table->integer('station_id')->nullable();

            $table->integer('qty_sold');
            $table->integer('cogs')->nullable();
            $table->integer('selling_price');
            $table->integer('grand_total');
            $table->string('sold_by')->nullable();               
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
        Schema::dropIfExists('stock_sales');
    }
}
