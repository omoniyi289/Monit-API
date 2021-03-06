<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemvariantsByStationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itemvariants_by_station', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id');    
            $table->integer('company_id')->nullable();    
            $table->integer('station_id')->nullable();
            $table->string('variant_option');
            $table->string('variant_value');
            $table->integer('reorder_level');
            $table->integer('qty_in_stock');
            $table->string('last_restock_date')->nullable();    
            $table->string('supply_price')->nullable();;
            $table->string('retail_price')->nullable();          
            $table->string('created_by')->nullable();
            $table->string('modified_by')->nullable();            
            $table->string('compositesku')->nullable();    
            $table->integer('active')->default(1);
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
        Schema::dropIfExists('itemvariants_by_station');
    }
}
