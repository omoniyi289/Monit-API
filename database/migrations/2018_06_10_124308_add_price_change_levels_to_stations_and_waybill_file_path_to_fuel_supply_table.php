<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriceChangeLevelsToStationsAndWaybillFilePathToFuelSupplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('stations', function (Blueprint $table) {
            $table->integer('pc_approval_levels')->default(1); 
             }); 
         Schema::table('product_change_logs', function (Blueprint $table) {
            $table->integer('is_approved_level_2')->nullable(); 
             }); 
         Schema::table('product_change_logs', function (Blueprint $table) {
            $table->integer('is_approved_level_3')->nullable(); 
             }); 


        Schema::table('fuel_supplies', function (Blueprint $table) {
            $table->string('waybill_path')->nullable(); 
             });  
            
            
  }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
