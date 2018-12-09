<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_counts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id')->nullable();    
            $table->foreign('item_id')->references('id')->on('items')->onUpdate('cascade');
            $table->integer('company_id')->nullable();    
            $table->integer('station_id')->nullable();

            $table->integer('qty_counted');
            $table->integer('qty_in_stock');
   
            $table->string('created_by')->nullable();
            $table->string('compositesku')->nullable();    
            $table->integer('active')->default(1);
            $table->integer('in_stock')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_counts');
    }
}
